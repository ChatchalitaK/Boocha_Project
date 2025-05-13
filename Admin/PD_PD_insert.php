

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
    $productID = trim($_POST['ProductID']);
    $productName = trim($_POST['ProductName']);
    $description = trim($_POST['Description']);
    $price = trim($_POST['Price']);
    $stock = trim($_POST['Stock']);
    $status = trim($_POST['Status']);
    $image = trim($_POST['Image']);
    $teaType = trim($_POST['TeaType']);   // TeaTypeID
    $province = trim($_POST['Province']); // ProvinceID
    $roastLevel = trim($_POST['RoastLevel']); // RoastLevelID
    $grade = trim($_POST['Grade']); // GradeID

    if (!empty($productID) && !empty($productName) && !empty($price) && !empty($stock) && !empty($status) && !empty($image) && !empty($teaType) && !empty($province) && !empty($roastLevel) && !empty($grade)) {
        // Insert data into product table
        $insertStmt = $conn->prepare("INSERT INTO product (ProductID, ProductName, Description, Price, Stock, Status, Image, TeaTypeID, ProvinceID, RoastLevelID, GradeID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insertStmt->bind_param("sssssssssss", $productID, $productName, $description, $price, $stock, $status, $image, $teaType, $province, $roastLevel, $grade);

        if ($insertStmt->execute()) {
            header("Location: product.php?created=1");
            exit;
        } else {
            $error_message = "An error occurred while saving the product.";
        }
    } else {
        $error_message = "All fields are required.";
    }
}

// ดึงข้อมูลสำหรับ TeaType, RoastLevel, Province, Grade
$teaTypes = $conn->query("SELECT TeaTypeID, TeaTypeName FROM tea_type");
$roastLevels = $conn->query("SELECT RoastLevelID, RoastLevelName FROM roastlevel");
$provinces = $conn->query("SELECT ProvinceID, ProvinceName FROM province");
$grades = $conn->query("SELECT GradeID, GradeName FROM grade");

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
                    <h2>Create New Product</h2>
                </div>
            </div>

            <div class="create-customer-content">
                <form method="post" id="createProductForm">
                    <label>Product ID:</label>
                    <input type="text" name="ProductID" required><br>
                    
                    <label>Product Name:</label>
                    <input type="text" name="ProductName" required><br>
                    
                    <label>Description:</label>
                    <textarea name="Description"></textarea><br>
                    
                    <label>Price:</label>
                    <input type="number" step="0.01" name="Price" required><br>
                    
                    <label>Stock:</label>
                    <input type="number" name="Stock" required><br>
                    
                    <label>Status:</label>
                    <select name="Status" required>
                        <option value="">Select Status</option>
                        <option value="Available">Available</option>
                        <option value="Unavailable">Unavailable</option>
                    </select><br>
                    
                    <label>Image Link:</label>
                    <input type="text" name="Image" required><br>
                    
                    <!-- Dropdown for TeaType -->
                    <label for="TeaType">Tea Type:</label>
                    <select name="TeaType" required>
                        <option value="">Select Tea Type</option>
                        <?php
                        // Fetch TeaType from the database
                        $teaTypeResult = $conn->query("SELECT TeaTypeID, TeaTypeName FROM tea_type");
                        while ($row = $teaTypeResult->fetch_assoc()) {
                            // Show TeaTypeName and send TeaTypeID in the form
                            echo "<option value='" . $row['TeaTypeID'] . "'>" . $row['TeaTypeName'] . "</option>";
                        }
                        ?>
                    </select>


                    <!-- Dropdown for RoastLevel -->
                    <label for="RoastLevel">Roast Level:</label>
                    <select name="RoastLevel" required>
                        <option value="">Select Roast Level</option>
                        <?php
                        // Fetch RoastLevel from the database
                        $roastLevelResult = $conn->query("SELECT RoastLevelID, RoastLevelName FROM roastlevel");
                        while ($row = $roastLevelResult->fetch_assoc()) {
                            echo "<option value='" . $row['RoastLevelID'] . "'>" . $row['RoastLevelName'] . "</option>";
                        }
                        ?>
                    </select>

                    <!-- Dropdown for Grade -->
                    <label for="Grade">Grade:</label>
                    <select name="Grade" required>
                        <option value="">Select Grade</option>
                        <?php
                        // Fetch Grade from the database
                        $gradeResult = $conn->query("SELECT GradeID, GradeName FROM grade");
                        while ($row = $gradeResult->fetch_assoc()) {
                            echo "<option value='" . $row['GradeID'] . "'>" . $row['GradeName'] . "</option>";
                        }
                        ?>
                    </select>

                    <!-- Dropdown for Province -->
                    <label for="Province">Province:</label>
                    <select name="Province" required>
                        <option value="">Select Province</option>
                        <?php
                        // Fetch Province from the database
                        $provinceResult = $conn->query("SELECT ProvinceID, ProvinceName FROM province");
                        while ($row = $provinceResult->fetch_assoc()) {
                            echo "<option value='" . $row['ProvinceID'] . "'>" . $row['ProvinceName'] . "</option>";
                        }
                        ?>
                    </select>

                    <?php
                    if (isset($error_message)) {
                        echo '<p style="color: red;">' . htmlspecialchars($error_message) . '</p>';
                    }
                    ?>
                </form>

            </div>

            <div class="create-product-actions">
                <button type="submit" form="createProductForm">Create Product</button>
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