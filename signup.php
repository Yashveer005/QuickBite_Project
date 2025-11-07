<?php
include('db_connect.php');

// When form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // ✅ BACKEND Validation for name (only letters and spaces)
    if (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        echo "<script>alert('Name must contain only letters and spaces!');window.location='signup.php';</script>";
        exit;
    }

    // Check if email already exists
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Email already registered! Please login.');window.location='login.php';</script>";
    } else {
        $insert = mysqli_query($conn, "INSERT INTO users (name, email, password) VALUES ('$name','$email','$password')");
        if ($insert) {
            echo "<script>alert('Signup successful! Please login now.');window.location='login.php';</script>";
        } else {
            echo "<script>alert('Something went wrong! Please try again.');</script>";
        }
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

        nav a:hover {
            text-decoration: underline;
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

<!-- HEADER (same as homepage) -->
<header>
  <div class="logo">Quick<span>Bite</span></div>
  <nav>
    <a href="index.php">Home</a>
    <a href="menu.php">Menu</a>
    <a href="login.php">Login</a>
    <a href="signup.php">Signup</a>
  </nav>
</header>

<!-- SIGNUP FORM -->
<div class="signup-container">
    <h2>Create Your Account</h2>
    <form method="POST" onsubmit="return validateForm()">
        <input type="text" name="name" id="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" minlength="5" required>
        <button type="submit">Sign Up</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>

<script>
// ✅ FRONTEND Validation for name (only alphabets + spaces)
function validateForm() {
    const name = document.getElementById("name").value.trim();
    const regex = /^[a-zA-Z\s]+$/;
    if (!regex.test(name)) {
        alert("Name must contain only letters and spaces!");
        return false;
    }
    return true;
}
</script>

</body>
</html>