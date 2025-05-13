
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
    $customerID = $_POST['CustomerID'];
    $productID = $_POST['ProductID'];
    $comment = $_POST['Comment'];
    // ตรวจสอบว่า ReviewImage มีค่าหรือไม่ หากไม่มีให้เป็น NULL
    $reviewImage = !empty($_POST['ReviewImage']) ? $_POST['ReviewImage'] : NULL;

    // ตรวจสอบว่า CustomerID และ ProductID มีอยู่จริงหรือไม่
    $checkCustomer = $conn->prepare("SELECT UserID FROM user WHERE UserID = ?");
    $checkCustomer->bind_param("s", $customerID);
    $checkCustomer->execute();
    $checkCustomer->store_result();

    $checkProduct = $conn->prepare("SELECT ProductID FROM product WHERE ProductID = ?");
    $checkProduct->bind_param("s", $productID);
    $checkProduct->execute();
    $checkProduct->store_result();

    if ($checkCustomer->num_rows === 0 || $checkProduct->num_rows === 0) {
        $error_message = "The Customer ID or Product ID does not exist in the system.";
    } else {
        // ดึง ReviewID ล่าสุด
        $result = $conn->query("SELECT ReviewID FROM review ORDER BY ReviewID DESC LIMIT 1");
        if ($result && $row = $result->fetch_assoc()) {
            // ดึงหมายเลขจาก ReviewID ที่มีอยู่
            $lastID = (int)substr($row['ReviewID'], 3);
            $newID = $lastID + 1;
        } else {
            // ถ้าไม่มี ReviewID ใดๆ ก็เริ่มต้นที่ 1
            $newID = 1;
        }

        // สร้าง ReviewID ใหม่ในรูปแบบ "RVW0000001"
        $reviewID = 'RVW' . str_pad($newID, 7, '0', STR_PAD_LEFT);

        $sql = "INSERT INTO review (ReviewID, CustomerID, ProductID, Review_Date, Comment, ReviewImage) 
                VALUES (?, ?, ?, CURRENT_TIMESTAMP, ?, ?)";

        $stmt = $conn->prepare($sql);

        // ตรวจสอบว่า $reviewImage มีค่าหรือไม่ หากไม่มีก็ให้เป็น NULL
        $reviewImage = !empty($_POST['ReviewImage']) ? $_POST['ReviewImage'] : NULL;

        // หาก $reviewImage เป็น NULL, ให้ bind param ที่ไม่มีค่าของ ReviewImage
        if ($reviewImage === NULL) {
            $stmt->bind_param("sssss", $reviewID, $customerID, $productID, $comment, $reviewImage);
        } else {
            // หาก $reviewImage มีค่า ก็ให้ bind param พร้อม ReviewImage
            $stmt->bind_param("sssss", $reviewID, $customerID, $productID, $comment, $reviewImage);
        }

        // ทำการ execute เพื่อบันทึกข้อมูลลงฐานข้อมูล
        if ($stmt->execute()) {
            // ถ้าบันทึกสำเร็จ, redirect ไปยังหน้า review.php พร้อมกับ query string
            header("Location: review.php?created=1");
            exit;
        } else {
            // ถ้าบันทึกไม่สำเร็จ, แสดงข้อความ error
            $error_message = "An error occurred while saving the review: " . $stmt->error;
        }
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
                <img src="assets/imgs/customer01.jpg" alt="">
            </div>
        </div>

        <div class="create-customer">
            <div class="create-customer-header">
                <div class="cardHeader">
                    <h2>Create New Review</h2>
                </div>
            </div>

            <div class="create-customer-content">
                <form method="post" action="" id="createReviewForm">
                    <label>Customer ID :</label>
                    <input type="text" name="CustomerID" value="<?php echo isset($customerID) ? htmlspecialchars($customerID) : ''; ?>" required><br>

                    <label>Product ID :</label>
                    <input type="text" name="ProductID" value="<?php echo isset($productID) ? htmlspecialchars($productID) : ''; ?>" required><br>

                    <label>Comment :</label>
                    <textarea name="Comment" required><?php echo isset($comment) ? htmlspecialchars($comment) : ''; ?></textarea><br>

                    <label>Review Image (URL) :</label>
                    <input type="url" name="ReviewImage" value="<?php echo isset($reviewImage) ? htmlspecialchars($reviewImage) : ''; ?>" placeholder="Optional - Paste image URL here"><br>

                </form>
            </div>

            <div class="create-customer-actions">
                <button type="submit" form="createReviewForm">Create Review</button>
            </div>

            <!-- แสดง error message ถ้ามี -->
            <?php if (isset($error_message)) { ?>
                <p style="color: red;"><?php echo $error_message; ?></p>
            <?php } ?>

            <!-- แสดงข้อความเมื่อบันทึกสำเร็จ -->
            <?php if (isset($_GET['created'])) { ?>
                <p style="color: green;">Review created successfully!</p>
            <?php } ?>
        </div>
    </div>

   <!-- ======== Scripts ======= -->
   <script src="assets/js/main.js"></script>
   <!-- ====== ionicons ====== -->
   <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
   <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
