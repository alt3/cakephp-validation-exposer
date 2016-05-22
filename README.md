# cakephp-validation-exposer

[![Build Status](https://img.shields.io/travis/alt3/cakephp-validation-exposer/master.svg?style=flat-square)](https://travis-ci.org/alt3/cakephp-validation-exposer)
[![StyleCI Status](https://styleci.io/repos/59366680/shield)](https://styleci.io/repos/59366680)
[![codecov](https://codecov.io/gh/alt3/cakephp-validation-exposer/branch/master/graph/badge.svg)](https://codecov.io/gh/alt3/cakephp-validation-exposer)
[![Total Downloads](https://img.shields.io/packagist/dt/alt3/cakephp-validation-exposer.svg?style=flat-square)](https://packagist.org/packages/alt3/cakephp-validation-exposer)
[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.txt)

Easily expose your CakePHP 3.x application validation rules.

## Why use it?

Exposing your application's validation rules can be very useful for e.g.
completely separated frontend applications. Imagine a React frontend for your
API being able to realtime configure (very fast) local validation rules exactly
matching your CakePHP API backend's validation rules. Some benefits:

- no more mismatches between local and backend validations
- backend validation changes instantly applied in frontend application
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

1. Create a `ValidationExposer` object anywhere in your application
2. Call the `rules()` method
3. Present the returned array with validation information as you see fit

### API example


```php
<?php
use Alt3\ValidationExposer\Lib\ValidationExposer;

class SystemController extends AppController

    public function rules()
    {
        $validationExposer = new ValidationExposer([
            'excludedTables' => [
                'table_to_skip' // this table will not be processed
            ],
            'hiddenRuleParts' =>
                'message' // do not show this part inside the `rules` array
            ]
        ]);

        $this->set([
            'success' => true,
            'data' => $validationExposer->rules(),
            '_serialize' => ['success', 'data']
        ]);
    }
}
```

## Configuration

Any table found in the `excludedTables` configuration array will not be
searched for validation information.

> Please note that the `phinxlog` table is excluded by default.

Add one or more of the following fields to the `hiddenRuleParts` configuration
array and they will not appear in the result set:

- `name`: holds the rule name
- `rule`: holds the internal rule name (numeric, unique, etc)
- `message`: holds the validation message
- `parts`: holds arguments passed to the validation rule

## Methods

### `rules()`

Calling the `rules()` method will return a hash containing all
validation information found in your application structured similar to shown
below:

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

### `tables()`

Use the `tables()` method to produce a flat array with all tables included
during validation aggregation.

```php
(
    [0] => cocktails
    [1] => liquors
    [2] => users
)
````

### `excludedTables()`

Use the `excludedTables()` method to produce a flat array with tables not
included in validation aggregation.

```php
(
    [0] => phinxlog
    [1] => staging
)
````

## Contribute

Before submitting a PR make sure:

- [PHPUnit](http://book.cakephp.org/3.0/en/development/testing.html#running-tests)
and [CakePHP Code Sniffer](https://github.com/cakephp/cakephp-codesniffer) tests pass
- [Coveralls Code Coverage ](https://coveralls.io/github/alt3/cakephp-validation-exposer) remains at 100%
