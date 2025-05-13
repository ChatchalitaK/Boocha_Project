
<?php
// เชื่อมต่อกับฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "boocha";  // เปลี่ยนให้เป็นชื่อฐานข้อมูลของคุณ

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// เช็คการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบค่าคำค้นหาจาก URL (ถ้ามี)
$search = isset($_GET['search']) ? $_GET['search'] : '';

// กำหนดจำนวนข้อมูลที่จะแสดงในแต่ละหน้า
$limit = 10;

// ตรวจสอบค่าหน้า (ถ้าไม่มีจะให้เป็นหน้าแรก)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1); // ป้องกันค่าหน้าเป็นค่าติดลบ

// คำนวณจุดเริ่มต้นของข้อมูลที่จะแสดงในแต่ละหน้า
$start_from = ($page - 1) * $limit;

$sql = "
SELECT 
    o.OrderID,
    o.CustomerID,
    o.OrderDate,
    od.ProductID,
    od.Quantity,
    p.Total_price,
    p.Payment_Status, 
    s.Shipment_Status, 
    pc.product_count
FROM (
    SELECT o.OrderID
    FROM `order` o
    WHERE o.OrderID LIKE '%$search%' OR o.CustomerID LIKE '%$search%'
    ORDER BY o.OrderID
    LIMIT $start_from, $limit
) AS limited_orders
JOIN `order` o ON o.OrderID = limited_orders.OrderID
JOIN order_detail od ON o.OrderID = od.OrderID
LEFT JOIN payment p ON o.OrderID = p.OrderID
LEFT JOIN shipment s ON o.OrderID = s.OrderID
LEFT JOIN (
    SELECT OrderID, COUNT(*) AS product_count
    FROM order_detail
    GROUP BY OrderID
) pc ON o.OrderID = pc.OrderID
ORDER BY o.OrderID
";

$result = $conn->query($sql);

