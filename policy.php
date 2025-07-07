<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Website Policy – MLBB Club</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/style.css">
    <style>
        h1,
        h2 {
            color: #ff4655;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding-top: 100px;
            flex: 1;
        }

        a {
            color: #ff4655;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
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

    <div class="container">
        <h1>Website Policy</h1>

        <h2>1. Privacy</h2>
        <p>We respect your privacy. Any personal data collected on this website, such as usernames or email addresses,
            is stored securely and used only for site-related functions. We do not share your information with third
            parties without your permission.</p>

        <h2>2. Account Security</h2>
        <p>Users are responsible for maintaining the confidentiality of their login credentials. If you suspect any
            unauthorized activity on your account, please report it to the administrator immediately.</p>

        <h2>3. Content Usage</h2>
        <p>All content, including images, articles, and media, is intended for the MLBB Club community. Reusing or
            copying any content without permission is not allowed. Respect intellectual property rights.</p>

        <h2>4. Behavior and Conduct</h2>
        <p>We expect all users to behave respectfully. Hate speech, spam, or abusive behavior will result in suspension
            or removal from the site.</p>

        <h2>5. Cookies</h2>
        <p>This website may use cookies to enhance user experience. By continuing to browse the site, you agree to our
            use of cookies.</p>

        <h2>6. Policy Changes</h2>
        <p>We may update this policy at any time. Users are encouraged to review this page regularly to stay informed of
            any changes.</p>

        <p>If you have any questions about this policy, contact us at <a
                href="mailto:support@mlbbclub.com">support@mlbbclub.com</a>.</p>
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