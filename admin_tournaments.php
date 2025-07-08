<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$regs = $pdo->query("
    SELECT r.*, u.username
    FROM   tournament_registrations r
    JOIN   users u  ON u.id = r.user_id
    ORDER  BY r.registered_at ASC
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin • Tournament Registrations | MLBB Club</title>
  <link rel="icon" href="pictures/tab_icon.png" type="image/png">
  <link rel="stylesheet" href="styles/style.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      min-height: 100vh;
      background: linear-gradient(135deg, #1a1a1a 0%, #000000 100%);
      color: #fff;
      display: flex;
      flex-direction: column;
    }
    .admin-text {
    display: flex;
    align-items: center;
    gap: 1rem;
    color: white;
    font-size: 1.2rem; }

    .wrapper {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 140px 20px 40px;
    }

    h3 {
      margin-bottom: 20px;
      color: #ff4655;
      text-align: center;
    }

    .table-container {
      width: 100%;
      max-width: 1150px;
      overflow-x: auto;
      border: 1px solid #333;
      border-radius: 8px;
      background: #1a1f2e;
      box-shadow: 0 0 15px rgba(255, 70, 85, .35);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      text-align: center;
      min-width: 900px;
    }

    th, td {
      padding: 12px 16px;
    }

    th {
      background: #ff4655;
      border-bottom: 2px solid #ff4655;
      font-weight: 600;
      font-size: .9rem;
      color: rgb(0, 0, 0);
    }

    td {
      background: black;
      border-bottom: 1px solid #333;
      font-size: .85rem;
    }

    .actions a {
      margin: 0 6px;
      color: #ff4655;
      text-decoration: none;
    }

    .actions a:hover {
      color: #ffd1d6;
    }
  </style>
</head>
<body>

<header class="navbar">
  <div class="nav-left">
    <div class="logo"><img src="pictures/logo.png" alt="MLBB Logo"></div>
    <nav class="nav-links">
      <a href="admin.php">Home</a>
      <a href="admin_payments.php">Payment</a>
      <a href="admin_tournaments.php" class="active">Tournament</a>
      <a href="admin_staff.php">Staff</a>
      <a href="logout.php">Logout</a>
    </nav>
  </div>
  <div class="admin-text">Welcome, <?= htmlspecialchars($_SESSION['username']) ?></div>
</header>

<div class="wrapper">
  <h3>Tournament Registrations</h3>

  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>User</th>
          <th>Tournament</th>
          <th>Role</th>
          <th>Team / IG‑ID / Spectator</th>
          <th>Registered</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($regs as $r): ?>
        <tr>
          <td><?= $r['id'] ?></td>
          <td><?= htmlspecialchars($r['username'] ?? '') ?></td>
          <td><?= htmlspecialchars($r['tournament_name'] ?? '') ?></td>
          <td><?= ucfirst($r['role']) ?></td>
          <td>
            <?php if ($r['role'] === 'player'): ?>
              <?= htmlspecialchars($r['team_name'] ?? '-') ?><br>
              <small>ID: <?= htmlspecialchars($r['ingame_id'] ?? '-') ?></small>
            <?php else: ?>
              <?= htmlspecialchars($r['spectator_type'] ?? '-') ?>
            <?php endif; ?>
          </td>
          <td><?= $r['registered_at'] ? date('d M Y H:i', strtotime($r['registered_at'])) : '-' ?></td>
          <td class="actions">
            <a href="edit_registration.php?id=<?= $r['id'] ?>">Edit</a>
            <a href="delete_registration.php?id=<?= $r['id'] ?>" onclick="return confirm('Delete this registration?')">Delete</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
