<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => '로그인이 필요합니다.']);
    exit();
}

// POST로 전송된 데이터를 받음
$data = json_decode(file_get_contents('php://input'), true);

// DB 연결
$servername = "localhost"; //Server Name
$db_username = "Master_User"; //DB_User Name
$db_password = "qwe123"; //DB_User Password
$dbname = "password_manager"; //DB Name

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'DB connection failed']);
    exit();
}

// POST된 데이터에서 값 추출
$keyword = $conn->real_escape_string($data['keyword']); // 키워드
$password = $data['password']; // 평문 비밀번호 (클라이언트에서 전송된 비밀번호)
$service_domain = $conn->real_escape_string($data['service_domain']); // 서비스 이름
$service_username = $conn->real_escape_string($data['service_username']);  // 사용자 이름 또는 이메일
$url = $conn->real_escape_string($data['url']);  // 서비스 URL
$user_id = $_SESSION['user_id'];  // 세션에서 로그인한 사용자 ID

// 비밀번호 해시화 (password_hash 사용)
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// 데이터베이스에 데이터 삽입 (URL 및 서비스 사용자 이름 포함)
$sql = "INSERT INTO passwords (user_id, keyword, service_password, hashpassword , service_username, url, service_name) 
        VALUES ('$user_id', '$keyword', '$password', '$hashed_password', '$service_username', '$url', '$service_domain')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'DB insert failed']);
}

$conn->close();
?>
