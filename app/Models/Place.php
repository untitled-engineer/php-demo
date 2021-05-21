<?php


namespace App\Models;


use function Symfony\Component\String\s;

/**
 * Class Place
 * @package App\Models
 */
class Place
{

    private string $id;

    /** @var String */
    private string $name;

    /** @var Coordinate */
    private Coordinate $coordinate;

    /**
     * Place constructor.
     * @param String $name
     * @param String $coordinate
     */
    public function __construct(string $name, Coordinate $coordinate){
        $this->id = uniqid();
        $this->name = $name;
        $this->coordinate = $coordinate;
    }

    public static function fromDbRow(array $row)
    {
        $self = new self($row['name'],
            new Coordinate(floatval($row['lat']), floatval($row['long'])));

        $self->id = $row['id'];

        return $self;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
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

}
