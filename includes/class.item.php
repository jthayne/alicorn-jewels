<?php

/**
 * Class: Item
 *
 * Manages the jewelry types
 */
class Item
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
     * Adds a new item to the database
     *
     * @param string $name
     * @param string $price
     * @param string $desc
     */
    public function add($name, $price, $desc = '')
    {
        $sql = 'INSERT INTO item (ItemName, Description, Price) VALUES (:name, :desc, :price)';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':name' => $name, ':desc' => $desc, ':price' => $price]);

        return true;
    }//end add()

    /**
     * remove
     *
     * Disable a given item
     *
     * @param integer $id
     */
    public function remove($id)
    {
        $sql = 'UPDATE item SET enabled = 0 WHERE id - :id';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return true;
    }

    /**
     * list
     *
     * Returns an array containing the names and ids of enabled item
     *
     * @return array
     */
    public function list()
    {
        $results = [];

        $sql = 'SELECT TypeName, ID FROM item WHERE enabled = 1 ORDER BY TypeName ASC';
        foreach ($this->db->query($sql) as $row) {
            $temp = [
                'id' => $row['ID'],
                'name' => $row['TypeName'];
            ];

            $results[] = $temp;
        }

        return $results;
    }//end list()
}//end Item
