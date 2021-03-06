<?php

namespace App\Models;

use PDO;

class Model
{
    private $conn;
    private $config;
    public function __construct()
    {
        $this->config = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/lib/config.ini');
        $this->conn = $this->connectDB();
        $this->initializeTable();
    }
    public function initializeTable()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS {$this->config['tablename']}(
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            name VARCHAR(30) NOT NULL,
            description VARCHAR(100) NOT NULL,
            deadline DATETIME,
            priority INT,
            status TINYINT(1)
            );
        ";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }
    public function connectDB()
    {
        try {
            $conn = new PDO("mysql:host=" . $this->config['host'] . ";dbname=" . $this->config['dbname'] . ";charset=utf8", $this->config['username'], $this->config['password']);
            return $conn;
        } catch (PDOException $exc) {
            echo $exc->getMessage();
        }
    }
    public function showAll()
    {
        $sql = "SELECT * FROM {$this->config['tablename']};";
        try {
            $result = $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo $exc->getMessage();
        }

    }
    public function show($id)
    {
        $sql = "SELECT * FROM {$this->config['tablename']} WHERE id = :id LIMIT 1";
        try {
            $stmt = $this->conn->prepare($sql);
            $values = [
                ':id' => $id
            ];
            $stmt->execute($values);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo $exc->getMessage();
        }
    }
    public function insert($data)
    {
        $sql = "
        INSERT INTO {$this->config['tablename']} (name, description, deadline, priority, status)
        VALUES (:name, :description, :deadline, :priority, :status)
        ";

        try {
            $stmt = $this->conn->prepare($sql);
            $values = [
                ':name' => $data['name'],
                ':description' => $data['description'],
                ':deadline' => $data['deadline'],
                ':priority' => $data['priority'],
                ':status' => $data['status'],
            ];
            return $stmt->execute($values);
        } catch (PDOException $e) {
            echo $exc->getMessage();
        }
    }
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->config['tablename']} WHERE id=:id";

        try {
            $stmt = $this->conn->prepare($sql);
            $values = [
                ':id' => $id
            ];
            return $stmt->execute($values);
        } catch (PDOException $e) {
            echo $exc->getMessage();
        }
    }
    public function update($id, $data)
    {
        $sql = "UPDATE {$this->config['tablename']} SET name=:name, description=:description, deadline=:deadline, priority=:priority, status=:status WHERE id=:id;";

        try {
            $stmt = $this->conn->prepare($sql);
            $values = [
                ':name' => $data['name'],
                ':description' => $data['description'],
                ':deadline' => $data['deadline'],
                ':priority' => $data['priority'],
                ':status' => $data['status'],
                ':id' => $id
            ];
            return $stmt->execute($values);
        } catch (PDOException $e) {
            echo $exc->getMessage();
        }
    }
}