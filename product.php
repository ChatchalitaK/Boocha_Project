<?php 
session_start();
require_once 'config.php';

// Build the base query
$query = "SELECT 
    Product.ProductID,
    Product.ProductName,
    Product.Price,
    Product.Status,
    Product.Image,
    Tea_Type.TeaTypeName,
    Province.ProvinceName,
    RoastLevel.RoastLevelName,
    Grade.GradeName
FROM Product
JOIN Tea_Type ON Product.TeaTypeID = Tea_Type.TeaTypeID
JOIN Province ON Product.ProvinceID = Province.ProvinceID
JOIN RoastLevel ON Product.RoastLevelID = RoastLevel.RoastLevelID
JOIN Grade ON Product.GradeID = Grade.GradeID
WHERE Product.Status = 'Available'";

// Distinc value
// ดึงข้อมูล Tea Types (unique)
$teaTypeQuery = "SELECT DISTINCT TeaTypeName FROM Tea_Type";
$teaTypeResult = $conn->query($teaTypeQuery);

// ดึงข้อมูล Provinces (unique)
$provinceQuery = "SELECT DISTINCT ProvinceName FROM Province";
$provinceResult = $conn->query($provinceQuery);

// ดึงข้อมูล Roast Levels (unique)
$roastLevelQuery = "SELECT DISTINCT RoastLevelName FROM RoastLevel";
$roastLevelResult = $conn->query($roastLevelQuery);

// ดึงข้อมูล Grades (unique)
$gradeQuery = "SELECT DISTINCT GradeName FROM Grade";
$gradeResult = $conn->query($gradeQuery);

// Receive values from the filter and search
$teaType = isset($_GET['teaType']) ? $_GET['teaType'] : [];
$province = isset($_GET['province']) ? $_GET['province'] : [];
$roastLevel = isset($_GET['roastLevel']) ? $_GET['roastLevel'] : [];
$grade = isset($_GET['grade']) ? $_GET['grade'] : [];
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';

$filterConditions = [];
$whereClauses = [];
$params = [];
$types = '';

if (isset($_GET['teaType']) && !empty($_GET['teaType'])) {
    $typeConditions = [];
    foreach ($_GET['teaType'] as $type) {
        // ป้องกัน SQL injection ด้วยการ escape หรือใช้ prepared statement ถ้าจำเป็น
        $typeConditions[] = "Tea_Type.TeaTypeName = '" . mysqli_real_escape_string($conn, $type) . "'";
    }

    if (!empty($typeConditions)) {
        $whereClauses[] = '(' . implode(' OR ', $typeConditions) . ')';
    }
}

if (isset($_GET['province']) && !empty($_GET['province'])) {
    $provConditions = [];
    foreach ($_GET['province'] as $prov) {
        // Escape input เพื่อความปลอดภัย ถ้าไม่ได้ใช้ prepared statement
        $provConditions[] = "Province.ProvinceName = '" . mysqli_real_escape_string($conn, $prov) . "'";
    }

    if (!empty($provConditions)) {
        $whereClauses[] = '(' . implode(' OR ', $provConditions) . ')';
    }
}

if (isset($_GET['roastLevel']) && !empty($_GET['roastLevel'])) {
    $roastConditions = [];
    foreach ($_GET['roastLevel'] as $roast) {
        $roastConditions[] = "RoastLevel.RoastLevelName = '" . mysqli_real_escape_string($conn, $roast) . "'";
    }

    if (!empty($roastConditions)) {
        $whereClauses[] = '(' . implode(' OR ', $roastConditions) . ')';
    }
}

if (isset($_GET['grade']) && !empty($_GET['grade'])) {
    $gradeConditions = [];
    foreach ((array) $_GET['grade'] as $gradeValue) {
        $gradeConditions[] = "Grade.GradeName = '" . mysqli_real_escape_string($conn, $gradeValue) . "'";
    }

    if (!empty($gradeConditions)) {
        $whereClauses[] = '(' . implode(' OR ', $gradeConditions) . ')';
    }
}

