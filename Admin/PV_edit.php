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

// Check if ProvinceID is provided
if (!isset($_GET['ProvinceID'])) {
    echo "Province ID not found.";
    exit;
}

$provinceID = $_GET['ProvinceID'];

// Fetch province data
$sql = "SELECT * FROM province WHERE ProvinceID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $provinceID);
$stmt->execute();
$result = $stmt->get_result();
$province = $result->fetch_assoc();

if (!$province) {
    echo "Province not found.";
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $provinceName = trim($_POST['ProvinceName']);

    if (!empty($provinceName)) {
        // Check for duplicate name
        $check_sql = "SELECT * FROM province WHERE ProvinceName = ? AND ProvinceID != ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("ss", $provinceName, $provinceID);
        $stmt->execute();
        $check_result = $stmt->get_result();

        if ($check_result->num_rows > 0) {
            $error_message = "This province name already exists. Please choose a different name.";
        } else {
            $update_sql = "UPDATE province SET ProvinceName = ? WHERE ProvinceID = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("ss", $provinceName, $provinceID);

            if ($stmt->execute()) {
                header("Location: province.php?updated=1");
                exit;
            } else {
                $error_message = "An error occurred while updating the data.";
            }
        }
    } else {
        $error_message = "Please enter the province name.";
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
                    <h2>Edit Province: <?= htmlspecialchars($province['ProvinceID']) ?></h2>
                </div>
            </div>

            <div class="edit-customer-content">
                <form method="post" id="editProvinceForm">
                    <label>Province Name:</label>
                    <input type="text" name="ProvinceName" value="<?= htmlspecialchars($province['ProvinceName']) ?>" required><br>

                    <?php
                    if (isset($error_message)) {
                        echo '<p style="color:red;">' . htmlspecialchars($error_message) . '</p>';
                    }
                    ?>
                </form>
            </div>

            <div class="edit-customer-actions">
                <button type="submit" form="editProvinceForm">Save</button>
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
