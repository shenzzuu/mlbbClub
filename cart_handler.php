<?php
session_start();
require 'db.php';

if (!isset($_POST['action'], $_POST['id'])) {
    http_response_code(400);
    exit('Invalid request');
}

$action = $_POST['action'];
$id     = (int)$_POST['id'];
$qty    = isset($_POST['qty']) ? max(1, (int)$_POST['qty']) : 1;

$stmt = $pdo->prepare(
    "SELECT id, name, price, img_url, stock
     FROM   products
     WHERE  id = ?"
);
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    http_response_code(404);
    exit('Product not found');
}

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

switch ($action) {
    case 'add':
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['qty'] += $qty;
        } else {
            $_SESSION['cart'][$id] = [
                'id'    => $product['id'],
                'name'  => $product['name'],
                'price' => $product['price'],
                'img'   => $product['img_url'] ?: 'pictures/default.jpg',
                'qty'   => $qty
            ];
        }
        break;

    case 'update':
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['qty'] = $qty;
        }
        break;

    case 'remove':
        unset($_SESSION['cart'][$id]);
        break;

    default:
        http_response_code(400);
        exit('Unknown action');
}

$totalQty = array_sum(array_column($_SESSION['cart'], 'qty'));

if (isset($_POST['ajax'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'status'    => 'ok',
        'cartCount' => $totalQty
    ]);
    exit;
}

header('Location: cart.php');
exit;