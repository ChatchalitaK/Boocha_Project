<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo 'unauthorized';
    exit;
}

$user_id = $_SESSION['user_id'];
$note = isset($_POST['order_note']) && trim($_POST['order_note']) !== '' ? trim($_POST['order_note']) : null;
$payment_method = $_POST['payment_method'] ?? null;
$cart_item_ids = $_POST['cart_item_ids'] ?? [];
$total_price = $_POST['total_price'] ?? 0;

if (!$payment_method || empty($cart_item_ids)) {
    echo 'missing required fields';
    exit;
}

// ดึงรายการสินค้าจาก cart_item
$placeholders = implode(',', array_fill(0, count($cart_item_ids), '?'));
$types = str_repeat('s', count($cart_item_ids));

$query = "
    SELECT ci.Cart_itemID, ci.ProductID, ci.Product_quantity, p.Price
    FROM cart_item ci
    JOIN Product p ON ci.ProductID = p.ProductID
    WHERE ci.Cart_itemID IN ($placeholders)
";
$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$cart_item_ids);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = $result->fetch_all(MYSQLI_ASSOC);

// เริ่ม transaction
$conn->begin_transaction();

try {
    // 1. เพิ่มลงใน order
    $order_date = date('Y-m-d');
    if ($note !== null) {
        $stmt = $conn->prepare("INSERT INTO `order` (CustomerID, OrderDate, Note) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $user_id, $order_date, $note);
    } else {
        $stmt = $conn->prepare("INSERT INTO `order` (CustomerID, OrderDate, Note) VALUES (?, ?, NULL)");
        $stmt->bind_param("ss", $user_id, $order_date);
    }
    $stmt->execute();
    $order_id_query = "SELECT OrderID FROM `order` WHERE OrderID = (SELECT MAX(OrderID) FROM `order` WHERE CustomerID = ?)";
    $stmt_order_id = $conn->prepare($order_id_query);
    $stmt_order_id->bind_param("s", $user_id);
    $stmt_order_id->execute();
    $result_order_id = $stmt_order_id->get_result();
    $order_id_row = $result_order_id->fetch_assoc();
    $order_id = $order_id_row['OrderID'];  // ดึง OrderID ที่ถูกสร้างจากฐานข้อมูล

    // 2. เพิ่มลงใน order_detail
    $stmt_detail = $conn->prepare("INSERT INTO order_detail (OrderID, ProductID, Quantity, Total_productPrice) VALUES (?, ?, ?, ?)");
    foreach ($cart_items as $item) {
        $product_id = $item['ProductID'];
        $quantity = $item['Product_quantity'];
        $total_item_price = $quantity * $item['Price'];
        $stmt_detail->bind_param("ssid", $order_id, $product_id, $quantity, $total_item_price);
        error_log("Running insert: $product_id - $quantity");
        $stmt_detail->execute();
    }

    // 3. เพิ่มใน payment
    $payment_status = 'Pending';
    $paydate = null;
    $stmt_payment = $conn->prepare("INSERT INTO payment (OrderID, Total_price, Payment_method, Payment_status, Paydate) VALUES (?, ?, ?, ?, ?)");
    $stmt_payment->bind_param("sdsss", $order_id, $total_price, $payment_method, $payment_status, $paydate);
    $stmt_payment->execute();

    // 4. เพิ่มใน shipment
    $shipment_status = 'Preparing';
    $ship_date = null;
    $stmt_ship = $conn->prepare("INSERT INTO shipment (OrderID, Shipment_status, Ship_date) VALUES (?, ?, ?)");
    $stmt_ship->bind_param("sss", $order_id, $shipment_status, $ship_date);
    $stmt_ship->execute();

    // 5. ลบสินค้าจาก cart_item
    $del_stmt = $conn->prepare("DELETE FROM cart_item WHERE Cart_itemID IN ($placeholders)");
    $del_stmt->bind_param($types, ...$cart_item_ids);
    $del_stmt->execute();

    // 6. ลดจำนวนสินค้าที่ถูกสั่งซื้อใน table Product
    foreach ($cart_items as $item) {
        $product_id = $item['ProductID'];
        $ordered_quantity = $item['Product_quantity'];

        // ดึงจำนวนสินค้าที่มีอยู่ใน stock
        $stmt_stock = $conn->prepare("SELECT Stock FROM Product WHERE ProductID = ?");
        $stmt_stock->bind_param("s", $product_id);
        $stmt_stock->execute();
        $result_stock = $stmt_stock->get_result();
        $stock_row = $result_stock->fetch_assoc();

        if ($stock_row) {
            $current_stock = $stock_row['Stock'];
            $new_stock = $current_stock - $ordered_quantity;

            // อัปเดต stock ใหม่ใน table Product
            if ($new_stock >= 0) {
                $stmt_update_stock = $conn->prepare("UPDATE Product SET Stock = ? WHERE ProductID = ?");
                $stmt_update_stock->bind_param("is", $new_stock, $product_id);
                $stmt_update_stock->execute();
            } else {
                // ถ้าสต๊อกไม่พอ
                throw new Exception("Not enough stock for product ID: $product_id");
            }
        } else {
            throw new Exception("Product ID: $product_id not found in the database.");
        }
    }

    // commit ทั้งหมด
    $conn->commit();

    echo 'success';
} catch (Exception $e) {
    $conn->rollback();
    error_log("Order Failed: " . $e->getMessage());
    echo 'error: ' . $e->getMessage();
}
?>
