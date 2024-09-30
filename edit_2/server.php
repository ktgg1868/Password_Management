<?php
session_start();  // 로그인된 사용자의 ID를 세션에서 확인

$servername = "localhost";
$username = "root";
$password = "your_password";
$dbname = "password_manager";

// MySQL 연결
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputData = json_decode(file_get_contents('php://input'), true);

    if (isset($inputData['keyword']) && isset($inputData['password']) && isset($inputData['hashpassword'])) {
        $keyword = $conn->real_escape_string($inputData['keyword']);
        $password = $conn->real_escape_string($inputData['password']);
        $hashpassword = $conn->real_escape_string($inputData['hashpassword']);

        // 사용자의 ID를 세션에서 가져옴
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];

            // 비밀번호를 MySQL DB에 저장
            $sql = "INSERT INTO passwords (user_id, keyword, password, hashpassword) VALUES ('$user_id', '$keyword', '$password', '$hashpassword')";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'DB 저장 실패: ' . $conn->error]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => '사용자 인증 필요']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid data received']);
    }
}
$conn->close();
?>