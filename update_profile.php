<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// รับค่าจากฟอร์ม
$username = $_POST['Username'];
$firstname = $_POST['Firstname'];
$lastname = $_POST['Lastname'];
$address = $_POST['Shipping_Address'];
$tel = $_POST['Tel_number'];
$email = $_POST['Email'];

// ตรวจสอบค่าว่าง
if (!$username || !$firstname || !$lastname || !$address || !$tel || !$email) {
    die("All fields are required.");
}

// เตรียม SQL
$sql = "UPDATE user SET Username=?, Firstname=?, Lastname=?, Shipping_Address=?, Tel_number=?, Email=? WHERE UserID=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssss", $username, $firstname, $lastname, $address, $tel, $email, $user_id);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "Error updating profile: " . $conn->error;
}

?>
