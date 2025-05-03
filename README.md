# GeoBrandTopList

## Prerequisites

- Docker
- PHP 8.1+ and Composer installed (for the Laravel 10)

## Quick Project Structure

- **api/**            Laravel backend Api
- **web/**            Frontend (HTML/CSS/JS) + Nginx config
- **docker-compose.yml**

## Running the Frontend

1. From the project root, run:
   docker compose up -d

2. Open your browser and go to:
   http://localhost:5500/login.html


## Running the Laravel Backend (manually)

1. Open a new terminal and go to the backend directory:
   cd api

2. Install PHP dependencies:
   composer install

3. Copy the example environment file:
   cp .env.example .env

4. Generate the application key:
   php artisan key:generate

5. Start the Laravel development server on port 7000:
   php artisan serve --host=0.0.0.0 --port=7000

6. The backend will be available at:
   http://localhost:7000/api/v1/...

## CRUD via cURL or Postman

You can test the API endpoints using tools like Postman or cURL. Make sure your backend is running (via `php artisan serve` on port 7000) and accessible from the frontend or your HTTP client.

## Stopping and Cleaning Up

To stop containers and remove volumes (frontend + DB):
  docker compose down -v

To restart:
  docker compose up -d

## Notes

- Manage the overall monorepo with pnpm,
- Dockerize the backend && database
- Implement a proper dashbaord view :l

