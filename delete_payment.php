<?php
session_start();
require 'db.php';
if (!isset($_SESSION['username']) || $_SESSION['role']!=='admin') {
    header('Location: login.php'); exit();
}
if (!isset($_GET['id'])) exit('ID missing');
$id = (int)$_GET['id'];

$pdo->prepare("DELETE FROM payments WHERE id = ?")->execute([$id]);
header('Location: admin_payments.php');
exit();