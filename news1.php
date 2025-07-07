<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ONIC Crowned MPL ID S15 Champions | MLBB Club</title>
  <link rel="stylesheet" href="styles/style.css">
  <style>
   .hero {
  width: 50%;                 
  max-width: 1000px;      
  margin: 40px auto 0;
  aspect-ratio: 16 / 9;
  border-radius: 10px;     
  box-shadow: 0 0 15px rgba(0,0,0,0.5); 
}

    .article-container {
      max-width: 900px;
      margin: 3rem auto;
      padding: 0 1rem;
    }

    .article-title {
      font-size: 2.5rem;
      font-weight: bold;
      color: #ffcc00;
      margin-bottom: 1rem;
    }

    .article-meta {
      color: #aaa;
      font-size: 0.9rem;
      margin-bottom: 2rem;
    }

    .article-content p {
      line-height: 1.8;
      font-size: 1.1rem;
      margin-bottom: 1.5rem;
    }

    .back-btn {
      display: inline-block;
      margin-top: 2rem;
      padding: 0.6rem 1.2rem;
      background-color: #ff4655;
      color: white;
      text-decoration: none;
      border-radius: 5px;
    }

    .back-btn:hover {
      background-color: #ff2a3d;
    }
  </style>
</head>
<body>

<header class="navbar">
  <div class="nav-left">
    <div class="logo">
      <img src="pictures/logo.png" alt="MLBB Logo">
    </div>
    <nav class="nav-links">
      <a href="index.php">Home</a>
      <a href="about.php">About</a>
      <a href="members.php">Members</a>
      <a href="esports.php">E-Sport</a>
      <a href="subscription.php">Subscription</a>
      <a href="merchandise.php">Merchandises</a>
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

<div class="hero"></div>

<div class="article-container">
  <div class="article-title">ONIC Crowned MPL ID Season 15 Champions</div>
  <div class="article-meta">Posted on July 5, 2025 | MLBB E-Sports</div>

  <div class="article-content">
    <p>ONIC Esports has once again proven their dominance by clinching the title of MPL ID Season 15 Champions! Their incredible 3-2 victory in the Grand Finals showcased their resilience, strategy, and the heart of champions.</p>

    <p>This win not only secures their place as legends in the Indonesian MLBB scene but also fuels the nation’s hopes for a historic back-to-back win at the upcoming Esports World Cup 2025.</p>

    <p>The tournament was filled with unexpected turns, brilliant drafts, and crowd-roaring comebacks—solidifying MPL ID’s position as one of the most exciting esports leagues globally.</p>

    <p>As ONIC sets their sights on the international stage, fans are eagerly awaiting what the team will bring to the Esports World Cup. Will they make history twice?</p>
  </div>

  <a href="esports.php" class="back-btn">← Back to E-Sports</a>
</div>

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
      <h4>Contact Us</h4>
      <p>Email: support@mlbbclub.my</p>
      <p>Instagram: <a href="#">@mlbbclubmy</a></p>
      <p>Facebook: <a href="#">MLBB Club Malaysia</a></p>
    </div>
  </div>

  <div class="footer-bottom">
    <p>&copy; <?= date('Y') ?> MLBB Club Malaysia. All Rights Reserved.</p>
  </div>
</footer>

</body>
</html>