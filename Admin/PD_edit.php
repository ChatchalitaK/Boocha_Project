
<?php
// เชื่อมต่อกับฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "boocha";  // เปลี่ยนให้เป็นชื่อฐานข้อมูลของคุณ

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// เช็คการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// รับค่า ProductID จาก URL
if (isset($_GET['ProductID'])) {
    $productID = $_GET['ProductID'];
    $sql = "SELECT * FROM product WHERE ProductID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $productID);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
} else {
    echo "ไม่พบรหัสสินค้า";
    exit;
}

// ถ้ามีการกด submit แบบ POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['ProductName'];
    $desc = $_POST['Description'];
    $price = $_POST['Price'];
    $stock = $_POST['Stock'];
    $status = $_POST['Status'];
    $image = isset($_POST['Image']) ? $_POST['Image'] : '';

   // เปลี่ยนจากการใช้ $pdo เป็น $conn
    $query = "UPDATE product SET Image = ? WHERE ProductID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $image, $productID);  // เปลี่ยนให้สอดคล้องกับประเภทข้อมูล
    $stmt->execute();


    // อัปเดตข้อมูลสินค้า
    $update_sql = "UPDATE product SET ProductName=?, Description=?, Price=?, Stock=?, Status=?, Image=?, update_date=NOW() WHERE ProductID=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssdisss", $name, $desc, $price, $stock, $status, $image, $productID);

    if ($stmt->execute()) {
        header("Location: product.php?updated=1");
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

        <div class="edit-product">
            <div class="edit-product-header">
                <div class="cardHeader">
                    <h2>Edit Product : <?= htmlspecialchars($product['ProductID']) ?></h2>
                </div>
            </div>

            <div class="edit-product-content">
                <!-- รูปภาพด้านซ้าย -->
                <?php if ($product['Image']): ?>
                    <div class="image-preview-side">
                        <img src="<?= htmlspecialchars($product['Image']) ?>" alt="Product Image">
                    </div>
                <?php endif; ?>

                <!-- ฟอร์มด้านขวา -->
                <form method="post" id="editProductForm">
                    <label>ProductName :</label>
                    <input type="text" name="ProductName" value="<?= htmlspecialchars($product['ProductName']) ?>" required><br>

                    <label>Description :</label>
                    <textarea name="Description" required><?= htmlspecialchars($product['Description']) ?></textarea><br>

                    <label>Price :</label>
                    <input type="number" name="Price" step="0.1" value="<?= htmlspecialchars($product['Price']) ?>" required><br>

                    <label>Stock :</label>
                    <input type="number" name="Stock" value="<?= htmlspecialchars($product['Stock']) ?>" required><br>

                    <label>Status :</label>
                    <select name="Status" required>
                        <option value="Available" <?= $product['Status'] == 'Available' ? 'selected' : '' ?>>Available</option>
                        <option value="Unavailable" <?= $product['Status'] == 'Unavailable' ? 'selected' : '' ?>>Unavailable</option>
                    </select><br>


                    <label>Enter image URL:</label>
                    <input type="text" name="Image" placeholder="Enter image URL" value="<?= htmlspecialchars($product['Image']) ?>" required><br>
                </form>
            </div>

            <!-- ปุ่ม Save  -->
            <div class="edit-product-actions">
                <button type="submit" form="editProductForm">Save</button>
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
