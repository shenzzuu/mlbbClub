<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>About - Mobile Legends Club</title>
    <link rel="icon" href="pictures/tab_icon.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/about.css">
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
        <button class="login-btn dropdown-toggle"><?= htmlspecialchars($_SESSION['username'])?></button>
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
  <section class="about-section intro">
    <h1>ABOUT MOBILE LEGENDS CLUB</h1>
    <p>Welcome to the Mobile Legends Club, the ultimate destination for Mobile Legends: Bang Bang enthusiasts! Our club is dedicated to bringing players together, fostering a competitive yet friendly environment, and providing exclusive content for our members.</p>
  </section>

  <section class="about-section story">
    <h2>OUR STORY</h2>
    <p>Born from a passion for competitive mobile gaming, Mobile Legends Club emerged in 2023 as a sanctuary for MOBA enthusiasts. What began as a small group of dedicated players has transformed into Southeast Asia's premier MLBB community, uniting over 50,000 members across the region. We've pioneered the ultimate MOBA experience by blending competitive rigor with the camaraderie that makes Mobile Legends: Bang Bang truly special.</p>

    <p>Our journey mirrors the epic battles in the Land of Dawn - starting as underdogs, we've leveled up through strategic plays and community synergy. Each season, we evolve like your favorite heroes, introducing innovative tournaments, training programs, and social features that keep our members at the forefront of the MLBB universe.</p>
  </section>

  <section class="about-section offers">
    <h2>WHAT WE OFFER</h2>
    <p>As a member of our club, you'll get access to:</p>
    <ul>
      <li>Exclusive tournaments with cash prizes</li>
      <li>Regular in-game giveaways</li>
      <li>Strategy guides from top players</li>
      <li>Early access to new hero reviews</li>
      <li>Member-only merchandise</li>
      <li>A vibrant community of fellow players</li>
    </ul>
  </section>

  <section class="about-section mission-vision">
    <div class="mission">
      <h2>OUR MISSION</h2>
      <p>To forge the most electrifying Mobile Legends community where every player - from Grandmaster to Mythic - can level up their skills, build legendary teams, and experience the full thrill of competitive MOBA gameplay.</p>
    </div>
    <div class="vision">
      <h2>OUR VISION</h2>
      <p>To revolutionize mobile esports fandom by creating an ecosystem where casual players transform into champions, and where the Mobile Legends experience extends far beyond the battlefield.</p>
    </div>
  </section>

  <section class="about-section join">
    <h2>JOIN US TODAY</h2>
    <p>Whether you're a casual player or aspiring pro, there's a place for you in our club. Click the "JOIN NOW" button to become part of our growing community!</p>
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

</body>
</html>