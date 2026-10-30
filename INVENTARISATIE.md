# Inventarisatie – NL Dienst Toeslagen (Fase 1)

## 1. Wat is het project?

**Doel:** een webapplicatie waarin burgers toeslagen van de (fictieve) NL Dienst Toeslagen bekijken en aanvragen, en waarin een beheerder gebruikers beheert. Het is gemaakt als beroepsproject (Periode 7) door een team van vier studenten, met Scrum.

**Gebruikers / rollen:**
- **Gast:** overzicht van beschikbare toeslagen, inloggen, account aanmaken
- **Burger:** huur-, zorg- en kinderopvangtoeslag aanvragen, status per aanvraag bekijken
- **Medewerker / Admin:** gebruikers beheren (rol wijzigen, blokkeren, verwijderen), rollen & rechten bekijken

**Globale features:** registratie en login met rollen (middleware `role`), toeslagenoverzicht met voorwaarden, aanvraagformulieren per toeslag met validatie, een statuspagina per aanvraag, admin-gebruikersbeheer, NL/EN-taalwissel en een licht/donker thema.

## 2. Hoe start je het nu lokaal (zonder Docker)?

Tooling: PHP 8.2+ met extensies, Composer en Git.

```bash
cd toeslagen
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve          # http://127.0.0.1:8000
```

Knelpunten: iedere teamgenoot moet zelf de juiste PHP-versie en de `php.ini`-extensies (`pdo_sqlite`, `sqlite3`, `mbstring`, `openssl`, `fileinfo`) regelen. Daardoor geldt vaak "werkt bij mij wel, bij jou niet".

## 3. Afhankelijkheden

| Soort | Wat | Opmerking |
|---|---|---|
| Runtime | PHP ^8.2 | In Docker: PHP 8.3 |
| Framework | Laravel 12, laravel/tinker | Via Composer (`composer.lock` aanwezig) |
| Package manager | Composer 2 | Alleen nodig tijdens de build |
| Dev-dependencies | Faker, PHPUnit, Pint, Pail, Sail, Collision | Niet nodig in de runtime-image |
| Frontend | Tailwind/Vite staan in `package.json` | Worden niet gebruikt door de views (de CSS staat in de layout), dus er is geen Node-build nodig. Google Fonts komt via een CDN. |
| PHP-extensies | pdo_sqlite (origineel), mbstring, openssl, fileinfo | In Docker: `pdo_mysql` + `opcache` |
| Database | SQLite-bestand `database/database.sqlite` | In Docker vervangen door MySQL 8.4 |
| Cache / sessies / queue | Driver `database` (tabellen `cache`, `sessions`, `jobs`) | Geen Redis nodig |
| Mail | `MAIL_MAILER=log` | Geen mailserver nodig |
| Search / storage | Geen | Disk `local` |

**Opgeruimd in de build:** de mappen `toeslagen/toeslagen/` (een oude kopie met een eigen `vendor`, 91 MB) en `toeslagen/toeslagen-laravel/` (een oude Laravel 11-zip) worden niet gebruikt door de app en worden via `.dockerignore` buiten de image gehouden. Ze zijn niet verwijderd, om de originele repository niet te veranderen.

## 4. Poorten / URL's

| Situatie | URL |
|---|---|
| Origineel (`artisan serve`) | http://127.0.0.1:8000 |
| Docker | http://localhost:8080 (container intern: 80) |
| MySQL | `db:3306`, alleen binnen het Docker-netwerk |

**Belangrijke routes:** `/up` (health), `/inloggen`, `/registreren`, `/toeslagen`, `/toeslagen/{slug}/aanvragen`, `/mijn-aanvragen/{id}`, `/admin/gebruikers`, `/rollen`.

## 5. Persistente data

| Data | Waar | Docker-oplossing |
|---|---|---|
| Gebruikers, toeslagen, aanvragen | database | volume `db_data` |
| Sessies, cache, jobs | database (driver `database`) | volume `db_data` |
| Logs | `storage/logs` | stderr (`docker compose logs`) + volume `app_storage` |
| Uploads | `storage/app` (nu niet gebruikt, wel voorbereid) | volume `app_storage` |
| Applicatiesleutel | `.env` → `APP_KEY` | `.env`, of automatisch gegenereerd in `app_storage` |

## 6. Benodigde configuratie

- **Env vars:** `APP_KEY`, `APP_ENV`, `APP_DEBUG`, `APP_URL`, `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, `SESSION_DRIVER`, `CACHE_STORE`, `QUEUE_CONNECTION`, `LOG_CHANNEL`, `MAIL_MAILER`
- **Secrets:** `APP_KEY`, `DB_PASSWORD`, `DB_ROOT_PASSWORD`. Deze staan in `.env`, dat niet in git komt.
- **Config-bestanden:** `config/*.php` lezen alles al via `env()`, dus er waren geen codewijzigingen nodig. Er staat geen hardcoded `localhost` in de applicatiecode; `DB_HOST` wordt `db`.
- **Webserver:** de DocumentRoot moet naar `public/` wijzen, met rewrite naar `index.php`.

## 7. Definition of Done

De Dockerization is klaar als:
- `docker compose up --build` op een schone machine met alleen Docker de app start, met een gevulde database en zonder handmatige stappen;
- alle acceptatiecriteria uit de README zijn afgevinkt en de smoke test slaagt (registreren → aanvragen → restart → data nog aanwezig);
- er geen secrets in git staan en `.env.example` volledig is;
- de README quickstart, poorten, variabelen, architectuur, keuzes en troubleshooting bevat;
- de code in een repository of zip staat die een ander kan clonen en starten.
