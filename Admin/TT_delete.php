<?php

$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "boocha";

$conn = new mysqli($servername, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบว่ามีการส่งคำขอ POST และ TeaTypeID ถูกส่งมาหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['TeaTypeID'])) {
    $teaTypeID = $_POST['TeaTypeID'];

    // ลบข้อมูลจากตาราง tea_type
    $stmt = $conn->prepare("DELETE FROM tea_type WHERE TeaTypeID = ?");
    $stmt->bind_param("s", $teaTypeID);

    if ($stmt->execute()) {
        header("Location: teatype.php?deleted=1"); // กลับไปหน้าแสดงรายการ
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
