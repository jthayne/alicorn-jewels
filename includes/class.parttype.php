<?php

/**
 * Class: PartType
 *
 * Manages the jewelry types
 */
class PartType
{
    public $db;

    /**
     * __construct
     *
     * @param object $db
     */
    public function __construct($db)
    {
        $this->db = $db;
    }//end __construct()

    /**
     * add
     *
     * Adds a new part type to the database
     *
     * @param string $name
     * @param string $desc
     */
    public function add($name, $desc = '')
    {
        $sql = 'INSERT INTO parttype (TypeName, Description) VALUES (:name, :desc)';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':name' => $name, ':desc' => $desc]);
    }//end add()

    /**
     * remove
     *
     * Disable a given part type
     *
     * @param integer $id
     */
    public function remove($id)
    {
        $sql = 'UPDATE parttype SET enabled = 0 WHERE id - :id';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
    }

    /**
     * list
     *
     * Returns an array containing the names and ids of enabled part types
     *
     * @return array
     */
    public function list()
    {
        $results = [];

        $sql = 'SELECT TypeName, ID FROM parttype WHERE enabled = 1 ORDER BY TypeName ASC';
        foreach ($this->db->query($sql) as $row) {
            $temp = [
                'id' => $row['ID'],
                'name' => $row['TypeName'],
            ];

            $results[] = $temp;
        }

        return $results;
    }//end list()
}//end PartType
