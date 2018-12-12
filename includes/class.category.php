<?php
namespace Alicorn;

/**
 * Class Category
 */
class Category {
    private $db;

    /**
     * Category constructor.
     *
     * @param $db
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * @param bool $includeDisabled
     *
     * @return mixed
     */
    public function getAll($includeDisabled = false)
    {
        if ($includeDisabled === true) {
            $enabledStatement1 = '';
            $enabledStatement2 = '';
        } else {
            $enabledStatement1 = ' AND c1.enabled = 1 ';
            $enabledStatement2 = ' WHERE c2.enabled = 1 ';
        }

        $query = 'SELECT *
            FROM (   
                SELECT 
                    c1.id,
                    c1.name
                FROM categories c1
                WHERE c1.parent = 0
                    ' . $enabledStatement1 . '
                UNION
                SELECT 
                    c2.id,
                    CONCAT(p.name, " - ", c2.name) as name
                FROM categories c2
                    JOIN categories p ON c2.parent = p.id
                ' . $enabledStatement2 . ') c
            ORDER BY name ASC';

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $result = $stmt->fetchAll();

        return $result;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function getName($id)
    {
        $query = 'SELECT name FROM categories WHERE id = :id LIMIT 1';

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $result = $stmt->fetchColumn();

        return $result;
    }
}
