<?php
session_start();
require 'db.php';                                 
require_once 'stripe-php-master/init.php';          

if (!isset($_SESSION['username']))        exit('User not logged in.');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') exit('Invalid request.');
if (empty($_POST['tier']))                exit('Tier missing.');

$tier        = strtolower($_POST['tier']);
$tier_prices = [
    'starter'  =>  9.99,
    'buddy'    => 19.99,
    'pro'      => 29.99,
    'premium'  => 49.99,
];
if (!isset($tier_prices[$tier]))          exit('Bad tier');
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$_SESSION['username']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) exit('User not found.');
$user_id   = $user['id'];
$amountMy  = $tier_prices[$tier];

\Stripe\Stripe::setApiKey('sk_test_51RfhHWD6VwAlAIq9TMluaxyvIIZxpT5mwbodrV1GVwaoocArO6Ap1i1xEzrLpiuLStLmcz8DCxMQN6wY1ZgXblwO00gC5tPFbF');

try {
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data'  => [
                'currency'     => 'myr',
                'product_data' => ['name' => ucfirst($tier).' Membership'],
                'unit_amount'  => intval($amountMy * 100),  
            ],
            'quantity' => 1,
        ]],
        'mode'         => 'payment',
        'success_url' => "https://mlbbclub.onrender.com/success.php?session_id={CHECKOUT_SESSION_ID}",
        'cancel_url'  => "https://mlbbclub.onrender.com/subscription.php?cancel=1",
        'metadata'     => [
            'user_id' => $user_id,
            'tier'    => $tier,
        ],
    ]);
} catch (Exception $e) {
    exit("Stripe error: ".$e->getMessage());
}

$save = $pdo->prepare("
    INSERT INTO payments (user_id, stripe_sid, tier, amount_myr, status)
    VALUES (:uid, :sid, :tier, :amt, 'pending')
");
$save->execute([
    'uid'  => $user_id,
    'sid'  => $session->id,
    'tier' => $tier,
    'amt'  => $amountMy,
]);

header("Location: ".$session->url);
exit;
