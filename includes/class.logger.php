<?php

class Logger {
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function log($level, $script, $summary, $details) 
    {
        $query = 'INSERT INTO log (level, script, summary, details) VALUES (:level, :script, :summary, :details)';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':level', $level);
        $stmt->bindValue(':script', $script);
        $stmt->bindValue(':summary', $summary);
        $stmt->bindValue(':details', $details);
        $stmt->execute();
    }
}
