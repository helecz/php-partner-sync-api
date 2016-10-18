# Hele partner sync API

This repository provides PHP client library for synchronization of reservations with Hele.cz website.

Minimal supported version of PHP is 5.3.

## Installation

The best way to install this library is using [Composer](http://getcomposer.org/):

```
> composer require hele/partner-sync-api
```

## Simple usage

```php
$app = new \HelePartnerSyncApi\Application('secret-key');
$app->onCheckSlots(function (\DateTime $date, array $parameters) {
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

Callback in `onCheckSlots` must return array of arrays in following format:

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
