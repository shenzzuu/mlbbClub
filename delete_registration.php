<?php
session_start();
require 'db.php';
if(!isset($_SESSION['username'])||$_SESSION['role']!=='admin'){header('Location: login.php');exit();}
if(!isset($_GET['id'])) exit('Missing ID');
$id=(int)$_GET['id'];

$pdo->prepare("DELETE FROM tournament_registrations WHERE id=?")->execute([$id]);
header('Location: admin_tournaments.php'); exit();