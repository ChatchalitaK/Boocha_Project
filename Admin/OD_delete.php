
<?php
$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "boocha";

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli($servername, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบว่าเป็นการ POST และมีค่า OrderID
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['OrderID'])) {
    $orderID = $_POST['OrderID'];

    // ตรวจสอบว่า OrderID ไม่ว่าง
    if (!empty($orderID)) {
        // ลบข้อมูลจากตาราง order_detail ที่เกี่ยวข้องกับ OrderID
        $stmt_detail = $conn->prepare("DELETE FROM order_detail WHERE OrderID = ?");
        $stmt_detail->bind_param("s", $orderID);
        $stmt_detail->execute();

        // ลบข้อมูลจากตาราง payment ที่เกี่ยวข้องกับ OrderID
        $stmt_payment = $conn->prepare("DELETE FROM payment WHERE OrderID = ?");
        $stmt_payment->bind_param("s", $orderID);
        $stmt_payment->execute();

        // ลบข้อมูลจากตาราง shipment ที่เกี่ยวข้องกับ OrderID
        $stmt_shipment = $conn->prepare("DELETE FROM shipment WHERE OrderID = ?");
        $stmt_shipment->bind_param("s", $orderID);
        $stmt_shipment->execute();

        // ลบข้อมูลจากตาราง order
        $stmt_order = $conn->prepare("DELETE FROM `order` WHERE OrderID = ?");
        $stmt_order->bind_param("s", $orderID);

        if ($stmt_order->execute()) {
            header("Location: order.php?deleted=1"); // รีไดเร็กต์ไปยังหน้า order.php พร้อมพารามิเตอร์ deleted=1
            exit();
        } else {
            echo "Error deleting order: " . $conn->error;
        }
    } else {
        echo "Invalid OrderID.";
    }
} else {
    echo "Invalid request.";
}
?>