// รวมจำนวนแถวทั้งหมดสำหรับ pagination
$total_result = $conn->query("
    SELECT COUNT(DISTINCT o.OrderID) AS total
    FROM `order` o
    WHERE o.OrderID LIKE '%$search%' OR o.CustomerID LIKE '%$search%'
")->fetch_assoc();

$total_pages = ceil($total_result["total"] / $limit);
if ($total_pages == 0) $total_pages = 1; // กันกรณีไม่มีข้อมูล แต่ยังให้แสดงหน้าเดียว

if ($page > $total_pages) {
    header("Location: ?page=" . $total_pages . "&search=" . urlencode($search));
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- ======== Styles ======== -->
    <link rel="stylesheet" href="assets/css/style1.css">
     <!-- เพิ่ม Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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

            <!-- ตารางแสดงข้อมูล order-->
            <div class="order-table-container">
                <div class="top-row">
                    <h2>Orders Management</h2>
                </div>

                <div class="search-insert-row">
                    <form method="get" action="order.php">
                        <div class="search">
                            <label>
                                <input type="text" name="search" placeholder="Search here" value="<?= htmlspecialchars($search) ?>">
                                <ion-icon name="search-outline"></ion-icon>
                            </label>
                        </div>
                    </form>

                    <a href="OD_insert.php" class="insert-btn">
                        <ion-icon name="add-circle-outline"></ion-icon>
                        Create Order
                    </a>

                </div>

                <table class="order-table">
                    <thead>
                        <tr>
                            <th>OrderID</th>
                            <th>CustomerID</th>
                            <th>Order_date</th>
                            <th>ProductID</th>
                            <th>Quantity</th>
                            <th>TotalPrice</th>
                            <th>Payment Status</th>
                            <th>Shipment Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        if ($result && $result->num_rows > 0) {
                            $prev_order_id = null;
                            while($row = $result->fetch_assoc()) {
                                // หาก OrderID เปลี่ยนแปลง แสดงข้อมูลหลัก
                                if ($prev_order_id != $row["OrderID"]) {
                                    echo "<tr>";
                                    echo "<td rowspan='" . $row["product_count"] . "' class='centered'>" . $row["OrderID"] . "</td>";
                                    echo "<td rowspan='" . $row["product_count"] . "' class='centered'>" . $row["CustomerID"] . "</td>";
                                    echo "<td rowspan='" . $row["product_count"] . "' class='centered'>" . $row["OrderDate"] . "</td>";
                                    echo "<td class='centered'>" . $row["ProductID"] . "</td>";
                                    echo "<td class='centered'>" . $row["Quantity"] . "</td>";
                                    echo "<td rowspan='" . $row["product_count"] . "' class='centered'>" . $row["Total_price"] . "</td>";
                                    echo "<td rowspan='" . $row["product_count"] . "' class='centered'>" . $row["Payment_Status"] . "</td>";
                                    echo "<td rowspan='" . $row["product_count"] . "' class='centered'>" . $row["Shipment_Status"] . "</td>";
                                    echo "<td rowspan='" . $row["product_count"] . "' class='actions'>
                                            <div class='action-buttons'>
                                                <a href='OD_edit.php?OrderID=" . $row['OrderID'] . "' class='edit-btn'>Edit</a>
                                                <button type='button' class='delete-btn' onclick='openModal(\"" . $row['OrderID'] . "\")'>Delete</button>
                                            </div>
                                        </td>";
                                    echo "</tr>";
                                } else {
                                    // เฉพาะแถว ProductID และ Quantity สำหรับแถวถัดไป
                                    echo "<tr>";
                                    echo "<td class='centered'>" . $row["ProductID"] . "</td>";
                                    echo "<td class='centered'>" . $row["Quantity"] . "</td>";
                                    echo "</tr>";
                                }
                                $prev_order_id = $row["OrderID"];
                            }
                        } else {
                            echo "<tr><td colspan='9'>No data found</td></tr>";
                        }
                        ?>
                    </tbody>

                
                </table>

                <!-- Pagination -->
                <div class="pagination">
                    <?php
                    $adjacents = 2;
                    $searchParam = '&search=' . urlencode($search);

                    if ($page > 1) {
                        echo '<a href="?page=' . ($page - 1) . $searchParam . '">&laquo;</a>';
                    }

                    if ($total_pages <= 7 + ($adjacents * 2)) {
                        for ($i = 1; $i <= $total_pages; $i++) {
                            echo '<a href="?page=' . $i . $searchParam . '" class="' . ($i == $page ? 'active' : '') . '">' . $i . '</a>';
                        }
                    } else {
                        if ($page < 1 + ($adjacents * 2)) {
                            for ($i = 1; $i < 4 + ($adjacents * 2); $i++) {
                                echo '<a href="?page=' . $i . $searchParam . '" class="' . ($i == $page ? 'active' : '') . '">' . $i . '</a>';
                            }
                            echo '<span>...</span>';
                            echo '<a href="?page=' . $total_pages . $searchParam . '">' . $total_pages . '</a>';
                        } elseif ($page > ($total_pages - ($adjacents * 2))) {
                            echo '<a href="?page=1' . $searchParam . '">1</a>';
                            echo '<span>...</span>';
                            for ($i = $total_pages - (2 + ($adjacents * 2)); $i <= $total_pages; $i++) {
                                echo '<a href="?page=' . $i . $searchParam . '" class="' . ($i == $page ? 'active' : '') . '">' . $i . '</a>';
                            }
                        } else {
                            echo '<a href="?page=1' . $searchParam . '">1</a>';
                            echo '<span>...</span>';
                            for ($i = $page - $adjacents; $i <= $page + $adjacents; $i++) {
                                echo '<a href="?page=' . $i . $searchParam . '" class="' . ($i == $page ? 'active' : '') . '">' . $i . '</a>';
                            }
                            echo '<span>...</span>';
                            echo '<a href="?page=' . $total_pages . $searchParam . '">' . $total_pages . '</a>';
                        }
                    }

                    if ($page < $total_pages) {
                        echo '<a href="?page=' . ($page + 1) . $searchParam . '">&raquo;</a>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

   <!-- ======== Scripts ======= -->
   <script src="assets/js/main.js"></script>
   <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
   <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
   <script>
    function openModal(orderID) {
        document.getElementById('deleteOrderID').value = orderID;
        document.getElementById('deleteModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }
    </script>

     <!-- Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <p>Are you sure you want to delete this order?</p>
            <form method="post" action="OD_delete.php" id="deleteForm">
                <input type="hidden" name="OrderID" id="deleteOrderID">
                <button type="submit" class="confirm-btn">Yes, Delete</button>
                <button type="button" class="cancel-btn" onclick="closeModal()">Cancel</button>
            </form>
        </div>
    </div>

</body>
</html>