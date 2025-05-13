<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "boocha";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if TeaTypeID is provided
if (!isset($_GET['TeaTypeID'])) {
    echo "Tea Type ID not found.";
    exit;
}

$teaTypeID = $_GET['TeaTypeID'];

// Fetch tea type data
$sql = "SELECT * FROM tea_type WHERE TeaTypeID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $teaTypeID);
$stmt->execute();
$result = $stmt->get_result();
$tea = $result->fetch_assoc();

if (!$tea) {
    echo "Tea type not found.";
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $teaTypeName = trim($_POST['TeaTypeName']);

    if (!empty($teaTypeName)) {
        // Check for duplicate name (excluding the current one)
        $check_sql = "SELECT * FROM tea_type WHERE TeaTypeName = ? AND TeaTypeID != ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("ss", $teaTypeName, $teaTypeID);
        $stmt->execute();
        $check_result = $stmt->get_result();

        if ($check_result->num_rows > 0) {
            $error_message = "This tea type name already exists. Please use a different name.";
        } else {
            // Proceed to update
            $update_sql = "UPDATE tea_type SET TeaTypeName = ? WHERE TeaTypeID = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("ss", $teaTypeName, $teaTypeID);

            if ($stmt->execute()) {
                header("Location: teatype.php?updated=1");
                exit;
            } else {
                $error_message = "An error occurred while updating the data.";
            }
        }
    } else {
        $error_message = "Please enter the tea type name.";
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
                    <h2>Edit Tea Type: <?= htmlspecialchars($tea['TeaTypeID']) ?></h2>
                </div>
            </div>

            <div class="edit-customer-content">
                <form method="post" id="editTeaTypeForm">
                    <label>Tea Type Name:</label>
                    <input type="text" name="TeaTypeName" value="<?= htmlspecialchars($tea['TeaTypeName']) ?>" required><br>

                    <?php
                    if (isset($error_message)) {
                        echo '<p style="color:red;">' . htmlspecialchars($error_message) . '</p>';
                    }
                    ?>
                </form>
            </div>

            <!-- ปุ่ม Save  -->
            <div class="edit-customer-actions">
                <button type="submit" form="editTeaTypeForm">Save</button>
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
