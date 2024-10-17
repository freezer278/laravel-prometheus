# Prometheus client for laravel framework

[![Latest Version on Packagist](https://img.shields.io/packagist/v/vmorozov/laravel-prometheus.svg?style=flat-square)](https://packagist.org/packages/vmorozov/laravel-prometheus)
[![Tests](https://img.shields.io/github/actions/workflow/status/vmorozov/laravel-prometheus/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/vmorozov/laravel-prometheus/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/vmorozov/laravel-prometheus.svg?style=flat-square)](https://packagist.org/packages/vmorozov/laravel-prometheus)

This is where your description should go. Try and limit it to a paragraph or two. Consider adding a small example.

## Installation

1. Install the package via composer:

```bash
composer require vmorozov/laravel-prometheus
```
2. Publish vendor files:
```bash
php artisan vendor:publish --provider="VMorozov\\Prometheus\\PrometheusServiceProvider"
```

## Usage

This package provides 2 default metrics that allow to get response times (divided into buckets and percentiles) and requests counts.
To see the collected metrics go to `/metrics` endpoint of your application.

[//]: # (## Configuration)

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Vladimir Morozov](https://github.com/vmorozov)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
