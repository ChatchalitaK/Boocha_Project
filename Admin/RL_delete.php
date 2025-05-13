<?php
// roastlevel_delete.php

$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "boocha";

$conn = new mysqli($servername, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['RoastLevelID'])) {
    $roastLevelID = $_POST['RoastLevelID'];

    // ลบข้อมูลในตาราง roastlevel
    $stmt = $conn->prepare("DELETE FROM roastlevel WHERE RoastLevelID = ?");
    $stmt->bind_param("s", $roastLevelID);

    if ($stmt->execute()) {
        header("Location: roastlevel.php?deleted=1"); // กลับไปยังหน้าแสดงระดับการคั่ว
        exit();
    } else {
        echo "Error deleting roast level: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>
