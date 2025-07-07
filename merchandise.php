<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$perPage = 14;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $perPage;

$total_stmt = $pdo->query("SELECT COUNT(*) FROM products");
$total_rows = $total_stmt->fetchColumn();
$totalPages = ceil($total_rows / $perPage);

$stmt = $pdo->prepare("SELECT * FROM products ORDER BY id LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Merchandise | Mobile Legends Club</title>
  <link rel="icon" href="pictures/tab_icon.png" type="image/png">
  <link rel="stylesheet" href="styles/style.css">
  <link rel="stylesheet" href="styles/merch.css">
  <style>
    .products {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
      padding: 40px;
    }
    .card {
      background: #111;
      border-radius: 10px;
      padding: 20px;
      text-align: center;
      color: #fff;
      box-shadow: 0 0 5px #f05;
    }
    .card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
      border-radius: 8px;
    }
    .card h3 a {
      color: #fff;
      text-decoration: none;
    }
    .pagination {
      text-align: center;
      margin: 20px 0;
    }
    .pagination a {
      display: inline-block;
      margin: 0 5px;
      padding: 8px 14px;
      background: #222;
      color: #fff;
      border-radius: 5px;
      text-decoration: none;
    }
    .pagination a.active {
      background: #f05;
      font-weight: bold;
    }
  </style>
</head>
<body>

<header class="navbar">
  <div class="nav-left">
    <div class="logo">
      <img src="pictures/logo.png" alt="MLBB Logo" />
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
          <?= htmlspecialchars($_SESSION['username']) ?>
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
<h1 style="text-align:center; color: #ff4655; margin-top:50px;">Merchandise</h1>
<section class="products">
  <?php if ($products): ?>
    <?php foreach ($products as $product): ?>
      <div class="card">
        <a href="product_detail.php?id=<?= $product['id'] ?>">
          <img src="<?= htmlspecialchars($product['img_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
        </a>
        <h3>
          <a href="product_detail.php?id=<?= $product['id'] ?>">
            <?= htmlspecialchars($product['name']) ?>
          </a>
        </h3>
        <p>RM <?= number_format($product['price'], 2) ?></p>
        <p style="color:#aaa;">Stock: <?= $product['stock'] ?></p>
        <?php if (!isset($product['stock']) || $product['stock'] > 0): ?>
          <form class="add-cart-form" action="cart_handler.php" method="post">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="id" value="<?= $product['id'] ?>">
            <label for="qty">Qty:</label>
            <input type="number" name="qty" value="1" min="1" max="<?= $product['stock'] ?? 99 ?>" style="width: 50px;" />
            <button type="submit">Add to Cart</button>
          </form>
        <?php else: ?>
          <p style="color: #f05; font-weight: bold;">Out of Stock</p>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p style="color:white; text-align:center;">No products available.</p>
  <?php endif; ?>
</section>

<div class="pagination">
  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
    <a href="?page=<?= $i ?>" class="<?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
  <?php endfor; ?>
</div>
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
<script>
document.addEventListener('DOMContentLoaded', () => {

  document.querySelectorAll('.add-cart-form').forEach(form => {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();

      const data = new FormData(form);
      data.append('ajax', '1');        

      try {
        const res  = await fetch('cart_handler.php', { method:'POST', body:data });

        if (!res.ok || res.redirected) {
          throw new Error('bad response');
        }

        const json = await res.json();
        if (json.status === 'ok') {
          const badge = document.getElementById('cart-badge');
          if (badge) {
            badge.textContent = json.cartCount;
            badge.style.display = json.cartCount > 0 ? 'inline-block' : 'none';
          }
          alert('Item added to cart!');
        } else {
          alert('Server error – please try again.');
        }

      } catch (_err) {
        alert('Network error – please try again later.');
      }
    });
  });
});
</script>


</body>
</html>