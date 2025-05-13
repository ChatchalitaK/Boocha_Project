<?php
// เชื่อมต่อกับฐานข้อมูล
$servername = "localhost";
$db_username = "root";   // เปลี่ยนจาก $username เป็น $db_username
$db_password = "";
$db_name = "boocha";

try {
    // สร้างการเชื่อมต่อ
    $conn = new mysqli($servername, $db_username, $db_password, $db_name);

    // เช็คการเชื่อมต่อ
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit();
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

// ใช้ prepared statements เพื่อหลีกเลี่ยง SQL Injection
$stmt = $conn->prepare("SELECT UserID, Username, Firstname, Lastname, Tel_number, Email, latest_update 
                        FROM user 
                        WHERE role IN ('Admin')
                        AND (
                            UserID LIKE ? OR
                            Username LIKE ? OR
                            Firstname LIKE ? OR
                            Lastname LIKE ? OR
                            Tel_number LIKE ? OR
                            Email LIKE ?
                        )
                        LIMIT ?, ?");
$search_param = "%$search%";
$stmt->bind_param("ssssssii", $search_param, $search_param, $search_param, $search_param, $search_param, $search_param, $start_from, $limit);
$stmt->execute();
$result = $stmt->get_result();

// คำนวณจำนวนหน้าทั้งหมด
$count_stmt = $conn->prepare("SELECT COUNT(*) AS total 
                              FROM user 
                              WHERE role IN ('Admin')
                              AND (
                                  UserID LIKE ? OR
                                  Username LIKE ? OR
                                  Firstname LIKE ? OR
                                  Lastname LIKE ? OR
                                  Tel_number LIKE ? OR
                                  Email LIKE ?
                              )");
$count_stmt->bind_param("ssssss", $search_param, $search_param, $search_param, $search_param, $search_param, $search_param);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
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
                    <span class="title">Customers</span>
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
                    <h2>Administrator Management</h2>
                </div>

                <div class="search-insert-row">
                    <form method="get" action="admin.php">
                        <div class="search">
                            <label>
                                <input type="text" name="search" placeholder="Search here" value="<?= htmlspecialchars($search) ?>">
                                <ion-icon name="search-outline"></ion-icon>
                            </label>
                        </div>
                    </form>

                    <div class="button-group">
                        <a href="customer.php" class="customer-btn">
                            <ion-icon name="people-circle-outline"></ion-icon>
                            Customer Management
                        </a>

                        <a href="AM_insert.php" class="insert-cus-btn">
                            <ion-icon name="add-circle-outline"></ion-icon>
                            Create Admin account
                        </a>
                    </div>
                </div>


                <table class="customer-table">
                    <thead>
                        <tr>
                            <th>UserID</th>
                            <th>Username</th>
                            <th>Firstname</th>
                            <th>Lastname</th>
                            <th>Tel. Number</th>
                            <th>Email</th>
                            <th>Latest update</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row["UserID"] ?></td>
                                    <td><?= $row["Username"] ?></td>
                                    <td><?= $row["Firstname"] ?></td>
                                    <td><?= $row["Lastname"] ?></td>
                                    <td><?= $row["Tel_number"] ?></td>
                                    <td><?= $row["Email"] ?></td>
                                    <td><?= $row["latest_update"] ?></td>
                                    <td class="actions">
                                        <div class="action-buttons">
                                            <a href="AM_edit.php?UserID=<?= $row['UserID'] ?>" class="edit-btn">Edit</a>
                                            
                                            <button type="button" class="delete-btn" onclick="openModal('<?= $row['UserID'] ?>')">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="8">No data found</td></tr>
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
    function openModal(userID) {
        document.getElementById('deleteUserID').value = userID;
        document.getElementById('deleteModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }
    </script>

     <!-- Modal -->
     <div id="deleteModal" class="modal">
    <div class="modal-content">
        <p>Are you sure you want to delete this user?</p>
        <form method="post" action="AM_delete.php" id="deleteForm">
            <input type="hidden" name="UserID" id="deleteUserID">
            <button type="submit" class="confirm-btn">Yes, Delete</button>
            <button type="button" class="cancel-btn" onclick="closeModal()">Cancel</button>
        </form>
    </div>
    </div>       

</body>
</html>