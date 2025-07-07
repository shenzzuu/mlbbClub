<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    exit('Invalid product id');
}

$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) exit('Product not found');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($product['name']) ?> | MLBB Club</title>
  <link rel="icon" href="pictures/tab_icon.png" type="image/png">
  <link rel="stylesheet" href="styles/style.css">
  <style>
    body {
      color: black;
      margin: 0;
    }
    .detail-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 140px 20px 60px;
      min-height: 100vh;
    }
    h1.page-title {
      color: #ff4655;
      font-size: 2rem;
      margin-bottom: 30px;
    }
    .detail-wrap {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 30px;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 12px rgba(255, 70, 85, 0.3);
      max-width: 1000px;
      width: 100%;
    }
    .detail-img img {
      width: 350px;
      height: auto;
      border-radius: 10px;
      object-fit: cover;
      border: 2px solid #ff4655;
    }
    .detail-info {
      flex: 1;
      min-width: 260px;
      color: black;
    }
    .detail-info h2 {
      margin: 0 0 10px;
      color: #ff4655;
    }
    .detail-info p {
      font-size: 0.95rem;
      margin: 10px 0;
    }
    .detail-info input[type="number"] {
      padding: 6px;
      font-size: 1rem;
      border-radius: 4px;
      border: 1px solid #ccc;
    }
    .join-btn {
      background-color: #ff4655;
      border: none;
      padding: 10px 18px;
      color: white;
      border-radius: 6px;
      cursor: pointer;
    }
    .join-btn:hover {
      background-color: #ff2c3d;
    }
  </style>
</head>
<body class="dashboard">

<header class="navbar">
  <div class="nav-left">
    <div class="logo"><img src="pictures/logo.png" alt="MLBB Logo"></div>
    <nav class="nav-links">
      <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>">Home</a>
      <a href="about.php" class="<?= basename($_SERVER['PHP_SELF']) === 'about.php' ? 'active' : '' ?>">About</a>
      <a href="members.php" class="<?= basename($_SERVER['PHP_SELF']) === 'members.php' ? 'active' : '' ?>">Members</a>
      <a href="esports.php" class="<?= basename($_SERVER['PHP_SELF']) === 'esports.php' ? 'active' : '' ?>">E‑Sport</a>
      <a href="subscription.php" class="<?= basename($_SERVER['PHP_SELF']) === 'subscription.php' ? 'active' : '' ?>">Subscription</a>
      <a href="merchandise.php" class="<?= basename($_SERVER['PHP_SELF']) === 'merchandise.php' ? 'active' : '' ?>">Merchandises</a>
      <a href="cart.php" class="<?= basename($_SERVER['PHP_SELF']) === 'cart.php' ? 'active' : '' ?>">Cart</a>
      <a href="order_history.php" class="<?= basename($_SERVER['PHP_SELF']) === 'order_history.php' ? 'active' : '' ?>">Orders</a>
    </nav>
  </div>
</header>

<div class="detail-container">
  <h1 class="page-title">Product Details</h1>
  <div class="detail-wrap">
    <div class="detail-img">
      <img src="<?= htmlspecialchars($product['img_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
    </div>

    <div class="detail-info">
      <h2><?= htmlspecialchars($product['name']) ?></h2>
      <p style="font-size:1.2rem;"><strong>RM <?= number_format($product['price'],2) ?></strong></p>
      <p><?= nl2br(htmlspecialchars($product['description'] ?? 'No description.')) ?></p>
      <p style="color:#aaa;">Stock: <?= $product['stock'] ?></p>

      <?php if ($product['stock'] > 0): ?>
        <form action="cart_handler.php" method="post">
          <input type="hidden" name="action" value="add">
          <input type="hidden" name="id" value="<?= $product['id'] ?>">
          <label>Qty:
            <input type="number" name="qty" value="1" min="1" max="<?= $product['stock'] ?>" style="width:60px;">
          </label><br><br>
          <button class="join-btn" type="submit">Add to Cart</button>
        </form>
      <?php else: ?>
        <p style="color:#f05;font-weight:bold;">Out of Stock</p>
      <?php endif; ?>
    </div>
  </div>
</div>
<footer class="footer">
  <div class="footer-content">
    <div class="footer-section about">
      <h3>MLBB Club Malaysia</h3>
      <p>Your ultimate hub for Mobile Legends fans. From tournaments to exclusive content — join the legend today!</p>
    </div>

    <div class="footer-section links">
      <h4>Quick Links</h4>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="esports.php">E-Sport</a></li>
        <li><a href="subscription.php">Subscription</a></li>
        <li><a href="merchandise.php">Merchandise</a></li>
      </ul>
    </div>

    <div class="footer-section contact">
      <h4>Contact Us</h4>
      <p>Email: support@mlbbclub.my</p>
      <p>Instagram: @mlbbclubmy</p>
      <p>Facebook: MLBB Club Malaysia</p>
    </div>
  </div>

  <div class="footer-bottom">
  <p>
    &copy; <?= date('Y') ?> MLBB Club Malaysia. All Rights Reserved. |
    <a href="policy.php" style="color:#ccc;">Policy</a>
  </p>
</div>
</footer>
</body>
</html>