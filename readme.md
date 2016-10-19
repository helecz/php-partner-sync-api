# Hele partner sync API

[![Build Status](https://travis-ci.org/helecz/php-partner-sync-api.svg)](https://travis-ci.org/helecz/php-partner-sync-api)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/helecz/php-partner-sync-api/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/helecz/php-partner-sync-api/?branch=master)

This repository provides PHP client library for synchronization of reservations with Hele.cz website.

Minimal supported version of PHP is 5.3.

## Installation

The best way to install this library is using [Composer](http://getcomposer.org/):

```
composer require hele/partner-sync-api
```

Or download archive from [Github](https://github.com/helecz/php-partner-sync-api/releases) and extract to your project.

## Simple usage

```php
<?php

// require __DIR__ . '/hele-partner-sync-api/autoload.php'; // non-composer usage
require __DIR__ . '/vendor/autoload.php';

$app = new \HelePartnerSyncApi\Application('secret-key');
$app->onGetSlots(function (\DateTime $date, array $parameters) {
    // return $this->reservationFacade->getFreeSlots($date);
});
$app->onCreateReservation(function (\DateTime $startDateTime, \DateTime $endDateTime, $quantity, array $parameters) {
    // $this->reservationFacade->createReservation(...);
});
$app->onCancelReservation(function (\DateTime $startDateTime, \DateTime $endDateTime, $quantity, array $parameters) {
    // $this->reservationFacade->cancelReservation(...);
});
$app->run();
```

If reservation cannot be created for some reason, you can throw any Exception and the reservation on Hele website will not be performed.
The `$parameters` argument may contain custom data needed by your application (e.g. some `serviceId` identifying service in your application) - if you need so, contact us.

Callback in `onGetSlots` must return array of arrays in following format.
You should always return all slots from your database (matching given date and parameters) even if only few of them will be synchronized with Hele.

```php
[
    [
        'startDateTime' => DateTimeInterface,
        'endDateTime' => DateTimeInterface,
        'capacity' => int,
    ],
    [
        'startDateTime' => DateTimeInterface,
        'endDateTime' => DateTimeInterface,
        'capacity' => int,
    ],
]
```
