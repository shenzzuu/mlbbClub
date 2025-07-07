<?php
session_start();
require 'db.php';                         // PDO connection

/* -- 0. Guard checks -------------------------------------------------- */
if (!isset($_SESSION['username'])) {
    die('Login required');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request');
}

/* -- 1. Fetch user id ------------------------------------------------- */
$stmt = $pdo->prepare("SELECT id, tier FROM users WHERE username = ?");
$stmt->execute([$_SESSION['username']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || strtolower($user['tier']) !== 'premium') {
    die('Premium membership required');
}
$user_id = $user['id'];

/* -- 2. Collect & normalise POST data -------------------------------- */
$role            = $_POST['role']            ?? '';
$tournament_name = trim($_POST['tournament_name'] ?? '');

if (!in_array($role, ['player', 'spectator'], true) || $tournament_name === '') {
    die('Missing or bad parameters');
}

/* helper to turn empty strings into NULL */
$nullIfEmpty = fn($v) => ($v === '' ? null : $v);

$data = [
    'user_id'        => $user_id,
    't_name'         => $tournament_name,
    'role'           => $role,
    'ingame_id'      => $nullIfEmpty($_POST['ingame_id']       ?? null),
    'preferred_role' => $nullIfEmpty($_POST['preferred_role']  ?? null),
    'team_name'      => $nullIfEmpty($_POST['team_name']       ?? null),
    'spectator_type' => $nullIfEmpty($_POST['spectator_type']  ?? null),
    'notes'          => $nullIfEmpty($_POST['notes']           ?? null),
];

/* -- 3. Store in DB --------------------------------------------------- */
$sql = "
INSERT INTO tournament_registrations
(user_id, tournament_name, role, ingame_id, preferred_role,
 team_name, spectator_type, notes)
VALUES
(:user_id, :t_name, :role, :ingame_id, :preferred_role,
 :team_name, :spectator_type, :notes)
";

$ins = $pdo->prepare($sql);
$ins->execute($data);

/* -- 4. Redirect / feedback ------------------------------------------ */
header('Location: esports.php?registered=1');
exit;