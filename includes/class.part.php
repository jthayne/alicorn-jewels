<?php

/**
 * Class: Part
 *
 */
class Part
{

    /**
     * add
     *
     * Add a new part to the database
     *
     * @param string $name     The name of the part
     * @param number $quantity The quantity of the part
     * @param number $cost     The overall cost of the part
     * @param string $brand    The brand of the part
     * @param string $source   Where the part was purchased
     */
    public function add($name, $quantity, $cost, $brand, $source)
    {
        $costpp = $cost / $quantity;

        $params = [
            ':name' => $name,
            ':quantity' => $quantity,
            ':cost' => $cost,
            ':costpp' => $costpp,
            ':brand' => $brand,
            ':origqty' => $quantity,
            ':source' => $source,
        ];

        $sql = 'INSERT INTO part (PartName, Quantity, CostPerPiece, Brand, Source)
            VALUES (:name, :quantity, :cost, :brand, :source)';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return true;
    }//end add()

    /**
     * update
     *
     * Update a row in the part table not including the quantity or price
     *
     * @param mixed $name     The name of the part
     * @param mixed $brand    The brand of the part
     * @param string $source  Where the part was purchased
     * @param mixed $id       The ID of the part
     */
    public function update($name, $brand, $source, $id)
    {
        $params = [
            ':name' => $name,
            ':brand' => $brand,
            ':source' => $source,
            ':id' => $id,
        ];

        $sql = 'UPDATE part SET PartName = :name, Brand = :brand, Source = :source WHERE ID = :id';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return true;
    }//end update()

    /**
     * _updateQuantity
     *
     * Updates a part to a given quantity based on the given ID
     *
     * @param mixed $id       The ID of the row to be updated
     * @param mixed $quantity The quantity being changed to
     */
    private function _updateQuantity($id, $quantity)
    {
        $sql = 'UPDATE part SET Quantity = :qty WHERE ID = :id';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id, ':qty' => $quantity]);

        return true;
    }//end _updateQuantity()

    /**
     * used
     *
     * Reduce the overall inventory by using a specified quantity based on the part name
     *
     * @param mixed $name     The name of the part(s)
     * @param mixed $quantity The quantity of the parts being used.
     */
    public function used($name, $quantity)
    {
        $sql = 'SELECT ID, Quantity FROM part WHERE PartName = :name AND Quantity > 0 ORDER BY DateInserted ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':name' => $name]);

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            if ($row['Quantity'] < $quantity) {
                $this->_updateQuantity($row['id'], 0);
                $quantity -= $row['Quantity'];
            } else {
                $this->_updateQuantity($row['id'], $row['Quantity'] - $quantity);

                $quantity -= $row['Quantity'];
                break;
            }
        }

        return true;
    }//end used()
}//end class
