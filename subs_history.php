<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

/* ── Get user ID ───────────────────────────── */
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$_SESSION['username']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) exit('User not found.');
$user_id = $user['id'];

/* ── Auto-expire outdated subscriptions ────── */
$today = date('Y-m-d');

$pdo->prepare("
    UPDATE subscriptions
       SET status = 'expired'
     WHERE user_id = ? AND status = 'active' AND end_date IS NOT NULL AND end_date < ?
")->execute([$user_id, $today]);

$pdo->prepare("
    UPDATE users
       SET tier = NULL, tier_expiry = NULL
     WHERE id = ? AND tier_expiry IS NOT NULL AND tier_expiry < ?
")->execute([$user_id, $today]);

$subs = $pdo->prepare("SELECT * FROM subscriptions WHERE user_id = ? ORDER BY start_date DESC");
$subs->execute([$user_id]);
$subscriptions = $subs->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Subscription History</title>
  <link rel="icon" href="pictures/tab_icon.png" type="image/png">
  <link rel="stylesheet" href="styles/style.css">
  <link rel="stylesheet" href="styles/subs.css">
</head>
<body>

<header class="navbar">
  <div class="nav-left">
    <div class="logo"><img src="pictures/logo.png" alt="MLBB Logo"></div>
    <nav class="nav-links">
  <a href="index.php"         class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">Home</a>
  <a href="about.php"         class="<?= basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : '' ?>">About</a>
  <a href="members.php"       class="<?= basename($_SERVER['PHP_SELF']) == 'members.php' ? 'active' : '' ?>">Members</a>
  <a href="esports.php"       class="<?= basename($_SERVER['PHP_SELF']) == 'esports.php' ? 'active' : '' ?>">E-Sport</a>
  <a href="subscription.php"  class="<?= basename($_SERVER['PHP_SELF']) == 'subscription.php' ? 'active' : '' ?>">Subscription</a>
  <a href="subs_history.php"  class="<?= basename($_SERVER['PHP_SELF']) == 'subs_history.php' ? 'active' : '' ?>">My Subscriptions</a>
  <a href="merchandise.php"   class="<?= basename($_SERVER['PHP_SELF']) == 'merchandise.php' ? 'active' : '' ?>">Merchandises</a>
</nav>

  </div>
  <div class="nav-right">
    <input type="text" placeholder="Search..." />
    <?php if (isset($_SESSION['username'])): ?>
      <div class="dropdown">
        <button class="login-btn dropdown-toggle"><?= htmlspecialchars($_SESSION['username']) ?></button>
        <div class="dropdown-menu">
          <a href="profile.php">Profile</a><a href="logout.php">Logout</a>
        </div>
      </div>
    <?php else: ?>
      <a href="login.php"><button class="login-btn">Login</button></a>
    <?php endif; ?>
  </div>
</header>

<h1>📜 Subscription History</h1>

<div class="history-intro">
  <p>This page keeps a complete record of all your past and current subscription activities. Whether you upgraded for exclusive perks or just tested a plan, everything is documented here for your convenience.</p>
  <p>Need to cancel a subscription? You can do so while it’s still active — simply choose a reason and click cancel. We value your feedback and are continuously working to improve our service.</p>
</div>

<main class="page-content">
<div class="history">
<?php if (empty($subscriptions)): ?>
  <p style="text-align:center;">No subscriptions found.</p>
<?php else: ?>
  <table>
    <tr>
      <th>Tier</th><th>Start Date</th><th>End Date</th>
      <th>Status</th><th>Payment</th><th>Cancel Reason</th><th>Action</th>
    </tr>
<?php foreach ($subscriptions as $sub): ?>
  <tr>
    <td><?= ucfirst(htmlspecialchars($sub['tier'] ?? '-')) ?></td>
    <td><?= $sub['start_date'] ? date('d M Y', strtotime($sub['start_date'])) : '—' ?></td>
    <td><?= $sub['end_date'] ? date('d M Y', strtotime($sub['end_date'])) : '—' ?></td>
    <td class="<?= htmlspecialchars($sub['status']) ?>"><?= ucfirst($sub['status']) ?></td>
    <td><?= ucfirst($sub['payment_status'] ?? '-') ?></td>
    <td><?= $sub['status'] === 'cancelled' ? htmlspecialchars($sub['cancel_reason'] ?? '-') : '-' ?></td>
    <td>
      <?php if ($sub['status'] === 'active'): ?>
        <form action="cancel_subs.php" method="post" onsubmit="return confirm('Cancel this subscription?');">
          <input type="hidden" name="mid" value="<?= $sub['id'] ?>">
          <select name="reason" required>
              <option value="" disabled selected>Select reason</option>
              <option>Too expensive</option>
              <option>No longer needed</option>
              <option>Found better alternative</option>
              <option>Poor service</option>
              <option>Other</option>
          </select>
          <button style="margin-top:5px;">Cancel</button>
        </form>
      <?php else: ?>—<?php endif; ?>
    </td>
  </tr>
<?php endforeach; ?>
  </table>
<?php endif; ?>
</div>
</main>

<footer class="footer">
  <div class="footer-content">
    <div class="footer-section about">
      <h3>MLBB Club Malaysia</h3>
      <p>Your ultimate hub for Mobile Legends fans. From tournaments to exclusive content — join the legend today!</p>
    </div>

    <div class="footer-section links">
      <h4>Quick Links</h4>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="esports.php">E-Sport</a></li>
        <li><a href="subscription.php">Subscription</a></li>
        <li><a href="merchandise.php">Merchandise</a></li>
      </ul>
    </div>

    <div class="footer-section contact">
      <h4>Contact Us</h4>
      <p>Email: support@mlbbclub.my</p>
      <p>Instagram: @mlbbclubmy</p>
      <p>Facebook: MLBB Club Malaysia</p>
    </div>
  </div>

  <div class="footer-bottom">
  <p>
    &copy; <?= date('Y') ?> MLBB Club Malaysia. All Rights Reserved. |
    <a href="policy.php" style="color:#ccc;">Policy</a>
  </p>
</div>
</footer>

</body>
</html>