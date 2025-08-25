<?php
// Load configuration
$dbConfig = require 'config.php';

// Create PDO connection
$db = new PDO($dbConfig['dsn'], $dbConfig['username'], $dbConfig['password']);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Detect database type
$isPostgres = strpos($dbConfig['dsn'], 'pgsql:') === 0;

// Create tables if they do not exist
if ($isPostgres) {
    $db->exec("
        CREATE TABLE IF NOT EXISTS authors (
            id SERIAL PRIMARY KEY,
            first_name VARCHAR(255) NOT NULL,
            last_name VARCHAR(255) NOT NULL
        );
    ");
    $db->exec("
        CREATE TABLE IF NOT EXISTS books (
            id SERIAL PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            year INT NOT NULL,
            author_id INT,
            FOREIGN KEY (author_id) REFERENCES authors(id) ON DELETE CASCADE
        );
    ");
} else {
    $db->exec("
        CREATE TABLE IF NOT EXISTS authors (
            id INT AUTO_INCREMENT PRIMARY KEY,
            first_name VARCHAR(255) NOT NULL,
            last_name VARCHAR(255) NOT NULL
        );
    ");
    $db->exec("
        CREATE TABLE IF NOT EXISTS books (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            year INT NOT NULL,
            author_id INT,
            FOREIGN KEY (author_id) REFERENCES authors(id) ON DELETE CASCADE
        );
    ");
}

// Load models
require 'models/author.php';
require 'models/book.php';

$authorModel = new Author($db);
$bookModel = new Book($db);

// CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Parse request
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$pathParts = explode('/', trim($path, '/'));

// Routing
switch ($pathParts[0]) {
    case 'authors':
        if ($method === 'GET') {
            echo json_encode($authorModel->findAll());
        } elseif ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $id = $authorModel->create($data['first_name'], $data['last_name']);
            echo json_encode(['id' => $id]);
        } elseif (isset($pathParts[1])) {
            $id = (int)$pathParts[1];
            $data = json_decode(file_get_contents('php://input'), true);
            if ($method === 'GET') {
                echo json_encode($authorModel->findById($id));
            } elseif ($method === 'PUT' || $method === 'PATCH') {
                $authorModel->update($id, $data['first_name'], $data['last_name']);
                echo json_encode(['message' => 'Author updated']);
            } elseif ($method === 'DELETE') {
                $authorModel->delete($id);
                echo json_encode(['message' => 'Author deleted']);
            }
        }
        break;

    case 'books':
        if ($method === 'GET') {
            echo json_encode($bookModel->findAll());
        } elseif ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $bookModel->create($data['title'], $data['year'], $data['author_id']);
            echo json_encode(['message' => 'Book created']);
                } elseif (isset($pathParts[1])) {
            $id = (int)$pathParts[1];
            $data = json_decode(file_get_contents('php://input'), true);
            if ($method === 'GET') {
                echo json_encode($bookModel->findById($id));
            } elseif ($method === 'PUT' || $method === 'PATCH') {
                $bookModel->update($id, $data['title'], $data['year'], $data['author_id']);
                echo json_encode(['message' => 'Book updated']);
            } elseif ($method === 'DELETE') {
                $bookModel->delete($id);
                echo json_encode(['message' => 'Book deleted']);
            }
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
        break;
}
