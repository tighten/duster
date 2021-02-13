![Project Banner](https://raw.githubusercontent.com/tighten/duster/main/banner.png)
# Duster

Automatically apply Tighten's default code style for Laravel apps:

- PHPCS, with PSR-12 + some special preferences
- Tighten's Tlint
- Maybe JS and CSS?

To achieve this, this package installs PHPCS (and PHPCBF with it) and Tlint, and automatically configures them. Tlint uses the default `Tighten` preset. PHPCS uses the [`Tighten` preset](https://github.com/tighten/tighten-coding-standard) which is `PSR-12` and a few Tighten-specific rules.

## Installation

You can install the package via composer:

```bash
composer require tightenco/duster
./vendor/bin/duster init
```

You must run `./vendor/bin/duster init` after installing, or you won't have a local copy of the PHPCS config file, and Duster won't work.

The `init` command will also add `.php_cs.cache` to your `.gitignore` and, optionally, add a GitHub action to run Duster's linters.

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
```

To run individual fixes:

```bash
./vendor/bin/duster tlint fix
./vendor/bin/duster phpcs fix
```

### Customizing the lints

To override the configuration for PHPCS, you can edit the `.phpcs.xml.dist` file and add customizations below the `<rule ref="Tighten"/>` line or even disable the Tighten rule and use your own ruleset. Learn more in this [introductory article](https://ncona.com/2012/12/creating-your-own-phpcs-standard/).

To override the configuration for Tlint, create a `tlint.json` file in your project root. Learn more in the [Tlint documentation](https://github.com/tighten/tlint#configuration).

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email hello@tighten.co instead of using the issue tracker.

## Credits

- [Matt Stauffer](https://github.com/mattstauffer)
- [Tom Witkowski](https://github.com/devgummibeer) -- much of the originalk idea and syntax for this was inspired by his `elbgoods/ci-test-tools` package
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
