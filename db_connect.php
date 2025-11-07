<?php
// db_connect.php
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';        // XAMPP default empty
$DB_NAME = 'food_db'; // your DB name

$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if(!$conn){
  die("Database connection error: " . mysqli_connect_error());
}
// set charset
mysqli_set_charset($conn, "utf8mb4");
?>