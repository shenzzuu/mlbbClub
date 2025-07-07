<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "Order ID missing."; exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$id]);
$order = $stmt->fetch();

if (!$order) {
    echo "Order not found."; exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'];
    $update = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $update->execute([$status, $id]);
    echo "<script>alert('Order updated successfully');window.location='admin.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Order #<?= $id ?></title>
  <link rel="icon" href="pictures/tab_icon.png" type="image/png">
  <link href="https://fonts.googleapis.com/css2?family=Orbitron&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles/style.css">
  <style>
    body {
      background: linear-gradient(135deg, #1a1a1a 0%, #000000 100%);
      color: white;
      margin: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .form-container {
      background-color: #1a1f2e;
      padding: 30px;
      border-radius: 10px;
      width: 100%;
      max-width: 400px;
      box-shadow: 0 0 20px rgba(255, 70, 85, 0.5);
    }

    h2 {
      text-align: center;
      color: #ff4655;
    }

    label {
      display: block;
      margin: 15px 0 5px;
    }

    select {
      width: 100%;
      padding: 10px;
      background: #2b2f40;
      color: white;
      border: 1px solid #ff4655;
      border-radius: 5px;
    }

    button {
      margin-top: 20px;
      width: 100%;
      padding: 12px;
      background-color: #ff4655;
      border: none;
      color: white;
      cursor: pointer;
      font-size: 1rem;
      border-radius: 5px;
    }

    button:hover {
      background-color: #e03e4e;
    }
  </style>
</head>
<header class="navbar">
  <div class="nav-left">
    <div class="logo"><img src="pictures/logo.png" alt="MLBB Logo"></div>
    <nav class="nav-links">
      <a href="admin.php">Home</a>
      <a href="admin_payments.php">Payment</a>
      <a href="admin_tournaments.php">Tournament</a>
      <a href="admin_staff.php">Staff</a>
      <a href="logout.php">Logout</a>
    </nav>
  </div>
  <div class="admin-text">Welcome, <?= htmlspecialchars($_SESSION['username']) ?></div>
</header>
<body>
  <div class="form-container">
    <h2>Edit Order #<?= $id ?></h2>
    <form method="post">
      <label for="status">Status:</label>
      <select name="status" id="status">
        <option value="pending"   <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
        <option value="completed" <?= $order['status'] === 'paid' ? 'selected' : '' ?>>Completed</option>
        <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
      </select>
      <button type="submit">Update</button>
    </form>
  </div>
</body>
</html>
