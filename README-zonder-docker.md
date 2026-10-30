# NL Dienst Toeslagen – Beroepsproject P7 groep 2

Webapplicatie waarmee burgers toeslagen (huurtoeslag, zorgtoeslag en kinderopvangtoeslag) kunnen bekijken en aanvragen, en waarmee een admin gebruikers, rollen en aanvragen beheert. Gebouwd met Laravel 12 en SQLite, in het Nederlands en Engels, met lichte en donkere weergave.

Team: Ahmet, Sameer, Ilyas en Ramy.

## Inhoud van de repository

| Map | Inhoud |
|---|---|
| `toeslagen/` | De Laravel-applicatie |
| `documentatie/` | Functioneel ontwerp, class diagrams en overige documentatie |

## Benodigdheden

- PHP 8.2 of hoger, met de extensies `pdo_sqlite`, `sqlite3`, `mbstring`, `openssl` en `fileinfo` aan (in `php.ini`)
- [Composer](https://getcomposer.org/)
- Git

Er is geen MySQL of XAMPP nodig: de app gebruikt een SQLite-database (`toeslagen/database/database.sqlite`).

## Installatie

1. Clone de repository in een nieuwe map en ga naar de applicatie:

   ```bash
   git clone https://gitlabsrv05.stichtingpraktijkleren.nl/oitsdo24u/beroepsproject_p7/Beroepsproject-P7-groep-2.git
   cd Beroepsproject-P7-groep-2/toeslagen
   ```

2. Installeer de PHP-packages:

   ```bash
   composer install
   ```

3. Maak het configuratiebestand aan (sla over als `.env` al bestaat):

   ```bash
   cp .env.example .env
   ```

   Op Windows (cmd): `copy .env.example .env`

4. Genereer de applicatiesleutel:

   ```bash
   php artisan key:generate
   ```

5. Maak de database opnieuw aan en vul hem met testdata en testaccounts:

   ```bash
   php artisan migrate:fresh --seed
   ```

   Vraagt Laravel of het databasebestand aangemaakt moet worden? Kies dan `yes`.

6. Start de applicatie:

   ```bash
   php artisan serve
   ```

7. Open <http://127.0.0.1:8000> in je browser.

## Testaccounts

Deze accounts worden aangemaakt door `php artisan migrate:fresh --seed`:

| Rol | E-mailadres | Wachtwoord |
|---|---|---|
| Admin | admin@test.nl | Test123! |
| Medewerker | medewerker@test.nl | Test123! |
| Burger | burger@test.nl | Test123! |

Je kunt ook zelf een burgeraccount aanmaken via **Account aanmaken**.

## Testdata

- De drie toeslagen (huurtoeslag, zorgtoeslag, kinderopvangtoeslag) met hun voorwaarden
- De testaccounts hierboven

## Wat kan je testen

| Rol | Functies |
|---|---|
| Gast | Overzicht van beschikbare toeslagen, inloggen, account aanmaken |
| Burger | Toeslag aanvragen, overzicht en status van eigen aanvragen |
| Admin | Gebruikers beheren (rol wijzigen, blokkeren, verwijderen), rollen & rechten bekijken |
| Iedereen | Taal wisselen (NL/EN) en lichte/donkere weergave |

## Problemen oplossen

- **`could not find driver`**: zet `extension=pdo_sqlite` en `extension=sqlite3` aan in je `php.ini`.
- **`No application encryption key has been specified`**: voer `php artisan key:generate` uit.
- **Wijzigingen niet zichtbaar**: voer `php artisan optimize:clear` uit.

## Documentatie

Zie de map [`documentatie/`](documentatie/):

- [Functioneel ontwerp](documentatie/functioneel-ontwerp.md)
- [Class diagrams](documentatie/class-diagram.md)
