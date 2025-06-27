# Bandito / Thesis Project Setup

## Prerequisites
- Docker (Engine) installed

- Docker Compose (v2) installed

- Git client (to clone the repo)

- A modern browser (Chrome, Firefox, Brave)

## Setup Instructions

1. Clone the repo:

```bash
git clone git@github.com:isomorphicAlgorithm/thesis.git
cd thesis
```

2. Set your environment variables:

In the project root (thesis) there should be an .env with these three lines:

```bash
HOST_UID=1000
HOST_GID=1000
MYSQL_ROOT_PASSWORD=<choose-a-strong-password>
```

Also .env.local should be created and have the correct credentials inside. (Check the .env.local.example)

If your local user’s UID/GID aren’t 1000, adjust HOST_UID/HOST_GID accordingly.

3. Ensure the entrypoint.sh is executable:

```bash
chmod +x app/docker/php/entrypoint.sh
```
(You only need to do this once; it’s already tracked in Git.)

4. Launch everything with Docker Compose:

```bash
docker compose up --build
```
This will:

Build the PHP image (with Composer, Node/Yarn, Symfony CLI)

Start MySQL, wait for it to be ready

Install PHP & JS dependencies (via Composer & Yarn)

Build assets (yarn dev)

Run Doctrine migrations

Spin up PHP-FPM and Nginx

Expose the app on http://localhost:9000

The logs will be from MySQL, PHP-FPM and Nginx as the app boots.

5. Explore the app:

Open your browser at http://localhost:9000

Browse routes like /bands, /albums, /profile

All uploads and cache folders are auto-created & permissions-fixed at startup

6. Import Bands or Musicians using this command:

```bash
docker compose exec php bin/console bandito:import-artist 'Band/Musician Name'
```

7. Handy commands:

Rebuild (e.g. after changing composer.json or package.json):

```bash
docker compose up --build
```

Stop all containers:

```bash
docker compose down
```
Shell into the PHP container:

```bash
docker compose exec php bash
```