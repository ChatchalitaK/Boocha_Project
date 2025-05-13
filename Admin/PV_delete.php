
<?php

$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "boocha";

$conn = new mysqli($servername, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบว่ามีการส่งคำขอ POST และ ProvinceID ถูกส่งมาหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ProvinceID'])) {
    $provinceID = $_POST['ProvinceID'];

    // ลบข้อมูลจากตาราง province
    $stmt = $conn->prepare("DELETE FROM province WHERE ProvinceID = ?");
    $stmt->bind_param("s", $provinceID);

    if ($stmt->execute()) {
        header("Location: province.php?deleted=1"); // กลับไปหน้าแสดงรายการ
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
