<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        echo "mismatch";
        exit;
    }

    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $sql = "UPDATE user SET Password_hash = ? WHERE UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $hashed_password, $user_id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <link rel="stylesheet" href="customer.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<section class="profile-container">
    <div class="profile-box">
        <h3>Change Password</h3>
        <form id="changePasswordForm" method="post">
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="new_password" required>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" required>
            </div>
            <div class="form-buttons">
                <button type="submit" class="save-btn">Confirm</button>
            </div>
        </form>
    </div>
</section>

<!-- Success Modal -->
<div id="passwordSuccessModal" class="modal" style="display:none;">
    <div class="modal-content">
        <h3>Password Changed Successfully!</h3>
        <div class="button-group">
            <button onclick="redirectBack()">Close</button>
        </div>
    </div>
</div>

<script>
$('#changePasswordForm').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        url: 'change_password.php',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            if (response.trim() === "success") {
                $('#passwordSuccessModal').css('display', 'flex');
            } else if (response.trim() === "mismatch") {
                alert('Passwords do not match.');
            } else {
                alert('Something went wrong. Please try again.');
            }
        },
        error: function() {
            alert('Server error.');
        }
    });
});

function redirectBack() {
    window.location.href = 'customer.php';  // หรือเปลี่ยนเป็น customer_edit.php ถ้าคุณมีหน้านั้น
}
</script>

<style>
.modal {
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background: rgba(0,0,0,0.6);
    display: flex;
    align-items: center;
    justify-content: center;
}
.modal-content {
    background: #F2DEC2;
    padding: 40px 50px;
    border-radius: 16px;
    text-align: center;
    width: 550px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
.button-group button {
    margin: 10px;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    color: #1E140B;
    background-color: #BEC39E;
    font-weight: 600;
}
</style>

</body>
</html>
