
<?php
// เชื่อมต่อกับฐานข้อมูล
$servername = "localhost";
$db_username = "root"; // ชื่อสำหรับเชื่อม DB
$db_password = "";
$db_name = "boocha";

$conn = new mysqli($servername, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ถ้าเป็นการบันทึกข้อมูล (Submit Form)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['Username'];
    $password = $_POST['Password'];
    $firstname = $_POST['Firstname'];
    $lastname = $_POST['Lastname'];
    $tel = $_POST['Tel_number'];
    $email = $_POST['Email'];
    $role = 'Admin';
    $shipping = '';         // ไม่มีฟอร์ม Shipping ก็ set ว่าง

    // ตรวจสอบเบอร์โทร
    if (!preg_match("/^0\d{9}$/", $tel)) {
        $error_message = "Please enter a valid 10-digit phone number starting with 0.";
        $tel = "";
    }
    // ตรวจสอบ email format
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please enter a valid email address.";
        $email = "";
    }
    else {
        // ตรวจสอบ domain
        $allowed_tlds = ['com', 'net', 'org', 'co.th'];
        $domain = substr(strrchr($email, "@"), 1);
        $domain_parts = explode('.', $domain);
        $tld = strtolower(end($domain_parts));

        if (!in_array($tld, $allowed_tlds)) {
            $error_message = "Please use an email with a valid domain (.com, .net, .org, .co.th).";
            $email = "";
        }
        else {
            // ตรวจสอบ Username ซ้ำ
            $checkUsername = $conn->query("SELECT Username FROM user WHERE Username = '$username'");
            if ($checkUsername->num_rows > 0) {
                $error_message = "This username is already in use.";
                $username = "";
            } else {
                // ตรวจสอบ Email ซ้ำ
                $checkEmail = $conn->query("SELECT Email FROM user WHERE Email = '$email'");
                if ($checkEmail->num_rows > 0) {
                    $error_message = "This email is already in use.";
                    $email = "";
                } else {
                    // ตรวจสอบเบอร์โทรซ้ำ
                    $checkTel = $conn->query("SELECT Tel_number FROM user WHERE Tel_number = '$tel'");
                    if ($checkTel->num_rows > 0) {
                        $error_message = "This phone number is already in use.";
                        $tel = "";
                    } else {
                        // ทุกอย่างผ่าน
                        $password_hash = password_hash($password, PASSWORD_DEFAULT);

                        $sql = "INSERT INTO user (Username, Password_hash, Firstname, Lastname, Shipping_Address, Tel_number, Email, Role, latest_update)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ssssssss", $username, $password_hash, $firstname, $lastname, $shipping, $tel, $email, $role);


                        if ($stmt->execute()) {
                            header("Location: admin.php?created=1");
                            exit;
                        } else {
                            $error_message = "An error occurred. Please try again.";
                        }
                    }
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Customer Account</title>
    <link rel="stylesheet" href="assets/css/insert_style.css">
</head>
<body>
<div class="container">
    <div class="navigation">
        <ul>
            <li>
                <a href="#">
                    <span class="icon"><ion-icon name="leaf-outline"></ion-icon></span>
                    <span class="title">BOOCHA</span>
                </a>
            </li>
            <li><a href="index.html"><span class="icon"><ion-icon name="home-outline"></ion-icon></span><span class="title">Home</span></a></li>
            <li><a href="dashboard_day.php"><span class="icon"><ion-icon name="settings-outline"></ion-icon></span><span class="title">Dashboard</span></a></li>
            <li><a href="customer.php"><span class="icon"><ion-icon name="people-outline"></ion-icon></span><span class="title">Customers</span></a></li>
            <li><a href="product.php"><span class="icon"><ion-icon name="storefront-outline"></ion-icon></span><span class="title">Product</span></a></li>
            <li><a href="order.php"><span class="icon"><ion-icon name="settings-outline"></ion-icon></span><span class="title">Orders</span></a></li>
            <li><a href="/"><span class="icon"><ion-icon name="log-out-outline"></ion-icon></span><span class="title">Sign Out</span></a></li>
        </ul>
    </div>

    <div class="main">
        <div class="topbar">
            <div class="toggle">
                <ion-icon name="menu-outline"></ion-icon>
            </div>
            <div class="user">
                <img src="assets/imgs/customer01.jpg" alt="">
            </div>
        </div>

        <div class="create-customer">
            <div class="create-customer-header">
                <div class="cardHeader">
                    <h2>Create New Administrator Account</h2>
                </div>
            </div>

            <div class="create-customer-content">
                <form method="post" id="createCustomerForm">
                    <label>Username :</label>
                    <input type="text" name="Username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" required><br>

                    <label>Password :</label>
                    <input type="password" name="Password" required><br>

                    <label>Firstname :</label>
                    <input type="text" name="Firstname" value="<?php echo isset($firstname) ? htmlspecialchars($firstname) : ''; ?>" required><br>

                    <label>Lastname :</label>
                    <input type="text" name="Lastname" value="<?php echo isset($lastname) ? htmlspecialchars($lastname) : ''; ?>" required><br>

                    <label>Tel Number :</label>
                    <input type="text" name="Tel_number" value="<?php echo isset($tel) ? htmlspecialchars($tel) : ''; ?>" required><br>

                    <label>Email :</label>
                    <input type="email" name="Email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required><br>

                    <?php
                    if (isset($error_message)) {
                        echo '<p style="color: red;">' . htmlspecialchars($error_message) . '</p>';
                    }
                    ?>
                </form>
            </div>

            <div class="create-customer-actions">
                <button type="submit" form="createCustomerForm">Create Account</button>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/main.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>