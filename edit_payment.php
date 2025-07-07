<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
if (!isset($_GET['id'])) exit('Payment ID missing');
$id = (int)$_GET['id'];

/* fetch payment record */
$pay = $pdo->prepare("SELECT * FROM payments WHERE id = ?");
$pay->execute([$id]);
$p = $pay->fetch(PDO::FETCH_ASSOC);
if (!$p) exit('Payment not found');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'];
    $amount = (float)$_POST['amount_myr'];

    $pdo->prepare("
        UPDATE payments
           SET status = :s,
               amount_myr = :a
         WHERE id = :id
    ")->execute(['s' => $status, 'a' => $amount, 'id' => $id]);

    echo "<script>alert('Payment updated');location='admin_payments.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Edit Payment #<?= $id ?></title>
  <link rel="icon" href="pictures/tab_icon.png" type="image/png">
  <link rel="stylesheet" href="styles/style.css">
  <style>
    body {
      background: linear-gradient(135deg, #1a1a1a 0%, #000000 100%);
      color: #fff;
      text-align: center;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
    }
    form {
      background: #1a1f2e;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 15px #ff4655;
      width: 100%;
      max-width: 400px;
    }
    label {
      display: block;
      margin-top: 15px;
      text-align: left;
    }
    input, select {
      width: 100%;
      padding: 8px;
      background: #2b2f40;
      border: 1px solid #ff4655;
      color: #fff;
      border-radius: 5px;
    }
    button {
      margin-top: 20px;
      background: #ff4655;
      padding: 10px 25px;
      border: none;
      color: #fff;
      border-radius: 5px;
      cursor: pointer;
    }
    button:hover {
      background: #ff2c3d;
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
      <a href="admin_tournaments.php">Tournament</a>
      <a href="admin_staff.php">Staff</a>
      <a href="logout.php">Logout</a>
    </nav>
  </div>
  <div class="admin-text">Welcome, <?= htmlspecialchars($_SESSION['username']) ?></div>
</header>

  <form method="post">
    <h3>Edit Payment #<?= $id ?></h3>

    <label>Status:
      <select name="status" required>
        <?php foreach (['pending', 'paid', 'failed'] as $s): ?>
          <option value="<?= $s ?>" <?= $p['status'] === $s ? 'selected' : '' ?>>
            <?= ucfirst($s) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>

    <label>Amount (RM):
      <input type="number" step="0.01" name="amount_myr"
             value="<?= $p['amount_myr'] ?>" required>
    </label>

    <button type="submit">Save</button>
  </form>
</body>
</html>