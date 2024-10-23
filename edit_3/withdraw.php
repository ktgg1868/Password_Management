<?php
session_start();

$servername = "localhost"; //Server Name
$db_username = "root"; //DB_User Name
$db_password = "qwe123!!"; //DB_User Password
$dbname = "password_manager"; //DB Name

// MySQL 연결
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 사용자가 로그인했는지 확인
if (!isset($_SESSION['user_id'])) {
    echo "로그인 후에 계정을 탈퇴할 수 있습니다.";
    exit();
}

// 사용자 계정 삭제 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_SESSION['user_id']);
    
    // 사용자 계정 삭제
    $sql = "DELETE FROM users WHERE username = '$username'";
    if ($conn->query($sql) === TRUE) {
        echo "회원 탈퇴가 완료되었습니다.";
        session_destroy();  // 세션 삭제
        header("Location: login.html");  // 탈퇴 후 안내 페이지로 리다이렉트
        exit();
    } else {
        echo "회원 탈퇴 중 오류가 발생했습니다: " . $conn->error;
    }
}

$conn->close();
?>
