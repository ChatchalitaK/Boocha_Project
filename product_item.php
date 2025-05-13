<?php 
session_start();  // อย่าลืมเปิด session
require_once 'config.php';

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    $query = "SELECT * FROM Product WHERE ProductID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
} else {
    echo "Result not found";
    exit;
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
    <link rel="stylesheet" href="product_item.css">
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

<!-- Product Detail -->
<div class="product-container">
    <img class="product-image" src="<?= htmlspecialchars($product['Image']) ?>" alt="<?= htmlspecialchars($product['ProductName']) ?>">

    <div class="product-details">
        <h2><?= htmlspecialchars($product['ProductName']) ?></h2>
        <p><?= nl2br(htmlspecialchars($product['Description'])) ?></p>

        <div class="price-stock">
            <p>Price: <?= htmlspecialchars($product['Price']) ?> THB</p>
            <p>Stock: <?= htmlspecialchars($product['Stock']) ?></p>
        </div>

        <div class="quantity-container">
            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" id="quantity" value="1" min="1" max="<?= htmlspecialchars($product['Stock']) ?>">
            <span id="quantity-error" class="error-message"></span>
        </div>

        <button type="button" class="add-to-cart-btn" onclick="handleAddToCart()">Add to cart</button>
        <button type="button" class="view-review-btn" onclick="viewReview()">View Review</button>
    </div>
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

<!-- ส่งสถานะ login ลงไปใน JS -->
<script>
var isLoggedIn = <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>;
</script>

<script>
function viewReview() {
    const productId = "<?= htmlspecialchars($product['ProductID']) ?>";
    window.location.href = 'view_review.php?id=' + productId;
}
</script>

<!-- Quantity Checking -->
<script>
document.getElementById('quantity').addEventListener('input', function () {
    const input = this;
    const value = parseInt(input.value);
    const min = parseInt(input.min);
    const max = parseInt(input.max);
    const errorMsg = document.getElementById('quantity-error');

    if (value < min) {
        errorMsg.textContent = "Minimum quantity is " + min ;
        input.value = min;
    } else if (value > max) {
        errorMsg.textContent = "Only " + max + " item(s) in stock";
        input.value = max;
    } else {
        errorMsg.textContent = "";
    }
});
</script>

<!-- Handle Add to Cart -->
<!-- Modal -->
<div id="successModal" class="modal" style="display:none;">
  <div class="modal-content">
    <h3>Item added to cart!</h3>
    <div class="button-group">
      <button onclick="location.href='cart.php'">View Cart</button>
      <button onclick="closeModal()">Continue Shopping</button>
    </div>
  </div>
</div>

<style>
.modal {
  position: fixed;
  z-index: 1000;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background: rgba(0,0,0,0.6);
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-content {
  background: #F2DEC2;
  padding: 40px 50px;
  border-radius: 16px;
  text-align: center;
  width: 550px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.button-group {
  margin-top: 20px; 
}

.button-group button {
  margin: 10px;
  padding: 10px 20px;
  border: none;
  border-radius: 8px;
  font-size: 16px;
  cursor: pointer;
  color: #455A20;
}

.button-group button:first-child {
  background-color: #DEC89E; 
  color: #1E140B;
  font-weight: 600;
}

.button-group button:last-child {
  background-color: #BEC39E; 
  color: #1E140B;
  font-weight: 600;
}
</style>

<script>
function handleAddToCart() {
    const quantity = document.getElementById('quantity').value;
    const productId = "<?= htmlspecialchars($product['ProductID']) ?>";

    if (!isLoggedIn) {
        window.location.href = 'index.php';
    } else {
        const formData = new FormData();
        formData.append('ProductID', productId);
        formData.append('quantity', quantity);

        fetch('add_to_cart.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            document.getElementById('successModal').style.display = 'flex';
            console.log(data);
        })
        .catch(error => console.error('Error:', error));
    }
}

function closeModal() {
    document.getElementById('successModal').style.display = 'none';
}
</script>

<!--Test-->
<!--<form action="logout.php" method="post" style="display: inline;">
    <button type="submit" style="
        background-color: #f44336;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
    ">Logout</button>
</form>-->

</body>
</html>
