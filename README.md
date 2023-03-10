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

You can view a list of the compiled rules and examples of what they do in the [style guide](./style-guide.md).

## Installation

You can install the package via composer:

```bash
composer require tightenco/duster --dev
```

Optionally you can publish a GitHub Actions config:

```bash
./vendor/bin/duster github-actions
```

## Usage

To lint everything at once:

```bash
./vendor/bin/duster lint
```

To fix everything at once:

```bash
./vendor/bin/duster fix
```

To dust only files that have uncommitted changes according to Git, you may use the `--dirty` option:

```bash
./vendor/bin/duster lint --dirty
#or
./vendor/bin/duster fix --dirty
```

To view all available commands:

```bash
./vendor/bin/duster
#or
./vendor/bin/duster commands
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

To run additional scripts as part of Duster first add them to `duster.json` as part of `scripts` separated into `lint` and `fix`.

The key is the name of the command (used with the `--using` flag), and the value is an array of arguments passed to [`Symfony\Component\Process\Process`](https://symfony.com/doc/current/components/process.html).

```json
{
    "scripts": {
        "lint": {
            "phpstan": ["./vendor/bin/phpstan", "analyse"]
        }
    }
}
```

Duster will pick these up automatically when running either `lint` or `fix`.

To customize which tools Duster runs, or the order in which they are executed you can use the `--using` flag and supply a comma-separated list of commands:

```bash
./vendor/bin/duster lint --using="phpstan,tlint,pint"
```

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

Now you can add customizations below the `<rule ref="Tighten"/>` line or even disable the Tighten rule to use your own ruleset. Learn more in this [introductory article](https://ncona.com/2012/12/creating-your-own-phpcs-standard/).

### PHP CS Fixer

Create a `.php-cs-fixer.dist.php` file in your project root with the contents from [Duster's `.php-cs-fixer.dist.php`](standards/.php-cs-fixer.dist.php) file. Learn more in the [PHP CS Fixer documentation](https://cs.symfony.com/doc/config.html).

### Pint

Create a `pint.json` file in your project root, you can use [Duster's `pint.json`](standards/pint.json) file as a starting point. Learn more in the [Pint documentation](https://laravel.com/docs/pint#configuring-pint).

## GitHub Action

There's a [GitHub Action](https://github.com/tighten/duster-action) you use to clean-up your workflows.



>**Warning** Heads Up! Workflows that commit to your repo will stop any currently running workflows and not trigger another workflow run.

One solution is to run your other workflows after Duster has completed by updating the trigger on those workflows:

```yml
on:
  # Commits made in Duster Fix will not trigger any workflows
  # This workflow is configured to run after Duster finishes
  workflow_run:
    workflows: ["Duster Fix"]
    types:
      - completed
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security-related issues, please email hello@tighten.co instead of using the issue tracker.

## Credits

- [Matt Stauffer](https://github.com/mattstauffer)
- [Anthony Clark](https://github.com/driftingly)
- [Tom Witkowski](https://github.com/devgummibeer) - much of the original idea and syntax for this was inspired by his [`elbgoods/ci-test-tools`](https://github.com/elbgoods/ci-test-tools) package
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
