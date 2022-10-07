# Typify

[![PHP from Packagist](https://img.shields.io/packagist/php-v/decodelabs/typify?style=flat)](https://packagist.org/packages/decodelabs/typify)
[![Latest Version](https://img.shields.io/packagist/v/decodelabs/typify.svg?style=flat)](https://packagist.org/packages/decodelabs/typify)
[![Total Downloads](https://img.shields.io/packagist/dt/decodelabs/typify.svg?style=flat)](https://packagist.org/packages/decodelabs/typify)
[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/decodelabs/typify/Integrate)](https://github.com/decodelabs/typify/actions/workflows/integrate.yml)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-44CC11.svg?longCache=true&style=flat)](https://github.com/phpstan/phpstan)
[![License](https://img.shields.io/packagist/l/decodelabs/typify?style=flat)](https://packagist.org/packages/decodelabs/typify)

### Mime type detection tools for PHP

Use typify to identify and apply mime types information to your files and responses.

_Get news and updates on the [DecodeLabs blog](https://blog.decodelabs.com)._

---


## Installation

```bash
composer require decodelabs/typify
```

## Usage

### Importing

Typify uses [Veneer](https://github.com/decodelabs/veneer) to provide a unified frontage under <code>DecodeLabs\Typify</code>.
You can access all the primary functionality via this static frontage without compromising testing and dependency injection.


### Detecting types

Detect a mime type for a file path:

```php
use DecodeLabs\Typify;

echo Typify::detect(__FILE__);
// application/x-php
```

Get known extensions for a type:

```php
use DecodeLabs\Typify;

$exts = Typify::getExtensionsFor('text/plain');
// txt, text, conf, def, list, log, in
```

Suggest an extension for a mime type:

```php
use DecodeLabs\Typify;

echo Typify::getExtensionFor('text/plain');
// txt
```

## Licensing
Typify is licensed under the MIT License. See [LICENSE](./LICENSE) for the full license text.
