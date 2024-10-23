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
$service_username = $_POST['service_username'];
$service_name = $_POST['service_name'];
$service_password = $_POST['service_password'];

$hashpassword = hash('sha256', $_POST['service_password']);

// ID 찾기
$sql = "SELECT id FROM passwords WHERE service_username=? AND service_name=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $service_username, $service_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $id = $row['id']; // 찾은 ID

    // 데이터 수정 쿼리
    $update_sql = "UPDATE passwords SET service_username=?, service_name=?, service_password=?, hashpassword=? WHERE id=?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssi", $service_username, $service_name, $service_password, $hashpassword, $id);

    if ($update_stmt->execute()) {
        echo "패스워드가 성공적으로 수정되었습니다.";
    } else {
        echo "수정 실패: " . $update_stmt->error;
    }
} else {
    echo "해당 레코드를 찾을 수 없습니다.";
}

// 연결 종료
$stmt->close();
$conn->close();
?>

<!-- 관리 페이지로 돌아가기 버튼 -->
<br>
<a href="manage_password.php"><button>관리 페이지로 돌아가기</button></a>