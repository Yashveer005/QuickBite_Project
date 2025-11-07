<?php
// index.php
include('db_connect.php');
session_start();
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
  --orange:#ff6600; --orange-2:#ff944d; --blue:#007bff; --muted:#6b6b6b;
  --bg:#ffffff; --card-shadow:0 10px 30px rgba(15,15,15,0.06); --radius:14px; --maxw:1200px;
}
*{box-sizing:border-box;margin:0;padding:0;font-family:'Poppins',system-ui,Arial;}
body{background:var(--bg);color:#222;-webkit-font-smoothing:antialiased;}
.container{max-width:var(--maxw);margin:0 auto;padding:0 18px;}
/* header */
.header{display:flex;align-items:center;justify-content:space-between;padding:16px 0}
.brand{font-weight:800;font-size:22px}
.brand span:first-child{color:var(--blue)} .brand span:last-child{color:var(--orange)}
.nav a{margin-left:16px;color:#333;text-decoration:none;font-weight:600}
/* greeting strip */
.greet{background:#fff8f2;color:var(--orange);text-align:center;padding:10px;font-weight:700;border-radius:10px;box-shadow:var(--card-shadow);margin-bottom:14px}
/* hero */
.hero{background:linear-gradient(180deg, rgba(255,255,255,0.9), rgba(255,255,255,0.9)),url('assets/images/hero-bg.jpg') center/cover no-repeat;padding:48px;border-radius:12px;box-shadow:var(--card-shadow);text-align:center}
.hero h1{font-size:34px;color:var(--orange);margin-bottom:8px}
.hero p{color:#444;margin-bottom:18px}
.search-wrap{display:flex;justify-content:center}
.search{display:flex;max-width:600px;width:100%;border-radius:999px;overflow:hidden;box-shadow:0 8px 20px rgba(0,0,0,0.06)}
.search input{flex:1;padding:12px 16px;border:0;font-size:15px}
.search button{background:var(--orange);color:#fff;border:0;padding:11px 18px;cursor:pointer;font-weight:700}
/* marquee */
.marquee{margin-top:12px;background:#fff3eb;padding:10px;border-radius:10px;color:var(--orange);font-weight:700;box-shadow:var(--card-shadow)}
/* grid cards */
.section-title{text-align:center;font-weight:800;margin:36px 0 18px;font-size:20px}
.grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px}
.card{background:#fff;border-radius:12px;overflow:hidden;box-shadow:var(--card-shadow);transition:transform .18s}
.card:hover{transform:translateY(-6px)}
.card img{width:100%;height:150px;object-fit:cover}
.card .body{padding:12px}
.card h3{color:var(--orange);margin-bottom:6px;font-size:16px}
.card p{color:var(--muted);font-size:13px;margin-bottom:8px}
.meta{display:flex;justify-content:space-between;align-items:center}
.add-btn{background:var(--orange-2);color:#fff;padding:8px 12px;border-radius:8px;border:0;cursor:pointer;font-weight:700}
/* restaurants */
.rest-grid{display:flex;gap:14px;flex-wrap:wrap;margin-top:10px}
/* testimonials */
.test-grid{display:flex;gap:14px;flex-wrap:wrap;justify-content:center;margin-top:12px}
.test{background:#fff;padding:14px;width:280px;border-radius:12px;box-shadow:var(--card-shadow);font-style:italic}
/* footer */
.footer{margin-top:40px;padding:18px;text-align:center;background:linear-gradient(90deg,var(--orange),var(--orange-2));color:#fff;border-radius:10px}
/* scroll and offer */
#scrollTop{display:none;position:fixed;right:18px;bottom:18px;background:var(--orange-2);color:#fff;padding:12px;border-radius:999px;border:0;cursor:pointer;box-shadow:0 8px 18px rgba(0,0,0,0.2)}
#offerBubble{display:none;position:fixed;right:18px;bottom:80px;background:linear-gradient(90deg,var(--orange-2),var(--orange));color:#fff;padding:10px 14px;border-radius:40px;box-shadow:0 8px 20px rgba(0,0,0,0.16);cursor:pointer;font-weight:800}
@media (max-width:600px){ .hero h1{font-size:24px} .grid{gap:12px} .test{width:220px}}
</style>
</head>
<body>
  <div class="container">
    <!-- header -->
    <div class="header" data-aos="fade-down">
      <div class="brand"><span>Quick</span><span>Bite</span></div>
      <div class="nav">
  <a href="index.php">Home</a>
  <a href="menu.php">Menu</a>
  <a href="cart.php">Cart</a>
  <?php if(isset($_SESSION['user_name'])): ?>
    <a href="logout.php" style="color:#ff6600;font-weight:700;">Logout</a>
  <?php else: ?>
    <a href="login.php" style="color:#007bff;font-weight:700;">Login</a>
  <?php endif; ?>
</div>
    </div>

    <!-- greeting -->
    <div id="greet" class="greet" data-aos="fade-up"></div>

    <!-- hero -->
    <section class="hero" data-aos="fade-up">
      <div class="hero-inner">
        <h1>Delicious meals, delivered fast</h1>
        <p>Order from nearby restaurants — hot & fresh at your doorstep.</p>

        <!-- search: same param 'search' used in menu.php -->
        <div class="search-wrap">
          <form class="search" action="menu.php" method="GET" role="search">
            <input type="text" name="search" placeholder="Search for pizza, burger, paneer..." required>
            <button type="submit">Search</button>
          </form>
        </div>

        <div class="marquee" aria-hidden="true">🔥 Trending: Cheese Burst Pizza • Paneer Tikka • Veg Burger • Chocolate Lava Cake</div>
      </div>
    </section>

    <!-- popular -->
    <h2 class="section-title" data-aos="fade-up">Popular Picks</h2>
    <div class="grid" data-aos="fade-up">
      <?php
      $r = mysqli_query($conn, "SELECT * FROM menu_items ORDER BY id DESC LIMIT 8");
      if($r && mysqli_num_rows($r)>0){
        while($row = mysqli_fetch_assoc($r)){
          $img = !empty($row['image']) ? $row['image'] : 'default.jpg';
          $name = htmlspecialchars($row['name'],ENT_QUOTES);
          $desc = htmlspecialchars($row['description'],ENT_QUOTES);
          echo "<div class='card'>
                  <img src='assets/images/{$img}' alt='{$name}'>
                  <div class='body'>
                    <h3>{$name}</h3>
                    <p>{$desc}</p>
                    <div class='meta'>
                      <div style='font-weight:800'>₹ {$row['price']}</div>
                      <div><a href='cart.php?add={$row['id']}'><button class='add-btn'>Add</button></a></div>
                    </div>
                  </div>
                </div>";
        }
      } else {
        echo "<p style='color:var(--muted)'>No items yet.</p>";
      }
      ?>
    </div>

    <!-- restaurants -->
    <h2 class="section-title" data-aos="fade-up">Top Restaurants</h2>
    <div class="rest-grid" data-aos="fade-up">
      <div class="card" style="min-width:260px"><img src="assets/images/restaurant1.jpg"><div class="body"><h3>Pizza Hub</h3><p style="color:var(--muted)">Italian • 4.5★</p></div></div>
      <div class="card" style="min-width:260px"><img src="assets/images/restaurant2.jpg"><div class="body"><h3>Burger Point</h3><p style="color:var(--muted)">Fast food • 4.3★</p></div></div>
      <div class="card" style="min-width:260px"><img src="assets/images/restaurant3.jpg"><div class="body"><h3>Curry Palace</h3><p style="color:var(--muted)">North Indian • 4.6★</p></div></div>
    </div>

    <!-- testimonials -->
    <h2 class="section-title" data-aos="fade-up">What Our Customers Say</h2>
    <div class="test-grid" data-aos="fade-up">
      <div class="test">“QuickBite never disappoints! Always hot, always tasty.” — Riya S.</div>
      <div class="test">“Super fast delivery and amazing discounts!” — Arjun M.</div>
      <div class="test">“Paneer Butter Masala was divine 😋 10/10!” — Neha P.</div>
    </div>

    <div style="height:26px"></div>
  </div>

  <div id="offerBubble">💸 ₹150 OFF on first order!</div>
  <button id="scrollTop" aria-label="Scroll to top">↑</button>

  <footer class="footer">© 2025 QuickBite — Designed by Yashveer Singh</footer>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
AOS.init({duration:700,once:true});
// greeting
const hour = new Date().getHours();
const greetEl = document.getElementById('greet');
<?php
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
?>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
AOS.init({duration:700,once:true});
</script>

<?php
// ✅ Get username from session if logged in
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
?>

<script>
// ✅ Dynamic greeting based on time
const hour = new Date().getHours();
const name = "<?php echo $user_name; ?>";
const greetEl = document.getElementById('greet');
let greetMsg = "";

if(hour < 12) greetMsg = "Good Morning, " + name + " 🍳";
else if(hour < 18) greetMsg = "Good Afternoon, " + name + " ☀";
else greetMsg = "Good Evening, " + name + " 🌙";

if(greetEl) greetEl.innerText = greetMsg;
// offer bubble show
setTimeout(()=> document.getElementById('offerBubble').style.display='block', 1000);
document.getElementById('offerBubble').onclick = ()=> location.href='signup.php';
// scroll top
const st=document.getElementById('scrollTop');
window.addEventListener('scroll',()=> st.style.display = window.scrollY > 300 ? 'block' : 'none');
st.addEventListener('click', ()=> window.scrollTo({top:0,behavior:'smooth'}));
</script>
</body>
</html>