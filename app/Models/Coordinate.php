<?php


namespace App\Models;


class Coordinate
{

    /** @var Float */
    private float $lat;

    /** @var Float */
    private float $long;

    /**
     * Coordinate constructor.
     */
    public function __construct(float $lat, float $long)
    {
        $this->lat = $lat;
        $this->long = $long;
    }

    /**
     * @param string $coordinate
     * @return Coordinate
     */
    public static function fromString(string $coordinate): Coordinate
    {
        $pieces = explode(",", $coordinate);

        return new Coordinate(floatval($pieces[0]), floatval($pieces[0]));
    }

    /**
     * @return Float
     */
    public function getLat(): float
    {
        return $this->lat;
    }

    /**
     * @param Float $lat
     */
    public function setLat(float $lat): void
    {
        $this->lat = $lat;
    }

    /**
     * @return Float
     */
    public function getLong(): float
    {
        return $this->long;
    }

    /**
     * @param Float $long
     */
    public function setLong(float $long): void
    {
        $this->long = $long;
    }
}
