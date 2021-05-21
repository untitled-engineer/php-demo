<?php


namespace App\Repositories;


use App\Models\Coordinate;
use App\Models\Place;
use PDO;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class PlacesRepository
{

    /**
     * @param Place[] $data
     */
    private array $data;

    /** @var PDO  */
    private PDO $pdo;

    public function __construct()
    {
        // TODO Note: CSV file here represents the data source in general. In a real application, it can be a database, remote server, or API.
        $this->pdo =  new PDO('pgsql:host=localhost;port=5432;dbname=als;user=als;password=als');

        $this->data = array();

        /* todo add to some data seeder

        ini_set('auto_detect_line_endings',TRUE);

        $handle = fopen(base_path() . '/resources/data/places.csv','r');
        while ( ($data = fgetcsv($handle) ) !== FALSE ) {
            $p = new Place($data[0], Coordinate::fromString($data[1]));
            $this->data[$p->getId()] = $p;
        }
        array_shift($this->data);
        fclose($handle);

        $this->_populate_db_data();
        */
    }

    public function getAllPlaces(): array
    {
        // todo warm up cache before return

        if (count($this->data)) {
            return $this->data;
        }

        $sql = 'SELECT id, name, lat, long FROM places ORDER BY name';

        $stmt = $this->pdo->prepare($sql);

        if (!$stmt->execute()) {
            //throw todo
        }

        foreach ($stmt->fetchAll() as $row) {
            $place = Place::fromDbRow($row);
            $this->data[$place->getId()] = $place;
        }

        return $this->data;
    }

    /**
     * @param $id
     * @param $range
     * @return array
     * @Link https://stackoverflow.com/questions/2234204/find-nearest-latitude-longitude-with-an-sql-query
     */
    public function getNearestPlaces($id, $range): array
    {
        /** @var Place $current */
        $place = $this->getById($id);
        $places = [];

        $location = "'POINT(" . $place->getCoordinate()->getLat() . " " . $place->getCoordinate()->getLong() . ")'";
        $sql = '
WITH closest_candidates AS (
    SELECT *
    FROM places
    ORDER BY places.geog <-> ' . $location . '::geometry
    LIMIT 6
)
SELECT *, ST_Distance(geog, ' . $location . '::geometry) / 100 as distance
FROM closest_candidates
WHERE (ST_Distance(geog, ' . $location . '::geometry) / 100) < :distance
ORDER BY ST_Distance(geog, ' . $location . '::geometry)
LIMIT 6;
';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":distance", $range);

        if (!$stmt->execute()) {
            //throw todo
        }

        foreach ($stmt->fetchAll() as $row) {
            array_push($places, $row);
        }

        array_shift($places);

        return $places;
    }

    private function getById($id): Place
    {
        if (array_key_exists($id, $this->data)) {
            return $this->data[$id];
        }

        $sql = 'SELECT id, name, lat, long FROM places WHERE id = :id';

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue("id", $id);

        if (!$stmt->execute()) {
            //throw todo
        }

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return Place::fromDbRow($row);
        }

        throw new NotFoundResourceException("Place not exists");
    }

    private function _populate_db_data()
    {
        $sql = 'TRUNCATE TABLE places; ';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $sql = 'INSERT INTO places(id, name, lat, long, geog) ' .
                'VALUES(:id, :name, :lat, :long, ST_MakePoint(:geog_lat, :geog_long))';

        /** @var Place $place */
        foreach ($this->data as $place){

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(':id', $place->getId());
            $stmt->bindValue(':name', $place->getName());
            $stmt->bindValue(':lat', $place->getCoordinate()->getLat());
            $stmt->bindValue(':long', $place->getCoordinate()->getLong());
            $stmt->bindValue(':geog_lat', $place->getCoordinate()->getLat());
            $stmt->bindValue(':geog_long', $place->getCoordinate()->getLong());

            $stmt->execute();
        }
    }
}