// Add all filters
if (!empty($whereClauses)) {
    $query .= ' AND ' . implode(' AND ', $whereClauses);
}

// Add search condition if search term exists
if ($search) {
    $filterConditions[] = "ProductName LIKE ?";
    $params[] = '%' . $search . '%';
    $types .= 's'; // 's' represents string type for binding
}

// Combine all conditions to the query
if (!empty($filterConditions)) {
    $query .= " AND (" . implode(' OR ', $filterConditions) . ")";
}

// Sorting
if ($sort === 'asc') {
    $query .= " ORDER BY Price ASC";
} elseif ($sort === 'desc') {
    $query .= " ORDER BY Price DESC";
}

// --- Pagination Config ---
$limit = 12;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// ต่อ query ด้วย LIMIT (ใช้ placeholder สำหรับ prepared statement)
$query .= " LIMIT ?, ?";

// เพิ่ม type สำหรับ integer ของ LIMIT และ OFFSET
$types .= "ii";
$params[] = $start;
$params[] = $limit;

// Prepare statement
$stmt = $conn->prepare($query);

if ($stmt) {
    // Bind parameters dynamically
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    // Execute
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch products
    if ($result->num_rows > 0) {
        $products = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $products = [];
        echo "<div class='no-results'>Result not found</div>";
    }

    $stmt->close();
} else {
    echo "Error preparing statement: " . $conn->error;
}

// ---------- Total Page Calculation ----------
$countQuery = preg_replace('/ORDER BY .*?(ASC|DESC)\s*/i', '', $query);
$countQuery = preg_replace('/LIMIT\s+\?,\s+\?$/', '', $countQuery);

