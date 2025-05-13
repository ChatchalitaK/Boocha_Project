<?php
// report_delete.php

$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "boocha";

$conn = new mysqli($servername, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ReportID'])) {
    $reportID = $_POST['ReportID'];

    // ลบข้อมูลในตาราง report
    $stmt = $conn->prepare("DELETE FROM report WHERE ReportID = ?");
    $stmt->bind_param("s", $reportID);

    if ($stmt->execute()) {
        header("Location: report.php?deleted=1"); // กลับไปยังหน้าแสดงรายงาน
        exit();
    } else {
        echo "Error deleting report: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>
