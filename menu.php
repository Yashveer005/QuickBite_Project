<?php
// menu.php
include('db_connect.php');
session_start();

// build filter safely
$filter = "";
if(!empty($_GET['search'])){
  $s = mysqli_real_escape_string($conn, $_GET['search']);
  $filter = "WHERE name LIKE '%$s%' OR description LIKE '%$s%'";
} elseif(!empty($_GET['category'])){
  $c = mysqli_real_escape_string($conn, $_GET['category']);
  $filter = "WHERE name LIKE '%$c%' OR description LIKE '%$c%'";
}
$sql = "SELECT * FROM menu_items $filter ORDER BY id DESC";
$res = mysqli_query($conn, $sql);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>QuickBite — Menu</title>
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
:root{--orange:#ff6600;--orange2:#ff944d;--bg:#fff;--muted:#777;--shadow:0 10px 30px rgba(15,15,15,0.06);--radius:12px;--maxw:1100px}
*{box-sizing:border-box;margin:0;padding:0;font-family:Poppins,system-ui}
body{background:var(--bg);color:#222}
.container{max-width:var(--maxw);margin:0 auto;padding:18px}
.header{display:flex;justify-content:space-between;align-items:center;padding:10px 0}
.brand{font-weight:800;font-size:20px}
.brand span:first-child{color:#007bff} .brand span:last-child{color:var(--orange)}
.hero{background:linear-gradient(90deg,var(--orange2),var(--orange));color:#fff;padding:28px;border-radius:12px;box-shadow:var(--shadow)}
.hero h1{font-size:24px;margin-bottom:6px}
.search-top{margin-top:12px;display:flex;max-width:680px;margin-left:auto;margin-right:auto;border-radius:999px;overflow:hidden;box-shadow:var(--shadow)}
.search-top input{flex:1;padding:10px;border:0}
.search-top button{background:#fff;padding:10px 14px;border:0;cursor:pointer;font-weight:700}
.catbar{display:flex;gap:10px;flex-wrap:wrap;justify-content:center;margin:18px 0}
.catbar a{background:#fff;border:2px solid var(--orange);color:var(--orange);padding:8px 14px;border-radius:24px;font-weight:700;text-decoration:none}
.catbar a.active{background:var(--orange);color:#fff}
.grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:18px}
.card{background:#fff;border-radius:12px;box-shadow:var(--shadow);overflow:hidden;transition:transform .18s}
.card:hover{transform:translateY(-6px)}
.card img{width:100%;height:150px;object-fit:cover}
.card .body{padding:12px}
.card h3{color:var(--orange);margin-bottom:6px}
.meta{display:flex;justify-content:space-between;align-items:center}
.add{background:var(--orange2);color:#fff;padding:8px 12px;border-radius:8px;border:0;cursor:pointer;font-weight:700}
.footer{margin-top:28px;padding:16px;text-align:center;background:linear-gradient(90deg,var(--orange),var(--orange2));color:#fff;border-radius:10px}
</style>
</head>
<body>
  <div class="container">
    <div class="header">
      <div class="brand"><span>Quick</span><span>Bite</span></div>
      <div><a href="index.php">Home</a> &nbsp; <a href="cart.php">Cart</a></div>
    </div>

    <section class="hero" data-aos="fade-up">
      <<h1>Explore Our Menu</h1>
<p style="opacity:.95;font-weight:500">Search, filter and add items to your cart.</p>
<p style="font-size:13px;color:#fff8f2;">🍕 Over 100+ dishes delivered daily — get your favourites hot & fresh!</p>

      <div class="search-top">
        <form action="menu.php" method="GET" style="display:flex;width:100%">
          <input name="search" placeholder="Search pizza, burger, paneer...">
          <button type="submit">Search</button>
        </form>
      </div>
    </section>

    <div class="catbar" data-aos="fade-up">
      <?php
        $cats = ['All'=>'','Pizza'=>'Pizza','Burger'=>'Burger','Paneer'=>'Paneer','Dal'=>'Dal','Dessert'=>'Dessert'];
        foreach($cats as $label=>$val){
          $active = ((!isset($_GET['category']) && empty($_GET['search']) && $val=='') || (isset($_GET['category']) && $_GET['category']==$val)) ? 'active' : '';
          $href = $val=='' ? 'menu.php' : 'menu.php?category='.urlencode($val);
          echo "<a class='$active' href='$href'>$label</a>";
        }
      ?>
    </div>

    <div class="grid" data-aos="fade-up">
      <?php
      if($res && mysqli_num_rows($res)>0){
        while($r = mysqli_fetch_assoc($res)){
          $img = !empty($r['image']) ? $r['image'] : 'default.jpg';
          $name = htmlspecialchars($r['name'],ENT_QUOTES);
          $desc = htmlspecialchars($r['description'],ENT_QUOTES);
          echo "<div class='card'>
                  <img src='assets/images/{$img}' alt='{$name}'>
                  <div class='body'>
                    <h3>{$name}</h3>
                    <p style='color:var(--muted);font-size:13px'>{$desc}</p>
                    <div class='meta'>
                      <div style='font-weight:800'>₹ {$r['price']}</div>
                      <div><a href='cart.php?add={$r['id']}'><button class='add'>Add</button></a></div>
                    </div>
                  </div>
                </div>";
        }
      } else {
        echo "<p style='text-align:center;color:var(--muted)'>No items found.</p>";
      }
      ?>
    </div>

  </div>
  <div class="footer">© 2025 QuickBite</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>AOS.init({duration:700,once:true});</script>
</body>
</html>