# клиенткая библиотека PayU для протокола Automatic Live Update
[![Travis CI](https://travis-ci.org/PayU/alu-client-php.svg)](https://travis-ci.org/PayU/alu-client-php) [![Latest Stable Version](https://poser.pugx.org/payu/alu-client/v/stable.svg)](https://packagist.org/packages/payu/alu-client) [![Total Downloads](https://poser.pugx.org/payu/alu-client/downloads.svg)](https://packagist.org/packages/payu/alu-client) [![License](https://poser.pugx.org/payu/alu-client/license.svg)](https://packagist.org/packages/payu/alu-client)

## Требования

 * PHP 5.3 или выше
 * curl extension с поддержкой OpenSSL
 * PHPUnit 4.2.0 для запуска набора тестов (по желанию)
 * Composer (по желанию)

## Composer

Вы можете установить библиотеку используя [Composer](http://getcomposer.org/). Добавьте следующий кусок кода в ваш файлcomposer.json:

    {
      "require": {
        "payu/alu-client": "1.*"
      }
    }

Потом установите командой:

    composer install

Для использования библиотеке включите Composer's [autoload](https://getcomposer.org/doc/00-intro.md#autoloading]):

    require_once('vendor/autoload.php');

## Ручная установка

Для получения последней версии клиенткой библиотеки PayU для протокола Automatic Live Update выполните:

    git clone https://github.com/PayU/AluClient.git

Для того, что бы воспользоваться библиотекой, вам нужно добавить следующую строчку в ваш PHP скрипт:

    require_once("/path/to/payu/alu-client/src/init.php");

## Приступая к работе

Вы можете найти примеры использования в папке с примерами (examples):

* basicExample.php - Минимальные требования для авторизации через протокол ALU используя информацию о кредитной/дебитойвой банковской карте (Если у вас есть сертификат PCI DSS)
* tokenPayment.php - Минимальные требования для авторизации заказа через протокол ALU используя токены (Token)
* threeDSReturn.php - Пример возврата со страницы 3D Secure авторизации и пример ответа

