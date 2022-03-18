<?php

require_once ('classes/Parking.php');

$freePlaces = [
    1,
    1,
    1
];

$cars = [
    't',
    'c',
    't'
];

$errorMessage = '';

try {
    $parking = new Parking($freePlaces, $cars);
} catch (LengthException $ex) {
    $errorMessage = 'length exception: ';
    $errorMessage .= $ex->getMessage();
} catch (InvalidArgumentException $ex) {
    $errorMessage = 'invalid argument: ';
    $errorMessage .= $ex->getMessage();
} catch (Throwable $ex) {
    $errorMessage = get_class($ex) . ': ';
    $errorMessage .= $ex->getMessage();
}

if (!empty($errorMessage)) {
    echo $errorMessage;
    die();
}

print_r($parking->getPlacesAvailability());;