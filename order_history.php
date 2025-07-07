<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$_SESSION['username']]);
$user = $stmt->fetch();
if (!$user) exit("User not found.");
$user_id = $user['id'];

$status_filter = $_GET['status'] ?? 'all';
$sql = "SELECT * FROM orders WHERE user_id = ?";
$params = [$user_id];
if ($status_filter !== 'all') {
    $sql .= " AND status = ?";
    $params[] = $status_filter;
}
$sql .= " ORDER BY created_at DESC";
$order_stmt = $pdo->prepare($sql);
$order_stmt->execute($params);
$orders = $order_stmt->fetchAll(PDO::FETCH_ASSOC);

$order_ids = array_column($orders, 'id');
$items = [];
if (!empty($order_ids)) {
    $in = implode(',', array_fill(0, count($order_ids), '?'));
    $item_stmt = $pdo->prepare("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id IN ($in)");
    $item_stmt->execute($order_ids);
    foreach ($item_stmt->fetchAll(PDO::FETCH_ASSOC) as $item) {
        $items[$item['order_id']][] = $item;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order History</title>
    <link rel="icon" href="pictures/tab_icon.png" type="image/png">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/merch.css">
</head>
<body>
<header class="navbar">
  <div class="nav-left">
    <div class="logo">
      <img src="pictures/logo.png" alt="MLBB Logo">
    </div>
    <nav class="nav-links">
  <a href="index.php"
     class="<?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>">Home</a>

  <a href="about.php"
     class="<?= basename($_SERVER['PHP_SELF']) === 'about.php' ? 'active' : '' ?>">About</a>

  <a href="members.php"
     class="<?= basename($_SERVER['PHP_SELF']) === 'members.php' ? 'active' : '' ?>">Members</a>

  <a href="esports.php"
     class="<?= basename($_SERVER['PHP_SELF']) === 'esports.php' ? 'active' : '' ?>">E‑Sport</a>

  <a href="subscription.php"
     class="<?= basename($_SERVER['PHP_SELF']) === 'subscription.php' ? 'active' : '' ?>">Subscription</a>

  <a href="merchandise.php"
     class="<?= basename($_SERVER['PHP_SELF']) === 'merchandise.php' ? 'active' : '' ?>">Merchandises</a>

  <a href="cart.php"
     class="<?= basename($_SERVER['PHP_SELF']) === 'cart.php' ? 'active' : '' ?>">Cart</a>

  <a href="order_history.php"
     class="<?= basename($_SERVER['PHP_SELF']) === 'order_history.php' ? 'active' : '' ?>">Orders</a>
</nav>

  </div>
  <div class="nav-right">
  <input type="text" placeholder="Search..." />
  <?php if (isset($_SESSION['username']) && isset($_SESSION['role'])): ?>
  <div class="dropdown">
    <button class="login-btn dropdown-toggle">
      <?= htmlspecialchars($_SESSION['username'])?>
    </button>
    <div class="dropdown-menu">
      <a href="profile.php">Profile</a>
      <a href="logout.php">Logout</a>
    </div>
  </div>
<?php else: ?>
  <a href="login.php"><button class="login-btn">Login</button></a>
<?php endif; ?>
</div>
</header>

<main class="page-content">
<section class="cart">
    <form method="get" style="text-align: center; margin-bottom: 20px;">
        <label for="status">Filter by Status:</label>
        <select name="status" onchange="this.form.submit()">
            <option value="all" <?= $status_filter === 'all' ? 'selected' : '' ?>>All</option>
            <option value="pending" <?= $status_filter === 'pending' ? 'selected' : '' ?>>Pending</option>
            <option value="completed" <?= $status_filter === 'completed' ? 'selected' : '' ?>>Completed</option>
            <option value="cancelled" <?= $status_filter === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
        </select>
    </form>

    <?php if (empty($orders)): ?>
        <p style="text-align: center;">No past orders found.</p>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <div class="card" style="width:80%; margin:auto;">
                <h3>Order #<?= $order['id'] ?> — <?= ucfirst($order['status']) ?> — RM <?= number_format($order['total'], 2) ?></h3>
                <p>Placed on: <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></p>

                <table style="margin-top:10px;">
                    <tr><th>Item</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr>
                    <?php foreach ($items[$order['id']] ?? [] as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>RM <?= number_format($item['price'], 2) ?></td>
                            <td>RM <?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>

                <form action="receipt.php" method="get" target="_blank" style="text-align:right; margin-top:10px;">
                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                    <button type="submit">View Receipt</button>
                </form>
            </div>
            <br>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
</main>

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
      <h4>Contact Us</h4>
      <p>Email: support@mlbbclub.my</p>
      <p>Instagram: <a href="#">@mlbbclubmy</a></p>
      <p>Facebook: <a href="#">MLBB Club Malaysia</a></p>
    </div>
  </div>

  <div class="footer-bottom">
    <p>&copy; <?= date('Y') ?> MLBB Club Malaysia. All Rights Reserved.</p>
  </div>
</footer>
</body>
</html>