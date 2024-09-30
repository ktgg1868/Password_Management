<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "your_mysql_password";
$dbname = "password_manager";

// MySQL 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    // 비밀번호 확인
    if ($password !== $password_confirm) {
        echo "비밀번호가 일치하지 않습니다.";
        exit();
    }

    // 사용자명 중복 확인
    $sql = "SELECT id FROM users WHERE username = '$username'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        echo "이미 사용 중인 사용자명입니다.";
        exit();
    }

    // 비밀번호 해시 처리
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // 사용자 추가
    $sql = "INSERT INTO users (username, password_hash) VALUES ('$username', '$password_hash')";
    if ($conn->query($sql) === TRUE) {
        echo "회원가입이 성공적으로 완료되었습니다.";
        header("Location: login.html");  // 회원가입 후 로그인 페이지로 리다이렉트
        exit();
    } else {
        echo "회원가입 중 오류가 발생했습니다: " . $conn->error;
    }
}

$conn->close();
?>