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
    $checkUsername = $conn->query("SELECT Username FROM user WHERE Username = '$username'");
    $checkTel = $conn->query("SELECT Tel_number FROM user WHERE Tel_number = '$telnumber'");

    if ($checkEmail->num_rows > 0) {
        $_SESSION['register_error'] = 'Email is already registered!';
        $_SESSION['active_form'] = 'register';
        header("Location: index.php");
        exit();
    } elseif ($checkUsername->num_rows > 0) {
        $_SESSION['register_error'] = 'Username is already taken!';
        $_SESSION['active_form'] = 'register';
        header("Location: index.php");
        exit();
    } elseif ($checkTel->num_rows > 0) {
        $_SESSION['register_error'] = 'Telephone number is already registered!';
        $_SESSION['active_form'] = 'register';
        header("Location: index.php");
        exit();
    }

    // ถ้าไม่มีข้อมูลซ้ำ, เพิ่มข้อมูล user ใหม่ พร้อมกำหนด role เป็น customer
    $newID = uniqid('USR'); // สร้างรหัส UserID ใหม่
    $conn->query("INSERT INTO user (UserID, Email, Firstname, Lastname, Tel_number, Shipping_Address, Username, Password_hash, latest_update, role) 
                  VALUES('$newID', '$email', '$firstname', '$lastname', '$telnumber', '$address', '$username', '$password', NOW(), 'Customer')");

    $_SESSION['register_success'] = 'Registration successful!';
    header("Location: index.php");
    exit();
}


if (isset($_POST['login'])) 
    $email = $_POST['email'];
    $password = $_POST['password'];

    // ดึงข้อมูล user จากอีเมล
    $user_result = $conn->query("SELECT * FROM user WHERE Email = '$email'");
    if ($user_result->num_rows > 0) {
        $user = $user_result->fetch_assoc();
    
        if (isset($user['Password_hash']) && password_verify($password, $user['Password_hash'])) {
            $_SESSION['username'] = $user['Username'];
            $_SESSION['firstname'] = $user['Firstname'];
            $_SESSION['lastname'] = $user['Lastname'];
            $_SESSION['email'] = $user['Email'];
        
            $role = strtolower(trim($user['Role'])); 
        
            if ($role === 'customer') {
                $_SESSION['user_id'] = $user['UserID'];
                $_SESSION['role'] = 'Customer';
                header("Location: home.php");
                exit();
            } elseif ($role === 'admin') {
                $_SESSION['role'] = 'Admin';
                header("Location: Admin/dashboard_day.php");
                exit();
            } else {
                $_SESSION['login_error'] = 'Not found';
                $_SESSION['active_form'] = 'login';
                header("Location: index.php");
                exit();
            }

            if (!isset($_SESSION['user_id'])) {
                echo "Please log in to add products to your cart.";
                exit;
            }
        }
        
    }    

    $_SESSION['login_error'] = 'Incorrect username or password';
    $_SESSION['active_form'] = 'login';
    header("Location: index.php");
    exit();
}

?>
