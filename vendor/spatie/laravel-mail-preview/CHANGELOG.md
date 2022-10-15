# Changelog

All notable changes to `laravel-mail-preview` will be documented in this file.

## 6.0.3 - 2022-08-19

### What's Changed

- fix: make setTimeoutInSeconds works by @bernardo-campos in https://github.com/spatie/laravel-mail-preview/pull/97

### New Contributors

- @bernardo-campos made their first contribution in https://github.com/spatie/laravel-mail-preview/pull/97

**Full Changelog**: https://github.com/spatie/laravel-mail-preview/compare/6.0.2...6.0.3

## 6.0.2 - 2022-05-30

### What's Changed

- Run parent transport constructor by @mrk-j in https://github.com/spatie/laravel-mail-preview/pull/83

**Full Changelog**: https://github.com/spatie/laravel-mail-preview/compare/6.0.1...6.0.2

## 6.0.1 - 2022-03-02

## What's Changed

- Fix wrong class in readme.md by @spoyntersmith in https://github.com/spatie/laravel-mail-preview/pull/80
- Fix HTML preview for Laravel 9 by @mrk-j in https://github.com/spatie/laravel-mail-preview/pull/82

## New Contributors

- @spoyntersmith made their first contribution in https://github.com/spatie/laravel-mail-preview/pull/80

**Full Changelog**: https://github.com/spatie/laravel-mail-preview/compare/6.0.0...6.0.1

## 6.0.0 - 2022-01-13

- add support for Laravel 9
- drop support for Laravel 8

## 5.2.0 - 2021-04-01

- add `assertNothingSent`

## 5.1.4 - 2021-03-12

- MailManager extension in afterResolving() for better service ordering (#73)

## 5.1.3 - 2021-02-15

- remove stray ray calls

## 5.1.2 - 2021-02-15

- fix missing return in bodyContains and two minor fixes (#64)

## 5.1.1 - 2021-02-07

- style update

## 5.1.0 - 2021-02-07

- add assertion methods
- add open eml version to overlay

## 5.0.4 - 2021-01-28

- allow to dynamically switch storage path

## 5.0.3 - 2021-01-28

- fix displaying mails

## 5.0.2 - 2021-01-28

- add `enabled` option

## 5.0.1 - 2021-01-28

- fix middleware name

## 5.0.0 - 2021-01-28

- rewrite of the package to modern standards

## 4.0.1 - 2021-01-27

- first release after transfer from themsaid/laravel-mail-preview
