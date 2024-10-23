<?php
session_start();

$servername = "localhost"; //Server Name
$db_username = "Master_User"; //DB_User Name
$db_password = "qwe123"; //DB_User Password
$dbname = "password_manager"; //DB Name


// MySQL 연결
$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // 사용자 확인
    $sql = "SELECT username, password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $isPasswordCorrect = password_verify($password, $row['password']);
    } else {
        $isPasswordCorrect = false;
    }

    //비밀번호가 틀리거나 존재하지 않는 계정일 경우
    if (!$isPasswordCorrect) {
        echo '<title>로그인</title>';
        echo '<h2>로그인 에러</h2>';
        echo '<p>존재하지 않는 사용자입니다.</p>';
        echo '<div style="display: flex; gap: 10px; height: 25px;">
        <form action="register.html"><button type="submit">회원가입</button></form>
        <form action="login.html"><button type="submit">돌아가기</button></form>
        </div>';
    } else {
        // 사용자 인증 성공
        $_SESSION['user_id'] = $row['username'];
        header("Location: manage_password.php");  // 비밀번호 관리 페이지로 리다이렉트
        exit();
    }

}
$conn->close();
?>
