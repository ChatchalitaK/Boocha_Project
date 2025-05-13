<?php
session_start();
require_once 'config.php';

// Update cart item quantity
if (isset($_POST['edit_cart'])) {
    file_put_contents('debug_log.txt', print_r($_POST, true));
    $cart_item_id = $_POST['cart_item_id'];
    $new_quantity = $_POST['new_quantity'];

    if ($new_quantity > 0) {
        $stmt = $conn->prepare("UPDATE cart_item SET Product_quantity = ? WHERE Cart_itemID = ?");
        $stmt->bind_param("is", $new_quantity, $cart_item_id);
        if ($stmt->execute()) {
            echo "Quantity updated successfully.";
        } else {
            echo "Error updating quantity.";
        }
    } else {
        echo "Invalid quantity.";
    }
}

?>
