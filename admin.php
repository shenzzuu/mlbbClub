<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$users       = $pdo->query("SELECT id, username, email, role FROM users ORDER BY id")
                   ->fetchAll(PDO::FETCH_ASSOC);
$products    = $pdo->query("SELECT id, name, price, stock, img_url FROM products ORDER BY id")
                   ->fetchAll(PDO::FETCH_ASSOC);
$orders      = $pdo->query("SELECT * FROM orders ORDER BY id DESC")
                   ->fetchAll(PDO::FETCH_ASSOC);
$memberships = $pdo->query("SELECT * FROM memberships ORDER BY id DESC")
                   ->fetchAll(PDO::FETCH_ASSOC);   
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard – MLBB Club</title>
  <link rel="icon" href="pictures/tab_icon.png" type="image/png">
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles/style.css">
  <link rel="stylesheet" href="styles/admin.css">
  <link rel="stylesheet" href="styles/admin_modal.css">
</head>
<body class="dashboard">

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

<div class="dashboard-grid"><br><br><br>

  <!-- USERS -->
  <div class="dashboard-section">
    <h3>Manage Users</h3>
    <table>
      <tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Actions</th></tr>
      <?php foreach ($users as $u): ?>
      <tr>
        <td><?= $u['id'] ?></td>
        <td><?= htmlspecialchars($u['username']) ?></td>
        <td><?= htmlspecialchars($u['email']) ?></td>
        <td><?= htmlspecialchars($u['role']) ?></td>
        <td class="actions">
          <a href="edit_user.php?id=<?= $u['id'] ?>">Edit</a>
          <a href="delete_user.php?id=<?= $u['id'] ?>" onclick="return confirm('Delete this user?')">Delete</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </table><br><br>
    <a href="add_user.php" class="join-btn">Add User</a>
  </div>

  <!-- PRODUCTS -->
  <div class="dashboard-section">
    <h3>Manage Products</h3>
    <table>
      <tr><th>ID</th><th>Name</th><th>Price (RM)</th><th>Stock</th><th>Actions</th></tr>
      <?php foreach ($products as $p): ?>
      <tr>
        <td><?= $p['id'] ?></td>
        <td><?= htmlspecialchars($p['name']) ?></td>
        <td><?= number_format($p['price'],2) ?></td>
        <td><?= $p['stock'] ?></td>
        <td class="actions">
          <a href="edit_product.php?id=<?= $p['id'] ?>">Edit</a>
          <a href="delete_product.php?id=<?= $p['id'] ?>" onclick="return confirm('Delete product?')">Delete</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
    <!-- Modal trigger -->
    <button id="showAddModal" class="join-btn">➕ Add Product</button>
  </div>

<!-- ORDERS -->
<div class="dashboard-section">
  <h3>Manage Orders</h3>
  <table>
    <tr><th>ID</th><th>User</th><th>Total (RM)</th><th>Status</th><th>Date</th><th>Actions</th></tr>
    <?php foreach ($orders as $o): ?>
    <tr>
      <td><?= $o['id'] ?></td>
      <td><?= $o['user_id'] ?></td>
      <td><?= number_format($o['total'],2) ?></td>
      <td><?= htmlspecialchars($o['status']) ?></td>
      <td><?= date('d M Y', strtotime($o['created_at'])) ?></td>
      <td class="actions">
        <a href="edit_order.php?id=<?= $o['id'] ?>">Edit</a>
        <a href="delete_order.php?id=<?= $o['id'] ?>" onclick="return confirm('Delete this order?')">Delete</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
</div>

<!-- MEMBERSHIPS -->
<div class="dashboard-section">
  <h3>Manage Memberships</h3>
  <table>
    <tr>
      <th>ID</th><th>User</th><th>Tier</th><th>Status</th><th>Start</th><th>End</th><th>Actions</th>
    </tr>
    <?php foreach ($memberships as $m): ?>
    <tr>
      <td><?= $m['id'] ?></td>
      <td><?= $m['user_id'] ?></td>
      <td><?= ucfirst($m['tier'] ?? $m['type']) ?></td>
      <td><?= ucfirst($m['status']) ?></td>
      <td><?= date('d M Y', strtotime($m['start_date'])) ?></td>
      <td><?= date('d M Y', strtotime($m['end_date'])) ?></td>
      <td class="actions">
        <a href="edit_membership.php?id=<?= $m['id'] ?>">Edit</a>
        <a href="delete_membership.php?id=<?= $m['id'] ?>" onclick="return confirm('Delete this membership?')">Delete</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
</div>

</div>

<div id="addModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h3>Add New Product</h3>
    <form id="addProductForm">
      <input name="name"      placeholder="Name" required><br>
      <input name="price"     type="number" step="0.01" placeholder="Price RM" required><br>
      <input name="stock"     type="number" placeholder="Stock" required><br>
      <input name="img_url"   placeholder="Image URL"><br>
      <button type="submit">Save</button>
    </form>
    <div id="addResult" style="margin-top:8px;"></div>
  </div>
</div>
<script src="scripts/product_modal.js"></script>

</body>
</html>
