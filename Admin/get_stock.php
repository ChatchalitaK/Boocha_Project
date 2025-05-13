<?php
$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "boocha";

$conn = new mysqli($servername, $db_username, $db_password, $db_name);
$conn->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;

if ($conn->connect_error) {
    // การจัดการเมื่อเชื่อมต่อไม่สำเร็จ
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

if (isset($_GET['ProductID'])) {
    $productID = $_GET['ProductID'];

    $sql = "SELECT Stock FROM product WHERE ProductID = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo json_encode(['error' => 'SQL prepare failed']);
        exit();
    }

    $stmt->bind_param("s", $productID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // ส่งข้อมูล stock กลับมาเป็น JSON
        echo json_encode(['stock' => $row['Stock']]);
    } else {
        echo json_encode(['stock' => 0]); // ถ้าไม่พบสินค้า
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'ProductID parameter is missing']);
}
?>

