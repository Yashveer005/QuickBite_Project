<?php include('db_connect.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>QuickBite | Home</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fc7100f4, #4a02ffff);
            margin: 0;
            padding: 0;
            text-align: center;
            color: white;
        }
        .container {
            padding: 80px 20px;
        }
        .logo img {
            height: 190px;
        }
        h2 {
            font-size: 56px;
            margin-top: 20px;
        }
        p {
            font-size: 18px;
            margin-top: 10px;
        }
        .btn {
            display: inline-block;
            margin-top: 100px;
            background-color: white;
            color: #fa5c00ff;
            padding: 12px 25px;
            font-weight: bold;
            border-radius: 8px;
            text-decoration: none;
            transition: 0.3s;
        }
        .btn:hover {
            background-color: #474747ff;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container">
        <div class="logo">
        </div>
        <h2>
  Welcome to 
  <span style="color: #071393ff;7bff;">Quick</span><span style="color:#ff6a00;">Bite</span>
</h2>

<p style="font-size:26px; margin-top:-20px; color:#fff8e7; font-style:italic;">
  Hot Meals, Fast Deals
</p>

<p>Delicious food from your favorite restaurants — delivered fast!</p>

<a class="btn" href="menu.php">🍔 Order Now</a>
    </div>
</body>
</html>