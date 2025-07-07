<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "<script>alert('No user ID provided.'); window.location.href='admin.php';</script>";
    exit();
}

$id = $_GET['id'];

$stmtAdmin = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmtAdmin->execute([$_SESSION['username']]);
$admin = $stmtAdmin->fetch(PDO::FETCH_ASSOC);

if ($admin && $admin['id'] == $id) {
    echo "<script>alert('You cannot delete your own account.'); window.location.href='admin.php';</script>";
    exit();
}

try {
    $sub_stmt = $pdo->prepare("DELETE FROM subscriptions WHERE user_id = ?");
    $sub_stmt->execute([$id]);

    $user_stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $user_stmt->execute([$id]);

    echo "<script>alert('User and related subscriptions deleted successfully.'); window.location.href='admin.php';</script>";
} catch (PDOException $e) {
    echo "<script>alert('Error: {$e->getMessage()}'); window.location.href='admin.php';</script>";
}
?>