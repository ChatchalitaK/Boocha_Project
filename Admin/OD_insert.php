

<?php
// เชื่อมต่อกับฐานข้อมูล
$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "boocha";

$conn = new mysqli($servername, $db_username, $db_password, $db_name);

$conn->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ถ้าเป็นการบันทึกข้อมูลจริง (Submit Form)
// การบันทึกข้อมูลการสั่งซื้อใหม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerID = $_POST['CustomerID'];
    $productIDs = $_POST['ProductIDs']; // array
    $quantities = $_POST['Quantities']; // array
    $paymentMethod = $_POST['PaymentMethod'];

    if (empty($customerID) || empty($productIDs) || empty($quantities)) {
        $error_message = "Please fill in all fields.";
    } else {
        $paymentStatus = 'Pending';
        $shipmentStatus = 'Preparing';
        $totalAmount = 0;

        // Start a transaction
        $conn->begin_transaction();

        try {
            // 1. Insert into orders table (OrderID จะถูกสร้างจาก Trigger)
            $sql_order = "INSERT INTO `order` (CustomerID, OrderDate) VALUES (?, NOW())";
            $stmt_order = $conn->prepare($sql_order);
            $stmt_order->bind_param("s", $customerID);
            if ($stmt_order->execute()) {
                // ดึงค่า OrderID โดยการ query ใหม่หลังจากการ insert
                $sql_get_orderID = "SELECT OrderID FROM `order` WHERE CustomerID = ? ORDER BY OrderDate DESC LIMIT 1";
                $stmt_get_orderID = $conn->prepare($sql_get_orderID);
                $stmt_get_orderID->bind_param("s", $customerID);
                $stmt_get_orderID->execute();
                $result = $stmt_get_orderID->get_result();
            
                if ($row = $result->fetch_assoc()) {
                    $orderID = $row['OrderID'];
                } else {
                    throw new Exception("Failed to get OrderID after insert.");
                }
            } else {
                throw new Exception("Execute failed for order: " . $stmt_order->error);
            }

            // ตรวจสอบ stock ก่อนการ insert ลงใน order_detail
            foreach ($productIDs as $index => $productID) {
                $quantity = $quantities[$index];

                // ดึงราคาของสินค้าและตรวจสอบ stock
                $product_sql = "SELECT Price, Stock FROM product WHERE ProductID = ?";
                $product_stmt = $conn->prepare($product_sql);
                $product_stmt->bind_param("s", $productID);
                $product_stmt->execute();
                $product_result = $product_stmt->get_result();

                if ($product_row = $product_result->fetch_assoc()) {
                    $price = $product_row['Price'];
                    $stock = $product_row['Stock'];

                    // ตรวจสอบจำนวนสินค้าที่สั่งซื้อไม่ให้เกินจำนวนที่มีในคลัง
                    if ($quantity > $stock) {
                        echo "จำนวนสินค้า ($productID) ไม่สามารถมากกว่าคงคลังได้ (คงคลังเหลือ $stock ชิ้น)";
                        exit();
                    }

                    // คำนวณราคาสินค้า
                    $subtotal = $price * $quantity;
                    $totalAmount += $subtotal;

                    // Insert into order_detail using $orderID
                    $sql_detail = "INSERT INTO order_detail (OrderID, ProductID, Quantity, Total_productPrice) 
                                VALUES (?, ?, ?, ?)";
                    $stmt_detail = $conn->prepare($sql_detail);
                    $stmt_detail->bind_param("ssid", $orderID, $productID, $quantity, $subtotal);
                    $stmt_detail->execute();
                } else {
                    throw new Exception("Product not found: " . $productID);
                }
            }

            // เพิ่มค่าจัดส่ง 80 บาท
            $totalAmount += 80;

            // 3. Insert into payment table
            $sql_payment = "INSERT INTO payment (OrderID, Total_price, Payment_Status, Payment_Method) VALUES (?, ?, ?, ?)";
            $stmt_payment = $conn->prepare($sql_payment);
            $stmt_payment->bind_param("sdss", $orderID, $totalAmount, $paymentStatus, $paymentMethod);  // Binding PaymentMethod
            $stmt_payment->execute();


            // 4. Insert into shipment table
            $sql_shipment = "INSERT INTO shipment (OrderID, Shipment_Status) VALUES (?, ?)";
            $stmt_shipment = $conn->prepare($sql_shipment);
            $stmt_shipment->bind_param("ss", $orderID, $shipmentStatus);
            $stmt_shipment->execute();

            // Commit transaction
            $conn->commit();

            header("Location: order.php?created=1");
            exit;
        } catch (Exception $e) {
            $conn->rollback();
            $error_message = "An error occurred. Please try again. " . $e->getMessage();
            echo $error_message;
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Order</title>
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
                    <h2>Create New Order</h2>
                </div>
            </div>

            <div class="create-order-content">
                <?php if (!empty($error_message)): ?>
                    <div class="error"><?= htmlspecialchars($error_message) ?></div>
                <?php endif; ?>

                <form method="post" id="createOrderForm">
                    <label>Customer ID:</label>
                    <input type="text" name="CustomerID" required><br>

                    <h3>Products in Order</h3>
                    <div id="order-items">
                        <div class="order-item">
                            <label>Product ID:</label>
                            <input type="text" name="ProductIDs[]" required>

                            <label>Quantity:</label>
                            <input type="number" name="Quantities[]" min="1" required oninput="checkQuantity(this)">
                            <button type="button" class="remove-item">Remove</button>
                            <br><br>
                            <hr>
                        </div>
                    </div><br>
                    <button type="button" class="addItem" id="addItem">Add Product</button>
                    <br><br>
                    
                    <!-- Payment Method -->
                    <label>Payment Method :</label>
                    <select name="PaymentMethod" required>
                        <option value="">-- Select Payment Method --</option>
                        <option value="Mobile Banking">Mobile Banking</option>
                        <option value="Credit/Debit Card">Credit/Debit Card</option>
                    </select><br>

                </form>

            <div class="create-customer-actions">
                <button type="submit" form="createOrderForm">Create Order</button>
            </div>
        </div>
    </div>

   <script src="assets/js/main.js"></script>
   <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
   <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
   <script>
        document.getElementById('addItem').addEventListener('click', function () {
            const container = document.getElementById('order-items');
            const newItem = document.createElement('div');
            newItem.classList.add('order-item');
            newItem.innerHTML = `
                <label>Product ID:</label>
                <input type="text" name="ProductIDs[]" required>

                <label>Quantity:</label>
                <input type="number" name="Quantities[]" min="1" required oninput="checkQuantity(this)">

                <span class="error-message" style="color: red; display: none;"></span> <!-- error message -->

                <button type="button" class="remove-item">Remove</button>
                <hr>
            `;
            container.appendChild(newItem);

            // ลบ item
            document.addEventListener('click', function (e) {
                if (e.target && e.target.classList.contains('remove-item')) {
                    e.target.closest('.order-item').remove();
                }
            });
        });

        // ตรวจสอบ quantity โดยใช้ AJAX
        async function checkQuantity(input) {
            const orderItem = input.closest('.order-item');
            const productID = orderItem.querySelector('[name="ProductIDs[]"]').value.trim();
            const quantity = parseInt(input.value);
            const errorMessage = orderItem.querySelector('.error-message'); 

            if (!productID || isNaN(quantity)) {
                errorMessage.style.display = 'none';
                return;
            }

            try {
                // ส่งคำขอ AJAX เพื่อตรวจสอบ stock
                const response = await fetch(`get_stock.php?ProductID=${encodeURIComponent(productID)}`);
                
                if (!response.ok) {
                    throw new Error("Network response was not ok.");
                }

                const data = await response.json();

                if (data && typeof data.stock !== 'undefined') {
                    const stock = data.stock || 0;

                    if (quantity > stock) {
                        errorMessage.textContent = `จำนวนสินค้าไม่สามารถมากกว่า ${stock} ได้`;
                        errorMessage.style.display = 'inline';
                        input.setCustomValidity(`จำนวนสินค้าไม่สามารถมากกว่า ${stock} ได้`);
                    } else {
                        errorMessage.style.display = 'none';
                        input.setCustomValidity(""); 
                    }
                } else {
                    throw new Error("Stock data is missing or invalid.");
                }

            } catch (error) {
                console.error("Error fetching stock:", error);
                errorMessage.textContent = "เกิดข้อผิดพลาดในการดึงข้อมูล stock";
                errorMessage.style.display = 'inline';
                input.setCustomValidity("เกิดข้อผิดพลาดในการดึงข้อมูล stock");
            }
        }

        // ส่งข้อมูลคำสั่งซื้อ
        document.getElementById('submitOrder').addEventListener('click', async function () {
            const form = document.getElementById('orderForm');
            const formData = new FormData(form);

            try {
                const response = await fetch('order_save.php', {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error("Network response was not ok.");
                }

                const result = await response.json();
                console.log('Order saved:', result);

            } catch (error) {
                console.error("Error saving order:", error);
            }
        });ห
    </script>



   
</body>
</html>
