<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo 'unauthorized';
    exit;
}

$user_id = $_SESSION['user_id'];
$current_password = $_POST['current_password'] ?? '';

$sql = "SELECT Password_hash FROM user WHERE UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($current_password, $user['Password_hash'])) {
    echo 'valid';
} else {
    echo 'invalid';
}
?>
