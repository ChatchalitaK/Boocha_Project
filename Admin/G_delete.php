<?php

$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "boocha";

$conn = new mysqli($servername, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบว่ามีการส่งคำขอ POST และ GradeID ถูกส่งมาหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['GradeID'])) {
    $gradeID = $_POST['GradeID'];

    // ลบข้อมูลจากตาราง grade
    $stmt = $conn->prepare("DELETE FROM grade WHERE GradeID = ?");
    $stmt->bind_param("s", $gradeID);

    if ($stmt->execute()) {
        header("Location: grade.php?deleted=1"); // กลับไปหน้าแสดงรายการ
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>
