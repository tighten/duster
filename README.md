![Project Banner](https://raw.githubusercontent.com/tighten/duster/main/banner.png)
# (WIP) Duster

[![Latest Version on Packagist](https://img.shields.io/packagist/v/tightenco/duster.svg?style=flat-square)](https://packagist.org/packages/tightenco/duster)

Automatically apply Tighten's default code style for Laravel apps:

- PHPCS and PHPCS-Fixer, with PSR-2 + some special preferences
- Tighten's Tlint
- Maybe JS and CSS?

## Installation

You can install the package via composer:

```bash
composer require tightenco/duster
```

## Usage

To run individual lints:

```bash
./vendor/bin/duster-tlint-lint
./vendor/bin/duster-phpcs-lint
```

To run individual fixes:

```bash
./vendor/bin/duster-tlint-fix
./vendor/bin/duster-phpcs-fix
```

To lint everything at once:

```bash
./vendor/bin/duster-lint
```

To fix everything at once:

```bash
./vendor/bin/duster-fix
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email hello@tighten.co instead of using the issue tracker.

## Credits

- [Matt Stauffer](https://github.com/mattstauffer)
- [Tom Witkowski](https://github.com/devgummibeer) -- much of the idea and syntax for this was inspired by his `elbgoods/ci-test-tools` package
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
