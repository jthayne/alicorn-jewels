<?php

/**
 * Class: ItemType
 *
 * Manages the jewelry types
 */
class ItemType
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
     * Adds a new item type to the database
     *
     * @param string $name
     * @param string $desc
     */
    public function add($name, $desc = '')
    {
        $sql = 'INSERT INTO itemtype (TypeName, Description) VALUES (:name, :desc)';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':name' => $name, ':desc' => $desc]);

        return true;
    }//end add()

    /**
     * remove
     *
     * Disable a given item type
     *
     * @param integer $id
     */
    public function remove($id)
    {
        $sql = 'UPDATE itemtype SET enabled = 0 WHERE id - :id';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return true;
    }

    /**
     * list
     *
     * Returns an array containing the names and ids of enabled item types
     *
     * @return array
     */
    public function list()
    {
        $results = [];

        $sql = 'SELECT TypeName, ID FROM itemtype WHERE enabled = 1 ORDER BY TypeName ASC';
        foreach ($this->db->query($sql) as $row) {
            $temp = [
                'id' => $row['ID'],
                'name' => $row['TypeName'];
            ];

            $results[] = $temp;
        }

        return $results;
    }//end list()
}//end ItemType
