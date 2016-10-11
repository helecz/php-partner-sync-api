<?php

use HelePartnerSyncApi\Application;

$partnerId = 'ba1d5397-0404-4bf0-afad-561616b366fe';
$client = new Application($partnerId);
$client->onCheckSlots(function (DateTime $date) {

});
$client->onCreateReservation(function (DateTime $startDateTime, DateTime $endDateTime, $quantity, array $parameters) {

});
$client->run();
