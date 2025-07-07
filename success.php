<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['session_id'])) {
    exit("Missing Stripe session ID.");
}

$stripe_sid = $_GET['session_id'];

// Get user
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$_SESSION['username']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) exit("User not found.");
$user_id = $user['id'];

// Get payment record
$stmt = $pdo->prepare("SELECT tier, amount_myr FROM payments WHERE stripe_sid = ? AND user_id = ?");
$stmt->execute([$stripe_sid, $user_id]);
$payment = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$payment) exit("Payment not found or unauthorized.");

$tier = strtolower($payment['tier']);
$validTiers = ['starter', 'buddy', 'pro', 'premium'];
if (!in_array($tier, $validTiers)) {
    exit("Invalid tier.");
}

// Update payment status to 'paid'
$pdo->prepare("UPDATE payments SET status = 'paid' WHERE stripe_sid = ?")->execute([$stripe_sid]);

// Expire previous subscriptions
$pdo->prepare("
    UPDATE subscriptions
    SET status = 'expired', end_date = ?
    WHERE user_id = ? AND status = 'active'
")->execute([date('Y-m-d'), $user_id]);

// Create new subscription
$startDate = date('Y-m-d');
$endDate = $tier === 'starter' ? null : date('Y-m-d', strtotime('+30 days'));

$pdo->prepare("
    INSERT INTO subscriptions (user_id, tier, status, start_date, end_date, payment_status)
    VALUES (?, ?, 'active', ?, ?, ?)
")->execute([
    $user_id,
    $tier,
    $startDate,
    $endDate,
    ($tier === 'starter') ? 'free' : 'paid'
]);

// Update user tier
$pdo->prepare("
    UPDATE users SET tier = ?, tier_expiry = ?
    WHERE id = ?
")->execute([$tier, $endDate, $user_id]);

unset($_SESSION['cart']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment Success</title>
  <link rel="icon" href="pictures/tab_icon.png" type="image/png">
  <link rel="stylesheet" href="styles/style.css">
  <style>
    body {
      background: #0f1923;
      color: #fff;
      font-family: 'Orbitron', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      text-align: center;
    }
    .box {
      background: #1a1f2e;
      padding: 2rem 3rem;
      border-radius: 10px;
      box-shadow: 0 0 15px #ff4655;
    }
    h2 {
      color: #4caf50;
      margin: 0 0 1rem;
    }
    .btns {
      margin-top: 1.5rem;
      display: flex;
      gap: 1rem;
      justify-content: center;
    }
    .btns a {
      padding: .7rem 1.5rem;
      background: #ff4655;
      color: #fff;
      text-decoration: none;
      border-radius: 5px;
    }
    .btns a:hover {
      background: #ff2c3d;
    }
  </style>
</head>
<body>
  <div class="box">
    <h2>✅ Payment Successful!</h2>
    <p>Your <strong><?= ucfirst($tier) ?> membership</strong> is now active.</p>
    <?php if ($endDate): ?>
      <p>Active until <?= date('d M Y', strtotime($endDate)) ?>.</p>
    <?php else: ?>
      <p>This plan has no expiry date.</p>
    <?php endif; ?>
    <div class="btns">
      <a href="index.php">Back to Home</a>
      <a href="subscription.php">View Subscription</a>
    </div>
  </div>
</body>
</html>