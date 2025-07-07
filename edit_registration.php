<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');  exit();
}
if (!isset($_GET['id']))            exit('Missing ID');

$id  = (int)$_GET['id'];
$row = $pdo->prepare("SELECT * FROM tournament_registrations WHERE id = ?");
$row->execute([$id]);
$r   = $row->fetch(PDO::FETCH_ASSOC);
if (!$r) exit('Registration not found');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "UPDATE tournament_registrations
              SET tournament_name = :t,
                  role            = :role,
                  ingame_id       = :ing,
                  preferred_role  = :pref,
                  team_name       = :team,
                  spectator_type  = :spec,
                  notes           = :notes
            WHERE id = :id";
    $pdo->prepare($sql)->execute([
        't'    => $_POST['tournament_name'],
        'role' => $_POST['role'],
        'ing'  => $_POST['ingame_id']       ?: null,
        'pref' => $_POST['preferred_role']  ?: null,
        'team' => $_POST['team_name']       ?: null,
        'spec' => $_POST['spectator_type']  ?: null,
        'notes'=> $_POST['notes']           ?: null,
        'id'   => $id
    ]);
    header('Location: admin_tournaments.php');  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit Registration #<?= $id ?></title>
  <link rel="icon" href="pictures/tab_icon.png" type="image/png">
  <link rel="stylesheet" href="styles/style.css">

  <style>
    *{box-sizing:border-box;
    }
    body{
      min-height:100vh;
      margin:0;
      background: linear-gradient(135deg, #1a1a1a 0%, #000000 100%);
      display:flex;
      justify-content:center;
      align-items:center;
      color:#fff
    }
    form{
      width:100%;
      max-width:460px;
      background:rgb(0, 0, 0);
      padding:28px 32px;
      border-radius:10px;
      box-shadow:0 0 15px rgba(255,70,85,.45)
    }
    h3{
        margin:0 0 20px;
        text-align:center;
        color:#ff4655;
        font-size:1.1rem
    }
    label{
        display:block;
        margin-top:14px;
        font-size:.9rem
    }
    input,select,textarea{
        width:100%;
        margin-top:6px;
        padding:9px 10px;
        font-size:.9rem;
        background:#2b2f40;
        border:1px solid #444;
        color:#fff;
        border-radius:5px
    }
    select option{
        color:#ffffff
    }
    textarea{resize:vertical;min-height:70px}
    button{
      width:100%;margin-top:24px;padding:11px 0;background:#ff4655;
      border:none;border-radius:6px;color:#fff;font-size:.9rem;cursor:pointer
    }
    button:hover{background:#ff2c3d}

    .player-block,.spectator-block{display:none}
  </style>

  <script>
    function toggleFields () {
      const role = document.getElementById('role').value;
      document.querySelector('.player-block').style.display     = (role==='player')    ? 'block':'none';
      document.querySelector('.spectator-block').style.display  = (role==='spectator') ? 'block':'none';
    }
  </script>
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
<body onload="toggleFields()">

<form method="post">
  <h3>Edit Registration #<?= $id ?></h3>

  <label>Tournament name:
    <input name="tournament_name" value="<?= htmlspecialchars($r['tournament_name']) ?>" required>
  </label>

  <label>Register as:
    <select name="role" id="role" onchange="toggleFields()" required>
      <option value="player"    <?= $r['role']==='player'    ? 'selected':'' ?>>Player</option>
      <option value="spectator" <?= $r['role']==='spectator' ? 'selected':'' ?>>Spectator</option>
    </select>
  </label>

  <div class="player-block">
    <label>In‑Game ID:
      <input name="ingame_id" value="<?= htmlspecialchars($r['ingame_id'] ?? '') ?>">
    </label>

    <label>Preferred role:
      <select name="preferred_role">
        <?php
          $roles = ['Tank','Mage','Marksman','Assassin','Support','Fighter'];
          foreach ($roles as $role) {
              $sel = ($r['preferred_role']===$role) ? 'selected':'';
              echo "<option $sel>$role</option>";
          }
        ?>
      </select>
    </label>

    <label>Team name:
      <input name="team_name" value="<?= htmlspecialchars($r['team_name'] ?? '') ?>">
    </label>
  </div>

  <div class="spectator-block">
    <label>Spectator type:
      <select name="spectator_type">
        <option value="">-- Pick --</option>
        <option <?= $r['spectator_type']==='Online'  ? 'selected':'' ?>>Online</option>
        <option <?= $r['spectator_type']==='On-Site' ? 'selected':'' ?>>On‑Site</option>
      </select>
    </label>
  </div>

  <label>Notes:
    <textarea name="notes" rows="3"><?= htmlspecialchars($r['notes'] ?? '') ?></textarea>
  </label>

  <button type="submit">Save</button>
</form>

</body>
</html>