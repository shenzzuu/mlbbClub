<?php
session_start();
require 'db.php';

$cart = $_SESSION['cart'] ?? [];
$total = 0;
$shipping = 7.00;
$promo_discount = 0.00;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['promo']) && $_POST['promo'] === 'MLBB10') {
    $promo_discount = 10.00;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <link rel="icon" href="pictures/tab_icon.png" type="image/png">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/merch.css">
    <style>
        body {
            color:rgb(255, 255, 255);
        }

        .cart-container {
            width: 90%;
            max-width: 1000px;
            margin: 30px auto;
            background: #1a1a1a;
            border-radius: 10px;
            padding: 20px;
            margin-top: 120px;
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #444;
            padding: 15px 0;
        }
        .cart-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .cart-left img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }
        .cart-left .info {
            display: flex;
            flex-direction: column;
        }
        .cart-right {
            text-align: right;
        }
        .qty-form {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .qty-form input[type="number"] {
            width: 50px;
            text-align: center;
        }
        .remove-btn, .checkout-btn {
            background-color: #f05;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        .remove-btn:hover, .checkout-btn:hover {
            background-color: #c03;
        }
        .checkout-summary {
            text-align: right;
            margin-top: 30px;
        }
        .checkout-summary p {
            margin: 6px 0;
        }
        .checkout-btn {
            margin-top: 20px;
        }
        .promo {
            margin-top: 15px;
            text-align: right;
        }
        .promo input {
            padding: 6px;
            border-radius: 5px;
            border: none;
            width: 150px;
        }
    </style>
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
<h1 style="text-align:center; color: #ff4655; margin-top:50px;">Your Cart</h1>
<div class="cart-container">
<?php if (empty($cart)): ?>
    <p style="text-align:center;">🛒 Your cart is empty.</p>
<?php else: ?>
    <?php foreach ($cart as $item): 
        $img = !empty($item['img']) ? $item['img'] : 'pictures/merch1.jpg';
        $name = htmlspecialchars($item['name'] ?? 'Unknown');
        $qty = $item['qty'] ?? 1;
        $price = $item['price'] ?? 0;
        $item_total = $price * $qty;
        $total += $item_total;
    ?>
    <div class="cart-item">
        <div class="cart-left">
            <img src="<?= $img ?>" alt="<?= $name ?>">
            <div class="info">
                <strong><?= $name ?></strong>
                <small>RM <?= number_format($price, 2) ?> each</small>
                <form method="post" action="cart_handler.php" class="qty-form">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                    <input type="number" name="qty" value="<?= $qty ?>" min="1" onchange="this.form.submit()">
                </form>
            </div>
        </div>
        <div class="cart-right">
            <p><strong>RM <?= number_format($item_total, 2) ?></strong></p>
            <form method="post" action="cart_handler.php">
                <input type="hidden" name="action" value="remove">
                <input type="hidden" name="id" value="<?= $item['id'] ?>">
                <button class="remove-btn">Remove</button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>

    <form method="post" class="promo">
        <label>Promo Code: </label>
        <input type="text" name="promo" placeholder="Enter code">
        <button type="submit" class="remove-btn">Apply</button>
    </form>

    <div class="checkout-summary">
        <p>Subtotal: RM <?= number_format($total, 2) ?></p>
        <p>Shipping: RM <?= number_format($shipping, 2) ?></p>
        <p>Promo Discount: -RM <?= number_format($promo_discount, 2) ?></p>
        <p><strong>Total: RM <?= number_format($total + $shipping - $promo_discount, 2) ?></strong></p>

        <form action="checkout.php" method="post">
            <button type="submit" class="checkout-btn">Proceed to Checkout</button>
        </form>
    </div>
<?php endif; ?>
</div>

</body>
</html>