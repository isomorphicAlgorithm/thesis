# Bandito / Thesis Project Setup

## Prerequisites

- Docker & Docker Compose installed
- Git
- Composer
- Node.js and npm/yarn

## Setup Instructions

1. Clone the repo:
```bash
git clone git@github.com:isomorphicAlgorithm/thesis.git
cd thesis
```

2. Create .env.local with the correct credentials inside.

3. Start Docker containers:
```bash
docker-compose up -d
```

4. Install PHP dependencies:
```bash
cd app
composer install
```

5. Install node dependencies & build assets:
```bash
npm install
npm run dev
```

6. Run database migrations:
```bash
php bin/console doctrine:migrations:migrate
```

7. Run the app:
```bash
symfony server:start
```

8. Access the app:
http://localhost:9000
