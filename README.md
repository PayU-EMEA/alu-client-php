# PayU Automatic Live Update Client Library
[![Travis CI](https://travis-ci.org/PayU-EMEA/alu-client-php.svg)](https://travis-ci.org/PayU-EMEA/alu-client-php) [![Latest Stable Version](https://poser.pugx.org/payu/alu-client/v/stable.svg)](https://packagist.org/packages/payu/alu-client) [![Total Downloads](https://poser.pugx.org/payu/alu-client/downloads.svg)](https://packagist.org/packages/payu/alu-client) [![License](https://poser.pugx.org/payu/alu-client/license.svg)](https://packagist.org/packages/payu/alu-client)

Documentation in [Russian](docs-intl/README.ru.md).

## Prerequisites

 * PHP 5.6 and above
 * curl extension with support for OpenSSL
 * PHPUnit 4.8.0 for running test suite (Optional)
 * Composer (Optional)

## Composer

You can install the library via [Composer](http://getcomposer.org/). Add this to your composer.json:

    {
      "require": {
        "payu/alu-client": "1.*"
      }
    }

Then install via:

    composer install

To use the library, include Composer's [autoload](https://getcomposer.org/doc/00-intro.md#autoloading]):

    require_once('vendor/autoload.php');

## Manual Installation

Obtain the latest version of the PayU Automatic Live Update Client Library with:

    git clone https://github.com/PayU/alu-client-php.git

To use the Library, add the following to your PHP script:

    require_once("/path/to/payu/alu-client/src/init.php");

## Getting Started

You can find usage examples in the examples directory:

* basicExample.php - Minimal requirements for order authorization via ALU protocol using Credit Card Information (If you are PCI DSS compliant)
* tokenPayment.php - Minimal requirements for order authorization via ALU protocol using Token
* threeDSReturn.php - Example of return from 3D Secure authorization and response
