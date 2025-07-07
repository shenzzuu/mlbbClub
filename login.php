<?php
session_start();
require 'db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && $password === $user['password']) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        if ($user['role'] === 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <link rel="icon" href="pictures/tab_icon.png" type="image/png">
  <style>
    body {
      background-color: #0f1923;
      font-family: 'Orbitron', sans-serif;
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .login-box {
      background-color: #1a1f2e;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 0 10px #ff4655;
      text-align: center;
      width: 300px;
    }
    input {
      display: block;
      width: 100%;
      margin: 1rem 0;
      padding: 0.5rem;
      font-size: 1rem;
      box-sizing: border-box;
    }
    button {
      padding: 0.6rem 1.5rem;
      font-size: 1rem;
      background-color: #ff4655;
      color: white;
      border: none;
      cursor: pointer;
      width: 100%;
    }
    .error {
      color: #ff4c4c;
      margin-bottom: 1rem;
    }
    .register-link {
      margin-top: 1rem;
      font-size: 0.9rem;
    }
    .register-link a {
      color: #ff4655;
      text-decoration: none;
    }
    .register-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="login-box">
    <h2>Login</h2>
    <?php if ($error): ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST">
      <input type="text" name="username" placeholder="Username" required />
      <input type="password" name="password" placeholder="Password" required />
      <button type="submit">Login</button>
    </form>
    <div class="register-link">
      <p>No account yet? <a href="register.php">Register here</a></p>
    </div>
  </div>
</body>
</html>