<?php
session_start();
require 'db.php';

$tier_access = ['starter' => 1, 'buddy' => 2, 'pro' => 3, 'premium' => 4];

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$stmt = $pdo->prepare("SELECT tier FROM users WHERE username = ?");
$stmt->execute([$_SESSION['username']]);
$user = $stmt->fetch();
if (!$user) exit("User not found");

$user_tier = strtolower($user['tier']);
$user_level = $tier_access[$user_tier] ?? 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>E-Sports | MLBB Club</title>
    <link rel="icon" href="pictures/tab_icon.png" type="image/png">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/esports.css">
    <script src="scripts/esports.js" defer></script>
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
      <?= htmlspecialchars($_SESSION['username'])?>
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

<main>
<?php if (isset($_GET['registered']) && $_GET['registered'] == '1'): ?>
  <div class="alert-success">
    ✅ Registration successful! You have been registered for the tournament.
  </div>
<?php endif; ?>
    <section class="section live">
        <h2>🎮 Live Tournament Stream</h2>
        <?php if ($user_level >= 1): ?>
            <iframe width="1296" height="729" src="https://www.youtube.com/embed/I_upqjMXllo" title="🔴 LIVE | MPL PH S15 | ENGLISH - GRAND FINALS" frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" 
                allowfullscreen></iframe>
        <?php else: ?>
            <p class="locked">Available to logged-in users only.</p>
        <?php endif; ?>
    </section>

    <section class="section news">
        <h2>📢 Latest Tournament News</h2>
        <?php if ($user_level >= 2): ?>
            <div class="news-item">
                <h3>Champion Announced</h3>
                <p>ONIC Crowned MPL ID Season 15 Champions, Powering Indonesia's Bid to Make History Twice at Esports World Cup 2025!</p>
                <a href="news1.php" class="read-more">Read More →</a>
            </div>
            <div class="news-item">
                <h3>MAL MY Season 3 Playoffs at Quill City Mall</h3>
                <p>The top teams are ready to face the "Final Boss" as the Malaysian Academy League enters its final stage!</p>
                <a href="news2.php" class="read-more">Read More →</a>
            </div>
        <?php else: ?>
            <p class="locked">Upgrade to Buddy tier to view tournament news.</p>
        <?php endif; ?>
    </section>

    <section class="section exclusive">
        <h2>🔥 Pro Insights & Behind-the-Scenes</h2>
        <?php if ($user_level >= 3): ?>
            <iframe width="1296" height="729" src="https://www.youtube.com/embed/kIUvDMCF-r4" title="M6 Documentary Episode 1 | Reign of the Superfam |  Powered 
                by Qiddiya Gaming" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        <?php else: ?>
            <p class="locked">Pro and above required to access exclusive content.</p>
        <?php endif; ?>
    </section>

    <section class="section premium">
        <h2>👑 Premium Vault</h2>
        <?php if ($user_level >= 4): ?>
            <p>Welcome Premium Member! Get exclusive early access to game updates, hero reworks, patch previews, and major in-game events before anyone else.</p><br>
            <ul>
                <li><a href="https://m.mobilelegends.com/news/articleldetail?newsid=3062931">Early Patch Notes & Balance Changes</a></li>
                <li><a href="https://m.mobilelegends.com/news/articleldetail?newsid=3082735">Sneak Peek: Upcoming Skins & Events</a></li>
                <li><a href="http://mobile-legends.fandom.com/wiki/Obsidia">Dev Insights: Future Hero Roadmap</a></li>
            </ul>
        <?php else: ?>
            <p class="locked">Upgrade to Premium to unlock all features.</p>
        <?php endif; ?>
    </section>
    <section class="section registration">
  <h2>📝 Tournament Registration</h2>
  <?php if ($user_level >= 4): ?>
    <form method="post" action="register_tournament.php" class="tourney-form">
  <label>Tournament Name:
    <input type="text" name="tournament_name" required>
  </label>

  <label>Register as:
    <select name="role" id="role-select" required onchange="toggleFields()">
      <option value="">Select</option>
      <option value="player">Player</option>
      <option value="spectator">Spectator</option>
    </select>
  </label>

  <div id="player-fields" style="display:none">
    <label>In-Game ID:
      <input type="text" name="ingame_id">
    </label>
    <label>Preferred Role:
      <select name="preferred_role">
        <option value="">-- Select Role --</option>
        <option>Tank</option>
        <option>Mage</option>
        <option>Marksman</option>
        <option>Assassin</option>
        <option>Support</option>
        <option>Fighter</option>
      </select>
    </label>
    <label>Team Name:
      <input type="text" name="team_name">
    </label>
  </div>

  <div id="spectator-fields" style="display:none">
    <label>Spectator Type:
      <select name="spectator_type">
        <option value="">-- Select Type --</option>
        <option>Online</option>
        <option>On-Site</option>
      </select>
    </label>
  </div>

  <label>Additional Notes:
    <textarea name="notes" rows="3" placeholder="Any comments or requests?"></textarea>
  </label>

  <button type="submit">Register</button>
</form>
  <?php else: ?>
    <p class="locked">Only Premium members can register for tournaments.</p>
  <?php endif; ?>
</section>
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
<script>
function toggleFields () {
  const role  = document.getElementById('role-select').value;
  const pBox  = document.getElementById('player-fields');
  const sBox  = document.getElementById('spectator-fields');

  pBox.style.display = role === 'player'    ? 'block' : 'none';
  sBox.style.display = role === 'spectator' ? 'block' : 'none';

  pBox.querySelectorAll('input,select').forEach(el => el.required = (role==='player'));
  sBox.querySelectorAll('select').forEach(el       => el.required = (role==='spectator'));
}

setTimeout(() => {
    const alertBox = document.querySelector('.alert-success');
    if (alertBox) alertBox.style.display = 'none';
  }, 3000); // hide after 5 seconds
</script>
</body>
</html>