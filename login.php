<?php
session_start();
include('db_connect.php');

$msg = "";

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Handle login
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // ✅ FIX: password ko SQL se hata diya
    $sql = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $res = mysqli_query($conn, $sql);

    if ($res && mysqli_num_rows($res) == 1) {

        $user = mysqli_fetch_assoc($res);

        // ✅ FIX: correct password verification
        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id']    = $user['id'];
            $_SESSION['user_name']  = $user['username'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['logged_in']  = true;

            header("Location: index.php");
            exit();

        } else {
            $msg = "❌ Wrong password";
        }

    } else {
        $msg = "❌ Email not registered";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | QuickBite</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #ff7b00, #ff4500);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background: #fff;
            width: 380px;
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 0 25px rgba(0,0,0,0.1);
            text-align: center;
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(20px);}
            to {opacity: 1; transform: translateY(0);}
        }

        h2 {
            color: #ff4500;
            margin-bottom: 10px;
        }

        p {
            color: #555;
            font-size: 14px;
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 10px;
            border: 1px solid #ccc;
            outline: none;
            font-size: 15px;
        }

        input:focus {
            border-color: #ff7b00;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #ff4500;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #e63e00;
        }

        .msg {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .footer-text {
            margin-top: 15px;
            font-size: 14px;
        }

        .footer-text a {
            color: #ff4500;
            text-decoration: none;
            font-weight: 600;
        }

        .footer-text a:hover {
            text-decoration: underline;
        }

        .logo {
            font-size: 26px;
            font-weight: 700;
            color: #ff4500;
            margin-bottom: 15px;
        }

        .logo span {
            color: #007bff;
        }
    </style>
</head>

<?php
if (isset($_GET['msg']) && $_GET['msg'] == 'loggedout') {
    echo "<script>
        setTimeout(() => {
            alert('✅ You have been logged out successfully!');
        }, 300);
    </script>";
}
?>

<body>
    <div class="container">
        <div class="logo"><span>Quick</span>Bite</div>
        <h2>Welcome Back 👋</h2>
        <p>Login to order your favorite meals</p>

        <?php if (!empty($msg)) echo "<p class='msg'>$msg</p>"; ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Enter your email" required>
            <input type="password" name="password" placeholder="Enter your password" required>
            <button type="submit">Login</button>
        </form>

        <p class="footer-text">Don't have an account? <a href="signup.php">Sign up</a></p>
    </div>
</body>
</html>
