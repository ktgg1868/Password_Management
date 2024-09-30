<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "your_password";
$dbname = "password_manager";

// MySQL 연결
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // 사용자 확인
    $sql = "SELECT id, password_hash FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password_hash'])) {
            // 사용자 인증 성공, 세션에 user_id 저장
            $_SESSION['user_id'] = $row['id'];
            header("Location: manage_passwords.php");  // 비밀번호 관리 페이지로 리다이렉트
        } else {
            echo "잘못된 비밀번호입니다.";
        }
    } else {
        echo "존재하지 않는 사용자입니다.";
    }
}
$conn->close();
?>
