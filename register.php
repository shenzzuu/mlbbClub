<?php
session_start();
require 'db.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username  = trim($_POST["username"]);
    $full_name = trim($_POST["full_name"]);
    $email     = trim($_POST["email"]);
    $password  = trim($_POST["password"]);     
    $agree     = isset($_POST["agree"]);

    if (!$agree) {
        $error = "You must agree to the terms and conditions.";
    } elseif (!$username || !$email || !$password) {
        $error = "All fields are required.";
    } else {
        try {
            $dupe = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $dupe->execute([$username, $email]);

            if ($dupe->fetch()) {
                $error = "Username or email already exists.";
            } else {
                $pdo->beginTransaction();

                $insUser = $pdo->prepare("
                    INSERT INTO users (username, full_name, email, password, role, tier, tier_expiry)
                    VALUES (?, ?, ?, ?, 'user', 'starter', NULL)
                ");
                $insUser->execute([$username, $full_name, $email, $password]); 
                $user_id = $pdo->lastInsertId();
                $today   = date('Y-m-d');

                $pdo->prepare("
                    INSERT INTO memberships (user_id, type, status, start_date, end_date)
                    VALUES (?, 'starter', 'active', ?, NULL)
                ")->execute([$user_id, $today]);

                $pdo->commit();
                $success = "Registration successful. You can now <a href='login.php'>login</a>.";

            }
        } catch (PDOException $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $error = "Database error: ".$e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
    <link rel="icon" href="pictures/tab_icon.png" type="image/png">
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&display=swap" rel="stylesheet">
  <style>
    body{background:#0f1923;font-family:'Orbitron',sans-serif;color:#fff;display:flex;justify-content:center;align-items:center;height:100vh;margin:0;}
    .register-box{background:#1a1f2e;padding:2rem;border-radius:10px;box-shadow:0 0 10px #ff4655;width:350px;text-align:center;}
    input[type=text],input[type=email],input[type=password]{display:block;width:100%;margin:1rem 0;padding:.5rem;font-size:1rem;}
    .checkbox{text-align:left;font-size:.85rem;margin:.5rem 0 1rem;}
    button{padding:.6rem 1.5rem;font-size:1rem;background:#ff4655;color:#fff;border:none;width:100%;}
    .message{margin:1rem 0;font-size:.9rem}.error{color:#ff4c4c}.success{color:#4caf50}
    .login-link{margin-top:1rem;font-size:.9rem}.login-link a{color:#ff4655;text-decoration:none}
  </style>
</head>
<body>
<div class="register-box">
  <h2>Create Account</h2>

  <?php if ($error): ?>
    <p class="message error"><?= htmlspecialchars($error) ?></p>
  <?php elseif ($success): ?>
    <p class="message success"><?= $success ?></p>
  <?php endif; ?>

  <form method="POST">
    <input type="text"     name="full_name" placeholder="Full Name" required>
    <input type="text"     name="username"  placeholder="Username" required>
    <input type="email"    name="email"     placeholder="Email" required>
    <input type="password" name="password"  placeholder="Password" required>

    <div class="checkbox">
      <label><input type="checkbox" name="agree" required> I agree to the <a href="policy.php">terms and conditions</a></label>
    </div>

    <button type="submit">Register</button>
  </form>

  <div class="login-link">
    <p>Already have an account? <a href="login.php">Login here</a></p>
  </div>
</div>
</body>
</html>
