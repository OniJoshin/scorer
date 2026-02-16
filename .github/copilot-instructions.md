# Copilot Instructions for Scorer

## Project Overview

**Scorer** is a Laravel 12 + NativePHP desktop application for tracking board game scores with flexible, game-specific scoring rules. The app runs as a cross-platform Electron desktop application while using Laravel as the backend framework.

## Architecture

### Core Stack
- **Backend**: Laravel 12 (PHP 8.2+) with Eloquent ORM
- **Frontend**: Blade templates + Alpine.js for interactivity + Tailwind CSS v4
- **Desktop**: NativePHP (Electron wrapper) - configured in `app/Providers/NativeAppServiceProvider.php`
- **Database**: MariaDB 10.11 (via DDEV) or SQLite for testing
- **Build Tools**: Vite with Laravel plugin and Tailwind CSS plugin

### Domain Model

The application has two scoring systems:

1. **Session-based scoring** (primary, newer):
   - `GameSession` represents a play session
   - `GameSessionResult` stores each player's performance with flexible `custom_score` JSON field
   - Supports dynamic scoring rules per game

2. **Legacy scoring**:
   - `Score` model links Games and Players (simpler, position-based)

**Key Models & Relationships:**
- `Game` → `GameSession` (one-to-many)
- `GameSession` → `GameSessionResult` (one-to-many) 
- `GameSessionResult` → `Player` (many-to-one)
- `Game` has `scoring_rules` JSON column defining custom score fields
- `GameSessionResult` has `custom_score` JSON column storing score data

### Flexible Scoring System

Games define custom scoring rules as JSON in `games.scoring_rules`. Example from `GameScoringSeeder`:

```php
[
    'fields' => [
        ['key' => 'routes', 'label' => 'Completed Routes', 'type' => 'number'],
        ['key' => 'longest_route', 'label' => 'Longest Route Bonus', 'type' => 'checkbox', 'points' => 10],
        ['key' => 'stations_left', 'label' => 'Unused Stations', 'type' => 'number', 'multiplier' => 4],
    ],
    'auto_rank' => 'sum'
]
```

Scoring fields support:
- `type`: 'number' or 'checkbox'
- `points`: fixed points for checkboxes
- `multiplier`: multiplies field value for calculations
- `auto_rank`: 'sum' calculates totals automatically

The `GameSessionController::updateResults()` saves player scores to `custom_score` JSON field.

## Development Workflow

### DDEV Environment (Primary)

The project uses DDEV for local development:

```bash
# Start environment
ddev start

# Access application
https://scores.ddev.site

# Run Artisan commands
ddev artisan migrate
ddev artisan db:seed
ddev artisan tinker

# Run tests
ddev exec vendor/bin/phpunit

# Database access
ddev phpmyadmin  # Opens PHPMyAdmin

# Xdebug (use tasks or commands)
ddev xdebug on
ddev xdebug off
```

DDEV config: `.ddev/config.yaml` (Laravel project type, PHP 8.3, MariaDB 10.11)

### Asset Building

```bash
# Development (watch mode)
npm run dev

# Production build
npm run build
```

Vite config includes Tailwind CSS v4 plugin - no separate `tailwind.config.js` needed.

### Testing

PHPUnit configured with SQLite in-memory database (`phpunit.xml`):
- Feature tests: `tests/Feature/`
- Unit tests: `tests/Unit/`

```bash
ddev exec vendor/bin/phpunit
# or
./vendor/bin/phpunit  # if not using DDEV
```

### NativePHP Desktop App

Build desktop application:

```bash
php artisan native:serve    # Development mode
php artisan native:build    # Production builds
```

Window configuration in `app/Providers/NativeAppServiceProvider.php`.

## Code Conventions

### Models

All models use standard Laravel conventions:
- Mass-assignable fields in `$fillable`
- Date fields in `$casts` as 'datetime'
- JSON fields in `$casts` as 'array'
- Relationships follow Laravel naming (camelCase methods)

Example: [app/Models/GameSession.php](app/Models/GameSession.php)

### Controllers

Standard Laravel resource controllers with additional custom routes:
- Resource routes for CRUD: `PlayerController`, `GameController`, `ScoreController`
- Custom session workflow: `GameSessionController` has `complete()` and `updateResults()` methods
- See [routes/web.php](routes/web.php) for routing patterns

### Views & Frontend

- **Location**: `resources/views/` organized by resource (`game_sessions/`, `players/`, etc.)
- **Layout**: `resources/views/layouts/` (if exists)
- **Styling**: Tailwind CSS v4 utility classes (no config file, uses defaults)
- **Interactivity**: Alpine.js for dynamic behavior (e.g., score calculation in `game_sessions/show.blade.php`)

Alpine.js pattern for score entry:
```blade
<div x-data="scoreEntry({{ json_encode($scoringRules['fields'] ?? []) }}, @json($result->custom_score ?? []))">
    <!-- Dynamic form fields based on scoring rules -->
    <span x-text="total"></span>  <!-- Computed total -->
</div>
```

### Database

- **Migrations**: Follow Laravel timestamp naming (`YYYY_MM_DD_HHMMSS_description.php`)
- **Seeders**: `GameScoringSeeder` demonstrates how to set up games with scoring rules
- Always use `Schema::table()` for alterations (see [2025_06_11_102053_add_scoring_rules_to_games_table.php](database/migrations/2025_06_11_102053_add_scoring_rules_to_games_table.php))

## Key Files

- [app/Models/GameSession.php](app/Models/GameSession.php) - Core session model
- [app/Models/GameSessionResult.php](app/Models/GameSessionResult.php) - Player results with flexible scoring
- [app/Models/Game.php](app/Models/Game.php) - Game definitions with `scoring_rules` JSON
- [app/Http/Controllers/GameSessionController.php](app/Http/Controllers/GameSessionController.php) - Session workflow logic
- [database/seeders/GameScoringSeeder.php](database/seeders/GameScoringSeeder.php) - Example scoring rules setup
- [app/Providers/NativeAppServiceProvider.php](app/Providers/NativeAppServiceProvider.php) - Desktop window configuration
- [routes/web.php](routes/web.php) - All HTTP routes

## Common Tasks

**Add a new scorable game:**
1. Create seeder or manual entry with `scoring_rules` JSON
2. Include fields array with scoring configuration
3. Update views if custom display logic needed

**Modify scoring calculation:**
- Frontend: Alpine.js component in blade views
- Backend: `GameSessionController::updateResults()` saves to `custom_score`

**Add database fields:**
1. Create migration with `php artisan make:migration`
2. Add to model's `$fillable` and `$casts` if needed
3. Run `ddev artisan migrate`

**Debug desktop app:**
- Check `app/Providers/NativeAppServiceProvider.php` for window config
- NativePHP docs: https://nativephp.com/docs/
