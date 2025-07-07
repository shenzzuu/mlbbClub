<?php
session_start();
require 'db.php';                       

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$payments = $pdo->query("
    SELECT p.*, u.username
      FROM payments p
      JOIN users  u ON u.id = p.user_id
  ORDER BY p.created_at ASC
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin • Payments | MLBB Club</title>
  <link rel="icon" href="pictures/tab_icon.png" type="image/png">

  <link rel="stylesheet" href="styles/style.css">

  <style>
    body{
    background: linear-gradient(135deg, #1a1a1a 0%, #000000 100%);
    margin: 0;
    padding-top: 100px; 
    min-height: 100vh;
    }
  .center-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: calc(100vh - 100px); 
    padding: 40px 20px;
  }

  .dashboard-section.centered {
    width: 90%;
    max-width: 1100px;
    background:rgb(0, 0, 0);
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 0 15px #ff4655;
  }

  .dashboard-section h3 {
    text-align: center;
    margin-bottom: 20px;
    color: #ff4655;
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }

  th, td {
    padding: 10px 12px;
    border: 1px solid #333;
    text-align: center;
  }

  td{
    color: #ffffff;
  }

  th {
    background: #ff4655;
    color:rgb(0, 0, 0);
  }

  .actions a {
    margin: 0 4px;
    color: #ff4655;
    text-decoration: none;
  }

  .actions a:hover {
    text-decoration: underline;
  }

  .admin-text {
    display: flex;
    align-items: center;
    gap: 1rem;
    color: white;
    font-size: 1.2rem; 
    }
</style>

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

<div class="center-wrapper">
  <div class="dashboard-section centered">
    <h3>Payment Records</h3>

    <table>
      <tr>
        <th>ID</th>
        <th>User</th>
        <th>Purpose</th>
        <th>Tier / Order</th>
        <th>Amount (RM)</th>
        <th>Status</th>
        <th>Created</th>
        <th>Actions</th>
      </tr>

      <?php foreach ($payments as $p): ?>
      <tr>
        <td><?= $p['id'] ?></td>
        <td><?= htmlspecialchars($p['username']) ?></td>
        <td><?= ucfirst($p['purpose']) ?></td>
        <td>
          <?= $p['purpose'] === 'subscription'
              ? ucfirst($p['tier'])
              : '#'.$p['order_id']; ?>
        </td>
        <td><?= number_format($p['amount_myr'], 2) ?></td>
        <td><?= ucfirst($p['status']) ?></td>
        <td><?= date('d M Y H:i', strtotime($p['created_at'])) ?></td>
        <td class="actions">
          <a href="edit_payment.php?id=<?= $p['id'] ?>">Edit</a>
          <a href="delete_payment.php?id=<?= $p['id'] ?>"
             onclick="return confirm('Delete this payment record?')">Delete</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
  </div>
</div>


    </table>
  </div>

</div>
</body>
</html>