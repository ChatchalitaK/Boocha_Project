<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "boocha";  // Change to your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get OrderID from URL
if (isset($_GET['OrderID'])) {
    $orderID = $_GET['OrderID'];

    // Get order main information
    $sql_order = "SELECT o.*, p.Payment_Status, s.Shipment_Status 
                  FROM `order` o
                  LEFT JOIN payment p ON o.OrderID = p.OrderID
                  LEFT JOIN shipment s ON o.OrderID = s.OrderID
                  WHERE o.OrderID = ?";
    $stmt = $conn->prepare($sql_order);
    $stmt->bind_param("s", $orderID);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();

    // Get order items
    $sql_items = "SELECT * FROM order_detail WHERE OrderID = ?";
    $stmt = $conn->prepare($sql_items);
    $stmt->bind_param("s", $orderID);
    $stmt->execute();
    $result_items = $stmt->get_result();
    $order_items = [];
    while ($row = $result_items->fetch_assoc()) {
        $order_items[] = $row;
    }
} else {
    // OrderID not found in URL
    echo "OrderID not found.";
    exit;
}

// If form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerID = $_POST['CustomerID'];
    $paymentStatus = $_POST['Payment_Status'];
    $shipmentStatus = $_POST['Shipment_Status'];

    $totalPrice = 0;

    if (isset($_POST['items'])) {
        $productIDs = array_column($_POST['items'], 'ProductID');
        $placeholders = implode(',', array_fill(0, count($productIDs), '?'));
        $types = str_repeat('s', count($productIDs));

        $sql_stock_all = "SELECT ProductID, Stock FROM product WHERE ProductID IN ($placeholders)";
        $stmt = $conn->prepare($sql_stock_all);
        $stmt->bind_param($types, ...$productIDs);
        $stmt->execute();
        $result_stock = $stmt->get_result();

        $stock_data = [];
        while ($row = $result_stock->fetch_assoc()) {
            $stock_data[$row['ProductID']] = $row['Stock'];
        }

        foreach ($_POST['items'] as $item) {
            $productID = $item['ProductID'];
            $quantity = $item['Quantity'];

            if ($quantity > 0) {
                $stock = $stock_data[$productID] ?? 0;

                if ($quantity > $stock) {
                    echo "Quantity for product ($productID) cannot exceed current stock. (Stock left: $stock units)";
                    exit();
                }

                $sql_update_item = "UPDATE order_detail SET Quantity = ? WHERE OrderID = ? AND ProductID = ?";
                $stmt = $conn->prepare($sql_update_item);
                $stmt->bind_param("iss", $quantity, $orderID, $productID);
                $stmt->execute();

                $sql_price = "SELECT Price FROM product WHERE ProductID = ?";
                $stmt = $conn->prepare($sql_price);
                $stmt->bind_param("s", $productID);
                $stmt->execute();
                $result_price = $stmt->get_result();
                $product = $result_price->fetch_assoc();

                if ($product) {
                    $totalPrice += $product['Price'] * $quantity;
                }
            } else {
                echo "Quantity for product $productID must be a positive number.";
                exit();
            }
        }
    } else {
        echo "No product data found in the form.";
        exit();
    }

    // Add shipping fee
    $shippingFee = 80;
    $totalPrice += $shippingFee;

    // Update payment
    $sql_update_payment = "UPDATE payment SET Payment_Status = ?, Total_price = ? WHERE OrderID = ?";
    $stmt = $conn->prepare($sql_update_payment);
    $stmt->bind_param("sss", $paymentStatus, $totalPrice, $orderID);
    $stmt->execute();

    // Update shipment
    $sql_update_shipment = "UPDATE shipment SET Shipment_Status = ? WHERE OrderID = ?";
    $stmt = $conn->prepare($sql_update_shipment);
    $stmt->bind_param("ss", $shipmentStatus, $orderID);
    $stmt->execute();

    // Update order table
    $sql_update_order = "UPDATE `order` SET CustomerID = ? WHERE OrderID = ?";
    $stmt = $conn->prepare($sql_update_order);
    $stmt->bind_param("ss", $customerID, $orderID);
    $stmt->execute();

    // Redirect to order list
    header("Location: order.php");
    exit();
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

        <div class="edit-order">
            <div class="edit-order-header">
                <div class="cardHeader">
                    <h2>Edit Order: <?= htmlspecialchars($order['OrderID']) ?></h2>
                </div>
            </div>

            <div class="edit-order-content">
                <form method="post" id="editOrderForm">
                    <label>Customer ID:</label>
                    <input type="text" name="CustomerID" value="<?= htmlspecialchars($order['CustomerID']) ?>" required><br>

                    <h3>Products in Order</h3>
                    <div id="order-items">
                        <?php foreach ($order_items as $index => $item): ?>
                            <div class="order-item">
                                <label>Product ID:</label>
                                <input type="text" name="items[<?= $index ?>][ProductID]" value="<?= htmlspecialchars($item['ProductID']) ?>" required>

                                <label>Quantity:</label>
                                <input type="number" name="items[<?= $index ?>][Quantity]" value="<?= htmlspecialchars($item['Quantity']) ?>" min="1" required oninput="checkQuantity(this)">

                                <span class="error-message" style="color: red; display: none;">จำนวนสินค้าไม่สามารถมากกว่าคงคลังได้</span>

                                <button class="remove-item" type="button">Remove</button>
                                <hr>
                            </div>

                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="addItem" id="addItem">Add Product</button>
                    <br><br>

                    <label>Payment Status:</label>
                    <select name="Payment_Status">
                        <option value="Pending" <?= $order['Payment_Status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="Paid" <?= $order['Payment_Status'] == 'Paid' ? 'selected' : '' ?>>Paid</option>
                        <option value="Failed" <?= $order['Payment_Status'] == 'Failed' ? 'selected' : '' ?>>Failed</option>
                    </select><br>

                    <label>Shipment Status:</label>
                    <select name="Shipment_Status">
                        <option value="Preparing" <?= $order['Shipment_Status'] == 'Preparing' ? 'selected' : '' ?>>Preparing</option>
                        <option value="Transit" <?= $order['Shipment_Status'] == 'Transit' ? 'selected' : '' ?>>Transit</option>
                        <option value="Delivered" <?= $order['Shipment_Status'] == 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                    </select><br>
                </form>
            </div>

            <div class="edit-order-actions">
                <button type="submit" form="editOrderForm">Save</button>
            </div>
        </div>
    </div>


    <!-- ======== Scripts ======= -->
    <script src="assets/js/main.js"></script>
    <!-- ====== ionicons ====== -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script>
        document.getElementById('addItem').addEventListener('click', function () {
            const container = document.getElementById('order-items');
            const index = container.querySelectorAll('.order-item').length;
            const newItem = document.createElement('div');
            newItem.classList.add('order-item');
            newItem.innerHTML = `
                <label>Product ID:</label>
                <input type="text" name="items[${index}][ProductID]" required>

                <label>Quantity:</label>
                <input type="number" name="items[${index}][Quantity]" min="1" required oninput="checkQuantity(this)">

                <span class="error-message" style="color: red; display: none;">จำนวนสินค้าไม่สามารถมากกว่าคงคลังได้</span>

                <button type="button" class="remove-item">Remove</button>
                <hr>
            `;
            container.appendChild(newItem);
            document.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('remove-item')) {
                e.target.closest('.order-item').remove();
            }
        });
        });

        async function checkQuantity(input) {
            const orderItem = input.closest('.order-item');
            const productID = orderItem.querySelector('[name*="[ProductID]"]').value.trim();
            const quantity = parseInt(input.value);
            const errorMessage = input.nextElementSibling;

            if (!productID || isNaN(quantity)) return;

            try {
            const response = await fetch(`get_stock.php?ProductID=${encodeURIComponent(productID)}`);
            const data = await response.json();
            const stock = data.stock || 0;

            if (quantity > stock) {
                errorMessage.textContent = `Quantity cannot exceed ${stock}.`;
                errorMessage.style.display = 'inline';
                input.setCustomValidity(`Quantity cannot exceed ${stock}.`);
            } else {
                errorMessage.style.display = 'none';
                input.setCustomValidity("");
            }

            } catch (error) {
                console.error("ไม่สามารถดึง stock ได้:", error);
                input.setCustomValidity("");
            }
        }
    </script>


</body>
</html>
