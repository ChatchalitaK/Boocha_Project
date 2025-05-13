<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "boocha";

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบว่ามี RoastLevelID ที่ส่งมาหรือไม่
if (!isset($_GET['RoastLevelID'])) {
    echo "ไม่พบรหัสระดับการคั่ว";
    exit;
}

$roastLevelID = $_GET['RoastLevelID'];

// ดึงข้อมูลระดับการคั่วจากฐานข้อมูล
$sql = "SELECT * FROM roastlevel WHERE RoastLevelID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $roastLevelID);
$stmt->execute();
$result = $stmt->get_result();
$roastLevel = $result->fetch_assoc();

if (!$roastLevel) {
    echo "ไม่พบข้อมูลระดับการคั่ว";
    exit;
}

// อัปเดตข้อมูลเมื่อมีการส่งแบบฟอร์ม
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $roastLevelName = trim($_POST['RoastLevelName']);

    if (!empty($roastLevelName)) {
        $update_sql = "UPDATE roastlevel SET RoastLevelName = ? WHERE RoastLevelID = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ss", $roastLevelName, $roastLevelID);

        if ($stmt->execute()) {
            header("Location: roastlevel.php?updated=1");
            exit;
        } else {
            $error_message = "เกิดข้อผิดพลาดในการอัปเดตข้อมูล.";
        }
    } else {
        $error_message = "กรุณากรอกชื่อระดับการคั่ว.";
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
                    <h2>Edit Roast Level: <?= htmlspecialchars($roastLevel['RoastLevelID']) ?></h2>
                </div>
            </div>

            <div class="edit-customer-content">
                <form method="post" id="editRoastLevelForm">
                    <label>Roast Level Name:</label>
                    <input type="text" name="RoastLevelName" value="<?= htmlspecialchars($roastLevel['RoastLevelName']) ?>" required><br>

                    <?php
                    if (isset($error_message)) {
                        echo '<p style="color:red;">' . htmlspecialchars($error_message) . '</p>';
                    }
                    ?>
                </form>
            </div>

            <!-- ปุ่ม Save  -->
            <div class="edit-customer-actions">
                <button type="submit" form="editRoastLevelForm">Save</button>
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
