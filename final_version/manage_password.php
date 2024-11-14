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

// 비밀번호 목록 가져오기
$sql = "SELECT user_id, keyword, service_name , service_username, service_password, url, created_at, hashpassword FROM passwords WHERE user_id = '$user_id'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>비밀번호 검증</title>
    <style>
        .button-container {
            margin: 10px;
        }
    </style>
</head>
<body>
    <h1>비밀번호 관리</h1>

    <button type="button" onclick="location.href='add.html' ">비밀번호 추가</button>
    <button type="button" onclick="location.href='remove.html' ">비밀번호 삭제</button>
    <button type="button" onclick="location.href='edit.html' ">정보 변경</button>
    <br><br>

    <div style="text-align:center">
        <table border="1" >
            <thead>
                <tr>
                    <th>서비스 이름</th>
                    <th>아이디</th>
                    <th>비밀번호</th>
                    <th>해시</th>
                    <th>주소</th>
                    <th>생성일자</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['service_name']) ?></td>
                    <td><?= htmlspecialchars($row['service_username']) ?></td>
                    <td><?= htmlspecialchars($row['service_password']) ?></td>
                    <td><?= htmlspecialchars($row['hashpassword']) ?></td>
                    <td><?= htmlspecialchars($row['url']) ?></td>
                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <br><br>
    <button type="button" onclick="location.href='add.html' ">비밀번호 추가</button>
    <button type="button" onclick="location.href='remove.html' ">비밀번호 삭제</button>
    <button type="button" onclick="location.href='edit.html' ">정보 변경</button>
    <button type="button" onclick="location.href='verification.php' ">검증</button>

    <br>

    <div class="button-container">
        <form action="logout.php" method="POST" style="display: inline;">
            <button type="submit">로그아웃</button>
        </form>
        <form action="withdraw.html" method="POST" style="display: inline;">
            <button type="submit">탈퇴하기</button>
        </form>
    </div>

</body>
</html>
