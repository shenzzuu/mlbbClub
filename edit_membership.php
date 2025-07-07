<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    exit('Membership ID missing.');
}
$id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM memberships WHERE id = ?");
$stmt->execute([$id]);
$membership = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$membership) exit('Membership not found.');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type   = $_POST['type']; 
    $status = $_POST['status'];
    $start  = $_POST['start_date'];
    $end    = $_POST['end_date'];

    $update = $pdo->prepare("
        UPDATE memberships
        SET type = :type, status = :status, start_date = :start, end_date = :end
        WHERE id = :id
    ");
    $update->execute([
        'type'   => $type,
        'status' => $status,
        'start'  => $start,
        'end'    => $end,
        'id'     => $id
    ]);

    echo "<script>alert('Membership updated successfully');window.location='admin.php';</script>";
    exit();
}

$currentType   = $membership['type'] ?? '';
$currentStatus = $membership['status'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Membership</title>
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
      max-width: 500px;
      box-shadow: 0 0 20px rgba(255, 70, 85, 0.5);
    }

    h2 {
      text-align: center;
      color: #ff4655;
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-top: 15px;
    }

    input, select {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
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
    <h2>Edit Membership #<?= $id ?></h2>
    <form method="post">
      <label>Tier:
        <select name="type" required>
          <?php foreach (['starter','buddy','pro','premium'] as $option): ?>
            <option value="<?= $option ?>" <?= $option === $currentType ? 'selected' : '' ?>>
              <?= ucfirst($option) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </label>

      <label>Status:
        <select name="status" required>
          <?php foreach (['active','expired','cancelled'] as $option): ?>
            <option value="<?= $option ?>" <?= $option === $currentStatus ? 'selected' : '' ?>>
              <?= ucfirst($option) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </label>

      <label>Start Date:
        <input type="date" name="start_date" value="<?= $membership['start_date'] ?>" required>
      </label>

      <label>End Date:
        <input type="date" name="end_date" value="<?= $membership['end_date'] ?>" required>
      </label>

      <button type="submit">Update</button>
    </form>
  </div>
</body>
</html>