<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized access.";
    exit;
}

$customerID = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the reviewID and orderDetailID from the POST request
    $reviewID = $_POST['reviewID'] ?? null;
    $orderDetailID = $_POST['orderDetailID'] ?? null;

    if ($reviewID && $orderDetailID) {
        // Delete the review from the database
        $sql = "DELETE FROM review WHERE ReviewID = ? AND CustomerID = ? AND OrderDetailID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $reviewID, $customerID, $orderDetailID);
        if ($stmt->execute()) {
            echo "Review deleted successfully";
        } else {
            echo "Error deleting review";
        }
    } else {
        echo "Missing parameters.";
    }
}
?>
