![Project Banner](https://raw.githubusercontent.com/tighten/duster/main/banner.png)
# Duster

Automatically apply Tighten's default code style for Laravel apps:

- PHPCS and PHP-CS-Fixer, with PSR-12 + some special preferences
- Tighten's Tlint
- Maybe JS and CSS?

To achieve this, this package installs PHPCS, PHP-CS-Fixer, and Tlint, and automatically configures them. Tlint uses the default `Tighten` preset. PHPCS uses the `PSR-12` preset and a few Tighten-specific rules. PHP-CS-Fixer uses the `PSR-12` preset and a few Tighten-specific rules.

## Installation

You can install the package via composer:

```bash
composer require tightenco/duster
```

PHPCS generates a file named `.php_cs.cache` that you'll want to ignore in Git. You can manually add this file to your `.gitignore` yourself, or run `./vendor/bin/duster init` and it'll add it for you.

## Usage

To lint everything at once:

```bash
./vendor/bin/duster lint
```

To fix everything at once:

```bash
./vendor/bin/duster fix
```

To run individual lints:

```bash
./vendor/bin/duster tlint
./vendor/bin/duster phpcs
./vendor/bin/duster phpcsfixer
```

To run individual fixes:

```bash
./vendor/bin/duster tlint fix
./vendor/bin/duster phpcs fix
./vendor/bin/duster phpcsfixer fix
```

### Customizing the lints

To override the PHPCS configuration, put your own `.phpcs.xml.dist` file in the root of your project.

To override the PHPCS-Fixer configuration, put your own `.php_cs.dist` file in the root of your project.

To override the Tlint configuration, put your own `tlint.json` file in the root of your project.

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
