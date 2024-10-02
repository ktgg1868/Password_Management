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

/*
// POST된 데이터 받기
$keyword = $conn->real_escape_string($data['keyword']);
$password = $conn->real_escape_string($data['password']);
$hashpassword = $conn->real_escape_string($data['hashpassword']);
$user_id = $_SESSION['user_id'];  // 세션에서 user_id 가져오기

// DB에 비밀번호 저장
$sql = "INSERT INTO passwords (user_id, keyword, password, hashpassword) VALUES ('$user_id', '$keyword', '$password', '$hashpassword')"; */

// POST된 데이터에서 값 추출
$keyword = $conn->real_escape_string($data['keyword']); //키워드
$password = $conn->real_escape_string($data['password']); //패스워드값
//$hashpassword = $conn->real_escape_string($data['hashpassword']); //해시값
$service_name = $conn->real_escape_string($data['service_name']); //서비스 이름
$service_username = $conn->real_escape_string($data['service_username']);  // 사용자 이름 또는 이메일
$url = $conn->real_escape_string($data['url']);  // 서비스 URL
$user_id = $_SESSION['user_id'];  // 세션에서 로그인한 사용자 ID

// 데이터베이스에 데이터 삽입 (URL 및 서비스 사용자 이름 포함)
$sql = "INSERT INTO passwords (user_id, keyword, service_password, service_username, url) 
        VALUES ('$user_id', '$keyword', '$password', '$service_username', '$url')";


if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'DB insert failed']);
}

$conn->close();
?>

| id               | int          | NO   | PRI | NULL              | auto_increment    |
| user_id          | int          | YES  | MUL | NULL              |                   |
| keyword          | varchar(255) | YES  |     | NULL              |                   |
| service_name     | varchar(100) | YES  |     | NULL              |                   |
| service_username | varchar(100) | YES  |     | NULL              |                   |
| service_password | varchar(255) | YES  |     | NULL              |                   |
| url              | varchar(255) | YES  |     | NULL              |                   |
| created_at       | timestamp    | YES  |     | CURRENT_TIMESTAMP | DEFAULT_GENERATED |