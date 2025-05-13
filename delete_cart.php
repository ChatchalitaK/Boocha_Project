<?php
session_start();  // อย่าลืมเปิด session
require_once 'config.php';

// Delete cart item
if (isset($_POST['delete_cart'])) {
    file_put_contents('debug_log.txt', print_r($_POST, true));
    $cart_item_id = $_POST['cart_item_id'];

    $stmt = $conn->prepare("DELETE FROM cart_item WHERE Cart_itemID = ?");
    $stmt->bind_param("s", $cart_item_id);
    if ($stmt->execute()) {
        echo "Item deleted successfully.";
    } else {
        echo "Error deleting item.";
    }
}
?>
