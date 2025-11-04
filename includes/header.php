<?php
// Start session for login/logout control
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="navbar">
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="menu.php">Menu</a></li>
        <li><a href="cart.php">Cart</a></li>

        <?php if (isset($_SESSION['user_id'])) { ?>
            <li><a href="logout.php">Logout</a></li>
        <?php } else { ?>
            <li><a href="login.php">Sign In</a></li>
            <li><a href="register.php">Sign Up</a></li>
        <?php } ?>
    </ul>
</div>

<style>
.navbar {
    background-color: #fab300bb; /* Swiggy-style orange */
    padding: 15px;
    border-radius: 10px;
    text-align: center;
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: 0px 2px 80px rgba(0, 0, 0, 1);
}

.navbar ul {
    list-style: none;
    margin: 90;
    padding: 0;
}

.navbar li {
    display: inline-block;
    margin: 0 15px;
}

.navbar a {
    color: white;
    font-weight: bold;
    text-decoration: none;
    font-size: 18px;
    transition: 0.3s;
}

.navbar a:hover {
    color: #f86926ff; /* light orange hover effect */
    text-shadow: 0 0 5px white;
}
</style>