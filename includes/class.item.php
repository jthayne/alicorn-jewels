<?php

namespace Alicorn;

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
     * @param object $db The database object
     */
    public function __construct($db)
    {
        $this->db = $db;
    }//end __construct()

    /**
     * Adds a new item to the database
     *
     * @param string $name  The name of the item
     * @param float  $price The price of the item
     * @param array  $parts An array of the part ids and quantities in the item
     * @param string $desc  A description of the item (optional)
     *
     * @return null
     */
    public function add(string $name, float $price, array $parts, string $desc = '')
    {
        $sql = 'INSERT INTO item (ItemName, Description, Price)
            VALUES (:name, :desc, :price)';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':name' => $name, ':desc' => $desc, ':price' => $price]);

        // Get ID
        $id = $this->db->lastInsertId();

        // Associate Parts
        // Determine Cost
        $sql = 'INSERT INTO partitem (PartID, ItemID, Quantity)
            VALUES (:pid, :itemid, :qty)';
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':itemid', $id);
        $stmt->bindParam(':pid', $pid);
        $stmt->bindParam(':qty', $qty);

        $costsql = 'SELECT CostPerPiece FROM part WHERE id = :id';
        $coststmt = $this->db->prepare($sql);
        $coststmt->bindParam(':id', $pid);

        $itemcost = 0;
        foreach ($parts as $pid => $qty) {
            $stmt->execute();

            $coststmt->execute();
            $pppa = $conststmt->fetch(\PDO::FETCH_ASSOC);
            $ppp = $pppa['CostPerPiece'];
            $itemcost += ($ppp * $qty);
        }

        // Update row with cost
        $sql = 'UPDATE item SET Cost = :cost WHERE ID = :id';
        $stmt = $this->fb->prepare($sql);
        $stmt->execute([':cost' => $itemcost, ':id' => $id]);
    }//end add()

    /**
     * Disable a given item without selling it
     *
     * @param integer $id The ID of the item to disable
     *
     * @return null
     */
    public function remove($id)
    {
        $sql = 'UPDATE item SET sold = 9 WHERE id = :id';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
    }

    /**
     * Mark an item as sold
     *
     * @param mixed $id The ID of the item being sold
     *
     * @return null
     */
    public function marksold($id)
    {
        $sql = 'UPDATE item SET sold = 1 WHERE id = :id';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
    }

    /**
     * Returns an array containing the names and ids of enabled item
     *
     * @return array
     */
    public function list()
    {
        $results = [];

        $sql = 'SELECT
                TypeName,
                ID
            FROM item
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
}//end Item
