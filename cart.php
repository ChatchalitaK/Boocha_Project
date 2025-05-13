<?php
session_start();  // อย่าลืมเปิด session
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT CartID FROM Cart WHERE CustomerID = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($cart_id);
$stmt->fetch();  // ดึง CartID ออกมา
$stmt->close();  // ปิด statement ตัวแรก

// Join cart_item กับ Product
$stmt = $conn->prepare("
    SELECT ci.Cart_itemID, ci.ProductID, ci.Product_quantity, p.ProductName, p.Price, p.Image, p.Stock
    FROM cart_item ci
    JOIN Product p ON ci.ProductID = p.ProductID
    WHERE ci.CartID = ?
");
$stmt->bind_param("s", $cart_id);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = $result->fetch_all(MYSQLI_ASSOC);

// ถ้าผู้ใช้ส่งคำขอแก้ไขจำนวนสินค้า
if (isset($_POST['edit_cart'])) {
    $cart_item_id = $_POST['cart_item_id'];
    $new_quantity = $_POST['new_quantity'];

    // ตรวจสอบว่า new_quantity เป็นค่าที่ถูกต้อง (ต้องเป็นตัวเลขที่มากกว่า 0)
    if ($new_quantity > 0) {
        // อัปเดตจำนวนสินค้าตาม cart_item_id
        $stmt = $conn->prepare("UPDATE cart_item SET Product_quantity = ? WHERE Cart_itemID = ?");
        $stmt->bind_param("ii", $new_quantity, $cart_item_id);
        if ($stmt->execute()) {
            echo "Quantity updated successfully.";
        } else {
            echo "Error updating quantity.";
        }
    } else {
        echo "Invalid quantity.";
    }
}

// ถ้าผู้ใช้ส่งคำขอลบสินค้าจากตะกร้า
if (isset($_POST['delete_cart'])) {
    $cart_item_id = $_POST['cart_item_id'];

    // ลบสินค้าจากตะกร้าโดยใช้ cart_item_id
    $stmt = $conn->prepare("DELETE FROM cart_item WHERE Cart_itemID = ?");
    $stmt->bind_param("i", $cart_item_id);
    if ($stmt->execute()) {
        echo "Item deleted successfully.";
    } else {
        echo "Error deleting item.";
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
<link rel="stylesheet" href="cart.css">
<script src="cart.js"></script>
</head>

<body>
    <section id="header">
        <a href="#"><img src="https://res.cloudinary.com/drcyehkac/image/upload/v1745313675/logo_color_dzdxzc.png" class="logo" alt=""></a>

        <div> 
            <ul id="navbar">
                <li><a class="active" href="home.php">Home</a></li>
                <li><a href="product.php">Product</a></li>
                <li><a href="about.php">About us</a></li>
                <li><a href="contact.php">Contact us</a></li>
                <li><a href="cart.php"><i class="fa-solid fa-cart-shopping"></i></a></li>
                <li><a href="customer.php"><i class="fa-solid fa-user"></i></a></li>
            </ul>
        </div>
    </section>

    <div class="choose-all">
        <input type="checkbox" id="chooseAll">
        <label for="chooseAll">Choose All</label>
    </div>

    <div class="cart-container">
    <?php if (empty($cart_items)): ?>
        <div class="empty-cart-message" style="text-align:center; padding: 50px 0; font-size: 1.2rem;">
            No items in your cart
        </div>
    <?php else: ?>
        <?php foreach ($cart_items as $item): ?>
            <div class="cart-item">
                <?php $disabled = $item['Product_quantity'] > $item['Stock'] ? 'disabled' : ''; ?>
                <input 
                    type="checkbox" 
                    class="item-checkbox" 
                    value="<?= $item['Cart_itemID'] ?>"
                    data-price="<?= number_format($item['Price'] * $item['Product_quantity'], 2, '.', '') ?>"
                    <?= $disabled ?>>
                <img src="<?= htmlspecialchars($item['Image']) ?>" alt="<?= htmlspecialchars($item['ProductName']) ?>">
                
                <div class="item-details">
                    <h3><?= htmlspecialchars($item['ProductName']) ?></h3>
                    <p>Quantity: <span class="item-quantity"><?= $item['Product_quantity'] ?></span></p>
                    <p>In Stock: <span class="item-stock"><?= $item['Stock'] ?></span></p>
                    <button type="number" class="edit-btn" data-id="<?= $item['Cart_itemID'] ?>" data-quantity="<?= $item['Product_quantity'] ?>">Edit</button>
                    <button class="delete-btn" data-id="<?= $item['Cart_itemID'] ?>">Delete</button>
                </div>

                <div class="item-price">
                    <p class="unit-price">Unit Price: <?= number_format($item['Price'], 2) ?> THB</p>
                    <p class="total-item-price">Total Price: <?= number_format($item['Price'] * $item['Product_quantity'], 2) ?> THB</p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    </div>
    
    <form id="orderForm" action="order.php" method="POST">
        <div class="cart-footer">
            <div class="total-price">
                Total: <span id="totalAmount">0.00 THB</span>
            </div>
            <button id="buyBtn" type="submit">Buy</button>

            <div id="errorMessage" style="color:red; font-size: 14px; margin-top: 10px;"></div>
            <div id="hiddenItems"></div>
        </div>
    </form>

    <script>
    document.getElementById('buyBtn').addEventListener('click', function (event) {
        const checkboxes = document.querySelectorAll('.item-checkbox:checked');
        const errorMessage = document.getElementById('errorMessage');  // Get the error message div
        const hiddenItemsContainer = document.getElementById('hiddenItems');  // Container for hidden inputs

        // Clear previous hidden inputs
        hiddenItemsContainer.innerHTML = '';

        // Clear the error message first
        errorMessage.textContent = '';

        // If no item is selected, show the error message
        if (checkboxes.length === 0) {
            errorMessage.textContent = "Please select at least one item before proceeding to buy.";
            event.preventDefault();  // Prevent form submission
        } else {
            // If items are selected, prepare hidden inputs to pass in the form
            checkboxes.forEach(checkbox => {
                const cartItemId = checkbox.value;
                
                // Create hidden input for each selected item
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'cart_item_ids[]'; // This should match the PHP variable name
                input.value = cartItemId;
                hiddenItemsContainer.appendChild(input);
            });
        }
    });

    // Hide the error message when a checkbox is selected
    document.querySelectorAll('.item-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const errorMessage = document.getElementById('errorMessage');
            // If any item is selected, hide the error message
            if (document.querySelectorAll('.item-checkbox:checked').length > 0) {
                errorMessage.textContent = ''; // Hide error message
            }
        });
    });
    </script>

    <!-- JavaScript จัดการเรื่องเลือก checkbox กับยอดรวม -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const chooseAll = document.getElementById('chooseAll');
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const totalAmount = document.getElementById('totalAmount');

        // ฟังก์ชันคำนวณยอดรวม
        function calculateTotal() {
            let total = 0;
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    total += parseFloat(checkbox.dataset.price);
                }
            });
            totalAmount.textContent = total.toFixed(2) + " THB";
        }
        
        window.calculateTotal = calculateTotal;

        // เวลาเช็กหรืออันเช็ก checkbox แต่ละอัน
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', calculateTotal);
        });

        // เวลาเช็ก/อันเช็ก "เลือกทั้งหมด"
        chooseAll.addEventListener('change', function() {
            const isChecked = chooseAll.checked;
            checkboxes.forEach(checkbox => {
                const quantity = parseInt(checkbox.dataset.quantity);
                const stock = parseInt(checkbox.dataset.stock);

                if (checkbox.disabled || quantity > stock) {
                    checkbox.checked = false;
                } else {
                    checkbox.checked = isChecked;
                }
            });
            calculateTotal();
        });

        // อัปเดตสถานะ checkbox ทุกครั้งที่ quantity เปลี่ยน
        const quantityInputs = document.querySelectorAll('.quantity-input');

        quantityInputs.forEach(input => {
            input.addEventListener('input', function() {
                const item = input.closest('.item');
                const checkbox = item.querySelector('.item-checkbox');
                const newQuantity = parseInt(input.value);
                const stock = parseInt(checkbox.dataset.stock);

                checkbox.dataset.quantity = newQuantity; // อัปเดต dataset

                if (newQuantity > stock) {
                    checkbox.disabled = true;
                    checkbox.checked = false;
                } else {
                    checkbox.disabled = false;
                }

                calculateTotal(); // คำนวณยอดรวมใหม่
            });
        });
    });
    </script>

    <!-- Edit Modal -->
    <div id="editModal" class="modal" style="display:none;">
    <div class="modal-content">
        <h3>Edit Quantity</h3>
        <div>
        <label for="editQuantity">Quantity:</label>
        <input type="number" id="editQuantity" name="editQuantity" min="1"/>
        <span id="quantityError" style="color:red; font-size: 14px;"></span>
        </div>
        <div class="button-group">
        <button id="cancelEditBtn" class="cancelEditBtn">Cancel</button>
        <button type="button" id="saveEditBtn">Save Changes</button>
        </div>
    </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal" style="display:none;">
    <div class="modal-content">
        <h3>Are you sure you want to delete this item?</h3>
        <div class="button-group">
        <button id="cancelDeleteBtn" class="cancelDeleteBtn">Cancel</button>
        <button id="confirmDeleteBtn">Yes, Delete</button>
        </div>
    </div>
    </div>

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
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.item-checkbox');

    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const cartItem = this.closest('.cart-item');
            if (this.checked) {
                cartItem.classList.add('selected');
            } else {
                cartItem.classList.remove('selected');
            }
        });
    });
});
</script>

<script>
document.getElementById('editQuantity').addEventListener('input', function () {
    const input = this;
    let value = parseInt(input.value);
    const min = parseInt(input.min);
    const max = parseInt(input.max);
    const errorMsg = document.getElementById('quantityError');
    const stockInfo = document.getElementById('stockInfo');

    if (isNaN(value)) {
        input.value = min;
        errorMsg.textContent = "Minimum quantity is " + min;
        stockInfo.innerHTML = "In Stock: <span>" + max + "</span>";
        return;
    }

    if (value < min) {
        input.value = min;
        errorMsg.textContent = "Minimum quantity is " + min;
        stockInfo.innerHTML = "In Stock: <span>" + max + "</span>";
    } else if (value > max) {
        input.value = max;
        errorMsg.textContent = "Only " + max + " item(s) in stock";
        stockInfo.innerHTML = "In Stock: <span>" + max + "</span>";
    } else {
        errorMsg.textContent = "";
        stockInfo.innerHTML = "";
    }
});
</script>

</html>
