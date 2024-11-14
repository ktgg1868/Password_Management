<?php
session_start();

// 세션값이 없으면 로그인 화면으로 리다이렉트
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// DB 연결
$servername = "localhost"; //Server Name
$db_username = "Master_User"; //DB_User Name
$db_password = "qwe123"; //DB_User Password
$dbname = "password_manager"; //DB Name

// MySQL 연결
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 로그인된 사용자 ID
$user_id = $_SESSION['user_id'];

$matched_passwords = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_name = $_POST['service_name'];
    $service_username = $_POST['service_username'];
    $service_password = $_POST['service_password'];

    // SQL 쿼리 준비 (서비스 이름과 사용자 이름을 기반으로 비밀번호 확인)
    $sql = "SELECT service_name, service_username, hashpassword 
            FROM passwords 
            WHERE user_id = ? 
            AND service_name = ? 
            AND service_username = ?";

    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die('SQL prepare error: ' . htmlspecialchars($conn->error));
    }

    // 파라미터 바인딩
    $stmt->bind_param("sss", $user_id, $service_name, $service_username);

    // 쿼리 실행
    if (!$stmt->execute()) {
        die('Execute error: ' . htmlspecialchars($stmt->error));
    }

    // 결과 가져오기
    $result = $stmt->get_result();

    // 결과가 있는지 확인
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // 비밀번호 확인 (입력된 비밀번호와 hashpassword 필드에 저장된 해시 비교)
            if (password_verify($service_password, $row['hashpassword'])) {
                $matched_passwords[] = $row;
            }
        }
    } else {
        echo "비밀번호를 찾을 수 없습니다.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>비밀번호 검증</h1>

    <form method="POST" action="">
        <input type="text" name="service_name" placeholder="서비스 이름" required>
        <input type="text" name="service_username" placeholder="아이디" required>
        <input type="password" name="service_password" placeholder="비밀번호" required>
        <button type="submit">비밀번호 확인</button>
    </form>

    <br>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <h2>검증 결과</h2>
        <?php if (count($matched_passwords) > 0): ?>
            <p>비밀번호가 일치합니다!</p>
            <ul>
                <?php foreach ($matched_passwords as $password): ?>
                    <li>서비스: <?php echo htmlspecialchars($password['service_name']); ?>, 사용자: <?php echo htmlspecialchars($password['service_username']); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>비밀번호가 일치하지 않습니다.</p>
        <?php endif; ?>
    <?php endif; ?>

    <button type="button" onclick="location.href='manage_password.php' ">관리화면</button>

</body>
</html>

