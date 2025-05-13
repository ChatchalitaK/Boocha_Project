<?php
// เชื่อมต่อกับฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "boocha";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// เช็คการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// รับค่า ReviewID จาก URL
if (isset($_GET['ReviewID'])) {
    $reviewID = $_GET['ReviewID'];
    $sql = "SELECT * FROM review WHERE ReviewID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $reviewID);
    $stmt->execute();
    $result = $stmt->get_result();
    $review = $result->fetch_assoc();
} else {
    echo "ไม่พบรหัสรีวิว";
    exit;
}

// อัปเดตรายงาน
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $comment = $_POST['Comment'];
    $image = $_POST['ReviewImage'];

    $update_sql = "UPDATE review SET Comment=?, ReviewImage=?, Review_Date=NOW() WHERE ReviewID=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sss", $comment, $image, $reviewID);

    if ($stmt->execute()) {
        header("Location: review.php?updated=1");
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
                    <h2>Edit Review : <?= htmlspecialchars($review['ReviewID']) ?></h2>
                </div>
            </div>

            <div class="edit-product-content">
                <?php if ($review['ReviewImage']): ?>
                    <div class="image-preview-side">
                        <img src="<?= htmlspecialchars($review['ReviewImage']) ?>" alt="Review Image">
                    </div>
                <?php endif; ?>

                <form method="post" id="editReviewForm">
                    <label>Customer ID :</label>
                    <input type="text" value="<?= htmlspecialchars($review['CustomerID']) ?>" readonly><br>

                    <label>Product ID :</label>
                    <input type="text" value="<?= htmlspecialchars($review['ProductID']) ?>" readonly><br>

                    <label>Review Comment :</label>
                    <textarea name="Comment" required><?= htmlspecialchars($review['Comment']) ?></textarea><br>

                    <label>Image URL :</label>
                    <input type="text" name="ReviewImage" value="<?= htmlspecialchars($review['ReviewImage']) ?>" required><br>

                </form>
            </div>

            <div class="edit-product-actions">
                <button type="submit" form="editReviewForm">Save</button>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/main.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>