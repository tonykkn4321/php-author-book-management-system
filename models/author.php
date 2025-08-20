<?php
class Author {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function findAll() {
        $stmt = $this->db->query("SELECT * FROM authors");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($first_name, $last_name) {
        $stmt = $this->db->prepare("INSERT INTO authors (first_name, last_name) VALUES (?, ?)");
        $stmt->execute([$first_name, $last_name]);
        return $this->db->lastInsertId();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM authors WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $first_name, $last_name) {
        $stmt = $this->db->prepare("UPDATE authors SET first_name = ?, last_name = ? WHERE id = ?");
        return $stmt->execute([$first_name, $last_name, $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM authors WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
