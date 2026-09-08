<div class="filament-hidden">

![Laravel Optimizely](https://raw.githubusercontent.com/jeffersongoncalves/laravel-optimizely/main/art/jeffersongoncalves-laravel-optimizely.png)

</div>

# Laravel Optimizely

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/laravel-optimizely.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-optimizely)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-optimizely/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jeffersongoncalves/laravel-optimizely/actions?query=workflow%3ATests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-optimizely/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/jeffersongoncalves/laravel-optimizely/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/laravel-optimizely.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-optimizely)
[![License](https://img.shields.io/packagist/l/jeffersongoncalves/laravel-optimizely.svg?style=flat-square)](LICENSE.md)

A PHP/Laravel client for the [Optimizely](https://www.optimizely.com/) REST API v2. Covers projects, experiments, campaigns, audiences, events and pages through a simple, typed API built on Laravel's `Http` client.

## Features

- Projects: list, get, create, update
- Experiments: list (with status filter), get, create, update, results, archive
- Campaigns: list, get, results
- Audiences: list, get
- Events and Pages: list
- A default project id from config, so every scoped call can omit it
- Throws `OptimizelyException` (with the original API error body) on any non-2xx response

## Installation

You can install the package via composer:

```bash
composer require jeffersongoncalves/laravel-optimizely
```

Publish the config file:

```bash
php artisan vendor:publish --tag=optimizely-config
```

Set your Optimizely credentials in `.env`:

```env
OPTIMIZELY_API_KEY=your-personal-access-token
OPTIMIZELY_PROJECT_ID=1234
```

Generate the token under **Profile > API Access** in your Optimizely account.

## Configuration

```php
// config/optimizely.php
return [
    'api_key' => env('OPTIMIZELY_API_KEY', ''),
    'base_url' => env('OPTIMIZELY_BASE_URL', 'https://api.optimizely.com/v2'),
    'project_id' => env('OPTIMIZELY_PROJECT_ID'),
    'default_per_page' => env('OPTIMIZELY_DEFAULT_PER_PAGE', 25),
];
```

## Usage

The package is resolved via the `Optimizely` facade or by injecting `JeffersonGoncalves\Optimizely\Optimizely`. Each API group is exposed as a method returning a dedicated resource class.

### Projects

```php
use JeffersonGoncalves\Optimizely\Facades\Optimizely;

$projects = Optimizely::projects()->list(page: 1, perPage: 50);
$project = Optimizely::projects()->get(1234);
$created = Optimizely::projects()->create('Marketing Site', platform: 'web');
$updated = Optimizely::projects()->update(1234, ['name' => 'Marketing Site v2']);
```

### Experiments

```php
// Omits the project id — falls back to config('optimizely.project_id')
$experiments = Optimizely::experiments()->list(status: 'running');

$experiment = Optimizely::experiments()->get(555);

$created = Optimizely::experiments()->create(
    name: 'Homepage CTA',
    type: 'a/b',
    trafficAllocation: 5000,
);

$updated = Optimizely::experiments()->update(555, ['status' => 'paused']);
$results = Optimizely::experiments()->results(555, '2026-01-01', '2026-01-31');
$archived = Optimizely::experiments()->archive(555);
```

### Campaigns, audiences, events and pages

```php
$campaigns = Optimizely::campaigns()->list();
$campaign = Optimizely::campaigns()->get(777);
$results = Optimizely::campaigns()->results(777);

$audiences = Optimizely::audiences()->list();
$audience = Optimizely::audiences()->get(888);

$events = Optimizely::events()->list();
$pages = Optimizely::pages()->list();

// Any scoped resource accepts an explicit project id as the first argument
$events = Optimizely::events()->list(projectId: 4321);
```

### Error handling

Any non-2xx API response throws `JeffersonGoncalves\Optimizely\Exceptions\OptimizelyException`, which exposes the decoded error body:

```php
use JeffersonGoncalves\Optimizely\Exceptions\OptimizelyException;

try {
    Optimizely::experiments()->get(1);
} catch (OptimizelyException $e) {
    logger()->error($e->getMessage(), $e->errorBody());
}
```

Calling a scoped resource with no project id — neither passed nor configured — throws `InvalidArgumentException` before any HTTP call is made.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Jefferson Gonçalves](https://github.com/jeffersongoncalves)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
