
<?php include('../db_connect.php'); session_start(); if(!isset($_SESSION['admin'])){ header('Location: login.php'); exit; }
$orders = mysqli_query($conn, "SELECT o.*, u.name as customer FROM orders o JOIN users u ON o.user_id=u.id ORDER BY o.order_date DESC");
?>
<!DOCTYPE html><html><head><meta charset="utf-8"><title>Orders</title><link rel="stylesheet" href="../assets/css/style.css"></head><body>
<?php include '../includes/header.php'; ?><div class="container"><h2>Orders</h2><table><tr><th>ID</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th></tr><?php while($o=mysqli_fetch_assoc($orders)){ echo "<tr><td>{$o['id']}</td><td>".htmlspecialchars($o['customer'])."</td><td>₹".number_format($o['total'],2)."</td><td>".htmlspecialchars($o['status'])."</td><td>{$o['order_date']}</td></tr>"; } ?></table></div></body></html>
