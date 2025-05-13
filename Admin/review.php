

<?php
// เชื่อมต่อกับฐานข้อมูล
$servername = "localhost";
$db_username = "root";   
$db_password = "";
$db_name = "boocha";

$conn = new mysqli($servername, $db_username, $db_password, $db_name);

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

// คำ SQL สำหรับค้นหาข้อมูลในตาราง review โดยมีการกรองจากคำค้นหาใน ReviewID, CustomerID, ProductID, Comment
$sql = "SELECT ReviewID, CustomerID, ProductID, Review_Date, Comment, ReviewImage
        FROM review
        WHERE 
            ReviewID LIKE '%$search%' OR
            CustomerID LIKE '%$search%' OR
            ProductID LIKE '%$search%' OR
            Comment LIKE '%$search%' 
        LIMIT $start_from, $limit";

$result = $conn->query($sql);

// คำ SQL สำหรับนับจำนวนข้อมูลทั้งหมดที่ตรงกับคำค้นหา
$count_sql = "SELECT COUNT(*) AS total 
              FROM review
              WHERE 
                  ReviewID LIKE '%$search%' OR
                  CustomerID LIKE '%$search%' OR
                  ProductID LIKE '%$search%' OR
                  Comment LIKE '%$search%'";

$count_result = $conn->query($count_sql);
$count_row = $count_result->fetch_assoc();
$total_pages = ceil($count_row["total"] / $limit);
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

            <!-- ตารางแสดงข้อมูล Shipment -->
            <div class="customer-table-container">
                <div class="top-row">
                    <h2>Review Management</h2>
                </div>

                <div class="search-insert-row">
                    <form method="get" action="review.php">
                        <div class="search">
                            <label>
                                <input type="text" name="search" placeholder="Search here" value="<?= htmlspecialchars($search) ?>">
                                <ion-icon name="search-outline"></ion-icon>
                            </label>
                        </div>
                    </form>

                    <div class="button-group">
                        <a href="Report.php" class="admin-btn">
                            <ion-icon name="people-circle-outline"></ion-icon>
                            Report Management
                        </a>

                        <a href="RV_insert.php" class="insert-cus-btn">
                            <ion-icon name="add-circle-outline"></ion-icon>
                            Create Review
                        </a>
                    </div>
                </div>

                <table class="customer-table">
                    <thead>
                        <tr>
                            <th>ReviewID</th>
                            <th>CustomerID</th>
                            <th>ProductID</th>
                            <th>Review Date</th>
                            <th>Comment</th>
                            <th>Review Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row["ReviewID"] ?></td>
                                    <td><?= $row["CustomerID"] ?></td>
                                    <td><?= $row["ProductID"] ?></td>
                                    <td><?= $row["Review_Date"] ?></td>
                                    <td><?= $row["Comment"] ?></td>
                                    <td>
                                        <?php if (!empty($row['ReviewImage'])): ?>
                                            <img src="<?= $row['ReviewImage'] ?>" alt="Review Image" style="width: 150px; height: auto;">
                                        <?php else: ?>
                                            <p>No image available</p>
                                        <?php endif; ?>
                                    </td>
                                    <td class="actions">
                                        <div class="action-buttons">
                                            <a href="RV_edit.php?ReviewID=<?= $row['ReviewID'] ?>" class="edit-btn">Edit</a>
                                            <button type="button" class="delete-btn" onclick="openModal('<?= $row['ReviewID'] ?>')">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6">No data found</td></tr>
                        <?php endif; ?>
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
    function openModal(reviewID) {
        console.log('Opening modal for Review ID:', reviewID);
        document.getElementById('deleteReviewID').value = reviewID;
        document.getElementById('deleteModal').style.display = 'block';  // แสดง modal
    }

    function closeModal() {
    document.getElementById('deleteModal').style.display = 'none';
    }

    </script>

    <!-- Modal -->
    <!-- Modal -->
    <div id="deleteModal" class="modal" style="display: none;">
        <div class="modal-content">
            <p>Are you sure you want to delete this review?</p>
            <form method="post" action="RV_delete.php" id="deleteForm">
                <input type="hidden" name="ReviewID" id="deleteReviewID">
                <button type="submit" class="confirm-btn">Yes, Delete</button>
                <button type="button" class="cancel-btn" onclick="closeModal()">Cancel</button>
            </form>
        </div>
    </div>


</body>
</html>