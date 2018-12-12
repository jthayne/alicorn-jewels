<?php

/**
 * Class Location
 */
class Location {
    private $db;

    /**
     * Location constructor.
     * @param $db
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * @param bool $includeDisabled
     * @return mixed
     */
    public function getAll($includeDisabled = false)
    {
        $query = 'SELECT id, name FROM location WHERE enabled = 1 ORDER BY name ASC';

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $result = $stmt->fetchAll();

        return $result;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getName($id)
    {
        $query = 'SELECT name FROM location WHERE id = :id LIMIT 1';

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $result = $stmt->fetchColumn();

        return $result;
    }
}
