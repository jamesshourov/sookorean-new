<div align="center">
    <p><img width="450" src="cover.png" alt="Laravel In-app Purchase cover"></p>
    <p>
        <img alt="Packagist PHP Version Support" src="https://img.shields.io/packagist/php-v/imdhemy/laravel-purchases">
        <img src="https://img.shields.io/packagist/v/imdhemy/laravel-purchases.svg?style=flat-square" alt="Latest Version on Packagist">
        <img src="https://img.shields.io/packagist/dt/imdhemy/laravel-purchases.svg?style=flat-square" alt="Total Downloads">
        <img alt="GitHub last commit" src="https://img.shields.io/github/last-commit/imdhemy/laravel-in-app-purchases">
        <a href="https://github.com/imdhemy/laravel-in-app-purchases/actions/workflows/code-style.yml"><img src="https://github.com/imdhemy/laravel-in-app-purchases/actions/workflows/code-style.yml/badge.svg" alt="Code Style:PSR12"></a>
        <a href="https://github.com/imdhemy/laravel-in-app-purchases/actions/workflows/tests.yml"><img src="https://github.com/imdhemy/laravel-in-app-purchases/actions/workflows/tests.yml/badge.svg" alt="Tests"></a>
    </p>
    <p> ✅ App Store ✅ Google Play </p>

</div>

# Laravel In-App purchase

Google Play and App Store provide the In-App Purchase (IAP) services. IAP can be used to sell a variety of content,
including subscriptions, new features, and services. The purchase event and the payment process occurs on and handled by
the mobile application (iOS and Android), then your backend needs to be informed about this purchase event to deliver
the purchased product or update the user's subscription state.

**Laravel In-App purchase** comes to help you to parse and validate the purchased products and handle the different
states of a subscription, like New subscription , auto-renew, cancellation, expiration and etc.

## Installation

Use composer to install the package:

```bash
composer require imdhemy/laravel-purchases
```

## Documentation

The documentation is available on [Liap manual](https://imdhemy.com/laravel-iap-docs/).

## License

Laravel In-App purchase is licensed under the [MIT license](./LICENSE.md).
