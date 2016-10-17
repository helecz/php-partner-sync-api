# Hele partner sync API

This repository provides PHP client library for synchronization of reservations with Hele.cz website.

Minimal supported version of PHP is 5.3.

## Installation

The best way to install this library is using [Composer](http://getcomposer.org/):

```
> composer require helecz/php-partner-sync-api
```

## Simple usage

```php
$client = new \HelePartnerSyncApi\Application('id-assigned-to-you');
$client->onCheckSlots(function (DateTime $date) {
    // return $this->reservationFacade->getFreeSlots($date);
});
$client->onCreateReservation(function (DateTime $startDateTime, DateTime $endDateTime, $quantity, array $parameters) {
    // $this->reservationFacade->createReservation(...);
});
$client->run();
```

If reservation cannot be created for some reason, you can throw any Exception and the reservation on Hele website will not be performed.

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

Please note that `$client->run()` behaves like standalone application and will exit at its end.
