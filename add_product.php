<?php
session_start();
require 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit('Unauthorized');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('POST required');
}

$name  = $_POST['name']  ?? '';
$price = $_POST['price'] ?? '';
$stock = $_POST['stock'] ?? '';
$img   = $_POST['img_url'] ?? '';

if (!$name || !is_numeric($price) || !is_numeric($stock)) {
    http_response_code(422);
    exit('Bad input');
}

$stmt = $pdo->prepare(
  "INSERT INTO products (name, price, stock, img_url) VALUES (:n,:p,:s,:i)"
);
$stmt->execute([
  'n' => $name,
  'p' => $price,
  's' => $stock,
  'i' => $img
]);

echo 'success';