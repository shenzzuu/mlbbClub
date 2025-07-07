<?php
    session_start();
    require 'db.php';

    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }

    $username = $_SESSION['username'];
    $error = '';
    $success = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $new_name = trim($_POST['full_name']);
        $new_email = trim($_POST['email']);

        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

            $ext = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
            $new_filename = $username . '_profile.' . $ext;
            $target_path = $upload_dir . $new_filename;

            if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_path)) {
                $stmt = $pdo->prepare("UPDATE users SET profile_pic = ? WHERE username = ?");
                $stmt->execute([$target_path, $username]);
            }
        }

        $new_username = trim($_POST['username']);
        $new_name = trim($_POST['full_name']);
        $new_email = trim($_POST['email']);
        
        $stmt = $pdo->prepare("UPDATE users SET username = ?, full_name = ?, email = ? WHERE username = ?");
        if ($stmt->execute([$new_username, $new_name, $new_email, $username])) {
            $_SESSION['username'] = $new_username;
            $username = $new_username;
            $success = "Profile updated.";
        } else {
            $error = "Failed to update profile.";
        }    
    }

    $stmt = $pdo->prepare("SELECT username, full_name, email, tier, profile_pic, created_at FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("User not found.");
    }

    $profilePic = $user['profile_pic'] ?: 'pictures/default_profile.png';
    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Profile - MLBB</title>
        <link href="https://fonts.googleapis.com/css2?family=Orbitron&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="styles/style.css">
        <link rel="stylesheet" href="styles/profile.css">
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
    </div>
    </header>

    <div class="container">
        <h2>ABOUT</h2>
        <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <?php if ($success): ?><div class="success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
    <div class="profile-section">
        <div class="profile-box">
            <img src="<?= htmlspecialchars($profilePic) ?>" alt="Profile Picture">
        </div>

        <div class="profile-info">
            <div class="info-list">
                <p><strong>USERNAME:</strong> <?= htmlspecialchars($user['username']) ?></p>
                <p><strong>NAME:</strong> <?= htmlspecialchars($user['full_name']) ?></p>
                <p><strong>EMAIL:</strong> <?= htmlspecialchars($user['email']) ?></p>
                <p><strong>MEMBER SINCE:</strong> <?= date("d-m-Y", strtotime($user['created_at'])) ?></p>
                <button type="button" onclick="openModal()">Edit Profile</button>
            </div>
        </div>

        <div class="tier-box">
            <div><strong>★</strong></div>
            <button>TIER</button>
            <h3><?= strtoupper(htmlspecialchars($user['tier'])) ?></h3>
            <button class="upgrade-btn">UPGRADE →</button>
        </div>
    </div>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Edit Profile</h3><br>
            <form method="POST" enctype="multipart/form-data">
                <label>Username:</label>
                <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

                <label>Full Name:</label>
                <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required>

                <label>Email:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

                <label>Upload New Profile Picture:</label>
                <input type="file" name="profile_pic" accept="image/*">

                <button type="submit">Save Changes</button>
            </form>
        </div>
    </div>

        <div class="description-box">
            <p>
                Our MLBB subscription leads to convenience and more benefits giving you faster access to exclusive skins,
                weekly diamonds, and priority customer support. Enjoy seamless top-ups, auto-renewal options, and in-game rewards 
                that keep you ahead of the competition. Whether you’re a casual player or a ranked warrior, our subscription ensures 
                you get the most value every time you log in. Don’t just play — play smarter, play rewarded.
            </p>
        </div>
    </div>

    <script src="scripts/profile.js"></script>
    </body>
    </html>
