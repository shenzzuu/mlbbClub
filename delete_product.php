<?php
session_start();
require 'db.php';
if ($_SESSION['role'] !== 'admin') exit('Unauthorized');

if (!isset($_GET['id'])) exit('No ID');
$id = (int)$_GET['id'];

try {
    $pdo->prepare("DELETE FROM products WHERE id=?")->execute([$id]);
    header("Location: admin.php?msg=deleted");
} catch (PDOException $e) {
    header("Location: admin.php?msg=err");
}