
<?php include('db_connect.php'); if(!isset($_SESSION['user_id'])){ /* allow browsing without login */ } 
$res = mysqli_query($conn, "SELECT m.*, r.name as rest_name FROM menu_items m JOIN restaurants r ON m.rest_id=r.id");
?>
<!DOCTYPE html><html><head><meta charset="utf-8"><title>Menu</title><link rel="stylesheet" href="assets/css/style.css"></head><body>
<?php include 'includes/header.php'; ?>
<div class="container">
  <h2>Menu</h2>
  <div class="cards">
    <?php while($row=mysqli_fetch_assoc($res)){ ?>
      <div class="card">
        <img src="assets/images/<?php echo htmlspecialchars($row['image']); ?>" alt="" style="width:200px;height:140px;object-fit:cover">
        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
        <p><?php echo htmlspecialchars($row['rest_name']); ?></p>
        <p>₹<?php echo number_format($row['price'],2); ?></p>
        <form method="post" action="cart.php">
          <input type="hidden" name="item_id" value="<?php echo $row['id']; ?>">
          <input type="number" name="qty" value="1" min="1" style="width:60px">
          <button name="add_to_cart" class="btn">Add to Cart</button>
        </form>
      </div>
    <?php } ?>
  </div>
</div>
</body></html>
