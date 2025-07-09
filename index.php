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
    <title>Mobile Legends Club</title>
    <link rel="icon" href="pictures/tab_icon.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/style.css">
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

<section class="hero">
  <video autoplay muted loop playsinline class="hero-bg-video">
    <source src="videos/intro.mp4" type="video/mp4" />
    Your browser does not support the video tag.
  </video>
  <div class="hero-overlay">
    <p class="tagline">The ultimate MOBA experience</p>
    <h1>MOBILE LEGENDS CLUB</h1>
    <a href="login.php">
    <button class="join-btn">JOIN NOW</button>
    </a>
  </div>
</section>

<section class="latest">
  <h2>THE LATEST</h2>
  <div class="latest-grid">

    <a href="https://en.moonton.com/news/217.html" target="_blank" class="card-link">
      <div class="card">
        <img src="pictures/pic1.png" alt="Latest News 1">
        <h3>ALLSTAR 2025 Goes Live With Prehistoric Battlefield Update!</h3>
        <p>ALLSTAR 2025 is now live as Mobile Legends: Bang Bang (MLBB) debuts it most dramatic map 
          transformation yet! Roar to life in the new dinosaur-mechanical world, where the battlefield 
          bursts with ancient visuals and thunderous audio. 
        </p>
      </div>
    </a>

    <a href="https://en.moonton.com/news/211.html" target="_blank" class="card-link">
      <div class="card">
        <img src="pictures/pic2.png" alt="Latest News 2">
        <h3>Mobile Legends Women's Invitational Boosts Women's Esports Scene</h3>
        <p>The Mobile Legends: Bang Bang (MLBB) Women's Invitational (MWI) is set to return at the 
          2025 Esports World Cup (EWC) as the largest women's tournament at the world's biggest multi-title esports event!
        </p>
      </div>
    </a>

    <a href="https://en.moonton.com/news/209.html" target="_blank" class="card-link">
      <div class="card">
        <img src="pictures/pic3.jpg" alt="Featured Content">
        <h3>The Phoenix Empress Wu Zetian Arrives in MLBB</h3>
        <p>Mobile Legends: Bang Bang (MLBB) unveils its newest hero: Wu Zetian, the reborn phoenix empress whose legend 
          spans millennia. Launching on 18 June, Zetian brings a unique blend of high-impact spellcasting and utility to the Land of Dawn.
        </p>
      </div>
    </a>

  </div>
</section>

<section class="season-countdown">
    <div class="season-container">
        <div class="season-image">
            <img src="pictures/season1.png" alt="Season Image">
        </div>
        <div class="season-content">
            <h2>SEASON ENDS IN</h2>
            <div class="countdown-timer">
                <div><span id="days">90</span><small>Days</small></div>
                <div><span id="hours">00</span><small>Hours</small></div>
                <div><span id="minutes">00</span><small>Minutes</small></div>
                <div><span id="seconds">00</span><small>Seconds</small></div>
            </div>
        </div>
    </div>
</section>

<section class="tournaments">
  <div class="tournaments-container">

    <div class="tournaments-text">
      <h1>MLBB GAMEPLAY</h1>
      <h3>COMPETE AND CONQUER</h3>
      <p>
        Mobile Legends: Bang Bang is a multiplayer online battle arena (MOBA) game designed for mobile phones. The game is
        free-to-play and is only monetized through in-game purchases like characters and skins. Each player can control a
        selectable character, called a Hero, with unique abilities and traits.
      </p>
      <a href="https://play.google.com/store/apps/details?id=com.mobile.legends&referrer=adjust_reftag%3DcWLj61nZ0jSZl%26utm_source%3DReLandingButton"
        target="_blank">
        <button class="tournaments-button">Play Now</button>
      </a>      
    </div>

    <div class="tournaments-video">
      <video autoplay muted loop playsinline>
        <source src="videos/gameplay.mp4" type="video/mp4">
        Your browser does not support the video tag.
      </video>
    </div>

  </div>
</section>

<section class="heroes">
  <h2>TOP PICK HEROES</h2>
  <div class="heroes-grid">

    <a href="https://mobile-legends.fandom.com/wiki/Ling" class="hero-link">
      <div class="hero-card">
        <img src="pictures/ling.jpg" alt="Hero 1">
        <p>Ling</p>
      </div>
    </a>

    <a href="https://mobile-legends.fandom.com/wiki/Fanny" class="hero-link">
      <div class="hero-card">
        <img src="pictures/fanny.jpg" alt="Hero 2">
        <p>Fanny</p>
      </div>
    </a>

    <a href="https://mobile-legends.fandom.com/wiki/Lukas" class="hero-link">
      <div class="hero-card">
        <img src="pictures/lukas.jpg" alt="Hero 3">
        <p>Lukas</p>
      </div>
    </a>

  </div>
</section>


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
  const countdown = () => {
    const endDate = new Date("2025-09-01T00:00:00");
    const now = new Date();
    const diff = endDate - now;

    const d = Math.floor(diff / (1000 * 60 * 60 * 24));
    const h = Math.floor((diff / (1000 * 60 * 60)) % 24);
    const m = Math.floor((diff / (1000 * 60)) % 60);
    const s = Math.floor((diff / 1000) % 60);

    document.getElementById("days").textContent = d;
    document.getElementById("hours").textContent = h.toString().padStart(2, "0");
    document.getElementById("minutes").textContent = m.toString().padStart(2, "0");
    document.getElementById("seconds").textContent = s.toString().padStart(2, "0");
  };

  setInterval(countdown, 1000);
</script>

</body>
</html>
