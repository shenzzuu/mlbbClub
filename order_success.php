<?php
session_start();
require 'db.php';                       // ← your PDO connection

/* ───────── 0. Basic guards ───────── */
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); exit();
}
if (!isset($_GET['session_id'])) {
    exit('Missing session_id');
}
$stripeSid = $_GET['session_id'];

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    header('Location: index.php'); exit();
}

/* ───────── 1. Fetch user row ─────── */
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$_SESSION['username']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) exit('User not found.');
$user_id = $user['id'];

/* ───────── 2. Calculate order total ─ */
$total = 0;
foreach ($cart as $c) {
    $total += $c['price'] * $c['qty'];
}

/* ───────── 3. DB transaction ─────── */
$pdo->beginTransaction();
try {
    /* 3a. create order */
    $orderStmt = $pdo->prepare("
        INSERT INTO orders (user_id, total, status, created_at)
        VALUES (?, ?, 'paid', NOW())
        RETURNING id
    ");
    $orderStmt->execute([$user_id, $total]);
    $order_id = $orderStmt->fetchColumn();

    /* 3b. insert order_items & update stock */
    $itemStmt  = $pdo->prepare("
        INSERT INTO order_items (order_id, product_id, quantity, price)
        VALUES (?, ?, ?, ?)
    ");
    $stockStmt = $pdo->prepare("
        UPDATE products SET stock = stock - ? WHERE id = ?
    ");

    foreach ($cart as $item) {
        $itemStmt->execute([$order_id,
                            $item['id'],
                            $item['qty'],
                            $item['price']]);
        $stockStmt->execute([$item['qty'], $item['id']]);
    }

    /* 3c. mark payment row as PAID */
    $payUpd = $pdo->prepare("
        UPDATE payments
           SET status    = 'paid',
               order_id  = :oid      -- store linkage
         WHERE stripe_sid = :sid
           AND user_id    = :uid
           AND status     = 'pending'
    ");
    $payUpd->execute([
        'oid' => $order_id,
        'sid' => $stripeSid,
        'uid' => $user_id
    ]);

    $pdo->commit();

    /* 3d. clear cart */
    unset($_SESSION['cart']);
    $_SESSION['checkout_success'] = true;

} catch (Throwable $e) {
    $pdo->rollBack();
    die("DB error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Success</title>
  <link rel="icon" href="pictures/tab_icon.png" type="image/png">
  <link rel="stylesheet" href="styles/style.css">
  <style>
    body{
      background:#0f1923;color:#fff;font-family:'Orbitron',sans-serif;
      display:flex;justify-content:center;align-items:center;height:100vh;margin:0;
      text-align:center
    }
    .box{
      background:#1a1f2e;padding:2.5rem 3.5rem;border-radius:12px;
      box-shadow:0 0 15px #ff4655;max-width:420px
    }
    h2{color:#4caf50;margin:0 0 1rem}
    .details{margin-top:1rem;font-size:0.9rem;color:#ccc}
    .btns{margin-top:1.8rem;display:flex;gap:1rem;justify-content:center}
    .btns a{
      padding:.7rem 1.6rem;background:#ff4655;color:#fff;border-radius:5px;
      text-decoration:none;font-size:0.9rem
    }
    .btns a:hover{background:#ff2c3d}
  </style>
</head>
<body>
  <div class="box">
    <h2>✅ Payment Successful!</h2>
    <p>Thank you, your order <strong>#<?= $order_id ?></strong> has been placed.</p>
    <p class="details">Total paid: <strong>RM <?= number_format($total,2) ?></strong></p>
    <div class="btns">
      <a href="order_history.php">View Orders</a>
      <a href="merchandise.php">Continue Shopping</a>
    </div>
  </div>
</body>
</html>