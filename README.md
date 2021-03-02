# CredentialExchange Technique

In a typical [DDD layered architecture][], the Application layer will almost
always need to know who is using it, even if only to help determine
authorization. This is not a problem of authentication per se. Instead, it is a
problem of taking the identification values provided by a prior authentication
(such as a JWT or a session ID) and matching them to a Domain layer User object.
Further, the problem must be solved in a way that does not tie the Application
or Domain layers to any particular Presentation (User Interface) layer.

This paper describes a technique to identify the Domain layer User by passing
the identifying information from the Presentation (User Interface) layer into
the Application layer, and letting the Application layer coordinate the creation
of the Domain layer User instance via Infrastructure implementations.

This technique eliminates any Presentation (User Interface) layer logic related
to discovering the Domain layer User (e.g. no need for error handling and other
conditionals). The Application layer can capture any errors from Domain layer
User identification into a Domain Payload for return back to the Presentation
layer. The Domain layer can continue to depend only on its own interfaces for
User modeling, independent of any other layer.

The components and collaborations in this technique are essentially a
specialized variation on the [Query Bus][] pattern, with the Credential as a
Query, the CredentialExchange as a QueryBus, and the CredentialHandler as a
QueryHandler.

[DDD layered architecture]: https://archfirst.org/domain-driven-design-6-layered-architecture/
[Query Bus]: https://barryvanveen.nl/blog/59-different-kinds-of-service-bus-command-bus-service-bus-and-query-bus

## Components

- **Credential**: One or more inputs indicating a user identity; this is an
  Application layer marker interface (i.e., an empty interface). There may be
  many different types of Credentials based on the Presentation layer
  requirements; the Credential types are part of the Infrastructure layer and
  implement the Credential marker interface. An example interface is [here](./src/App/Identification/Credential.php).

- **CredentialExchange**: Calls the CredentialHandler for a given Credential; this
  is an Application layer interface defining one method to get a Domain layer
  User object for a given Credential. The implementation is part of the
  Infrastructure layer. An example interface is [here](./src/App/Identification/CredentialExchange.php).

- **CredentialHandler**: Converts a particular type of Credential to a User. Each
  CredentialHandler is part of the Infrastructure layer only.

In trivial cases, the CredentialHandler may be collapsed into the
CredentialExchange, but this should be considered a degenerate variation.

Example implementations are [here](./src/Infra/Identification).

## Collaborations

- Presentation layer creates a Credential object from user inputs.
  [Http\Action\Item\PostItem](./src/Http/Action/Item/PostItem.php)

- Presentation layer passes the Credential as one of the Application layer
  inputs. [Http\Action\Item\PostItem](./src/Http/Action/Item/PostItem.php)

- Application layer calls CredentialExchange with the Credential.
  [App\UseCase\Item\EditItem](./src/App/UseCase/Item/EditItem.php)

- CredentialExchange chooses and invokes a CredentialHandler based on the type
  of Credential. [Infra\Identification\CredentialExchange](/src/Infra/Identification/CredentialExchange.php)

- CredentialHandler uses the Credential to fetch and return a Domain layer User
  object; the User is returned through the CredentialExchange back to the
  Application layer.
  [Infra\Identification\Token\TokenCredentialHandler](./src/Infra/Identification/Token/TokenCredentialHandler.php)

- Application layer involves User as needed; e.g. to check anonymity or
  authorization. [App\UseCase\Item\EditItem](./src/App/UseCase/Item/EditItem.php)

- Application layer returns a DomainPayload to the Presentation layer.
  [App\UseCase\Item\EditItem](./src/App/UseCase/Item/EditItem.php)


## Types of Credentials

These are some of the kinds of inputs that might encapsulated in a Credential:

- A JSON Web Token
- An API key and and secret
- A command-line username
- A framework-provided User object

Each of these Credentials likely requires a different CredentialHandler to
convert them to a Domain-specific User.

Framework users may have a framework-provided User object that gets put
together as part of the framework operation. For one example, see the
[Infra\Identification\Falafel](./src/Infra/Identification/Falafel/) Credential
and CredentialHandler implementations.

## Alternatives

Where besides the Application Layer might the Domain layer User be retrieved?

### Presentation (User Interface) Layer

Placing User lookups in the Presentation (User Interface) layer unnecessarily
complicates the Presentation logic.

For example, given an ADR Presentation layer, the Action should only do three
things:

- Collect user input from the HTTP Request

- Call the Domain (Application layer) with that input

- Pass the Domain (Application layer) results to a Responder

To create the Domain layer User, the Action will have to do all the lookup work,
handle all the errors related to that, short-circuit the call to the Application
layer if lookup fails, and report all of that back through the user interface.

Whereas doing it in the Application layer neatly encapsulates it where errors
etc. can be reported back in the Domain Payload, relieving the Action of all the
related conditionals.

### Domain Layer

The Domain layer, for its part, need not be concerned with who the
current-user-of-the-Application is. It just needs a Domain layer User object,
and does not care where it came from. Putting User Interface specifics about the
current User into the Domain improperly mixes Domain concerns with User
Interface concerns.


## Credits

Many thanks to Kevin Smith for his comments and criticism during the development
of this technique.
