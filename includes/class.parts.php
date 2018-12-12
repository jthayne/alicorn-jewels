<?php

class Parts {
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllParts()
    {
        $query = 'SELECT * FROM parts p INNER JOIN inventory i ON p.id = i.part_id';

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $result = $stmt->fetchAll();

        return $result;
    }

    public function addNewPart($name, $description, $location, $qty, $price, $category)
    {
        $query = 'INSERT INTO parts (name, description, category) VALUES (:name, :desc, :category)';

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':desc', $description);
        $stmt->bindParam(':category', $category);
        $stmt->execute();

        $id = $this->db->lastInsertId();

        $this->addNewPartQuantity($id, $qty, $price, $location);

        return $id;
    }

    public function addFileToPart($id, $filename)
    {
        $query = 'UPDATE parts SET image = :fname WHERE id = :id';

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':fname', $filename);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function addNewPartQuantity($id, $qty, $price, $locid)
    {
        $query = 'INSERT INTO inventory (part_id, quantity, purchase_price, purchase_date, purchase_qty, purchase_location_id) VALUES (:pid, :qty, :pprice, :pdate, :pqty, :plid)';

        $date = new DateTimeImmutable();
        $ts = $date->getTimestamp();

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':pid', $id);
        $stmt->bindParam(':qty', $qty);
        $stmt->bindParam(':pprice', $price);
        $stmt->bindParam(':pdate', $ts);
        $stmt->bindParam(':pqty', $qty);
        $stmt->bindParam(':plid', $locid);
        $stmt->execute();
    }

    public function updatePartQuantity($id, $qty)
    {
        $query = 'SELECT id, quantity FROM inventory WHERE part_id = :pid AND qty > 0 ORDER BY id ASC LIMIT 1';

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':pid', $id);
        $stmt->execute();

        $details = $stmt->fetch(\PDO::FETCH_ASSOC);

        $newQty = 0;

        $query = 'UPDATE inventory SET quantity = :qty WHERE id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':qty', $newQty);
        $stmt->bindParam(':id', $id);

        if ($details['quantity'] > $qty) {
            $newQty = $details['quantity'] - $qty;
            $stmt->execute();
        } else {
            $newQty = 0;
            $stmt->execute();

            $qty -= $details['quantity'];
            $this->updatePartQuantity($id, $qty);
        }
    }
}
