<?php
session_start();
require 'db.php';
require_once __DIR__ . '/vendor/autoload.php';

if (!isset($_SESSION['username'])) {
    exit('User not logged in.');
}

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    exit('Cart is empty.');
}

$line_items = [];
$grandTotal = 0;

foreach ($cart as $item) {
    $line_items[] = [
        'price_data' => [
            'currency'     => 'myr',
            'product_data' => ['name' => $item['name']],
            'unit_amount'  => intval($item['price'] * 100), 
        ],
        'quantity' => $item['qty'],
    ];
    $grandTotal += $item['price'] * $item['qty'];
}

$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$_SESSION['username']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) exit('User not found.');
$user_id = $user['id'];

\Stripe\Stripe::setApiKey('sk_test_51RfhHWD6VwAlAIq9TMluaxyvIIZxpT5mwbodrV1GVwaoocArO6Ap1i1xEzrLpiuLStLmcz8DCxMQN6wY1ZgXblwO00gC5tPFbF');

try {
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items'           => $line_items,
        'mode'                 => 'payment',
        'success_url' => "https://mlbbclub.onrender.com/order_success.php?session_id={CHECKOUT_SESSION_ID}",
        'cancel_url'  => "https://mlbbclub.onrender.com/merchandise.php?cancel=1",
        'metadata'             => [
            'user_id' => $user_id,
            'purpose' => 'order'
        ],
    ]);
} catch (Exception $e) {
    exit('Stripe error: '.$e->getMessage());
}

$save = $pdo->prepare("
    INSERT INTO payments (user_id, stripe_sid, purpose, amount_myr, status)
    VALUES (:uid, :sid, 'order', :amt, 'pending')
");
$save->execute([
    'uid' => $user_id,
    'sid' => $session->id,
    'amt' => $grandTotal
]);

header("Location: ".$session->url);
exit;
?>
