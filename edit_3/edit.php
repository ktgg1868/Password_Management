<?php
session_start();

$servername = "localhost"; //Server Name
$db_username = "Master_User"; //DB_User Name
$db_password = "qwe123"; //DB_User Password
$dbname = "password_manager"; //DB Name

// 데이터베이스 연결
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// 연결 오류 확인
if ($conn->connect_error) {
    die("연결 실패: " . $conn->connect_error);
}

// POST 데이터 받기
$id = $_POST['id'];
$user_id = $_POST['user_id'];
$keyword = $_POST['keyword'];
$service_name = $_POST['service_name'];
$service_username = $_POST['service_username'];
$service_password = $_POST['service_password'];
$url = $_POST['url'];

// 데이터 수정 쿼리
$sql = "UPDATE passwords SET user_id=?, keyword=?, service_name=?, service_username=?, service_password=?, url=? WHERE id=?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("isssssi", $user_id, $keyword, $service_name, $service_username, $service_password, $url, $id);

if ($stmt->execute()) {
    echo "패스워드가 성공적으로 수정되었습니다.";
} else {
    echo "수정 실패: " . $stmt->error;
}

// 연결 종료
$stmt->close();
$conn->close();
?>
