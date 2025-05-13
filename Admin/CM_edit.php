<?php
// เชื่อมต่อกับฐานข้อมูล
$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "boocha";

$conn = new mysqli($servername, $db_username, $db_password, $db_name);

// เช็คการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// รับค่า UserID จาก URL
if (isset($_GET['UserID'])) {
    $userID = $_GET['UserID'];
    $sql = "SELECT * FROM user WHERE UserID = ? AND Role = 'Customer'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        echo "User not found or not a customer.";
        exit;
    }
} else {
    echo "UserID not found.";
    exit;
}

// ตรวจสอบค่าที่ส่งมาจาก AJAX
if (isset($_GET['check_user'])) {
    $username = $_GET['Username'];
    $tel = $_GET['Tel'];
    $email = $_GET['Email'];

    // เช็คว่า Username, Tel, หรือ Email ซ้ำกับข้อมูลในฐานข้อมูลหรือไม่
    $check_sql = "SELECT UserID FROM user WHERE (Username = ? OR Tel_number = ? OR Email = ?)";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("sss", $username, $tel, $email);
    $stmt->execute();
    $check_result = $stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo json_encode(["exists" => true]);
    } else {
        echo json_encode(["exists" => false]);
    }
    exit;
}

// เมื่อกดปุ่มบันทึก
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['Username'];
    $password = $_POST['Password']; // plain password
    $firstname = $_POST['Firstname'];
    $lastname = $_POST['Lastname'];
    $shipping = $_POST['Shipping_Address'];
    $tel = $_POST['Tel_number'];
    $email = $_POST['Email'];

    // ตรวจสอบว่า Username, Tel, และ Email ซ้ำหรือไม่ (เว้นของตัวเองไว้)
    $check_sql = "SELECT UserID FROM user WHERE (Username = ? OR Tel_number = ? OR Email = ?) AND UserID != ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ssss", $username, $tel, $email, $userID);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "Username, Tel number, or Email already exists. Please choose another.";
        exit;
    }

    if (!empty($password)) {
        // กรณีเปลี่ยนรหัสผ่าน
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE user SET Username=?, Password_hash=?, Firstname=?, Lastname=?, Shipping_Address=?, Tel_number=?, Email=? WHERE UserID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss", $username, $password_hash, $firstname, $lastname, $shipping, $tel, $email, $userID);
    } else {
        // กรณีไม่เปลี่ยนรหัสผ่าน
        $sql = "UPDATE user SET Username=?, Firstname=?, Lastname=?, Shipping_Address=?, Tel_number=?, Email=? WHERE UserID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $username, $firstname, $lastname, $shipping, $tel, $email, $userID);
    }

    if ($stmt->execute()) {
        header("Location: customer.php?updated=1");
        exit;
    } else {
        echo "Error occurred: " . $conn->error;
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- ======== Styles ======== -->
    <link rel="stylesheet" href="assets/css/edit_style.css">
</head>

<body>
   <!--==================== Navigation ====================--> 
   <div class="container">
    <div class="navigation">
        <ul>
            <li>
                <a href="#">
                    <span class="icon">
                        <ion-icon name="leaf-outline"></ion-icon>
                    </span>
                    <span class="title">BOOCHA</span>
                </a>
            </li>

            <li>
                <a href="dashboard_day.php">
                    <span class="icon">
                        <ion-icon name="bar-chart-outline"></ion-icon>
                    </span>
                    <span class="title">Dashboard</span>
                </a>
            </li>
            
            <li>
                <a href="customer.php">
                    <span class="icon">
                        <ion-icon name="people-outline"></ion-icon>
                    </span>
                    <span class="title">Users</span>
                </a>
            </li>
            
            <li>
                <a href="product.php">
                    <span class="icon">
                        <ion-icon name="storefront-outline"></ion-icon>
                    </span>
                    <span class="title">Product</span>
                </a>
            </li>
            
            <li>
                <a href="order.php">
                    <span class="icon">
                        <ion-icon name="cart-outline"></ion-icon>
                    </span>
                    <span class="title">Orders</span>
                </a>
            </li>

            <li>
                <a href="report.php">
                    <span class="icon">
                        <ion-icon name="mail-unread-outline"></ion-icon>
                    </span>
                    <span class="title">Feedback</span>
                </a>
            </li>
            
            <li>
                <a href="../index.php">
                    <span class="icon">
                        <ion-icon name="log-out-outline"></ion-icon>
                    </span>
                    <span class="title">Log Out</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- ================ Main ================== -->
    <div class="main">
        <div class="topbar">
            <div class="toggle">
                <ion-icon name="menu-outline"></ion-icon>
            </div>

            <div class="user">
                <img src="assets/imgs/customer01.jpg" alt="">
            </div>
        </div>

        <div class="edit-customer">
            <div class="edit-customer-header">
                <div class="cardHeader">
                    <h2>Edit Customer : <?= htmlspecialchars($user['UserID']) ?></h2>
                </div>
            </div>
            

            <div class="edit-customer-content">
                <form method="post" id="editCustomerForm" onsubmit="return validateForm(event)">
                    <label>Username :</label>
                    <input type="text" name="Username" value="<?= htmlspecialchars($user['Username']) ?>" required><br>

                    <label>New Password :</label>
                    <input type="password" name="Password" placeholder="Leave blank to keep current"><br>

                    <label>Firstname :</label>
                    <input type="text" name="Firstname" value="<?= htmlspecialchars($user['Firstname']) ?>" required><br>

                    <label>Lastname :</label>
                    <input type="text" name="Lastname" value="<?= htmlspecialchars($user['Lastname']) ?>" required><br>

                    <label>Shipping Address :</label>
                    <input type="text" name="Shipping_Address" value="<?= htmlspecialchars($user['Shipping_Address']) ?>" required><br>

                    <label>Tel Number :</label>
                    <input type="text" name="Tel_number" value="<?= htmlspecialchars($user['Tel_number']) ?>" required><br>

                    <label>Email :</label>
                    <input type="email" name="Email" value="<?= htmlspecialchars($user['Email']) ?>" required><br>
                    
                    <!-- แสดงข้อความแจ้งเตือน -->
                    <div id="error-message" style="color: red; display: none;"></div>
            
                </form>

                <script>
                    // ตรวจสอบและแสดงข้อผิดพลาดในฟอร์ม
                    function validateForm(event) {
                        event.preventDefault();  // ป้องกันการส่งฟอร์มและโหลดหน้าใหม่

                        var errorMessage = document.getElementById('error-message');
                        var username = document.forms["editCustomerForm"]["Username"].value;
                        var password = document.forms["editCustomerForm"]["Password"].value;

                        // ตรวจสอบว่า Username ซ้ำหรือไม่
                        var usernameExists = false;  // สมมุติว่าเช็คจากฐานข้อมูล
                        if (usernameExists) {
                            errorMessage.textContent = "Username already exists. Please choose another.";
                            errorMessage.style.display = 'block';
                            return false;
                        }

                        // ถ้าไม่พบข้อผิดพลาด ส่งฟอร์ม
                        errorMessage.style.display = 'none';
                        document.getElementById("editCustomerForm").submit();  // ส่งฟอร์มหลังจากตรวจสอบเสร็จ
                        return true;
                    }
                </script>

            </div>

            <!-- ปุ่ม Save -->
            <div class="edit-customer-actions">
                <button type="submit" form="editCustomerForm">Save</button>
            </div>
        </div>
    </div>


   <!-- ======== Scripts ======= -->
   <script src="assets/js/main.js"></script>
   <!-- ====== ionicons ====== -->
   <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
   <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</body>
</html>
