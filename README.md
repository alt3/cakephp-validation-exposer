# cakephp-validation-exposer

[![Build Status](https://img.shields.io/travis/alt3/cakephp-validation-exposer/master.svg?style=flat-square)](https://travis-ci.org/alt3/cakephp-validation-exposer)
[![StyleCI Status](https://styleci.io/repos/45741948/shield)](https://styleci.io/repos/45741948)
[![Coverage](https://img.shields.io/coveralls/alt3/cakephp-validation-exposer/master.svg?style=flat-square)](https://coveralls.io/r/alt3/cakephp-validation-exposer)
[![Total Downloads](https://img.shields.io/packagist/dt/alt3/cakephp-validation-exposer.svg?style=flat-square)](https://packagist.org/packages/alt3/cakephp-validation-exposer)
[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.txt)

Easily expose your CakePHP 3.x application validation rules.

## Requirements

* CakePHP 3.0+

## Installation

1. Install the plugin using composer:

    ```bash
    composer require alt3/cakephp-validation-exposer:dev-master
    ```

2. To enable the plugin either run the following command:

    ```bash
    bin/cake plugin load Alt3/ValidationExposer
    ```

    or manually add the following line to your `config/bootstrap.php` file:

    ```bash
    Plugin::load('Alt3/ValidationExposer');
    ```

## Contribute

Before submitting a PR make sure:

- [PHPUnit](http://book.cakephp.org/3.0/en/development/testing.html#running-tests)
and [CakePHP Code Sniffer](https://github.com/cakephp/cakephp-codesniffer) tests pass
- [Coveralls Code Coverage ](https://coveralls.io/github/alt3/cakephp-validation-exposer) remains at 100%
