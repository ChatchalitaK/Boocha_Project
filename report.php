<?php 
session_start();  // อย่าลืมเปิด session
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['order'])) {
    echo "No Order ID provided.";
    exit;
}

$orderID = $_GET['order'];
$user_id = $_SESSION['user_id'];

// ตรวจสอบว่ามีรายงานแล้วหรือยัง
$stmt = $conn->prepare("SELECT * FROM report WHERE OrderID = ?");
$stmt->bind_param("s", $orderID);
$stmt->execute();
$reportResult = $stmt->get_result();

if ($_SERVER["REQUEST_METHOD"] === "POST" && $reportResult->num_rows === 0) {
    date_default_timezone_set('Asia/Bangkok');
    
    $reportTopic = $_POST['ReportTopic'];
    $reportExplanation = $_POST['ReportExplanation'];
    $reportImage = $_POST['ReportImage'];
    $reportDate = date('Y-m-d H:i:s');

    if (empty($reportTopic) || empty($reportExplanation)) {
        echo "<p style='color:red;'>Please fill in all required fields.</p>";
    } else {
        $stmt = $conn->prepare("INSERT INTO report (OrderID, ReportDate, ReportTopic, ReportExplanation, ReportImage, ReportStatus)
                                VALUES (?, ?, ?, ?, ?, 'In Progress')");
        $stmt->bind_param("sssss", $orderID, $reportDate, $reportTopic, $reportExplanation, $reportImage);
        $stmt->execute();
        header("Location: report.php?order=" . $orderID);
        exit;
    }
}

// ดึงข้อมูล report ล่าสุด (หากมี)
$reportData = ($reportResult->num_rows > 0) ? $reportResult->fetch_assoc() : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>BOOCHA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link rel="stylesheet" href="review_report.css">
</head>
<body>

    <!-- Header -->
    <section id="header">
        <a href="#"><img src="https://res.cloudinary.com/drcyehkac/image/upload/v1745313675/logo_color_dzdxzc.png" class="logo" alt=""></a>
        <div> 
            <ul id="navbar">
                <li><a class="Available" href="home.php">Home</a></li>
                <li><a href="product.php">Product</a></li>
                <li><a href="about.php">About us</a></li>
                <li><a href="contact.php">Contact us</a></li>
                <li><a href="cart.php"><i class="fa-solid fa-cart-shopping"></i></a></li>
                <li><a href="customer.php"><i class="fa-solid fa-user"></i></a></li>
            </ul>
        </div>
    </section>

    <div class="form-box">
        <h3>Report for Order : <?= htmlspecialchars($orderID) ?></h3>

        <?php if ($reportData): ?>
            <p><strong>Report Topic:</strong> <?= htmlspecialchars($reportData['ReportTopic']) ?></p>
            <p><strong>Explanation:</strong><br><?= nl2br(htmlspecialchars($reportData['ReportExplanation'])) ?></p>
            <?php if (!empty($reportData['ReportImage'])): ?>
                <p><strong>Attached Image:</strong><br><a href="<?= htmlspecialchars($reportData['ReportImage']) ?>" target="_blank">View Image</a></p>
            <?php endif; ?>
            <p><strong>Report Date:</strong> <?= $reportData['ReportDate'] ?></p>
            <p><strong>Status:</strong> 
                <span class="<?= $reportData['ReportStatus'] === 'Accepted' ? 'status-green' : 'status-yellow' ?>">
                    <?= $reportData['ReportStatus'] ?>
                </span>
            </p>
        <?php else: ?>
            <form method="POST">
                <label for="ReportTopic">Report Topic <span style="color:red">*</span></label>
                <select name="ReportTopic" required>
                    <option value="">-- Select Topic --</option>
                    <option value="Damaged Parcel">Damaged Parcel</option>
                    <option value="Wrong Item Received">Wrong Item Received</option>
                    <option value="Not All Item Received">Not All Item Received</option>
                    <option value="Other">Other</option>
                </select>

                <label for="ReportExplanation">Explanation <span style="color:red">*</span></label>
                <textarea name="ReportExplanation" rows="4" required></textarea>

                <label for="ReportImage">Image Link (Cloud-hosted, Optional)</label>
                <input type="text" name="ReportImage" placeholder="e.g. https://res.cloudinary.com/yourimage.jpg">

                <button type="submit" style="margin-top:20px;">Submit Report</button>
            </form>
        <?php endif; ?>
    </div>
    
    <!-- Footer -->
    <footer>
        <div class="col">
            <div class="follow">
                <p>Follow us</p>
                <div class="icon">
                    <i class="fab fa-facebook-f"></i>
                    <i class="fab fa-x-twitter"></i>
                    <i class="fab fa-instagram"></i>
                    <i class="fab fa-pinterest-p"></i>
                    <i class="fab fa-youtube"></i>
                </div>
            </div>
            <p>BOOCHA Bestie for your Best Tea!</p>
        </div>
    </footer>

</body>
</html>