<?php
include('db_connect.php');

// When form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Clean inputs
    $username = trim($_POST['username']);
    $username = preg_replace('/\s+/u', ' ', $username);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    // ✅ BACKEND name validation (letters + single spaces)
    if (!preg_match('/^[A-Za-z]+( [A-Za-z]+)*$/', $username)) {
        echo "<script>alert('Full Name must contain only letters and spaces!');window.location='signup.php';</script>";
        exit;
    }

    // Check if email already exists
    $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email' LIMIT 1");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Email already registered! Please login.');window.location='login.php';</script>";
        exit;
    }

    // ✅ FIX: HASH password before saving
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $insert = mysqli_query(
        $conn,
        "INSERT INTO users (username, email, password)
         VALUES ('$username','$email','$hashedPassword')"
    );

    if ($insert) {
        echo "<script>alert('Signup successful! Please login now.');window.location='login.php';</script>";
        exit;
    } else {
        echo "<script>alert('Something went wrong! Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Signup - QuickBite</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background: linear-gradient(120deg, #ff9933, #ff6600, #0033cc);
            background-size: 200% 200%;
            animation: gradientMove 6s ease infinite;
        }
        @keyframes gradientMove {
            0% {background-position: 0% 50%;}
            50% {background-position: 100% 50%;}
            100% {background-position: 0% 50%;}
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #ff6600;
            padding: 15px 50px;
            color: #fff;
        }
        .logo {
            font-size: 26px;
            font-weight: bold;
        }
        .logo span {
            color: #0033cc;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            font-weight: 500;
        }
        .signup-container {
            background-color: #ffffffee;
            border-radius: 12px;
            width: 400px;
            margin: 80px auto;
            padding: 30px;
            box-shadow: 0px 0px 15px rgba(0,0,0,0.2);
        }
        h2 {
            text-align: center;
            color: #0033cc;
            margin-bottom: 20px;
        }
        form input {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            outline: none;
            font-size: 15px;
        }
        form input:focus {
            border-color: #ff6600;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #0033cc;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #ff6600;
        }
        p {
            text-align: center;
            margin-top: 10px;
        }
        a {
            color: #0033cc;
        }
    </style>
</head>

<body>

<header>
  <div class="logo">Quick<span>Bite</span></div>
  <nav>
    <a href="index.php">Home</a>
    <a href="menu.php">Menu</a>
    <a href="login.php">Login</a>
    <a href="signup.php">Signup</a>
  </nav>
</header>

<div class="signup-container">
    <h2>Create Your Account</h2>
    <form method="POST" onsubmit="return validateForm()">
        <input type="text" name="username" id="username" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" minlength="5" required>
        <button type="submit">Sign Up</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>

<script>
// ✅ FRONTEND Validation (fixed regex)
function validateForm() {
    const name = document.getElementById("username").value.trim();
    const regex = /^[A-Za-z]+( [A-Za-z]+)*$/;

    if (!regex.test(name)) {
        alert("Full Name must contain only letters and spaces!");
        return false;
    }
    return true;
}
</script>

</body>
</html>