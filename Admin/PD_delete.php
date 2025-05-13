
<?php

$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "boocha";

$conn = new mysqli($servername, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ProductID'])) {
    $productID = $_POST['ProductID'];

    // ลบข้อมูลในตาราง user
    $stmt = $conn->prepare("DELETE FROM product WHERE ProductID = ?");
    $stmt->bind_param("s", $productID);

    if ($stmt->execute()) {
        header("Location: product.php?deleted=1"); // หรือหน้าเดิมของคุณ
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}