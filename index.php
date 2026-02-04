<?php
session_start();
include('db_connect.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>QuickBite — Home</title>

<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<style>
:root{
  --orange:#ff6600;
  --orange2:#ff944d;
  --blue:#007bff;
  --muted:#6b6b6b;
  --bg:#ffffff;
  --shadow:0 10px 30px rgba(0,0,0,0.08);
}
*{box-sizing:border-box;margin:0;padding:0;font-family:Poppins,Arial;}
body{background:var(--bg);color:#222}
.container{max-width:1200px;margin:auto;padding:0 18px}

/* HEADER */
.header{display:flex;justify-content:space-between;align-items:center;padding:16px 0}
.brand{font-size:24px;font-weight:800}
.brand span:first-child{color:var(--blue)}
.brand span:last-child{color:var(--orange)}
.nav a{margin-left:16px;text-decoration:none;font-weight:600;color:#333}
.user-hi{margin-left:16px;color:#555;font-weight:600}

/* GREETING */
.greet{
  background:#fff3eb;
  color:var(--orange);
  padding:12px;
  text-align:center;
  font-weight:700;
  border-radius:10px;
  margin-bottom:18px;
}

/* HERO */
.hero{
  background:linear-gradient(120deg,#fff,#fff3eb);
  padding:48px;
  border-radius:14px;
  box-shadow:var(--shadow);
  text-align:center;
}
.hero h1{color:var(--orange);font-size:36px;margin-bottom:10px}
.hero p{color:#444;margin-bottom:22px}

.search{
  display:flex;
  max-width:620px;
  margin:auto;
}
.search input{
  flex:1;
  padding:14px;
  border-radius:30px 0 0 30px;
  border:1px solid #ddd;
  font-size:15px;
}
.search button{
  padding:14px 22px;
  border:none;
  background:var(--orange);
  color:white;
  font-weight:700;
  border-radius:0 30px 30px 0;
  cursor:pointer;
}

.badges{
  margin-top:18px;
  display:flex;
  justify-content:center;
  gap:14px;
  flex-wrap:wrap;
}
.badge{
  background:#fff;
  padding:8px 14px;
  border-radius:20px;
  font-size:14px;
  font-weight:600;
  box-shadow:var(--shadow);
}

/* SECTION */
.section-title{
  text-align:center;
  margin:40px 0 20px;
  font-size:22px;
  font-weight:800;
}

/* CARDS */
.grid{
  display:grid;
  grid-template-columns:repeat(auto-fill,minmax(220px,1fr));
  gap:18px;
}
.card{
  background:#fff;
  border-radius:14px;
  box-shadow:var(--shadow);
  overflow:hidden;
  position:relative;
  transition:0.2s;
}
.card:hover{transform:translateY(-6px)}
.card img{width:100%;height:160px;object-fit:cover}
.veg{
  position:absolute;
  top:10px;
  left:10px;
  background:#2ecc71;
  color:white;
  font-size:12px;
  font-weight:700;
  padding:4px 8px;
  border-radius:6px;
}
.card .body{padding:14px}
.card h3{color:var(--orange);margin-bottom:6px}
.card p{font-size:13px;color:var(--muted);margin-bottom:10px}
.meta{
  display:flex;
  justify-content:space-between;
  align-items:center;
}
.add-btn{
  background:var(--orange2);
  border:none;
  color:white;
  padding:8px 14px;
  border-radius:8px;
  font-weight:700;
  cursor:pointer;
}

/* TESTIMONIALS */
.test-grid{
  display:flex;
  gap:16px;
  justify-content:center;
  flex-wrap:wrap;
}
.test{
  background:#fff;
  padding:16px;
  width:280px;
  border-radius:14px;
  box-shadow:var(--shadow);
  font-style:italic;
}

/* FOOTER */
.footer{
  margin-top:40px;
  padding:18px;
  text-align:center;
  color:white;
  background:linear-gradient(90deg,var(--orange),var(--orange2));
  border-radius:12px;
}
/* RESTAURANTS */
.rest-grid{
  display:grid;
  grid-template-columns:repeat(auto-fill,minmax(260px,1fr));
  gap:18px;
}
.rest-card{
  background:#fff;
  border-radius:14px;
  box-shadow:var(--shadow);
  overflow:hidden;
  transition:0.2s;
}
.rest-card:hover{transform:translateY(-6px)}
.rest-card img{
  width:100%;
  height:160px;
  object-fit:cover;
}
.rest-card .body{
  padding:14px;
}
.rest-card h3{
  margin-bottom:6px;
  color:#222;
}
.rest-card p{
  color:var(--muted);
  font-size:14px;
}
.rating{
  color:#ff9f00;
  font-weight:700;
}

</style>
</head>

<body>

<div class="container">

<!-- HEADER -->
<div class="header" data-aos="fade-down">
  <div class="brand"><span>Quick</span><span>Bite</span></div>
  <div class="nav">
    <a href="index.php">Home</a>
    <a href="menu.php">Menu</a>
    <a href="cart.php">Cart</a>
    <span class="user-hi">Hi, <?php echo htmlspecialchars($user_name); ?> 👋</span>
    <a href="logout.php" style="color:#ff6600;">Logout</a>
  </div>
</div>

<!-- GREETING -->
<div id="greet" class="greet"></div>

<!-- HERO -->
<section class="hero" data-aos="fade-up">
  <h1>Hungry? We’ve got you covered 😋</h1>
  <p>Fast delivery • Trusted restaurants • Great taste</p>

  <form class="search" action="menu.php" method="GET">
    <input type="text" name="search" placeholder="Search pizza, burger, paneer..." required>
    <button type="submit">Search</button>
  </form>

  <div class="badges">
    <div class="badge">🚚 30 min delivery</div>
    <div class="badge">⭐ Top rated</div>
    <div class="badge">🔥 Trending</div>
  </div>
</section>

<!-- POPULAR -->
<h2 class="section-title">Popular Picks</h2>
<div class="grid" data-aos="fade-up">
<?php
$q = mysqli_query($conn,"SELECT * FROM menu_items ORDER BY id DESC LIMIT 8");
if($q){
while($row=mysqli_fetch_assoc($q)){
$img = !empty($row['image']) ? $row['image'] : 'default.jpg';
echo "
<div class='card'>
  <span class='veg'>VEG</span>
  <img src='assets/images/$img'>
  <div class='body'>
    <h3>{$row['name']}</h3>
    <p>{$row['description']}</p>
    <div class='meta'>
      <b>₹ {$row['price']}</b>
      <a href='cart.php?add={$row['id']}'><button class='add-btn'>Add</button></a>
    </div>
  </div>
</div>";
}}
?>
</div>

<!-- FEATURED RESTAURANTS -->
<h2 class="section-title">Featured Restaurants</h2>

<div class="rest-grid" data-aos="fade-up">

  <div class="rest-card">
    <img src="assets/images/restaurant1.jpg" alt="Pizza Hub">
    <div class="body">
      <h3>Pizza Hub</h3>
      <p>Italian • Fast Food</p>
      <p class="rating">⭐ 4.5 • 30 mins</p>
    </div>
  </div>

  <div class="rest-card">
    <img src="assets/images/restaurant2.jpg" alt="Burger Point">
    <div class="body">
      <h3>Burger Point</h3>
      <p>Burgers • Snacks</p>
      <p class="rating">⭐ 4.3 • 25 mins</p>
    </div>
  </div>

  <div class="rest-card">
    <img src="assets/images/restaurant3.jpg" alt="Curry Palace">
    <div class="body">
      <h3>Curry Palace</h3>
      <p>North Indian • Punjabi</p>
      <p class="rating">⭐ 4.6 • 35 mins</p>
    </div>
  </div>

</div>

<!-- TESTIMONIALS -->
<h2 class="section-title">What Our Customers Say</h2>
<div class="test-grid" data-aos="fade-up">
  <div class="test">“QuickBite never disappoints. Always fresh!” — Riya</div>
  <div class="test">“Super fast delivery & great taste.” — Arjun</div>
  <div class="test">“Best paneer dishes in town 😋” — Neha</div>
</div>

</div>

<footer class="footer">
© 2026 QuickBite — Designed by Yashveer Singh
</footer>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
AOS.init({duration:700,once:true});

// Greeting message
const hour = new Date().getHours();
let msg="";
if(hour<12) msg="Good Morning, <?php echo $user_name; ?> ☀";
else if(hour<18) msg="Good Afternoon, <?php echo $user_name; ?> 🌤";
else msg="Good Evening, <?php echo $user_name; ?> 🌙";
document.getElementById("greet").innerText = msg;
</script>

</body>
</html>