<?php

class Parking {
    private const MAX_FLOORS_COUNT = 3;
    private const TRUCK = 't';
    private const PASSENGER_CAR = 'c';
    private const CAR_SYMBOLS = [
        self::PASSENGER_CAR, self::TRUCK
    ];

    private array $countOfFreePlaces;
    private array $cars;


    public function __construct(
        array $countOfFreePlaces,
        array $cars
    ) {
        $this->validateFreePlaces($countOfFreePlaces);
        $this->validateCars($cars);
        $this->countOfFreePlaces = $countOfFreePlaces;
        $this->cars = $cars;
    }

    /**
     * Validate free places data
     * @param array $countOfFreePlaces
     * @return void
     * @throws LengthException
     * @throws InvalidArgumentException
     */
    private static function validateFreePlaces(array $countOfFreePlaces)
    {
        if (count($countOfFreePlaces) !== self::MAX_FLOORS_COUNT) {
            throw new LengthException('quantity of elements for free places should be equal to ' . self::MAX_FLOORS_COUNT);
        }

        $isAllNumbers = ($countOfFreePlaces == array_filter($countOfFreePlaces, 'is_int'));
        if (!$isAllNumbers) {
            throw new InvalidArgumentException('all elements of free places should be integer numbers');
        }

    }

    /**
     * Validate cars data
     * @param array $cars
     * @return void
     * @throws InvalidArgumentException
     */
    private static function validateCars(array $cars)
    {
        $isAllStrings = ($cars == array_filter($cars, 'is_string'));
        if (!$isAllStrings) {
            throw new InvalidArgumentException('all elements of cars should be string');
        }
        foreach ($cars as $car) {
            if (!in_array($car, self::CAR_SYMBOLS, true)) {
                throw new InvalidArgumentException('car symbol should be one of - ' . implode(', ', self::CAR_SYMBOLS));
            }
        }
    }

    private function decreaseFreePlaces($index = null)
    {
        $numberOfFreeFloors = count($this->countOfFreePlaces);
        if (is_null($index)) {
            $this->countOfFreePlaces[$numberOfFreeFloors -1]--;
            if ($this->countOfFreePlaces[$numberOfFreeFloors -1] <= 0 && $numberOfFreeFloors > 1) {
                array_pop($this->countOfFreePlaces);
            }
        } elseif ($index === 0 && $this->countOfFreePlaces[0] > 0){
                $this->countOfFreePlaces[0]--;
        }
    }

    /**
     * Get avilability status for truck
     * @return string 
     */
    private function getPlaceAvailabilityTruck()
    {
        if ($this->countOfFreePlaces[0] > 0) {
            $availablePlace = 'y';
            $this->decreaseFreePlaces(0);
        } else {
            $availablePlace = 'n';
        }
        return $availablePlace;        
    }

    /**
     * Get availability of one place
     * @param string $car
     * @return string
     */
    private function getPlaceAvailability(string $car)
    {
        $availablePlace = '';
        $numberOfFreeFloors = count($this->countOfFreePlaces);
        $freePlacesHighFloor = end($this->countOfFreePlaces);
        if ($numberOfFreeFloors === 1 && $freePlacesHighFloor === 0) {
            return 'n';
        }
        if ($car === self::TRUCK) {
            return $this->getPlaceAvailabilityTruck();
        }
        while ($freePlacesHighFloor === 0 && count($this->countOfFreePlaces) > 0) {
            $freePlacesHighFloor = end($this->countOfFreePlaces);
            if ($freePlacesHighFloor === 0) {
                array_pop($this->countOfFreePlaces);
            }
        }
        if ($freePlacesHighFloor > 0 && $car === self::PASSENGER_CAR) {
            $availablePlace = 'y';
            $this->decreaseFreePlaces();
        } else {
            $availablePlace = 'n';
        }
        return $availablePlace;
    }

    /**
     * get available places
     * @return array
     */
    public function getPlacesAvailability()
    {
        $availablePlaces = [];
        foreach ($this->cars as $car) {
            $availablePlaces[] = $this->getPlaceAvailability($car);
        }
        return $availablePlaces;
    }
}