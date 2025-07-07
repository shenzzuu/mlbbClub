<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("User ID not specified.");
}

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}

$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);

    if ($username !== '' && $email !== '' && $role !== '') {
        $update = $pdo->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
        if ($update->execute([$username, $email, $role, $id])) {
            $success = true;
        } else {
            $success = false;
        }
    } else {
        $success = false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit User</title>
  <link rel="icon" href="pictures/tab_icon.png" type="image/png">
  <link href="https://fonts.googleapis.com/css2?family=Orbitron&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles/style.css">
  <style>
    body {
    background: linear-gradient(135deg, #1a1a1a 0%, #000000 100%);
    color: white;
    margin: 0;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    }
    .container {
    text-align: center;
    }
    form {
    max-width: 500px;
    background: #1a1f2e;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 12px #ff4655;
    }
    label {
        display: block;
        margin-top: 15px;
    }
    input, select {
        width: 100%;
        padding: 8px;
        margin-top: 5px;
        background: #2b2f40;
        color: white;
        border: 1px solid #ff4655;
        border-radius: 5px;
    }
    button {
        margin-top: 20px;
        background: #ff4655;
        color: white;
        border: none;
        padding: 10px 20px;
        font-size: 1rem;
        cursor: pointer;
        border-radius: 6px;
    }
    h2{
        color: #ff4655;
    }
  </style>
</head>
<header class="navbar">
  <div class="nav-left">
    <div class="logo"><img src="pictures/logo.png" alt="MLBB Logo"></div>
    <nav class="nav-links">
      <a href="admin.php">Home</a>
      <a href="admin_payments.php">Payment</a>
      <a href="admin_tournaments.php">Tournament</a>
      <a href="admin_staff.php">Staff</a>
      <a href="logout.php">Logout</a>
    </nav>
  </div>
  <div class="admin-text">Welcome, <?= htmlspecialchars($_SESSION['username']) ?></div>
</header>
<body>
<div class="container">
<h2>Edit User</h2><br>
<form method="POST">
    <label>Username:
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
    </label>
    <label>Email:
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
    </label>
    <label>Role:
        <select name="role" required>
            <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
        </select>
    </label>
    <button type="submit">Update</button>
</form>
</div>

<?php if ($success === true): ?>
<script>
    alert("User updated successfully!");
    window.location.href = "admin.php";
</script>
<?php elseif ($success === false): ?>
<script>
    alert("Failed to update user. Please check the form or try again.");
</script>
<?php endif; ?>

</body>
</html>