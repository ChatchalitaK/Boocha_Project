<?php
session_start();
require_once 'config.php';

// ตรวจสอบว่าล็อกอินแล้วและมี cart_id
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$cart_item_ids = isset($_POST['cart_item_ids']) ? $_POST['cart_item_ids'] : [];

if (empty($cart_item_ids)) {
    echo "No items selected.";
    exit;
}

$placeholders = implode(',', array_fill(0, count($cart_item_ids), '?'));
$types = str_repeat('s', count($cart_item_ids)); // assuming Cart_itemID is string (change to 'i' if integer)

$query = "
    SELECT ci.Cart_itemID, ci.ProductID, ci.Product_quantity, p.ProductName, p.Price
    FROM cart_item ci
    JOIN Product p ON ci.ProductID = p.ProductID
    WHERE ci.Cart_itemID IN ($placeholders)
";
$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$cart_item_ids);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = $result->fetch_all(MYSQLI_ASSOC);

// คำนวณราคารวม
$total_price = 0;
foreach ($cart_items as $item) {
    $item_total = $item['Product_quantity'] * $item['Price'];
    $total_price += $item_total;
}

// ค่าส่ง
$shipping_fee = 80;
$grand_total = $total_price + $shipping_fee;

$showModal = false;

if (isset($_SESSION['order_success'])) {
    $showModal = true;
    unset($_SESSION['order_success']); // ใช้ครั้งเดียว ล้างเลย
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
<link rel="stylesheet" href="order.css">
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

    <div class="order-box">
        <h2>Order Receipt</h2>

        <?php foreach ($cart_items as $item): ?>
            <div class="order-row">
                <div><?= htmlspecialchars($item['ProductName']) ?> × <?= $item['Product_quantity'] ?></div>
                <div><?= number_format($item['Product_quantity'] * $item['Price'], 2) ?> THB</div>
            </div>
        <?php endforeach; ?>

        <div class="order-row" style="margin-top: 20px;">
            <div>Shipping Fee</div>
            <div><?= number_format($shipping_fee, 2) ?> THB</div>
        </div>

        <div class="order-total order-row">
            <div>Total Price</div>
            <div><?= number_format($grand_total, 2) ?> THB</div>
        </div>
    </div>

    <div class="note-box">
        <h3>Add Note (Optional)</h3>
        <textarea name="order_note" id="order-note" maxlength="225" placeholder="Enter any note or instruction here..."></textarea>
    </div>

    <div class="payment-method-box">
        <h3>Payment Method</h3>
        <form id="order-form" method="POST">
            <div class="payment-method">
                <!-- ดึงข้อมูลจาก payment method -->
                <select name="payment_method" id="payment-method" required>
                    <option value="" disabled selected>Select Payment Method</option>
                    <?php
                    // ดึงข้อมูลวิธีการชำระเงินจากตาราง Payment
                    $payment_query = "SELECT DISTINCT Payment_method FROM payment";
                    $payment_result = $conn->query($payment_query);
                    while ($row = $payment_result->fetch_assoc()) {
                        echo "<option value='" . $row['Payment_method'] . "'>" . $row['Payment_method'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- ส่ง cart item ID ทั้งหมด -->
            <?php foreach ($cart_items as $item): ?>
                <input type="hidden" name="cart_item_ids[]" value="<?= $item['Cart_itemID'] ?>">
            <?php endforeach; ?>
            <input type="hidden" name="total_price" value="<?= $grand_total ?>">
            
            <!-- Error message -->
            <span id="payment-error" class="error-message" style="color: red; display: none;">Please select a payment method before placing the order.</span>
        </form>
    </div>

    <div class="order-button">
        <button type="button" id="submit-order">Order</button>
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

    <script>
    document.getElementById('submit-order').addEventListener('click', async function(event) {
        event.preventDefault();  // หยุดการส่งฟอร์มและไม่เปลี่ยนหน้า

        const paymentMethod = document.getElementById('payment-method').value;
        const paymentError = document.getElementById('payment-error');
        const note = document.getElementById('order-note').value;

        // เช็คว่า paymentMethod ไม่ว่าง
        if (!paymentMethod) {
            paymentError.style.display = 'block';
            return;
        } else {
            paymentError.style.display = 'none';
        }

        const form = document.getElementById('order-form');
        const formData = new FormData(form);
        formData.append('order_note', note); // เพิ่ม note

        try {
            // ส่งข้อมูลด้วย fetch ไปยัง submit_order.php
            const response = await fetch('submit_order.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.text();
            console.log(result); // เช็คดูว่าได้อะไรจาก submit_order.php

            if (result.trim() === 'success') {
                // แสดง modal ถ้าสั่งซื้อสำเร็จ
                document.getElementById('orderSuccessModal').style.display = 'flex';
            } else {
                // ถ้ามีข้อผิดพลาดจะโชว์ alert
                alert('Order failed: ' + result);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while placing the order.');
        }
    });
    </script>

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

    <div id="orderSuccessModal" class="modal" style="display:none;">
    <div class="modal-content">
        <h3>Ordered Successfully!</h3>
        <div class="button-group">
        <button onclick="redirectToCart()">Close</button>
        </div>
    </div>
    </div>

    <script>
    function redirectToCart() {
        window.location.href = 'cart.php';
    }

    function showOrderSuccessModal() {
        document.getElementById('orderSuccessModal').style.display = 'flex';
    }

    // ถ้า PHP แจ้งว่าควรแสดง modal
    <?php if ($showModal): ?>
        window.onload = function() {
            showOrderSuccessModal();
        };
    <?php endif; ?>
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $('#orderForm').on('submit', function(e) {
    e.preventDefault(); // หยุดไม่ให้ form รีโหลดหน้า

    $.ajax({
        url: 'submit_order.php',
        method: 'POST',
        data: $(this).serialize(), // ส่งค่าทั้งหมดใน form
        success: function(response) {
        $('#orderSuccessModal').show(); // แสดง modal
        },
        error: function() {
        alert('เกิดข้อผิดพลาดในการสั่งซื้อ');
        }
    });
    });
    </script>

</body>
</html>
