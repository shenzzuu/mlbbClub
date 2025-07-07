<?php
session_start();
require 'db.php';

$DEBUG = false; 

if (!isset($_SESSION['username'])) {
    header('Location: login.php'); exit();
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: subs_history.php?err=method'); exit();
}

$subId  = isset($_POST['mid']) ? (int)$_POST['mid']
         : (isset($_POST['id']) ? (int)$_POST['id'] : 0);
$reason = isset($_POST['reason']) ? trim($_POST['reason']) : '';

if ($subId <= 0 || $reason === '') {
    header('Location: subs_history.php?err=missing'); exit();
}

$q = $pdo->prepare("
  SELECT s.id, s.user_id
    FROM subscriptions s
    JOIN users u ON u.id = s.user_id
   WHERE s.id = ? AND s.status = 'active' AND u.username = ?
");
$q->execute([$subId, $_SESSION['username']]);
$sub = $q->fetch(PDO::FETCH_ASSOC);
if (!$sub) {
    header('Location: subs_history.php?err=unauth'); exit();
}

$user_id = $sub['user_id'];
$today   = date('Y-m-d');

$cols = $pdo->query("
    SELECT column_name
      FROM information_schema.columns
     WHERE table_name = 'subscriptions'
")->fetchAll(PDO::FETCH_COLUMN);
$hasTier = in_array('tier', $cols);
$hasStart= in_array('start_date', $cols);
$hasEnd  = in_array('end_date', $cols);
$hasPay  = in_array('payment_status', $cols);

try {
    $pdo->beginTransaction();

    $sth = $pdo->prepare("
        UPDATE subscriptions
           SET status        = 'cancelled',
               cancel_reason = ?,
               end_date      = ?
         WHERE id = ?
    ");
    $sth->execute([$reason, $today, $subId]);

    $pdo->prepare("
        UPDATE users SET tier='starter', tier_expiry=NULL WHERE id=?
    ")->execute([$user_id]);

    $chk = $pdo->prepare("
        SELECT 1 FROM subscriptions
         WHERE user_id=? AND tier='starter' AND status='active'
         LIMIT 1
    ");
    $chk->execute([$user_id]);

    if (!$chk->fetch()) {
        $fields = ['user_id','status'];
        $values = [$user_id,'active'];
        $qs     = '?,?';

        if ($hasTier)   { $fields[]='tier';           $values[]='starter';    $qs .= ',?'; }
        if ($hasStart)  { $fields[]='start_date';     $values[]=$today;       $qs .= ',?'; }
        if ($hasPay)    { $fields[]='payment_status'; $values[]='free';       $qs .= ',?'; }

        $sql = "INSERT INTO subscriptions (" . implode(',',$fields) . ")
                VALUES ($qs)";
        $pdo->prepare($sql)->execute($values);
    }

    $pdo->commit();
    header('Location: subs_history.php?msg=cancelled');
    exit();

} catch (Throwable $e) {
    $pdo->rollBack();
    if ($DEBUG) {
        echo "DB ERROR: " . $e->getMessage();
    } else {
        header('Location: subs_history.php?err=db');
    }
    exit();
}