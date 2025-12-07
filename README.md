# Peak Bagger

A Laravel application for tracking mountain peak ascents and building your personal peak-bagging portfolio.

## Overview

Peak Bagger is a web application that allows outdoor enthusiasts to track their mountain peak climbs. Users can browse a database of peaks, record their ascents with dates and notes, and upload photos from their climbs. The application provides an organized way to document your mountaineering achievements and share your experiences.

## Technology Stack

### Backend
- **PHP 8.2+** - Modern PHP with type declarations and constructor property promotion
- **Laravel 12** - Latest Laravel framework with streamlined file structure
- **Laravel Fortify** - Headless authentication backend providing login, registration, password reset, and two-factor authentication
- **Spatie Laravel Media Library** - Media management with S3 storage and CloudFront CDN delivery
- **Intervention Image** - Image processing and manipulation

### Frontend
- **Livewire 3** - Full-stack framework for building dynamic interfaces without leaving PHP
- **Livewire Volt** - Single-file components with functional and class-based APIs
- **Flux UI (Free Edition)** - Pre-built UI component library for Livewire
- **Tailwind CSS 4** - Utility-first CSS framework with CSS-first configuration
- **Alpine.js** - Lightweight JavaScript framework (included with Livewire)
- **Vite** - Modern frontend build tool

### Development & Testing
- **Pest 4** - Testing framework with browser testing, smoke testing, and visual regression testing
- **Laravel Pint** - Opinionated PHP code style fixer
- **Laravel Sail** - Docker-based local development environment
- **Laravel Pail** - Real-time log tailing

## Key Features

### Peak Database
- Comprehensive database of mountain peaks with:
  - Peak name and category
  - GPS coordinates (latitude/longitude)
  - Elevation information
  - Additional notes
- Search functionality to find peaks by name or category
- Filter peaks by category

### Ascent Tracking
- Record your climbs with:
  - Date of ascent
  - Personal notes about the experience
  - Multiple photo uploads from your climb
- View your ascent history
- Delete ascents if needed

### Media Management
- Upload multiple photos for each ascent
- Automatic image processing:
  - Thumbnail generation (300x300, cropped)
  - Preview images (1200px width, maintaining aspect ratio)
- Photos stored on Amazon S3 and delivered via CloudFront CDN
- Queued image conversions for better performance

### Authentication
- User registration and login
- Email verification
- Password reset functionality
- Two-factor authentication support
- Profile management
- Password change

### User Interface
- Modern, responsive design
- Dark mode support
- Accessible components from Flux UI
- Real-time updates with Livewire
- Loading states and user feedback

## Setup Instructions

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js and npm
- Database (MySQL, PostgreSQL, or SQLite)
- AWS S3 bucket and CloudFront distribution (for media storage)

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd peak-bagger
   ```

2. **Install dependencies and setup**
   ```bash
   composer setup
   ```
   This command will:
   - Install PHP dependencies
   - Copy `.env.example` to `.env`
   - Generate application key
   - Run database migrations
   - Install Node dependencies
   - Build frontend assets

3. **Configure environment variables**
   
   Edit `.env` and set the following required variables:
   
   ```env
   # Database
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=peak_bagger
   DB_USERNAME=root
   DB_PASSWORD=

   # AWS S3 (for media storage)
   AWS_ACCESS_KEY_ID=your-access-key
   AWS_SECRET_ACCESS_KEY=your-secret-key
   AWS_DEFAULT_REGION=us-east-1
   AWS_BUCKET=your-bucket-name
   FILESYSTEM_DISK=s3
   MEDIA_DISK=s3
   
   # CloudFront (for media delivery)
   CLOUDFRONT_URL=https://your-cloudfront-domain.example

   # Queue (for image processing)
   QUEUE_CONNECTION=database
   ```

4. **Seed the database (optional)**
   
   If you have a Wainwright dataset, place it at `database/seeders/data/wainwrights.json` and run:
   ```bash
   php artisan db:seed
   ```

### Development Workflow

#### Start the development server

Run all development services concurrently:
```bash
composer run dev
```

This starts:
- Laravel development server (`php artisan serve`)
- Queue worker (`php artisan queue:listen`)
- Log viewer (`php artisan pail`)
- Vite dev server (`npm run dev`)

#### Or start services individually

```bash
# Start Laravel server
php artisan serve

# Start queue worker (required for image processing)
php artisan queue:work --tries=3

# Watch frontend assets
npm run dev

# Tail logs
php artisan pail
```

#### Build for production
```bash
npm run build
```

### Testing

#### Run all tests
```bash
php artisan test
# or
composer run test
```

#### Run specific test file
```bash
php artisan test tests/Feature/ExampleTest.php
```

#### Run tests by filter/name
```bash
php artisan test --filter=testName
```

### Code Style

Format code using Laravel Pint:
```bash
vendor/bin/pint
```

Or format only changed files:
```bash
vendor/bin/pint --dirty
```

## Project Structure

```
app/
├── Actions/          # Fortify actions (user creation, password reset, etc.)
├── Console/          # Artisan commands (auto-registered)
├── Http/
│   ├── Controllers/  # HTTP controllers
│   └── Requests/     # Form request validation classes
├── Livewire/         # Livewire components (if any)
├── Models/           # Eloquent models
│   ├── Ascent.php    # Ascent model with media collection
│   ├── Peak.php      # Peak model with search/filter scopes
│   └── User.php      # User model with authentication
└── Policies/         # Authorization policies

database/
├── factories/        # Model factories for testing
├── migrations/       # Database migrations
└── seeders/          # Database seeders

resources/
├── views/            # Blade templates and Volt components
│   ├── components/   # Reusable UI components
│   ├── flux/         # Custom Flux UI components
│   ├── pages/        # Volt page components
│   └── settings/     # Settings page components
└── css/              # Stylesheets (Tailwind)

routes/
├── console.php       # Artisan console routes
└── web.php           # Web routes

tests/
├── Feature/          # Feature tests
└── Unit/             # Unit tests
```

## Additional Documentation

- [Media Library Setup](docs/medialibrary-setup.md) - Detailed S3 and CloudFront configuration
- [CI/CD](CI.md) - Continuous integration and deployment information
- [Agent Guidelines](AGENTS.md) - Guidelines for AI coding assistants

## Database Schema

### Peaks Table
- `id` - Primary key
- `name` - Peak name
- `category` - Peak category/classification
- `lat`, `lon` - GPS coordinates (decimal degrees)
- `elevation` - Elevation in meters/feet
- `notes` - Additional information
- `created_at`, `updated_at` - Timestamps

### Ascents Table
- `id` - Primary key
- `user_id` - Foreign key to users table
- `peak_id` - Foreign key to peaks table
- `date` - Date of ascent
- `notes` - Personal notes about the climb
- `created_at`, `updated_at` - Timestamps

### Media Table
- Managed by Spatie Media Library
- Stores photos associated with ascents
- Includes metadata for thumbnails and previews

## Contributing

When contributing to this project:

1. Follow existing code conventions and patterns
2. Use Laravel's built-in features (Eloquent relationships, query scopes, etc.)
3. Write tests for new features using Pest
4. Use array-based validation rules (e.g., `['required', 'date']`)
5. Format code with Laravel Pint before committing
6. Follow Laravel 12 structure conventions

## License

MIT License - See composer.json for details.
