# NL Dienst Toeslagen – Dockerized

Webapplicatie waarmee burgers toeslagen (huurtoeslag, zorgtoeslag, kinderopvangtoeslag) kunnen bekijken en aanvragen, en waarmee een admin gebruikers en rollen beheert. Gebouwd met Laravel 12 (Beroepsproject P7, groep 2).

**Dockerization scope:** de bestaande applicatie draait zonder lokale PHP/Composer/database met één commando. De app draait in een PHP 8.3 + Apache container, de data staat in een MySQL 8.4 container. Aan de applicatiecode is niets veranderd; alleen configuratie (via environment variables) en Docker-bestanden zijn toegevoegd.

Zie ook: [INVENTARISATIE.md](INVENTARISATIE.md) (projectscan).

---

## Vereisten

- Docker Desktop (Windows/macOS) of Docker Engine 24+ (Linux)
- Docker Compose v2 (`docker compose version` → v2.20 of hoger)
- Vrije poort **8080** (aan te passen via `APP_PORT` in `.env`)

Er is géén PHP, Composer, Node of MySQL op je eigen machine nodig.

## Quickstart

```bash
git clone <repo-url> toeslagen-docker
cd toeslagen-docker
cp .env.example .env          # Windows (cmd): copy .env.example .env
docker compose up --build
```

Open daarna **http://localhost:8080**.

De eerste start duurt 1–3 minuten (images downloaden, dependencies installeren, database vullen). Daarna start het binnen enkele seconden.

### Testaccount

| Rol | E-mail | Wachtwoord |
|---|---|---|
| Admin | admin@toeslagen.nl | Admin123! |

Een burgeraccount maak je zelf aan via **Account aanmaken** (`/registreren`).

### Handige commando's

| Doel | Commando |
|---|---|
| Starten (en bouwen) | `docker compose up --build` |
| Op de achtergrond starten | `docker compose up -d --build` |
| Logs volgen | `docker compose logs -f` |
| Status + health | `docker compose ps` |
| Stoppen | `docker compose down` |
| **Data resetten** | `docker compose down -v` |
| Artisan-commando | `docker compose exec app php artisan <commando>` |
| Database opnieuw opbouwen + seeden | `docker compose exec app php artisan migrate:fresh --seed --force` |
| MySQL-console | `docker compose exec db mysql -u toeslagen -p toeslagen` |

---

## Acceptatiecriteria

1. `docker compose up --build` start app én database zonder handmatige stappen (geen `key:generate`, geen `migrate` nodig).
2. Na het starten is http://localhost:8080 bereikbaar en toont `/toeslagen` de drie toeslagen.
3. De database wordt bij de eerste start automatisch gevuld via een `.sql`-bestand (`docker/mysql/init/01-schema-en-seed.sql`).
4. Data (gebruikers, aanvragen, sessies) blijft bewaard na `docker compose down` + `up` (named volume `db_data`).
5. Er staan geen echte wachtwoorden of sleutels in git: alle configuratie komt uit `.env` en `.env` staat in `.gitignore`. Alleen `.env.example` met placeholders is gecommit.
6. De app gebruikt de service-naam `db` als host, niet `localhost`.
7. Beide services hebben een healthcheck. `docker compose ps` toont `healthy`, en de app start pas als de database healthy is.
8. De image is te bouwen met `docker build .` en is multi-stage (build- en runtime-stage gescheiden).
9. De README bevat quickstart, poorten, verplichte variabelen, troubleshooting en een smoke test.
10. (Optioneel) De app is via Docker Swarm te deployen met 2 replicas en rolling updates.

## Smoke test (bewijs van werking)

