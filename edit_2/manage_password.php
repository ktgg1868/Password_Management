<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");  // 로그인 페이지로 리다이렉트
    exit();
}

$servername = "localhost";
$username = "root";
$password = "your_password";
$dbname = "password_manager";

// MySQL 연결
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];  // 로그인된 사용자 ID

// 비밀번호 목록 가져오기
$sql = "SELECT id, keyword, password, hashpassword FROM passwords WHERE user_id = '$user_id'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>비밀번호 관리</title>
</head>
<body>
    <h1>비밀번호 관리</h1>

    <a href="add.html">비밀번호 추가</a><br><br>

    <table border="1">
        <thead>
            <tr>
                <th>키워드</th>
                <th>비밀번호</th>
                <th>해시</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['keyword']) ?></td>
                <td><?= htmlspecialchars($row['password']) ?></td>
                <td><?= htmlspecialchars($row['hashpassword']) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

<?php $conn->close(); ?>
