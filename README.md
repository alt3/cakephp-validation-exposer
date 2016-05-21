# cakephp-validation-exposer

[![Build Status](https://img.shields.io/travis/alt3/cakephp-validation-exposer/master.svg?style=flat-square)](https://travis-ci.org/alt3/cakephp-validation-exposer)
[![StyleCI Status](https://styleci.io/repos/45741948/shield)](https://styleci.io/repos/45741948)
[![Coverage](https://img.shields.io/coveralls/alt3/cakephp-validation-exposer/master.svg?style=flat-square)](https://coveralls.io/r/alt3/cakephp-validation-exposer)
[![Total Downloads](https://img.shields.io/packagist/dt/alt3/cakephp-validation-exposer.svg?style=flat-square)](https://packagist.org/packages/alt3/cakephp-validation-exposer)
[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.txt)

Easily expose your CakePHP 3.x application validation rules.

## Why use it?

Exposing your application's validation rules can be very useful for e.g.
completely separated frontend applications. Imagine a React frontend for your
API being able to realtime configure (very fast) local validation rules exactly
matching your CakePHP API backend's validation rules. Some benefits:

- no more mismatches between local and backend validations
- backend validation changes directly applied in the frontend application
- no more fire-and-hope POSTing of data
- less local 412 validation errors

## Requirements

* CakePHP 3.0+

## Installation

1. Install the plugin using composer:

    ```bash
    composer require alt3/cakephp-validation-exposer:"^1.0"
    ```

2. To enable the plugin either run the following command:

    ```bash
    bin/cake plugin load Alt3/ValidationExposer
    ```

    or manually add the following line to your `config/bootstrap.php` file:

    ```bash
    Plugin::load('Alt3/ValidationExposer');
    ```

## Usage

Inside any controller

```php
<?php
use Alt3\ValidationExposer\Lib\ValidationExposer;


public function rules()
{
    $validationExposer = new ValidationExposer([
        'exclude' => [
            'table_to_skip'
        ]
    ]);

    $this->set([
        'success' => true,
        'data' => $validationExposer->applicationRules(),
        '_serialize' => ['success', 'data']
    ]);
}
```

The `applicationRules()` method will return a hash containing all validation
information in your application structured similar to shown below:

```php
[users] => Array
    (
        [id] => Array
            (
                [requiredFor] =>
                [allowedEmptyFor] => create
                [rules] => Array
                    (
                        [0] => Array
                            (
                                [name] => NUMERIC
                                [rule] => numeric
                                [message] =>
                            )

                    )

            )

        [email] => Array
            (
                [requiredFor] => create
                [allowedEmptyFor] =>
                [rules] => Array
                    (
                        [0] => Array
                            (
                                [name] => FORMAT
                                [rule] => email
                                [message] => Invalid email address format.
                            )

                        [1] => Array
                            (
                                [name] => UNIQUE
                                [rule] => validateUnique
                                [message] => This email address already exists
                            )

                    )

            )

        [password] => Array
            (
                [requiredFor] => create
                [allowedEmptyFor] =>
                [rules] => Array
                    (
                        [0] => Array
                            (
                                [name] => MIN_LENGTH
                                [rule] => minLength
                                [message] => Your password must be at least {minLength} characters.
                                [pass] => Array
                                    (
                                        [0] => 8
                                    )

                            )

                        [1] => Array
                            (
                                [name] => MAX_LENGTH
                                [rule] => maxLength
                                [message] => Your password cannot exceed {maxLength} characters
                                [pass] => Array
                                    (
                                        [0] => 255
                                    )

                            )
                    )
            )
        )
````

## TODO

- describe configuration options
- add tests
- make resultant fields configurable (e.g. do not show `rule`)

## Contribute

Before submitting a PR make sure:

- [PHPUnit](http://book.cakephp.org/3.0/en/development/testing.html#running-tests)
and [CakePHP Code Sniffer](https://github.com/cakephp/cakephp-codesniffer) tests pass
- [Coveralls Code Coverage ](https://coveralls.io/github/alt3/cakephp-validation-exposer) remains at 100%
