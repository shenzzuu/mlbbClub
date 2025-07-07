<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username']) || !isset($_POST['tier'])) {
    exit('Unauthorized access.');
}

$tier = $_POST['tier'];

$duration_days = match($tier) {
    'starter' => 30,
    'buddy'   => 30,
    'pro'     => 30,
    'premium' => 60,
    default   => 30,
};

$start_date = date('Y-m-d H:i:s');
$end_date = date('Y-m-d H:i:s', strtotime("+$duration_days days"));

$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$_SESSION['username']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    exit('User not found.');
}

$user_id = $user['id'];

$insert_sub = $pdo->prepare("
    INSERT INTO subscriptions (user_id, tier, start_date, end_date, status, payment_status)
    VALUES (?, ?, ?, ?, 'active', 'paid')
");
$insert_sub->execute([$user_id, $tier, $start_date, $end_date]);

$update_user = $pdo->prepare("
    UPDATE users SET tier = ?, tier_expiry = ? WHERE id = ?
");
$update_user->execute([$tier, $end_date, $user_id]);

header("Location: subscription.php?success=1");
exit;