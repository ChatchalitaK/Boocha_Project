

<?php
// เชื่อมต่อกับฐานข้อมูล
$servername = "localhost";
$db_username = "root";   // เปลี่ยนจาก $username เป็น $db_username
$db_password = "";
$db_name = "boocha";

$conn = new mysqli($servername, $db_username, $db_password, $db_name);


// เช็คการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['UserID'])) {
    $userID = $_GET['UserID'];
    $sql = "SELECT * FROM user WHERE UserID = ? AND (role = 'Admin')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        header('Location: admin.php?error=user_not_found');
        exit;
    }
} else {
    header('Location: admin.php?error=no_userid');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['Username'];
    $password = $_POST['Password']; // ได้เป็น plain password
    $firstname = $_POST['Firstname'];
    $lastname = $_POST['Lastname'];
    $tel = $_POST['Tel_number'];
    $email = $_POST['Email'];
    $latest_update = date("Y-m-d"); // เก็บวันที่ปัจจุบันแบบปี-เดือน-วัน

    if (!empty($password)) {
        // ถ้ากรอกรหัสใหม่ → ทำการ hash
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE user SET Username=?, Password_hash=?, Firstname=?, Lastname=?, Tel_number=?, Email=?, latest_update=? WHERE UserID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss", $username, $password_hash, $firstname, $lastname, $tel, $email, $latest_update, $userID);
    } else {
        // ถ้าไม่กรอกรหัสใหม่ → ไม่เปลี่ยน Password
        $sql = "UPDATE user SET Username=?, Firstname=?, Lastname=?, Tel_number=?, Email=?, latest_update=? WHERE UserID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $username, $firstname, $lastname, $tel, $email, $latest_update, $userID);
    }

    if ($stmt->execute()) {
        header("Location: admin.php?updated=1");
        exit;
    } else {
        echo "เกิดข้อผิดพลาด: " . $conn->error;
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
                    <h2>Edit Administrator : <?= htmlspecialchars($user['UserID']) ?></h2>
                </div>
            </div>

            <div class="edit-customer-content">
                <form method="post" id="editCustomerForm">
                    <label>Username :</label>
                    <input type="text" name="Username" value="<?= htmlspecialchars($user['Username']) ?>" required><br>

                    <label>New Password :</label>
                    <input type="password" name="Password" placeholder="Leave blank to keep current"><br>

                    <label>Firstname :</label>
                    <input type="text" name="Firstname" value="<?= htmlspecialchars($user['Firstname']) ?>" required><br>

                    <label>Lastname :</label>
                    <input type="text" name="Lastname" value="<?= htmlspecialchars($user['Lastname']) ?>" required><br>

                    <label>Tel Number :</label>
                    <input type="text" name="Tel_number" value="<?= htmlspecialchars($user['Tel_number']) ?>" required><br>

                    <label>Email :</label>
                    <input type="email" name="Email" value="<?= htmlspecialchars($user['Email']) ?>" required><br>
                </form>
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
