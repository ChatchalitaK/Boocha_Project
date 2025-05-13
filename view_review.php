<?php
require_once 'config.php';
session_start();

if (!isset($_GET['id'])) {
    echo "No product ID provided.";
    exit;
}
$product_id = $_GET['id'];

$query = "
    SELECT r.*, c.Username 
    FROM review r 
    JOIN user c ON r.CustomerID = c.UserID 
    WHERE r.ProductID = ? 
    ORDER BY r.Review_Date DESC
";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $product_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>BOOCHA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link rel="stylesheet" href="view_review.css">
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

    <h3>Reviews</h3>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="review-box">
                <div class="review-left">
                    <div class="review-header">
                        <div class="review-username"><strong><?= htmlspecialchars($row['Username']) ?></strong></div>
                        <div class="review-date"><?= htmlspecialchars($row['Review_Date']) ?></div>
                    </div>
                    <div class="review-content"><?= htmlspecialchars($row['Comment']) ?></div>
                </div>
                <div class="review-image">
                    <?php if (!empty($row['ReviewImage'])): ?>
                        <img src="<?= htmlspecialchars($row['ReviewImage']) ?>" alt="Review Image">
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align: center; font-size: 18px; margin-top: 40px; color: #666; font-weight: 500;">
            No reviews yet. Be the first to share your thoughts!
        </p>
    <?php endif; ?>

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
