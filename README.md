![Project Banner](https://raw.githubusercontent.com/tighten/duster/main/banner.png)
# Duster

Automatically apply Tighten's default code style for Laravel apps.

Duster is built on top of the following tools:

- TLint: lints Laravel and PHP code for issues not covered by other tools
  - using the default `Tighten` preset
- PHP_CodeSniffer: sniffs issues that can't be fixed automatically
  - using the `Tighten` preset which is mostly PSR1 with some Tighten-specific rules
- PHP CS Fixer: adds custom rules not supported by Laravel Pint
  - `CustomOrderedClassElementsFixer` Tighten-specific order of class elements
- Pint: Laravel's code style rules (with a few Tighten specific customizations)
  - using the default `Laravel` preset with some Tighten-specific rules

New Laravel Zero architecture inspired by and relies on Laravel Pint.

## Installation

You can install the package via composer:

```bash
composer require tightenco/duster --dev
```

Optionally you can publish a GitHub Actions linting config:

```bash
./vendor/bin/duster --github-actions
```

## Usage

To lint everything at once:

```bash
./vendor/bin/duster

# or
./vendor/bin/duster --lint
```

To fix everything at once:

```bash
./vendor/bin/duster --fix
```

To view all available commands:

```bash
./vendor/bin/duster --help
```

## Customizing

If you need to include or exclude files or directories for each tool you can create a `duster.json` config file in your project root:

```json
{
    "include": [
        "bin",
        "scripts",
        "src",
        "tests"
    ],
    "exclude": [
        "tests/fixtures"
    ]
}
```

This is especially helpful when including Duster in Laravel Zero applications.

### TLint

Create a `tlint.json` file in your project root. Learn more in the [TLint documentation](https://github.com/tighten/tlint#configuration).

### PHP_CodeSniffer

Create a `.phpcs.xml.dist` file in your project root with the following:

```xml
<?xml version="1.0"?>
<ruleset>
    <file>app</file>
    <file>config</file>
    <file>database</file>
    <file>public</file>
    <file>resources</file>
    <file>routes</file>
    <file>tests</file>

    <rule ref="Tighten"/>
</ruleset>
```

Now you can add customizations below the `<rule ref="Tighten"/>` line or even disable the Tighten rule and use your own ruleset. Learn more in this [introductory article](https://ncona.com/2012/12/creating-your-own-phpcs-standard/).

### PHP CS Fixer

Create a `.php-cs-fixer.dist.php` file in your project root with the contents from [Duster's `.php-cs-fixer.dist.php`](.php-cs-fixer.dist.php) file. Learn more in the [PHP CS Fixer documentation](https://cs.symfony.com/doc/config.html).

### Pint

Create a `pint.json` file in your project root, you can use [Duster's `pint.json`](pint.json) file as a starting point. Learn more in the [Pint documentation](https://laravel.com/docs/pint#configuring-pint).

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security-related issues, please email hello@tighten.co instead of using the issue tracker.

## Credits

- [Matt Stauffer](https://github.com/mattstauffer)
- [Tom Witkowski](https://github.com/devgummibeer) - much of the original idea and syntax for this was inspired by his [`elbgoods/ci-test-tools`](https://github.com/elbgoods/ci-test-tools) package
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
