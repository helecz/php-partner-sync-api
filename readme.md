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

- All `$parameters` arguments may contain custom data needed by your application (e.g. some `serviceId` identifying service in your application) - if you need so, contact us.
- All callbacks should finish within 9 seconds!
- Secret key will be assigned to you and should not leak anywhere (if that happens somehow, contact us for generating new one).

### `onCreateReservation`

This endpoint is called when user creates some order on Hele.cz.
You should save new reservation to your database with the data given.
If reservation cannot be created for some reason, you can throw any Exception and the reservation on Hele website will not be performed.
We call this endpoint only if we know there is a free slot on that time (according to output in `onGetSlots`), so throwing exceptions should not be needed.

Array provided in `$parameters` contains (beside your custom data) also following keys: `customerName`, `customerEmail`, `customerPhone` (in format `+420777111222`), `customerNote`.
Values of all these keys may be null.

### `onCancelReservation`

This endpoint is called when previously created reservation is cancelled.
You should delete old reservation from your database to free the time slot.
If you do not want to implement this feature, let us know and we will send you an email on such cases
(but you will need to perform this action manually).

### `onGetSlots`

This endpoint is called periodically to synchronize reservations on Hele.cz with your database.
You should always return all slots from your database (matching given date and parameters) even if only few of them will be available for Hele.
The callback must return array of arrays in following format:

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
