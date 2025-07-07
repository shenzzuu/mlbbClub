<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Logging Out...</title>
  <link rel="icon" href="pictures/tab_icon.png" type="image/png">
  <meta http-equiv="refresh" content="3;url=index.php">
  <link href="https://fonts.googleapis.com/css2?family=Orbitron&display=swap" rel="stylesheet">
  <style>
    body {
      background-color: #0f1923;
      color: white;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .message-box {
      text-align: center;
      background: #1a1f2e;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 12px #ff4655;
    }
    h1 {
      color: #ff4655;
      margin-bottom: 10px;
    }
    p {
      font-size: 1.1rem;
    }
    .redirect {
      margin-top: 15px;
      font-size: 0.95rem;
      color: #aaa;
    }
  </style>
</head>
<body>
  <div class="message-box">
    <h1>Logged Out</h1>
    <p>You have been safely logged out.</p>
    <div class="redirect">Redirecting to home in 3 seconds...</div>
  </div>
</body>
</html>