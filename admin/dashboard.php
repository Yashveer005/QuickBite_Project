
<?php include('../db_connect.php'); session_start(); if(!isset($_SESSION['admin'])){ header('Location: login.php'); exit; } ?>
<!DOCTYPE html><html><head><meta charset="utf-8"><title>Admin</title><link rel="stylesheet" href="../assets/css/style.css"></head><body>
<?php include '../includes/header.php'; ?>
<div class="container"><h2>Admin Dashboard</h2><ul><li><a href="add_item.php">Add Item</a></li><li><a href="view_orders.php">View Orders</a></li><li><a href="logout.php">Logout</a></li></ul></div>
</body></html>
