# Movie App API

A Laravel-based REST API for managing movies and TV series with multilingual support. This application provides endpoints to browse and retrieve movie and series data with internationalization features.

## Features

- **Movie Management**: Browse and retrieve detailed movie information
- **Series Management**: Browse and retrieve detailed TV series information
- **Multilingual Support**: Content available in multiple languages (English, Polish, German)
- **Genre System**: Movies and series categorized by genres with translations
- **TMDB Integration**: Uses TMDB IDs for external movie database integration
- **RESTful API**: Clean, well-structured API endpoints
- **Pagination**: Efficient data retrieval with customizable pagination
- **Comprehensive Testing**: Full test coverage for API endpoints

## Tech Stack

- **Backend**: Laravel 12.0
- **PHP**: ^8.2
- **Database**: MySQL/PostgreSQL (configurable)
- **Testing**: PHPUnit
- **API Documentation**: RESTful design with proper HTTP status codes
- **Docker**: Docker Compose with Laravel Sail support

## API Endpoints

### Movies
- `GET /api/movies` - List all movies (paginated, ordered by popularity)
- `GET /api/movies/{id}` - Get detailed information about a specific movie

### Series
- `GET /api/series` - List all series (paginated, ordered by popularity)
- `GET /api/series/{id}` - Get detailed information about a specific series

### Request Headers
- `Accept-Language: en|pl|de` - Set language preference (defaults to English)

## Response Format

All endpoints return JSON responses with the following structure:

### List Endpoints
```json
{
  "data": [
    {
        "id": 1,
        "title": "Movie Title",
        "overview": "Movie description",
        "vote_average": 123.3,
        "vote_count": 123,
        "popularity": 123.123,
        "release_date": "2026-01-20",
        "poster_path": "/poster_path.jpg",
        "backdrop_path": "/backdrop.jpg",
        "tmdb_id": 123,
        "genres": [
            {
              "id": 1,
              "name": "Action"
            }
        ]
    }
  ],
  "links": {
    "first": "http://example.com/api/movies?page=1",
    "last": "http://example.com/api/movies?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "per_page": 20,
    "to": 1,
    "total": 1
  }
}
```

### Single Item Endpoints
```json
{
  "data": {
    "id": 1,
    "title": "Movie Title",
    "overview": "Movie description",
    "vote_average": 123.3,
    "vote_count": 123,
    "popularity": 123.123,
    "release_date": "2026-01-20",
    "poster_path": "/poster_path.jpg",
    "backdrop_path": "/backdrop.jpg",
    "tmdb_id": 123,
    "genres": [
      {
        "id": 1,
        "name": "Action"
      }
    ]
  }
}
```

## Database Schema

The application uses the following main tables:

- **movies** - Movie data with TMDB integration
- **series** - TV series data with TMDB integration
- **genres** - Genre categories
- **languages** - Supported languages
- **movie_translations** - Movie content translations
- **serie_translations** - Series content translations
- **genre_translations** - Genre name translations
- **movie_genre** - Movie-genre relationships
- **serie_genre** - Series-genre relationships

## Database Seeders

The application includes seeders to populate initial data:

### LanguageSeeder
Seeds the `languages` table with default supported languages:
- English (en)
- Deutsch (de) 
- Polski (pl)

The seeder uses `updateOrCreate()` to prevent duplicates when run multiple times.

### Running Seeders
```bash
# Run all seeders
php artisan db:seed

# Run specific seeder
php artisan db:seed --class=LanguageSeeder

# Fresh migration with seeding
php artisan migrate:fresh --seed
```

## Installation

### Prerequisites
- PHP ^8.2
- Composer
- Database (MySQL/PostgreSQL/SQLite)

### Setup

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd movie_app
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database**
   Edit your `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=movie_app
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Run migrations with seeders**
   ```bash
   php artisan migrate --seed
   ```
   
   This will create the database tables and seed them with initial data, including:
   - Languages (English, Deutsch, Polski) via LanguageSeeder
   - A test user account

6. **Install frontend assets** (if needed)
   ```bash
   npm install
   npm run build
   ```

## Docker Installation

### Prerequisites
- Docker Desktop
- Docker Compose

### Docker Setup

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd movie_app
   ```

2. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Configure Docker environment**
   Edit your `.env` file for Docker:
   ```env
   # Docker Configuration
   APP_PORT=8080
   VITE_PORT=5173
   FORWARD_DB_PORT=3306
   
   # Database Configuration
   DB_CONNECTION=mysql
   DB_HOST=mysql
   DB_PORT=3306
   DB_DATABASE=movie_app
   DB_USERNAME=sail
   DB_PASSWORD=password
   ```

