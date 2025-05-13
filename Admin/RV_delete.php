<?php
$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "boocha";

$conn = new mysqli($servername, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ReviewID'])) {
    $reviewID = $_POST['ReviewID'];

    // ลบข้อมูลในตาราง review
    $stmt = $conn->prepare("DELETE FROM review WHERE ReviewID = ?");
    $stmt->bind_param("s", $reviewID);

    if ($stmt->execute()) {
        header("Location: review.php?deleted=1");
        exit();
    } else {
        echo "Error deleting review: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>

