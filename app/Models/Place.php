<?php


namespace App\Models;


/**
 * Class Place
 * @package App\Models
 */
class Place
{

    /** @var String */
    private string $name;

    /** @var Coordinate */
    private Coordinate $coordinate;

    /**
     * Place constructor.
     * @param String $name
     * @param String $coordinate
     */
    public function __construct(string $name, string $coordinate){
        $this->name = $name;
        $this->coordinate = Coordinate::fromString($coordinate);
    }

    /**
     * @return String
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param String $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Coordinate
     */
    public function getCoordinate(): Coordinate
    {
        return $this->coordinate;
    }

    /**
     * @param Coordinate $coordinate
     */
    public function setCoordinate(Coordinate $coordinate): void
    {
        $this->coordinate = $coordinate;
    }


    /**
     * @param Coordinate $coordinate
     * @Link https://stackoverflow.com/questions/10053358/measuring-the-distance-between-two-coordinates-in-php
     * @return int
     */
    public function getDistanceFrom(Coordinate $coordinate): int
    {

        $delta_lat = $coordinate->getLat() - $this->coordinate->getLat();
        $delta_lon = $coordinate->getLong() - $this->coordinate->getLong();

        $earth_radius = 6372.795477598;

        $alpha = $delta_lat/2;
        $beta = $delta_lon/2;
        $a  = sin(deg2rad($alpha))
            * sin(deg2rad($alpha))
            + cos(deg2rad($this->coordinate->getLat()))
            * cos(deg2rad($coordinate->getLat()))
            * sin(deg2rad($beta))
            * sin(deg2rad($beta))
        ;
        $c = asin(min(1, sqrt($a)));
        $distance = 2*$earth_radius * $c;

        return round($distance, 4);
    }

}
