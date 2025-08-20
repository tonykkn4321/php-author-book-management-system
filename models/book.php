<?php
class Book {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function findAll() {
        $stmt = $this->db->query("SELECT * FROM books");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($title, $year, $author_id) {
        $stmt = $this->db->prepare("INSERT INTO books (title, year, author_id) VALUES (?, ?, ?)");
        return $stmt->execute([$title, $year, $author_id]);
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM books WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $title, $year, $author_id) {
        $stmt = $this->db->prepare("UPDATE books SET title = ?, year = ?, author_id = ? WHERE id = ?");
        return $stmt->execute([$title, $year, $author_id, $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM books WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function findByAuthor($author_id) {
        $stmt = $this->db->prepare("SELECT * FROM books WHERE author_id = ?");
        $stmt->execute([$author_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}