<?php

include_once('Database.php');

class SQL
{
    private $conn = null;


    public function __construct()
    {
        $this->conn = Database::getConnection();
		
         try {
             $stmt = $this->conn->prepare(' set time_zone = \'+05:00\' ');
             $stmt->execute();
             return true;
         } catch (PDOException $e) {
             return false;
         }
		
    }
    public function query($query) {
        try {
            // Prepare statement then execute the query
            return $this->conn->prepare($query)->execute();

        } catch(PDOException $e) {
            echo "mysqli err: " . $e->getMessage();
            return false;
        }
    }
    public function insert_last_id($query) {
        try {
            // Prepare statement then execute the query
            $this->conn->prepare($query)->execute();
            return $this->conn->lastInsertId();

        } catch(PDOException $e) {
            echo "mysqli err: " . $e->getMessage();
            return false;
        }
    }
    public function fetch($query) {
        try {

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch(PDOException $e) {
            echo "mysqli err: " . $e->getMessage();
            return false;
        }
    }
    public function fetch_all($query) {
        try {

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $e) {
            echo "mysqli err: " . $e->getMessage();
            return false;
        }
    }
    public function count($query) {
        try {

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchColumn();

        } catch(PDOException $e) {
            echo "mysqli err: " . $e->getMessage();
            return false;
        }
    }
}