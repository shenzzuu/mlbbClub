<?php
session_start();
require 'db.php';
if ($_SESSION['role'] !== 'admin') exit('Unauthorized');

if (!isset($_GET['id'])) exit('No ID');
$id = (int)$_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $img   = $_POST['img_url'];

    $pdo->prepare(
      "UPDATE products SET name=:n, price=:p, stock=:s, img_url=:i WHERE id=:id"
    )->execute([
      'n'=>$name,'p'=>$price,'s'=>$stock,'i'=>$img,'id'=>$id
    ]);
    header("Location: admin.php");
    exit();
}

$product = $pdo->prepare("SELECT * FROM products WHERE id=?");
$product->execute([$id]);
$p = $product->fetch();
if (!$p) exit('Not found');
?>
<!DOCTYPE html>
<html>
<head>
  <title>Edit Product</title>
  <link rel="icon" href="pictures/tab_icon.png" type="image/png">
  <link rel="stylesheet" href="styles/style.css">
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
      max-width: 500px;
      box-shadow: 0 0 20px rgba(255, 70, 85, 0.5);
    }

    h2 {
      text-align: center;
      color: #ff4655;
    }

    label {
      display: block;
      margin-top: 15px;
      font-size: 0.95rem;
    }

    input {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      background: #2b2f40;
      color: white;
      border: 1px solid #ff4655;
      border-radius: 5px;
    }

    button, a {
      margin-top: 20px;
      display: inline-block;
      padding: 10px 20px;
      text-align: center;
      background-color:rgb(0, 0, 0);
      color: white;
      text-decoration: none;
      border: none;
      border-radius: 5px;
      font-size: 1rem;
      cursor: pointer;
    }

    a {
      margin-left: 10px;
    }

    button:hover {
      background-color: #e03e4e;
    }

    a:hover {
      background-color: #666;
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
    <h2>Edit Product #<?= $p['id'] ?></h2>
    <form method="post">
      <label>Name:
        <input name="name" value="<?= htmlspecialchars($p['name']) ?>">
      </label>
      <label>Price RM:
        <input name="price" type="number" step="0.01" value="<?= $p['price'] ?>">
      </label>
      <label>Stock:
        <input name="stock" type="number" value="<?= $p['stock'] ?>">
      </label>
      <label>Image URL:
        <input name="img_url" value="<?= htmlspecialchars($p['img_url']) ?>">
      </label>
      <button type="submit">Save</button>
      <a href="admin.php">Cancel</a>
    </form>
  </div>
</body>
</html>