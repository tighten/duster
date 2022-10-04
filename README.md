![Project Banner](https://raw.githubusercontent.com/tighten/duster/main/banner.png)
# Duster

Automatically apply Tighten's default code style for Laravel apps.

To achieve this, Duster installs and automatically configures the following tools:

- TLint: Opinionated code linter for Laravel and PHP
  - using the default `Tighten` preset
- PHP_CodeSniffer: catch issues that can't be fixed automatically
  - using the `Tighten` preset which is mostly PSR1 with some Tighten-specific rules
- PHP CS Fixer: custom rules not supported by Laravel Pint
  - `CustomOrderedClassElementsFixer` Tighten-specific order of class elements
- Pint: Laravel's code style rules (with a few Tighten specific customizations)
  - using the default `Laravel` preset with some Tighten-specific rules

## Installation

You can install the package via composer:

```bash
composer require tightenco/duster --dev
```

Optionally you can publish GitHub Actions linting config:

```bash
duster github-actions
```

## Usage

To lint everything at once:

```bash
duster
```

To fix everything at once:

```bash
duster fix
```

To view all available commands:

```bash
duster help
```

## Customizing

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

Create a `.php-cs-fixer.dist.php` file in your project root with the contents from [Duster's `.php-cs-fixer.dist.php`](.php-cs-fixer.dist.php).  Learn more in the [PHP CS Fixer documentation](https://cs.symfony.com/doc/config.html).

### Pint

Create a `pint.json` file in your project root with the following:

```json
{
    "preset": "laravel",
    "rules": {
        "concat_space": {
            "spacing": "one"
        },
        "class_attributes_separation": {
        }
    }
}
```

Now you can add or remove customizations. Learn more in the [Pint documentation](https://laravel.com/docs/pint#configuring-pint).

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email hello@tighten.co instead of using the issue tracker.

## Credits

- [Matt Stauffer](https://github.com/mattstauffer)
- [Tom Witkowski](https://github.com/devgummibeer) - much of the original idea and syntax for this was inspired by his [`elbgoods/ci-test-tools`](https://github.com/elbgoods/ci-test-tools) package
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
