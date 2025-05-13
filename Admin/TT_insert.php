

<?php
$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "boocha";

$conn = new mysqli($servername, $db_username, $db_password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $teaTypeName = trim($_POST['TeaTypeName']);

    if (!empty($teaTypeName)) {
        // ตรวจสอบว่า TeaTypeName นี้มีอยู่แล้วหรือยัง
        $checkStmt = $conn->prepare("SELECT TeaTypeName FROM tea_type WHERE TeaTypeName = ?");
        $checkStmt->bind_param("s", $teaTypeName);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $error_message = "This tea type already exists.";
        } else {
            $insertStmt = $conn->prepare("INSERT INTO tea_type (TeaTypeName) VALUES (?)");
            $insertStmt->bind_param("s", $teaTypeName);

            if ($insertStmt->execute()) {
                header("Location: teatype.php?created=1");
                exit;
            } else {
                $error_message = "An error occurred while saving the tea type.";
            }
        }
    } else {
        $error_message = "Tea type name must not be empty.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Customer Account</title>
    <!-- ======== Styles ======== -->
    <link rel="stylesheet" href="assets/css/insert_style.css">
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
                <img src="assets/imgs/customer01.jpg" alt="User Image">
            </div>
        </div>

        <div class="create-customer">
            <div class="create-customer-header">
                <div class="cardHeader">
                    <h2>Create New Tea Type</h2>
                </div>
            </div>

            <div class="create-customer-content">
                <form method="post" id="createTeaTypeForm">
                    <label>Tea Type Name :</label>
                    <input type="text" name="TeaTypeName" value="<?php echo isset($teaTypeName) ? htmlspecialchars($teaTypeName) : ''; ?>" required><br>

                    <?php
                    if (isset($error_message)) {
                        echo '<p style="color: red;">' . htmlspecialchars($error_message) . '</p>';
                    }
                    ?>
                </form>
            </div>

            <div class="create-customer-actions">
                <button type="submit" form="createTeaTypeForm">Create Tea Type</button>
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
