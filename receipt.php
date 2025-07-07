<?php
session_start();
require 'db.php';

if (!isset($_GET['order_id'])) {
    exit("No order selected.");
}

$order_id = (int)$_GET['order_id'];

$stmt = $pdo->prepare("
    SELECT o.* , u.username
      FROM orders   o
      JOIN users    u ON u.id = o.user_id
     WHERE o.id = ?
");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order || ($_SESSION['role'] !== 'admin' && $order['username'] !== $_SESSION['username'])) {
    exit("Unauthorized access.");
}

$stmt = $pdo->prepare("
    SELECT oi.* , p.name
      FROM order_items oi
      JOIN products    p ON p.id = oi.product_id
     WHERE oi.order_id = ?
");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Receipt #<?= $order_id ?></title>
<link rel="icon" href="pictures/tab_icon.png" type="image/png">

<style>
    *{box-sizing:border-box;margin:0;padding:0}
    body{
        font-family:'Orbitron', Arial, sans-serif;
        background:#0f1923;            
        color:#ffffff;
        padding:40px 20px;
    }
    .receipt{
        max-width:750px;
        margin:auto;
        background:#1a1f2e;
        border-radius:10px;
        box-shadow:0 0 15px #ff465540;
        padding:30px;
    }
    h1{
        color:#ff4655;
        text-align:center;
        margin-bottom:25px;
    }
    .meta{
        display:flex;
        flex-wrap:wrap;
        justify-content:space-between;
        margin-bottom:25px;
        font-size:0.95rem;
    }
    .meta p{margin:4px 0}
    table{
        width:100%;
        border-collapse:collapse;
        margin-bottom:20px;
        font-size:0.9rem;
    }
    th,td{
        padding:10px;
        text-align:left;
    }
    th{
        background:#ff4655;
        color:#000;
    }
    tbody tr:nth-child(even){background:#222831}
    tfoot td{
        font-weight:700;
        border-top:2px solid #ff4655;
    }
    .print-btn{
        display:block;
        margin:10px auto 0;
        background:#ff4655;
        border:none;
        color:#fff;
        padding:10px 25px;
        border-radius:6px;
        cursor:pointer;
        font-size:1rem;
        transition:background .25s;
    }
    .print-btn:hover{background:#e03a46}
    @media print{
        body{background:#fff;color:#000}
        .receipt{box-shadow:none}
        .print-btn{display:none}
    }
</style>
</head>
<body>

<div class="receipt">
    <h1>Order Receipt #<?= $order_id ?></h1>

    <div class="meta">
        <p><strong>User:</strong> <?= htmlspecialchars($order['username']) ?></p>
        <p><strong>Status:</strong> <?= ucfirst($order['status']) ?></p>
        <p><strong>Date:</strong> <?= date('d M Y H:i', strtotime($order['created_at'])) ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="45%">Product</th>
                <th width="15%">Qty</th>
                <th width="20%">Price (RM)</th>
                <th width="20%">Subtotal (RM)</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $it): ?>
            <tr>
                <td><?= htmlspecialchars($it['name']) ?></td>
                <td><?= $it['quantity'] ?></td>
                <td><?= number_format($it['price'],2) ?></td>
                <td><?= number_format($it['quantity'] * $it['price'],2) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align:right">Total:</td>
                <td><?= number_format($order['total'],2) ?></td>
            </tr>
        </tfoot>
    </table>

    <button class="print-btn" onclick="window.print()">Print Receipt</button>
</div>

</body>
</html>