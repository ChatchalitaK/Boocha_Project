<?php 
session_start();  
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$customerID = $_SESSION['user_id'] ?? null;
$productID = $_GET['product'] ?? null;
$reviewID = $_GET['reviewid'] ?? null;
$orderDetailID = $_GET['orderdetailid'] ?? null;

if (!$customerID || !$productID || !$orderDetailID) {
    echo "Missing parameters.";
    exit;
}

// ดึงชื่อสินค้า
$sql = "SELECT ProductName FROM product WHERE ProductID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $productID);
$stmt->execute();
$productResult = $stmt->get_result();
$product = $productResult->fetch_assoc();
$productName = $product['ProductName'] ?? 'Unknown Product';

if ($reviewID) {
    $sql = "SELECT * FROM review WHERE ReviewID = ? AND CustomerID = ? AND OrderDetailID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $reviewID, $customerID, $orderDetailID);
    $stmt->execute();
    $result = $stmt->get_result();
    $existingReview = $result->fetch_assoc();

    if (!$existingReview) {
        echo "Review not found.";
        exit;
    }

    $mode = 'edit';
    $comment = $existingReview['Comment'] ?? '';
    $image = $existingReview['ReviewImage'] ?? '';
} else {
    // ตรวจสอบว่ามีรีวิวของ orderdetailid นี้แล้วหรือยัง
    $sql = "SELECT * FROM review WHERE OrderDetailID = ? AND CustomerID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $orderDetailID, $customerID);
    $stmt->execute();
    $checkResult = $stmt->get_result();
    if ($checkResult->num_rows > 0) {
        $existingReview = $checkResult->fetch_assoc();
        $mode = 'edit';
        $reviewID = $existingReview['ReviewID'];
        $comment = $existingReview['Comment'] ?? '';
        $image = $existingReview['ReviewImage'] ?? '';
    } else {
        $mode = 'new';
        $comment = '';
        $image = '';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment = $_POST['comment'][$productID] ?? '';  
    $image = $_POST['image'][$productID] ?? '';  
    $hiddenReviewID = $_POST['reviewid'][$productID] ?? null;  
    $orderDetailID = $_POST['orderdetailid'] ?? null;

    if ($hiddenReviewID) {
        // UPDATE
        $sql = "UPDATE review SET Comment = ?, ReviewImage = ?, Review_Date = NOW() 
                WHERE ReviewID = ? AND CustomerID = ? AND OrderDetailID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $comment, $image, $hiddenReviewID, $customerID, $orderDetailID);
        $stmt->execute();
        header("Location: check_status.php");
        exit;
    } else {
        // INSERT
        $sql = "INSERT INTO review (CustomerID, ProductID, OrderDetailID, Review_Date, Comment, ReviewImage)
                VALUES (?, ?, ?, NOW(), ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $customerID, $productID, $orderDetailID, $comment, $image);
        $stmt->execute();
        $newReviewID = $conn->insert_id;
        header("Location: check_status.php");
        exit;
    }
}
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

    <div id="review">
        <div class="review-box">
            <h3><?= htmlspecialchars($productName) ?></h3>

            <form method="POST">
                <label>Comment</label>
                <textarea name="comment[<?= $productID ?>]" required><?= htmlspecialchars($comment) ?></textarea><br>

                <label>Image Link (Cloud-hosted, Optional)</label>
                <input type="url" name="image[<?= $productID ?>]" placeholder="e.g. https://res.cloudinary.com/yourimage.jpg" value="<?= htmlspecialchars($image) ?>" maxlength="225">

                <?php if ($image): ?>
                    <img src="<?= htmlspecialchars($image) ?>" alt="Review Image" class="review-image">
                <?php endif; ?>

                <input type="hidden" name="reviewid[<?= $productID ?>]" value="<?= htmlspecialchars($reviewID) ?>">
                <input type="hidden" name="orderdetailid" value="<?= htmlspecialchars($orderDetailID) ?>">
                <button type="submit" name="submit[<?= $productID ?>]"><?= $mode === 'edit' ? 'Save' : 'Post' ?></button>
                <?php if ($mode === 'edit'): ?>
                    <button type="button" name="delete" value="<?= $productID ?>" 
                        class="delete-button" 
                        onclick="showDeleteModal('<?= $reviewID ?>', '<?= $orderDetailID ?>')">Delete</button>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal" style="display: none;">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <h3>Are you sure you want to delete this item?</h3>
        <div class="button-group">
        <button id="cancelDeleteBtn" class="cancelDeleteBtn">Cancel</button>
        <button id="confirmDeleteBtn" class="delete-button">Yes, Delete</button>
        </div>
    </div>
    </div>

    <script>
    function showDeleteModal(reviewID, orderDetailID) {
        // Store the reviewID and orderDetailID globally to use later
        window.reviewIDToDelete = reviewID;
        window.orderDetailIDToDelete = orderDetailID;
        document.getElementById('deleteModal').style.display = 'flex';
    }

    document.getElementById('cancelDeleteBtn').onclick = function() {
        document.getElementById('deleteModal').style.display = 'none';
    };

    // When 'Yes, Delete' is clicked, send the delete request to the server
    document.getElementById('confirmDeleteBtn').onclick = function() {
        // Send AJAX request to delete the review
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'delete_review.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status == 200) {
                // On success, close the modal and redirect to the check_status page
                document.getElementById('deleteModal').style.display = 'none';
                window.location.href = 'check_status.php'; // or you can refresh the page if needed
            } else {
                alert('Error deleting review.');
            }
        };

        // Send the reviewID and orderDetailID to the server
        xhr.send('reviewID=' + window.reviewIDToDelete + '&orderDetailID=' + window.orderDetailIDToDelete);
    };
    </script>


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
