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
$delete_option = $_POST['delete_option'];
$value = $_POST['value'];

// 삭제 쿼리 준비
if ($delete_option == 'id') {
    $sql = "DELETE FROM passwords WHERE id=?";
} elseif ($delete_option == 'user_id') {
    $sql = "DELETE FROM passwords WHERE user_id=?";
} elseif ($delete_option == 'service_name') {
    $sql = "DELETE FROM passwords WHERE service_name=?";
}

// 쿼리 실행
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $value); // 모든 조건에서 value는 문자열로 처리

if ($stmt->execute()) {
    echo "패스워드가 성공적으로 삭제되었습니다.";
} else {
    echo "삭제 실패: " . $stmt->error;
}

// 연결 종료
$stmt->close();
$conn->close();
?>

<!-- 관리 페이지로 돌아가기 버튼 -->
<br>
<a href="manage_password.php"><button>관리 페이지로 돌아가기</button></a>