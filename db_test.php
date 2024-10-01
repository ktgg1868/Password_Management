<?php
session_start();
$servername = "********"; //Server Name
$username = "********"; //DB_User Name
$password = "********"; //DB_User Password
$dbname = "********"; //DB Name

// MySQL 연결
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    echo "연결 실패 -> ". $e->getMessage();
} else {
  echo "MySQL DB연결 성공";
}
?>