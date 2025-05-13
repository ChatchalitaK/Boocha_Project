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

// รับค่า ReportID จาก URL
if (isset($_GET['ReportID'])) {
    $reportID = $_GET['ReportID'];
    $sql = "SELECT * FROM report WHERE ReportID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $reportID);
    $stmt->execute();
    $result = $stmt->get_result();
    $report = $result->fetch_assoc();
} else {
    echo "ไม่พบรหัสรายงาน";
    exit;
}

// อัปเดตรายงาน
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $topic = $_POST['ReportTopic'];
    $explanation = $_POST['ReportExplanation'];
    $image = $_POST['ReportImage'];
    $status = $_POST['ReportStatus'];

    $update_sql = "UPDATE report SET ReportTopic=?, ReportExplanation=?, ReportImage=?, ReportStatus=?, ReportDate=NOW() WHERE ReportID=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssss", $topic, $explanation, $image, $status, $reportID);

    if ($stmt->execute()) {
        header("Location: report.php?updated=1");
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
                    <h2>Edit Report : <?= htmlspecialchars($report['ReportID']) ?></h2>
                </div>
            </div>

            <div class="edit-product-content">
                <?php if ($report['ReportImage']): ?>
                    <div class="image-preview-side">
                        <img src="<?= htmlspecialchars($report['ReportImage']) ?>" alt="Report Image">
                    </div>
                <?php endif; ?>

                <form method="post" id="editReportForm">
                    <label>Order ID :</label>
                    <input type="text" value="<?= htmlspecialchars($report['OrderID']) ?>" readonly><br>

                    <label>Report Topic :</label>
                    <input type="text" name="ReportTopic" value="<?= htmlspecialchars($report['ReportTopic']) ?>" required><br>

                    <label>Report Explanation :</label>
                    <textarea name="ReportExplanation" required><?= htmlspecialchars($report['ReportExplanation']) ?></textarea><br>

                    <label>Image URL :</label>
                    <input type="text" name="ReportImage" value="<?= htmlspecialchars($report['ReportImage']) ?>" required><br>

                    <label>Status :</label>
                    <select name="ReportStatus" required>
                        <option value="Pending" <?= $report['ReportStatus'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="Accepted" <?= $report['ReportStatus'] == 'Accepted' ? 'selected' : '' ?>>Accepted</option>
                    </select><br>
                </form>
            </div>

            <div class="edit-product-actions">
                <button type="submit" form="editReportForm">Save</button>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/main.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>