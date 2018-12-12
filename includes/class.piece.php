<?php

class Piece {
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Add a new piece to the database
     *
     * @param string $name
     * @param string $description
     * @param string $sku
     * @param double $price
     * @param array  $pieces
     */
    public function addNew($name, $description, $sku, $price, $pieces)
    {
        $query = 'INSERT INTO pieces (name, description, sku, price) VALUES (:name, :desc, :sku, :price)';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':desc', $description);
        $stmt->bindValue(':sku', $sku);
        $stmt->bindValue(':price', $price);
        $stmt->execute();

        $id = $this->db->lastInsertId();

        foreach ($pieces as $piece) {
            $this->addPartToPiece($id, $piece);
        }
    }

    public function addPartToPiece($id, $piece)
    {
        $query = 'INSERT INTO pieces_parts (piece_id, part_id, quantity) VALUES (:piece, :part, :qty)';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':piece_id', $id);
        $stmt->bindValue(':part_id', $piece['id']);
        $stmt->bindValue(':qty', $piece['quantity']);
        $stmt->execute();
    }
}
