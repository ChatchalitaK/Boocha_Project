

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

// สร้างคำ SQL สำหรับค้นหาข้อมูลในตาราง product โดยมีการกรองจากคำค้นหาใน ProductName, DetailID, และ ProductID
$sql = "SELECT * FROM product WHERE ProductName LIKE '%$search%' OR ProductID LIKE '%$search%' LIMIT $start_from, $limit";
$result = $conn->query($sql);

// คำนวณจำนวนหน้าทั้งหมด โดยนับจากการค้นหาใน ProductName, DetailID และ ProductID
$total_result = $conn->query("SELECT COUNT(*) AS total FROM product WHERE ProductName LIKE '%$search%' OR ProductID LIKE '%$search%'")->fetch_assoc();
$total_pages = ceil($total_result["total"] / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- ======== Styles ======== -->
     <link rel="stylesheet" href="assets/css/style1.css">
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

        <!-- ตารางแสดงข้อมูลสินค้า -->
        <div class="product-table-container">
            <div class="top-row">
                <h2>Product Management</h2>
            </div>

            <div class="search-insert-row">
                <form method="get" action="product.php">
                    <div class="search">
                        <label>
                            <input type="text" name="search" placeholder="Search here" value="<?= htmlspecialchars($search) ?>">
                            <ion-icon name="search-outline"></ion-icon>
                        </label>
                    </div>
                </form>

                <a href="PD_insert.php" class="insert-btn">
                    <ion-icon name="add-circle-outline"></ion-icon>
                    Create Product
                </a>

            </div>

            <table class="product-table">
                <thead>
                    <tr>
                         <th>Image</th>
                        <th>ProductID</th>
                        <th>ProductName</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>update_date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <?php
                                $description = $row["Description"];
                                $shortDescription = substr($description, 0, 50) . (strlen($description) > 50 ? '...' : '');
                            ?>
                            <tr>
                                <td>
                                    <a href="<?= htmlspecialchars($row["Image"]) ?>" target="_blank">
                                        <img src="<?= htmlspecialchars($row["Image"]) ?>" alt="Product Image" style="max-width: 130px; max-height: 130px; object-fit: cover;">
                                    </a>
                                </td>
                                <td><?= $row["ProductID"] ?></td>
                                <td><?= $row["ProductName"] ?></td>
                                <td><?= $row["Description"] ?></td>
                                <td><?= $row["Price"] ?></td>
                                <td><?= $row["Stock"] ?></td>
                                <td><?= $row["Status"] ?></td>
                                <td><?= $row["update_date"] ?></td>
                                <td class="actions">
                                   <div class="action-buttons">
                                       <a href="PD_edit.php?ProductID=<?= $row['ProductID'] ?>" class="edit-btn">Edit</a>
                                       <button type="button" class="delete-btn" onclick="openModal('<?= $row['ProductID'] ?>')">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="9">No data found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="pagination">
                <?php
                $adjacents = 2; // จำนวนหน้าที่แสดงข้างๆ หน้าปัจจุบัน
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
    </div> <!-- ปิด main -->

   <!-- ======== Scripts ======= -->
   <script src="assets/js/main.js"></script>

   <!-- ====== ionicons ====== -->
   <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
   <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
   <script>
    function openModal(ProductID) {
        document.getElementById('deleteProductID').value = ProductID;
        document.getElementById('deleteModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }
    </script>

     <!-- Modal -->
    <div id="deleteModal" class="modal">
    <div class="modal-content">
        <p>Are you sure you want to delete this product?</p>
        <form method="post" action="PD_delete.php" id="deleteForm">
            <input type="hidden" name="ProductID" id="deleteProductID">
            <button type="submit" class="confirm-btn">Yes, Delete</button>
            <button type="button" class="cancel-btn" onclick="closeModal()">Cancel</button>
        </form>
    </div>
    </div>


</body>
</html>
