<?php
namespace Alicorn;

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
     * @param object $db The database object
     */
    public function __construct($db)
    {
        $this->db = $db;
    }//end __construct()

    /**
     * Adds a new item type to the database
     *
     * @param string $name The name of the item type
     * @param string $desc The description of the item type (optional)
     *
     * @return null
     */
    public function add($name, $desc = '')
    {
        $sql = 'INSERT INTO itemtype (TypeName, Description) VALUES (:name, :desc)';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':name' => $name, ':desc' => $desc]);
    }//end add()

    /**
     * Disable a given item type
     *
     * @param integer $id The ID to be marked as disabled
     *
     * @return null
     */
    public function remove($id)
    {
        $sql = 'UPDATE itemtype SET enabled = 0 WHERE id - :id';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
    }

    /**
     * Returns an array containing the names and ids of enabled item types
     *
     * @return array
     */
    public function list()
    {
        $results = [];

        $sql = 'SELECT
                TypeName,
                ID
            FROM itemtype
            WHERE enabled = 1
            ORDER BY TypeName ASC';
        foreach ($this->db->query($sql) as $row) {
            $temp = [
                'id' => $row['ID'],
                'name' => $row['TypeName'],
            ];

            $results[] = $temp;
        }

        return $results;
    }//end list()
}//end ItemType
