<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$stmt = $pdo->prepare("SELECT id, tier, tier_expiry FROM users WHERE username = ?");
$stmt->execute([$_SESSION['username']]);
$user = $stmt->fetch();
if (!$user) exit('User not found.');

$user_id     = $user['id'];
$currentTier = $user['tier'] ?? 'starter';
$tier_expiry = $user['tier_expiry'];

$popup = '';
if (isset($_GET['success']) && $_GET['success'] == '1') {
    $popup = 'Subscription successful!';
} elseif (isset($_GET['fail'])) {
    $popup = 'Subscription failed. Please try again.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Subscription</title>
  <link rel="icon" href="pictures/tab_icon.png" type="image/png">
  <link rel="stylesheet" href="styles/style.css" />
  <link rel="stylesheet" href="styles/subs.css" />
  <script>
    window.onload = () => {
      const popup = "<?= $popup ?>";
      if (popup) alert(popup);
    };
  </script>
</head>
<body>

<header class="navbar">
  <div class="nav-left">
    <div class="logo">
      <img src="pictures/logo.png" alt="MLBB Logo">
    </div>
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
    <?php if (isset($_SESSION['username']) && isset($_SESSION['role'])): ?>
    <div class="dropdown">
      <button class="login-btn dropdown-toggle"><?= htmlspecialchars($_SESSION['username']) ?></button>
      <div class="dropdown-menu">
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
      </div>
    </div>
    <?php else: ?>
    <a href="login.php"><button class="login-btn">Login</button></a>
    <?php endif; ?>
  </div>
</header>

<div class="current-tier">
  <h2>Your Current Plan</h2>
  <p><strong><?= strtoupper($currentTier) ?></strong>
  <?= ($tier_expiry && strtolower($currentTier) !== 'starter') 
        ? ' (active until ' . date("d M Y", strtotime($tier_expiry)) . ')' 
        : '' ?></p>
</div>

<div class="tier-intro">
  <p>At Mobile Legends Club, your membership tier defines your journey. Whether you're just getting started or already climbing the ranks, each plan unlocks more than just features — it opens the door to a community of elite players, exclusive content, and competitive opportunities.</p>

  <p>Upgrade anytime to gain access to tournaments, early content drops, members-only rewards, and more. Don’t miss out on what our higher tiers have to offer — every level brings new advantages to enhance your gameplay and club experience.</p>

  <p>Choose the plan that fits your ambitions and <strong>level up your legend today!</strong></p>
</div>

<main class="page-content">
<div class="plans">
<?php
$tiers = [
  ['name' => 'Starter', 'price' => 'RM9.99', 'desc' => 'Access to community', 'tier' => 'starter'],
  ['name' => 'Buddy', 'price' => 'RM19.99', 'desc' => 'Buddy chat + Monthly skin draw', 'tier' => 'buddy'],
  ['name' => 'Pro', 'price' => 'RM29.99', 'desc' => 'Pro events & rewards', 'tier' => 'pro'],
  ['name' => 'Premium', 'price' => 'RM49.99', 'desc' => 'All-access + Premium perks', 'tier' => 'premium']
];

foreach ($tiers as $t):
?>
  <div class="plan">
    <h3><?= $t['name'] ?></h3>
    <p><strong><?= $t['price'] ?></strong></p>
    <p><?= $t['desc'] ?></p>

    <?php if ($t['tier'] === 'starter'): ?>
      <button class="subscribe-btn" disabled style="background-color: #888; cursor: not-allowed;">Default Plan</button>
      <p style="font-size: 0.85rem; color: #ccc;">You're already on this free plan.</p>
    <?php else: ?>
      <form action="payment.php" method="POST">
        <input type="hidden" name="tier" value="<?= $t['tier'] ?>">
        <input type="hidden" name="amount" value="<?= str_replace('RM', '', $t['price']) ?>">
        <button type="submit" class="subscribe-btn">Subscribe</button>
      </form>
    <?php endif; ?>
  </div>
<?php endforeach; ?>
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