1. `docker compose up -d --build` en wacht tot `docker compose ps` bij beide services `healthy` toont.
2. Open http://localhost:8080/up → groene "Application up"-pagina (healthcheck-endpoint van Laravel).
3. Open http://localhost:8080/toeslagen → Huurtoeslag, Zorgtoeslag en Kinderopvangtoeslag zijn zichtbaar (data uit MySQL).
4. Ga naar **Account aanmaken**, registreer een burger en log in.
5. Vraag **Huurtoeslag** aan (bijv. inkomen 25000, huishouden 1, huur 700) → je komt op de statuspagina "In behandeling".
6. `docker compose down` en daarna `docker compose up -d`.
7. Ververs de statuspagina: je bent nog ingelogd en de aanvraag bestaat nog → **data blijft bewaard**.
8. Log in als admin (`admin@toeslagen.nl` / `Admin123!`) → bij `/admin/gebruikers` staat de nieuwe burger.
9. `docker compose down -v` en `up` → database is weer leeg op de seed-data na (reset werkt).

> Deze smoke test is uitgevoerd op de opgeleverde setup: registreren, aanvraag indienen, restart, admin-login en reset gaven allemaal het verwachte resultaat.

---

## Architectuur

```
           Browser
              │  http://localhost:8080
              ▼
┌──────────────────────────────┐        network: backend
│ app  (toeslagen-app)         │
│ PHP 8.3 + Apache             │──────────┐
│ Laravel 12, poort 80         │          │ mysql://db:3306
│ volume: app_storage          │          ▼
└──────────────────────────────┘  ┌─────────────────────────┐
                                  │ db  (mysql:8.4)         │
                                  │ volume: db_data         │
                                  │ init: docker/mysql/init │
                                  └─────────────────────────┘
```

### Bestanden

| Bestand | Doel |
|---|---|
| `Dockerfile` | Multi-stage build: `composer:2` (dependencies) → `php:8.3-apache` (runtime) |
| `docker-compose.yml` | Services `app` + `db`, volumes, netwerk, healthchecks |
| `docker-compose.swarm.yml` | Optionele Swarm-stack (replicas, rolling updates) |
| `.env.example` | Alle configuratie met placeholders |
| `.dockerignore` | Houdt `.env`, `vendor/`, documentatie en oude mappen uit de build |
| `docker/entrypoint.sh` | Opstartscript: APP_KEY, wachten op DB, migraties, caches |
| `docker/apache/000-default.conf` | Apache DocumentRoot op `public/` |
| `docker/mysql/init/01-schema-en-seed.sql` | Schema + seed-data, wordt geladen bij een lege database |

## Dockerfile: keuzes

- **Base image `php:8.3-apache`**: officiële image en voldoet aan de eis van Laravel 12 (PHP ≥ 8.2). Apache en PHP draaien samen in één proces, dus er is geen aparte Nginx- of PHP-FPM-container nodig. Dat houdt de setup eenvoudig.
- **Build-stage `composer:2`**: Composer zit alleen in de build-stage en niet in de runtime-image. Dev-packages (`--no-dev`) worden niet geïnstalleerd.
- **Build caching**: eerst worden alleen `composer.json` en `composer.lock` gekopieerd en wordt `composer install` uitgevoerd, pas daarna de rest van de code. Bij een codewijziging wordt de dependency-laag hergebruikt en gaat een rebuild snel.
- **Extensies**: `pdo_mysql` (database) en `opcache` (performance). `mod_rewrite` staat aan voor de Laravel-routes, en de image gebruikt `php.ini-production`.
- **Production-like image**: geen dev-dependencies, `APP_DEBUG=false`, en config-, route- en view-cache bij het starten.
- Frontend: de views gebruiken geen Vite-build (de styling staat in de Blade-layout), dus er is geen Node-stage nodig.

## Configuratie (.env)