4. **Start the Docker environment**
   ```bash
   docker compose up -d
   ```
   
   This will start:
   - Laravel application container (PHP 8.5)
   - MySQL 8.4 database container
   - All necessary networking and volumes

5. **Run migrations with seeders in Docker**
   ```bash
   docker compose exec laravel.test php artisan migrate --seed
   ```

6. **Install frontend assets in Docker**
   ```bash
   docker compose exec laravel.test npm install
   docker compose exec laravel.test npm run build
   ```

### Docker Commands

- **Start containers**: `docker compose up -d`
- **Stop containers**: `docker compose down`
- **View logs**: `docker compose logs -f`
- **Execute commands**: `docker compose exec laravel.test <command>`
- **Access shell**: `docker compose exec laravel.test bash`

### Docker Development Workflow

```bash
# Start development environment
docker compose up -d

# Run tests
docker compose exec laravel.test php artisan test

# Run migrations
docker compose exec laravel.test php artisan migrate

# Clear cache
docker compose exec laravel.test php artisan cache:clear

# Install new packages
docker compose exec laravel.test composer require <package>
```

The application will be available at `http://localhost:8080` (or whatever port you configured in `APP_PORT`).

## Available Scripts

The project includes custom Composer scripts for convenience:

- `composer setup` - Complete project setup (install, migrate, build)
- `composer dev` - Start development server with queue and logs
- `composer test` - Run the test suite

## Artisan Commands

### TMDB Data Fetching

The application includes commands to fetch data from TMDB API:

#### `tmdb:fetch`
Fetches comprehensive data from TMDB API:
- 50 popular movies
- 10 popular TV series  
- All available genres

```bash
php artisan tmdb:fetch
```

This command dispatches background jobs to fetch the data asynchronously and calls the individual sync commands.

#### Individual Sync Commands
- `php artisan tmdb:sync-movies` - Sync movies from TMDB
- `php artisan tmdb:sync-series` - Sync series from TMDB

**Note**: These commands require valid TMDB API credentials configured in your environment variables.

## Development

### Running the Development Server
```bash
php artisan serve
```

### Running Tests
```bash
php artisan test
# or
composer test
```

### Development Environment
For a complete development environment with all services:
```bash
composer dev
```

This will start:
- Laravel development server
- Queue worker
- Log monitoring
- Vite development server

## API Usage Examples

### Get Movies List
```bash
curl -X GET "http://localhost:8000/api/movies" \
  -H "Accept: application/json"
```

**For Docker setup (default port 8080):**
```bash
curl -X GET "http://localhost:8080/api/movies" \
  -H "Accept: application/json"
```

### Get Movies with Pagination
```bash
curl -X GET "http://localhost:8000/api/movies?per_page=10" \
  -H "Accept: application/json"
```

**For Docker setup (default port 8080):**
```bash
curl -X GET "http://localhost:8080/api/movies?per_page=10" \
  -H "Accept: application/json"
```

### Get Movies in Polish
```bash
curl -X GET "http://localhost:8000/api/movies" \
  -H "Accept: application/json" \
  -H "Accept-Language: pl"
```

**For Docker setup (default port 8080):**
```bash
curl -X GET "http://localhost:8080/api/movies" \
  -H "Accept: application/json" \
  -H "Accept-Language: pl"
```

### Get Single Movie
```bash
curl -X GET "http://localhost:8000/api/movies/1" \
  -H "Accept: application/json"
```

**For Docker setup (default port 8080):**
```bash
curl -X GET "http://localhost:8080/api/movies/1" \
  -H "Accept: application/json"
```

## Testing

The application includes comprehensive feature tests covering:
- API endpoint responses
- Pagination functionality
- Language switching
- Data validation
- Error handling

Run tests with:
```bash
php artisan test --coverage
```

## Configuration

### Supported Languages
Configure supported locales in your `.env` file:
```env
SUPPORTED_LOCALES=en,pl,de
```

The application will automatically parse this comma-separated list of supported language codes.

### Pagination
Default pagination is set to 20 items per page with validation:
- Minimum: 3 items per page
- Maximum: 50 items per page

## Project Structure

```
app/
├── Http/
│   ├── Controllers/     # API controllers
│   └── Resources/       # API resource transformers
├── Models/             # Eloquent models
├── Repository/         # Data access layer
└── Services/           # Business logic
database/
├── migrations/         # Database schema
└── seeders/           # Sample data
tests/Feature/         # API endpoint tests
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Run the test suite
6. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
