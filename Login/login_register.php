<?php

session_start();
require_once 'config.php';

if (isset($_POST['register'])) {
    $email = $_POST['email'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $telnumber = $_POST['telnumber'];
    $address = $_POST['address'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // เช็คว่า email ซ้ำหรือไม่
    $checkEmail = $conn->query("SELECT Email FROM user WHERE Email = '$email'");
    if ($checkEmail->num_rows > 0) {
        $_SESSION['register_error'] = 'Email is already registered!';
        $_SESSION['active_form'] = 'register';
        header("Location: index.php");
        exit();
    } else {
        // ดึง UserID ล่าสุดจากฐานข้อมูล
        $result = $conn->query("SELECT UserID FROM user ORDER BY UserID DESC LIMIT 1");
        if ($result->num_rows > 0) {
            $lastUser = $result->fetch_assoc();
            $lastID = (int)substr($lastUser['UserID'], 3); // ตัด 'USR'
            $newID = 'USR' . str_pad($lastID + 1, 7, '0', STR_PAD_LEFT);
        } else {
            $newID = 'USR0000001';
        }

        // เพิ่มข้อมูล user ใหม่ พร้อมกำหนด role เป็น customer
        $conn->query("INSERT INTO user (UserID, Email, Firstname, Lastname, Tel_number, Shipping_Address, Username, Password_hash, latest_update, role) 
                      VALUES('$newID', '$email', '$firstname', '$lastname', '$telnumber', '$address', '$username', '$password', NOW(), 'Customer')");

        $_SESSION['register_success'] = 'Registration successful!';
        header("Location: index.php");
        exit();
    }
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // ดึงข้อมูล user จากอีเมล
    $user_result = $conn->query("SELECT * FROM user WHERE Email = '$email'");
    if ($user_result->num_rows > 0) {
        $user = $user_result->fetch_assoc();
        if (password_verify($password, $user['Password_hash'])) {
            $_SESSION['username'] = $user['Username'];
            $_SESSION['firstname'] = $user['Firstname'];
            $_SESSION['lastname'] = $user['Lastname'];
            $_SESSION['email'] = $user['Email'];
            $_SESSION['role'] = $user['role'];

            // ส่งไปตาม role
            if ($user['role'] === 'Customer') {
                header("Location: customer_page.php");
            } else {
                header("Location: admin.php");
            }
            exit();
        }
    }

    $_SESSION['login_error'] = 'Incorrect username or password';
    $_SESSION['active_form'] = 'login';
    header("Location: index.php");
    exit();
}
?>
