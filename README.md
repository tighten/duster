![Project Banner](https://raw.githubusercontent.com/tighten/duster/main/banner.png)
# Duster

Automatically apply Tighten's default code style for Laravel apps using:

- [Tighten's Coding Standard](https://github.com/tighten/tighten-coding-standard)
- [Tighten's Tlint](https://github.com/tighten/tlint)

This package installs and automatically configures both tools.

Tighten Coding Standard uses [Easy Coding Standard](https://github.com/symplify/easy-coding-standard) under the hood which combines [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) and [PHP Coding Standards Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) into one configuration.  The default `Tighten` config is PSR-12 and a few Tighten-specific rules.

Tlint uses the default `Tighten` preset.

## Installation

You can install the package via composer:

```bash
composer require tightenco/duster --dev
./vendor/bin/duster init
```

You must run `./vendor/bin/duster init` after installing, or you won't have a local copy of the TCS config file, and Duster won't work.

The `init` command will also optionally add a GitHub action to run Duster's linters.

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
./vendor/bin/duster tcs
```

To run individual fixes:

```bash
./vendor/bin/duster tlint fix
./vendor/bin/duster tcs fix
```

### Customizing the lints

Both tools can be completely customized to suit your project's needs. To learn more about how to customize each tool refer to the [Tighten Coding Standard documentation](https://github.com/tighten/tighten-coding-standard#configuration) and [TLint documentation](https://github.com/tighten/tlint#configuration)

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