$countStmt = $conn->prepare("SELECT COUNT(*) AS total FROM ($countQuery) AS temp");
if ($countStmt) {
    // ตัด 2 ตัวสุดท้ายที่เป็น LIMIT ออกจาก $params และ $types
    $countTypes = substr($types, 0, -2);
    $countParams = array_slice($params, 0, -2);

    if (!empty($countParams)) {
        $countStmt->bind_param($countTypes, ...$countParams);
    }

    $countStmt->execute();
    $countResult = $countStmt->get_result()->fetch_assoc();
    $totalPages = ceil($countResult['total'] / $limit);
    $countStmt->close();
} else {
    $totalPages = 1;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE-edge">
	<meta name="viewpoint" content="width=device-width, initial-scale=1.0">
	<title>BOOCHA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link rel="stylesheet" href="product.css">
</head>
<body>
    <section id="header">
        <a href="#"><img src="https://res.cloudinary.com/drcyehkac/image/upload/v1745313675/logo_color_dzdxzc.png" class="logo" alt=""></a>

        <div> 
            <ul id="navbar">
                <li><a class="Available" href="home.php">Home</a></li>
                <li><a href="product.php">Product</a></li>
                <li><a href="about.php">About us</a></li>
                <li><a href="contact.php">Contact us</a></li>
                <li><a href="cart.php"><i class="fa-solid fa-cart-shopping"></i></a></li>
                <li><a href="customer.php"><i class="fa-solid fa-user"></i></i></a></li>
            </ul>
        </div>
    </section>

    <div class="container">
    <div class="filter-box">
        <form method="get" id="filterForm">
            <h4>Tea Type</h4>
            <?php while ($row = $teaTypeResult->fetch_assoc()): ?>
                <label><input type="checkbox" name="teaType[]" value="<?= $row['TeaTypeName'] ?>" <?= in_array($row['TeaTypeName'], $teaType) ? 'checked' : '' ?>> <?= $row['TeaTypeName'] ?></label>
            <?php endwhile; ?>

            <h4>Province</h4>
            <?php while ($row = $provinceResult->fetch_assoc()): ?>
                <label><input type="checkbox" name="province[]" value="<?= $row['ProvinceName'] ?>" <?= in_array($row['ProvinceName'], $province) ? 'checked' : '' ?>> <?= $row['ProvinceName'] ?></label>
            <?php endwhile; ?>

            <h4>Roast Level</h4>
            <?php while ($row = $roastLevelResult->fetch_assoc()): ?>
                <label><input type="checkbox" name="roastLevel[]" value="<?= $row['RoastLevelName'] ?>" <?= in_array($row['RoastLevelName'], $roastLevel) ? 'checked' : '' ?>> <?= $row['RoastLevelName'] ?></label>
            <?php endwhile; ?>

            <h4>Grade</h4>
            <?php while ($row = $gradeResult->fetch_assoc()): ?>
                <label><input type="checkbox" name="grade[]" value="<?= $row['GradeName'] ?>" <?= in_array($row['GradeName'], $grade) ? 'checked' : '' ?>> <?= $row['GradeName'] ?></label>
            <?php endwhile; ?>

            <!-- Button ถูกลบไปแล้วเพราะไม่จำเป็น <button type="submit">Apply Filters</button> -->
        </form>

    </div>

        <div class="product-display">
        <div class="search-sort">
            <form method="get" id="filterForm">
                <input type="text" name="search" placeholder="Search" value="<?= htmlspecialchars($search) ?>" />
                
                <!-- Maintain selected filters when sorting or searching -->
                <?php foreach ($teaType as $type): ?>
                    <input type="hidden" name="teaType[]" value="<?= htmlspecialchars($type) ?>">
                <?php endforeach; ?>
                <?php foreach ($province as $prov): ?>
                    <input type="hidden" name="province[]" value="<?= htmlspecialchars($prov) ?>">
                <?php endforeach; ?>
                <?php foreach ($roastLevel as $roast): ?>
                    <input type="hidden" name="roastLevel[]" value="<?= htmlspecialchars($roast) ?>">
                <?php endforeach; ?>
                <?php foreach ($grade as $g): ?>
                    <input type="hidden" name="grade[]" value="<?= htmlspecialchars($g) ?>">
                <?php endforeach; ?>

                <button type="submit" name="sort" value="<?= (isset($_GET['sort']) && $_GET['sort'] == 'asc') ? '' : 'asc' ?>" class="<?= (isset($_GET['sort']) && $_GET['sort'] == 'asc') ? 'active' : '' ?>">Price Ascending</button>
                <button type="submit" name="sort" value="<?= (isset($_GET['sort']) && $_GET['sort'] == 'desc') ? '' : 'desc' ?>" class="<?= (isset($_GET['sort']) && $_GET['sort'] == 'desc') ? 'active' : '' ?>">Price Descending</button>
            </form>
        </div>

            <div class="product-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <img src="<?php echo htmlspecialchars($product['Image']); ?>" alt="<?php echo htmlspecialchars($product['ProductName']); ?>">
                        <h3>
                            <a href="product_item.php?id=<?= $product['ProductID'] ?>">
                                <?= htmlspecialchars($product['ProductName']) ?>
                            </a>
                        </h3>
                        <p><?php echo number_format($product['Price'], 2); ?> THB</p>
                        <!--<a href="add_to_cart.php?id=<?php echo $product['ProductID']; ?>" class="add-to-cart">
                            <i class="fa-solid fa-cart-shopping"></i> 
                        </a>-->

                    </div>
                <?php endforeach; ?>
            </div>
            <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php
                // สร้าง query string จากค่าฟิลเตอร์
                $queryParams = $_GET;
                unset($queryParams['page']); // ไม่เอา page เดิมไปด้วย
                $queryString = http_build_query($queryParams);

                if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>&<?= $queryString ?>" class="btn">Previous</a>
                <?php endif; ?>

                <?php for ($i = max(1, $page - 3); $i <= min($totalPages, $page + 3); $i++): ?>
                    <a href="?page=<?= $i ?>&<?= $queryString ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?>&<?= $queryString ?>" class="btn">Next</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
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
    <script src="script.js"></script>
    <script>
    // ดักการเปลี่ยนแปลง checkbox แล้วส่งฟอร์ม
    document.querySelectorAll('#filterForm input[type="checkbox"]').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
        });
    });
    </script>
</body>
</html>
