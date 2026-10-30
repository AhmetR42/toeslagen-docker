# Class diagram – toeslagen en aanvragen (Ahmet)

Dit class diagram laat de classes zien van mijn onderdelen: overzicht beschikbare toeslagen, huurtoeslag, zorgtoeslag en kinderopvangtoeslag aanvragen, en status per aanvraag. `User` is van Ilyas en staat erbij omdat een aanvraag bij een gebruiker hoort.

```mermaid
classDiagram
    class Toeslag {
        +int id
        +string slug
        +string naam
        +string omschrijving
        +array voorwaarden
        +decimal max_inkomen
        +getRouteKeyName() string
        +aanvragen() HasMany
    }

    class Aanvraag {
        +int id
        +int user_id
        +int toeslag_id
        +decimal inkomen
        +int huishouden
        +decimal huur_per_maand
        +int aantal_kinderen
        +int opvang_uren
        +bool zorgverzekering
        +string status
        +user() BelongsTo
        +toeslag() BelongsTo
        +statusLabel() string
    }

    class User {
        +int id
        +string name
        +string email
        +string rol
    }

    class ToeslagController {
        +index() View
    }

    class AanvraagController {
        +create(Toeslag) View
        +store(AanvraagRequest, Toeslag) RedirectResponse
        +show(Request, Aanvraag) View
    }

    class AanvraagRequest {
        +authorize() bool
        +rules() array
        +attributes() array
        +messages() array
    }

    class FormRequest
    class Controller
    class Model

    Model <|-- Toeslag
    Model <|-- Aanvraag
    Controller <|-- ToeslagController
    Controller <|-- AanvraagController
    FormRequest <|-- AanvraagRequest

    User "1" --> "0..*" Aanvraag : dient in
    Toeslag "1" --> "0..*" Aanvraag : hoort bij

    ToeslagController ..> Toeslag : toont
    AanvraagController ..> Aanvraag : maakt en toont
    AanvraagController ..> AanvraagRequest : valideert met
```

## Uitleg

| Class | Wat doet het |
|---|---|
| `Toeslag` | Een toeslag (huur-, zorg- of kinderopvangtoeslag). De `slug` wordt in de URL gebruikt, bijvoorbeeld `/toeslagen/huurtoeslag/aanvragen`. |
| `Aanvraag` | Een aanvraag van een burger. Velden die maar bij één toeslag horen (huur, kinderen, opvanguren, zorgverzekering) zijn nullable. `statusLabel()` geeft de status als leesbare tekst. |
| `ToeslagController` | `index()` toont het overzicht van beschikbare toeslagen. |
| `AanvraagController` | `create()` toont het formulier, `store()` slaat de aanvraag op met status *in behandeling*, `show()` toont de status van één aanvraag (403 als de aanvraag van iemand anders is). |
| `AanvraagRequest` | Validatie. `rules()` geeft per toeslag andere regels via `match`. |
