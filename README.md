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
docker-compose exec php composer install
```

5. Install node dependencies & build assets:
```bash
docker-compose exec php npm install
docker-compose exec php npm run dev
```

6. Run database migrations:
```bash
docker-compose exec php php bin/console doctrine:migrations:migrate
```

7. Access the app:
The app should now be accessible at: http://localhost:9000