<?php
session_start();
require_once 'config.php';

// ตรวจสอบว่า user login หรือยัง
if (!isset($_SESSION['user_id'])) {
    // ถ้ายังไม่ได้ login, ให้แสดง modal ให้ login ก่อน
    echo "<script>alert('Please log in to add items to your cart.'); window.location.href='index.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT CartID FROM Cart WHERE CustomerID = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($cart_id);

// หากมี CartID ให้เก็บลงใน session
if ($stmt->fetch()) {
    $_SESSION['cart_id'] = $cart_id;
} else {
    echo "No CartID found for this user.";  
}
$stmt->close();

if (isset($_POST['ProductID']) && isset($_POST['quantity'])) {
    $product_id = $_POST['ProductID'];
    $quantity = $_POST['quantity'];
    
    // ตรวจสอบว่า ProductID มีอยู่ในฐานข้อมูลหรือไม่
    $result = $conn->query("SELECT COUNT(*) AS count FROM Product WHERE ProductID = '$product_id'");
    $row = $result->fetch_assoc();
    if ($row['count'] == 0) {
        echo "Product not found.";
        exit;
    }

    // เพิ่มสินค้าลงในตะกร้า
    $stmt = $conn->prepare("INSERT INTO cart_item (CartID, ProductID, Product_quantity, Cartadd_date)
                            VALUES (?, ?, ?, NOW()) 
                            ON DUPLICATE KEY UPDATE 
                            Product_quantity = Product_quantity + VALUES(Product_quantity)");
    $stmt->bind_param("ssi", $cart_id, $product_id, $quantity);
    if ($stmt->execute()) {
        // แสดงข้อความ success เมื่อเพิ่มสินค้าเสร็จ
        $modal_message = "Product added to cart successfully!";
    } else {
        $modal_message = "Error adding product to cart.";
    }
} else {
    $modal_message = "Missing ProductID or quantity.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add to Cart</title>
    <style>
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- ฟอร์ม Add to Cart -->
    <form action="add_to_cart.php" method="post">
        <input type="hidden" name="ProductID" value="<?= htmlspecialchars($product['ProductID']) ?>">

        <div class="quantity-container">
            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" id="quantity" value="1" min="1" max="<?= htmlspecialchars($product['Stock']) ?>">
        </div>

        <button type="submit">Add to cart</button>
    </form>

    <!-- Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2><?= isset($modal_message) ? htmlspecialchars($modal_message) : "" ?></h2>
            <p>You can continue shopping or view your cart.</p>
            <button onclick="window.location.href='cart.php'">View Cart</button>
            <button onclick="window.location.href='product.php'">Continue Shopping</button>
        </div>
    </div>

    <script>
        // Get the modal and the button that opens the modal
        var modal = document.getElementById("myModal");
        var closeBtn = document.getElementsByClassName("close")[0];

        // Show the modal when the page loads if there's a message
        <?php if (isset($modal_message)): ?>
            modal.style.display = "block";
        <?php endif; ?>

        // When the user clicks on <span> (x), close the modal
        closeBtn.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
