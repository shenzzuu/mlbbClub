<?php
session_start();
require 'db.php';

$tier = 'starter';

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    $stmt = $pdo->prepare("SELECT tier FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && isset($user['tier'])) {
        $tier = strtolower($user['tier']);
    }
}

$members = [
  [
      'name' => 'Hafiz',
      'role' => 'Assassin',
      'img' => 'pictures/member1.jpg',
      'email' => '2023149239@student.uitm.edu.my',
      'bio' => 'Specializes in ambushing squishy enemies, eliminating targets quickly before vanishing into the shadows.',
      'social' => [
          'tiktok' => 'https://www.tiktok.com/@shenn_zzz?_t=ZS-8xnwHLlPspD&_r=1',
          'instagram' => 'https://www.instagram.com/fffw0wfff?igsh=MWI3MDN0cWt6bnFlYg==',
      ]
  ],
  [
      'name' => 'Zhafran',
      'role' => 'Marksman',
      'img' => 'pictures/member2.jpg',
      'email' => 'znafan4336@gmail.com',
      'bio' => 'Delivers consistent high damage from range, focusing on positioning and late-game domination.',
      'social' => [
          'tiktok' => 'https://www.tiktok.com/@mzhfran_?_t=ZS-8xnwO1ZALKa&_r=1',
          'instagram' => 'https://www.instagram.com/mzhafffff_?igsh=MWptbWFuZWF2bnVqYw==',
      ]
  ],
  [
      'name' => 'Hidayah',
      'role' => 'Support',
      'img' => 'pictures/member3.jpeg',
      'email' => '2023367787@student.uitm.edu.my',
      'bio' => 'Provides healing, shields, and crowd control to protect teammates and control team fights.',
      'social' => [
          'tiktok' => 'https://www.tiktok.com/@isssmeeeaaa?_t=ZS-8xnxtteiFcN&_r=1',
          'instagram' => 'https://www.instagram.com/hidayahrosdi_?igsh=MWF4dzdmM2o4cncwYg%3D%3D&utm_source=qr',
      ]
  ],
  [
      'name' => 'Najwan',
      'role' => 'Tank',
      'img' => 'pictures/member4.jpeg',
      'email' => 'irfannajwan22@gmail.com',
      'bio' => 'Initiates battles and soaks up damage, creating space for teammates and disrupting enemy formations.',
      'social' => [
          'tiktok' => 'http://najwan22.com/',
          'instagram' => 'http://tokitoki.com/',
      ]
  ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MLBB Members</title>
    <link rel="icon" href="pictures/tab_icon.png" type="image/png">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/members.css">
</head>
<body>

<header class="navbar">
  <div class="nav-left">
    <div class="logo">
      <img src="pictures/logo.png" alt="MLBB Logo">
    </div>
    <nav class="nav-links">
      <a href="index.php"        class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">Home</a>
      <a href="about.php"        class="<?= basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : '' ?>">About</a>
      <a href="members.php"      class="<?= basename($_SERVER['PHP_SELF']) == 'members.php' ? 'active' : '' ?>">Members</a>
      <a href="esports.php"      class="<?= basename($_SERVER['PHP_SELF']) == 'esports.php' ? 'active' : '' ?>">E-Sport</a>
      <a href="subscription.php" class="<?= basename($_SERVER['PHP_SELF']) == 'subscription.php' ? 'active' : '' ?>">Subscription</a>
      <a href="merchandise.php"  class="<?= basename($_SERVER['PHP_SELF']) == 'merchandise.php' ? 'active' : '' ?>">Merchandises</a>
    </nav>
  </div>
  <div class="nav-right">
    <input type="text" placeholder="Search..." />
    <?php if (isset($_SESSION['username']) && isset($_SESSION['role'])): ?>
      <div class="dropdown">
        <button class="login-btn dropdown-toggle">
          <?= htmlspecialchars($_SESSION['username']) ?>
        </button>
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

<?php if (isset($_SESSION['username'])): ?>
  <div class="user-tier">
    <p>👤 Your Tier: <strong><?= ucfirst($tier) ?></strong></p>
  </div>
<?php endif; ?>


<div class="members-intro">
  <h2>Our Elite MLBB Squad</h2>
  <p>Welcome to the heart of our club — the warriors who represent our spirit, grit, and strategy. Each member plays a unique role on the battlefield, from fearless initiators to precision marksmen.</p>
  <p>This page gives you a glimpse into the personalities behind the skills. Depending on your current tier, you'll unlock deeper insights, personal bios, and direct contact information.</p>
  <p>Upgrade your tier to access the full spectrum of their stories, strategies, and support. Great teams are built with knowledge — and now it’s your move.</p>
</div>

<main class="page-content">
<div class="members">
  <?php foreach ($members as $i => $member): ?>
    <div class="card" onclick="toggleDetails(<?= $i ?>)">
        <img src="<?= $member['img'] ?>" alt="<?= $member['name'] ?>">
        <h3><?= $member['name'] ?></h3>
        <p class="role"><?= $member['role'] ?></p>
        <div class="details" id="details-<?= $i ?>">
            <?php if (in_array($tier, ['pro', 'premium'])): ?>
                <p class="bio"><?= $member['bio'] ?></p>
            <?php elseif ($tier === 'buddy'): ?>
                <p class="bio"><?= substr($member['bio'], 0, 25) ?>... <em>(upgrade for full)</em></p>
            <?php endif; ?>
            <?php if ($tier === 'premium'): ?>
                <p class="email"><?= $member['email'] ?></p>
            <?php endif; ?>
        </div>
        <div class="social">
            <a href="<?= $member['social']['tiktok'] ?>"><img src="pictures/tt.png" alt="TikTok"></a>
            <a href="<?= $member['social']['instagram'] ?>"><img src="pictures/icon2.png" alt="Instagram"></a>
        </div>
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

<script src="scripts/members.js"></script>


</body>
</html>