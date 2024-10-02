<?php
session_start();
$servername = "********"; //Server Name
$db_username = "********"; //DB_User Name
$db_password = "********"; //DB_User Password
$dbname = "********"; //DB Name

// MySQL 연결
$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    echo "연결 실패 -> ". $e->getMessage();
} else {
  echo "MySQL DB연결 성공";
}

exit();

?>