| Variabele | Verplicht | Uitleg |
|---|---|---|
| `DB_DATABASE` | ja | Naam van de database (wordt aangemaakt door MySQL) |
| `DB_USERNAME` | ja | Database-gebruiker voor de app |
| `DB_PASSWORD` | ja | Wachtwoord van die gebruiker |
| `DB_ROOT_PASSWORD` | ja | Root-wachtwoord van MySQL |
| `APP_KEY` | nee | Leeg = wordt bij de eerste start gegenereerd en bewaard in volume `app_storage`. Verplicht bij Swarm (alle replicas moeten dezelfde sleutel hebben). |
| `APP_PORT` | nee | Poort op je machine (standaard 8080) |
| `APP_URL` | nee | Basis-URL voor links (standaard http://localhost:8080) |
| `APP_ENV` / `APP_DEBUG` | nee | Standaard `production` / `false` |

`DB_HOST=db`, `DB_PORT=3306` en `DB_CONNECTION=mysql` staan vast in `docker-compose.yml`, omdat ze bij de Docker-setup horen en niet per machine verschillen.

**Let op:** wijzig je `DB_*`-waarden nadat de database al bestaat, voer dan `docker compose down -v` uit. MySQL leest deze waarden alleen bij een lege database.

## Poorten

| Service | Intern | Extern |
|---|---|---|
| app | 80 | `${APP_PORT}` (8080) |
| db | 3306 | niet gepubliceerd (alleen bereikbaar binnen het netwerk) |

## Persistente data

| Volume | Inhoud | Waarom |
|---|---|---|
| `db_data` | MySQL-data (gebruikers, aanvragen, sessies, cache, jobs) | Zonder volume verdwijnen alle accounts en aanvragen bij elke restart |
| `app_storage` | Laravel `storage/` (logs, uploads, gegenereerde APP_KEY) | Uploads en de sleutel moeten een rebuild van de image overleven. Zonder vaste APP_KEY worden sessies ongeldig. |

## Migrations & seeding

- **Eerste start:** MySQL voert `docker/mysql/init/01-schema-en-seed.sql` uit. Dit bestand bevat alle tabellen, de toeslagen en het admin-account, en is gemaakt met `php artisan migrate --seed` en `mysqldump`.
- **Elke start:** het entrypoint draait `php artisan migrate --force`. Bestaande migraties staan al in de tabel `migrations` (`Nothing to migrate`), en nieuwe migraties van het team worden zo automatisch toegepast. Uitzetten kan met `RUN_MIGRATIONS=false`.
- **Handmatig opnieuw:** `docker compose exec app php artisan migrate:fresh --seed --force`.

---

## Keuzes & trade-offs

- **MySQL in plaats van SQLite:** het originele project gebruikt een SQLite-bestand. Voor Docker is een aparte database-service netter: een eigen volume, een healthcheck, en meerdere app-containers kunnen dezelfde database delen (nodig voor Swarm). De trade-off is een extra container en meer geheugengebruik. Er hoefde geen code te veranderen, alleen environment variables.
- **Apache + mod_php in plaats van Nginx + PHP-FPM:** één container is eenvoudiger te begrijpen en te debuggen. Nginx + FPM schaalt iets beter onder hoge load, maar dat is voor dit project niet nodig.
- **Seed via `.sql`-dump én `artisan migrate`:** de dump laadt snel en voldoet aan de eis "seeding via .sql". `migrate` vangt nieuwe migraties op. Nadeel: verandert het schema, dan moet de dump opnieuw gegenereerd worden.
- **APP_KEY automatisch genereren:** dat maakt "één commando" mogelijk zonder handmatige `key:generate`. De sleutel staat in een volume, zodat sessies een restart overleven. Bij `down -v` komt er een nieuwe sleutel, wat bij een reset acceptabel is.
- **Production-like in plaats van een dev-image:** code zit in de image (geen bind mount) en caches staan aan. Dat is stabiel en reproduceerbaar, maar na een codewijziging moet je opnieuw `--build` doen. Voor ontwikkeling zou een bind mount van `toeslagen/` handiger zijn.
- **Database-poort niet publiceren:** dat is veiliger, omdat MySQL alleen voor de app bereikbaar is. Wie met een GUI-tool wil kijken, kan tijdelijk `ports: ["3306:3306"]` toevoegen.
- **Queue/mail niet als aparte service:** de app gebruikt geen queue-jobs of echte mail (`MAIL_MAILER=log`), dus een worker of Mailpit-container zou alleen overhead zijn.
- **Logs naar stderr (`LOG_CHANNEL=stderr`):** zo zie je ze direct in `docker compose logs -f`, wat de standaard Docker-aanpak is.

## Veelvoorkomende problemen

| Probleem | Oplossing |
|---|---|
| `port is already allocated` / poort 8080 bezet | Zet `APP_PORT=8081` in `.env` en start opnieuw |
| App blijft `starting` of `unhealthy` | `docker compose logs app`. De eerste MySQL-start kan 30–60 s duren; de app wacht automatisch. |
| `Access denied for user` | `DB_*` in `.env` gewijzigd na de eerste start → `docker compose down -v` en opnieuw starten |
| `Database niet bereikbaar` in de logs | Controleer `docker compose ps db` en `docker compose logs db` |
| `419 Page Expired` bij een formulier | Sessie verlopen of cookies geblokkeerd → pagina verversen. Check of `APP_KEY` niet tussendoor is veranderd. |
| Wijzigingen in de code niet zichtbaar | Code zit in de image → `docker compose up --build` |
| `.env not found` / variabelen leeg | `cp .env.example .env` vergeten |
| Windows: `entrypoint.sh: not found` | Het script heeft Windows-regeleindes (CRLF) gekregen → in je editor omzetten naar LF, of `git config core.autocrlf input` en opnieuw clonen |

---

## Optioneel: Docker Swarm

`docker-compose.swarm.yml` deployt dezelfde images als stack:

```bash
docker swarm init
docker compose build                       # bouwt toeslagen-app:latest
# zet een vaste APP_KEY in .env, bv. de uitvoer van:
docker compose run --rm --no-deps --entrypoint php app artisan key:generate --show
set -a; . ./.env; set +a                   # Linux/macOS/Git Bash: variabelen laden
docker stack deploy -c docker-compose.swarm.yml toeslagen
docker service ls
```

- **Replicas:** de app draait met 2 replicas. Sessies en cache staan in MySQL, dus het maakt niet uit welke replica een request krijgt. De database draait met 1 replica (één schrijver) op de manager-node.
- **Schalen:** `docker service scale toeslagen_app=4`
- **Rolling update:** nieuwe image bouwen en daarna `docker service update --image toeslagen-app:latest toeslagen_app`. `update_config` vervangt één container tegelijk (`start-first`) en voert bij een fout automatisch een rollback uit.
- **`endpoint_mode: dnsrr`** voor de db: de app vindt de database direct via DNS, zonder virtuele IP.
- **Beperking:** op meerdere nodes moet de image in een registry staan (`docker push`) en is voor `db_data` gedeelde of node-gebonden opslag nodig.
- **Stoppen:** `docker stack rm toeslagen`

---

## Zonder Docker (originele werkwijze)

Zie [README-zonder-docker.md](README-zonder-docker.md) (originele README van het team): `composer install`, `.env` kopiëren, `php artisan key:generate`, `php artisan migrate:fresh --seed`, `php artisan serve`. Deze manier gebruikt SQLite.

## Team

Ahmet, Sameer, Ilyas en Ramy. Dockerization: Ahmet Akbulut.


## depends_on: wat het wel en niet doet

In docker-compose.yml staat bij app: depends_on: db met condition: service_healthy.

Wat het wel doet: Compose start de db-container eerst en start de app pas als de healthcheck van MySQL (mysqladmin ping) slaagt.

Wat het niet doet: depends_on garandeert niet dat de database later bereikbaar blijft of dat de juiste gebruiker en tabellen al bestaan. Zonder condition wacht Compose alleen tot de container gestart is, niet tot MySQL klaar is. Bij Docker Swarm wordt depends_on helemaal genegeerd. Daarom controleert docker/entrypoint.sh zelf ook of de database bereikbaar is voordat de migraties draaien.
