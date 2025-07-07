<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "No order ID provided.";
    exit();
}

$id = $_GET['id'];
$delete = $pdo->prepare("DELETE FROM orders WHERE id = ?");
$delete->execute([$id]);

echo "<script>alert('Order deleted.');window.location='admin.php';</script>";