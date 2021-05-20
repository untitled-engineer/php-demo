<?php


namespace App\Repositories;


use App\Models\Coordinate;
use App\Models\Place;

class PlacesRepository
{

    /**
     * @param Place[] $data
     */
    private array $data;

    public function __construct()
    {
        $this->data = array();

        ini_set('auto_detect_line_endings',TRUE);

        $handle = fopen(base_path() . '/resources/data/places.csv','r');
        while ( ($data = fgetcsv($handle) ) !== FALSE ) {
            array_push($this->data, new Place($data[0], Coordinate::fromString($data[1])));
        }
        array_shift($this->data);
        fclose($handle);
    }

    public function getAllPlaces(): array
    {
        return $this->data;
    }
}
