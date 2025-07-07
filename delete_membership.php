<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "No membership ID provided."; exit();
}

$id = $_GET['id'];
$del = $pdo->prepare("DELETE FROM memberships WHERE id = ?");
$del->execute([$id]);

echo "<script>alert('Membership deleted.');window.location='admin.php';</script>";