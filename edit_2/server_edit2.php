<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => '유효한 사용자 세션이 없습니다.']);
    exit();
}

// POST로 전송된 데이터를 받음
$data = json_decode(file_get_contents('php://input'), true);

// DB 연결
$servername = "********"; //Server Name
$db_username = "********"; //DB_User Name
$db_password = "********"; //DB_User Password
$dbname = "********"; //DB Name

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'DB connection failed']);
    exit();
}

// POST된 데이터에서 값 추출
$keyword = $conn->real_escape_string($data['keyword'] ?? '');
$password = $conn->real_escape_string($data['password'] ?? '');
$service_username = $conn->real_escape_string($data['service_username'] ?? '');
$url = $conn->real_escape_string($data['url'] ?? '');
$user_id = intval($_SESSION['user_id']);  // 정수로 변환

// 데이터베이스에 데이터 삽입
$sql = "INSERT INTO passwords (user_id, keyword, service_password, service_username, url) 
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("issss", $user_id, $keyword, $password, $service_username, $url);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'DB insert failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>