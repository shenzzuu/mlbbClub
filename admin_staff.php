<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin · Staff</title>
  <link rel="icon" href="pictures/tab_icon.png" type="image/png">
  <link rel="stylesheet" href="styles/style.css">
  <link rel="stylesheet" href="styles/admin_staff.css">
</head>
<body class="dashboard">

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

<h2 class="center-title">Our Club Staff</h2>

<div class="staff-grid" id="staffContainer">
  <div class="staff-card">
    <img src="pictures/staff4.jpg" alt="Hafiz">
    <h4>Hafiz</h4><p>Club Manager</p><p>011-61538600</p><p>2023149239@student.uitm.edu.my</p><p>2023149239</p>
    <div class="staff-actions">
      <button class="edit-btn" onclick="editStaff(this)">Edit</button>
      <button onclick="deleteStaff(this)">Delete</button>
    </div>
  </div>
  <div class="staff-card">
    <img src="pictures/staff1.jpg" alt="Zhafran">
    <h4>Zhafran</h4><p>Tournament Lead</p><p>011-29914823</p><p>znafan4336@gmail.com</p><p>2023393541</p>
    <div class="staff-actions">
      <button class="edit-btn" onclick="editStaff(this)">Edit</button>
      <button onclick="deleteStaff(this)">Delete</button>
    </div>
  </div>
  <div class="staff-card">
    <img src="pictures/staff2.png" alt="Zee">
    <h4>Hidayah</h4><p>Community Manager</p><p>011-73145636</p><p>2023367787@student.uitm.edu.my</p><p>2023367787</p>
    <div class="staff-actions">
      <button class="edit-btn" onclick="editStaff(this)">Edit</button>
      <button onclick="deleteStaff(this)">Delete</button>
    </div>
  </div>
  <div class="staff-card">
    <img src="pictures/staff3.jpg" alt="Irfan">
    <h4>Irfan</h4><p>Marketing Lead</p><p>011-55637662</P><p>irfannajwan22@gmail.com</p><p>2024936635</p>
    <div class="staff-actions">
      <button class="edit-btn" onclick="editStaff(this)">Edit</button>
      <button onclick="deleteStaff(this)">Delete</button>
    </div>
  </div>
</div>

<div class="center-actions">
  <button onclick="showAddModal()" class="join-btn">➕ Add New Staff</button>
</div>

<!-- EDIT MODAL -->
<div class="modal" id="editModal">
  <div class="modal-content">
    <h3>Edit Staff</h3>
    <input type="text" id="editName" placeholder="Name">
    <input type="text" id="editRole" placeholder="Role">
    <input type="email" id="editEmail" placeholder="Email">
    <button onclick="saveEdit()">Save</button>
  </div>
</div>

<!-- ADD MODAL -->
<div class="modal" id="addModal">
  <div class="modal-content">
    <h3>Add Staff</h3>
    <input type="text" id="newName"  placeholder="Name"  required>
    <input type="text" id="newRole"  placeholder="Role"  required>
    <input type="email" id="newEmail" placeholder="Email" required>
    <input type="text" id="newImg"   placeholder="Image URL (optional)">
    <button onclick="addStaff()">Add</button>
  </div>
</div>

<script src="scripts/admin_staff.js"></script>
</body>
</html>