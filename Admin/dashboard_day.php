
<?php include 'auto_delete_old_unpaid_orders.php'; ?>

<?php
// เริ่มต้นการเชื่อมต่อกับฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "boocha";  // ชื่อฐานข้อมูล

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// เช็คการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ====== ดึงจำนวนคำสั่งซื้อ (Sales) วันนี้
$sql = "SELECT COUNT(*) AS total_sales FROM `order` WHERE DATE(orderdate) = CURDATE()";
$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $total_sales = $row['total_sales'] ?? 0;
} else {
    $total_sales = 0;
}

// ====== ดึงยอด Earning วันนี้ จาก order + payment
$sql = "
    SELECT SUM(p.total_price) AS earnings
    FROM `order` o
    JOIN payment p ON o.orderID = p.orderID
    WHERE DATE(o.orderdate) = CURDATE()
";
$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $earnings = $row['earnings'] ? "฿" . number_format($row['earnings'], 2) : "$0.00";
} else {
    $earnings = "$0.00";
}

// ====== ตรวจสอบว่ามี order วันนี้หรือไม่
$sql_check_order = "SELECT COUNT(*) AS cnt FROM `order` WHERE DATE(orderdate) = CURDATE()";
$result_check = $conn->query($sql_check_order);
$row_check = $result_check->fetch_assoc();
$order_today_exists = $row_check['cnt'] > 0;

// ====== ดึงข้อมูล Best Selling Tea
$sql_best_tea = "
    SELECT tt.TeaTypeName
    FROM product p
    JOIN tea_type tt ON p.TeaTypeID = tt.TeaTypeID
    WHERE p.ProductID = (
        SELECT p.ProductID
        FROM order_detail od
        JOIN `order` o ON o.orderID = od.orderID
        WHERE DATE(o.orderdate) = CURDATE()
        GROUP BY p.ProductID
        ORDER BY SUM(od.Quantity) DESC
        LIMIT 1
    )
";
$result_best_tea = $conn->query($sql_best_tea);
if ($result_best_tea && $row_best_tea = $result_best_tea->fetch_assoc()) {
    $best_tea = $row_best_tea['TeaTypeName'] ?? "ไม่มีข้อมูล";
} else {
    $best_tea = "ไม่มีข้อมูล";
}

// ====== หาชาประเภทที่ขายได้น้อยที่สุด (ถ้ามีคำสั่งซื้อวันนี้)
if ($order_today_exists) {
    $sql_least_tea = "
        SELECT tt.TeaTypeName, IFNULL(SUM(od.Quantity), 0) AS total_sold
        FROM tea_type tt
        JOIN product p ON p.TeaTypeID = tt.TeaTypeID
        LEFT JOIN order_detail od ON p.ProductID = od.ProductID
        LEFT JOIN `order` o ON od.OrderID = o.OrderID AND DATE(o.orderdate) = CURDATE()
        GROUP BY tt.TeaTypeID, tt.TeaTypeName
        ORDER BY total_sold ASC
        LIMIT 1
    ";

    $result_least_tea = $conn->query($sql_least_tea);
    if ($result_least_tea && $result_least_tea->num_rows > 0) {
        $row_least_tea = $result_least_tea->fetch_assoc();
        $least_tea = $row_least_tea['TeaTypeName'] ?? "ไม่มีข้อมูล";
    } else {
        $least_tea = "ไม่มีข้อมูล";
    }
} else {
    // ไม่มี order วันนี้
    $least_tea = "ไม่มีข้อมูล";
}

// ====== ดึงข้อมูล Best Sales Province
$sql_best_province = "
   SELECT pr.ProvinceName, SUM(od.Total_productPrice) AS total_sales
    FROM order_detail od
    JOIN `order` o ON o.OrderID = od.OrderID
    JOIN product p ON p.ProductID = od.ProductID
    JOIN province pr ON p.ProvinceID = pr.ProvinceID
    WHERE DATE(o.orderdate) = CURDATE()
    GROUP BY pr.ProvinceID, pr.ProvinceName
    ORDER BY total_sales DESC
    LIMIT 1

";
$result_best_province = $conn->query($sql_best_province);
if ($result_best_province && $row_best_province = $result_best_province->fetch_assoc()) {
    $best_province = $row_best_province['ProvinceName'] ?? "ไม่มีข้อมูล";
} else {
    $best_province = "ไม่มีข้อมูล";
}

// ====== ดึงข้อมูล Least Sales Province (ถ้ามีคำสั่งซื้อวันนี้)
if ($order_today_exists) {
    $sql_least_province = "
        SELECT pr.ProvinceName, IFNULL(SUM(od.Total_productPrice), 0) AS total_sales
        FROM province pr
        JOIN product p ON pr.ProvinceID = p.ProvinceID
        LEFT JOIN order_detail od ON p.ProductID = od.ProductID
        LEFT JOIN `order` o ON od.OrderID = o.OrderID AND DATE(o.orderdate) = CURDATE()
        GROUP BY pr.ProvinceID, pr.ProvinceName
        ORDER BY total_sales ASC
        LIMIT 1
    ";

    $result_least_province = $conn->query($sql_least_province);
    if ($result_least_province && $result_least_province->num_rows > 0) {
        $row_least_province = $result_least_province->fetch_assoc();
        $least_province = $row_least_province['ProvinceName'] ?? "ไม่มีข้อมูล";
    } else {
        $least_province = "ไม่มีข้อมูล";
    }
} else {
    // ไม่มี order เลยในวันนี้
    $least_province = "ไม่มีข้อมูล";
}


$sql_line = "
    SELECT 
        HOUR(o.orderdate) AS order_hour,
        SUM(p.Total_price) AS total_sales
    FROM `order` o
    JOIN `payment` p ON o.orderid = p.orderid  -- เปลี่ยนตามชื่อคอลัมน์ที่เชื่อมระหว่าง 2 ตาราง
    WHERE DATE(o.orderdate) = CURDATE()
    GROUP BY order_hour
    ORDER BY order_hour
";


$result_line = $conn->query($sql_line);

// เตรียม label เป็น 00:00 - 23:00 ล่วงหน้า
$line_labels = [];
$line_data = [];

for ($h = 0; $h < 24; $h++) {
    $line_labels[] = str_pad($h, 2, '0', STR_PAD_LEFT) . ":00";
    $line_data[$h] = 0;
}

// ใส่ข้อมูลที่ดึงมาใส่ลงใน array ตามชั่วโมง
while ($row = $result_line->fetch_assoc()) {
    $hour = (int)$row['order_hour'];
    $line_data[$hour] = (float)$row['total_sales'];
}


// --------- กราฟ pie: สัดส่วนประเภทชา
$sql_pie = "
   SELECT tt.TeaTypeName, SUM(od.Quantity) AS total_qty
    FROM order_detail od
    JOIN product p ON od.ProductID = p.ProductID
    JOIN tea_type tt ON p.TeaTypeID = tt.TeaTypeID
    JOIN `order` o ON od.orderID = o.orderID
    WHERE DATE(o.orderdate) = CURDATE()
    GROUP BY tt.TeaTypeName
";

$result_pie = $conn->query($sql_pie);
$pie_labels = [];
$pie_data = [];
while ($row = $result_pie->fetch_assoc()) {
    $pie_labels[] = $row['TeaTypeName'];
    $pie_data[] = $row['total_qty'];
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

        <div class="time-buttons" style="text-align: center; margin-bottom: 20px;">
            <a href="dashboard_day.php"><button>Day</button></a>
            <a href="dashboard_week.php"><button>Week</button></a>
            <a href="dashboard_month.php"><button>Month</button></a>
            <a href="dashboard_year.php"><button>Year</button></a>
        </div>



        <!-- ================== Cards ===================== -->
        <div class="cardBox">

            <div class="card">
                <div>
                    <div class="numbers"><?php echo number_format($total_sales); ?></div>
                    <div class="cardName">Sales</div>
                </div>
                <div class="iconBx">
                    <ion-icon name="cart-outline"></ion-icon>
                </div>
            </div>

            <div class="card">
                <div>
                    <div class="numbers"><?php echo $earnings; ?></div>
                    <div class="cardName">Earning</div>
                </div>
                <div class="iconBx">
                    <ion-icon name="cash-outline"></ion-icon>
                </div>
            </div>
            <!-- Best Selling Tea Card -->
            <div class="card">
                <div>
                    <div class="numbers"><?php echo $best_tea; ?></div>
                    <div class="cardName">Best Selling Tea</div>
                </div>
                <div class="iconBx">
                    <ion-icon name="leaf-outline"></ion-icon>
                </div>
            </div>

            <!-- Least Selling Tea Card -->
            <div class="card">
                <div>
                    <div class="numbers"><?php echo $least_tea; ?></div>
                    <div class="cardName">Least Selling Tea</div>
                </div>
                <div class="iconBx">
                    <ion-icon name="leaf-outline"></ion-icon>
                </div>
            </div>

            <!-- Best Sales Province Card -->
            <div class="card">
                <div>
                    <div class="numbers"><?php echo $best_province; ?></div>
                    <div class="cardName">Best Sales Province</div>
                </div>
                <div class="iconBx">
                    <ion-icon name="location-outline"></ion-icon>
                </div>
            </div>

            <!-- Least Sales Province Card -->
            <div class="card">
                <div>
                    <div class="numbers"><?php echo $least_province; ?></div>
                    <div class="cardName">Least Sales Province</div>
                </div>
                <div class="iconBx">
                    <ion-icon name="location-outline"></ion-icon>
                </div>
            </div>

            <div class="graphs">
                <!-- กราฟเส้นที่ขนาด 400x400px -->
                <div class="chartCard">
                    <canvas id="lineChart2"></canvas>
                </div>

                <!-- กราฟวงกลมขนาด 400x400px -->
                <div class="chartCard">
                    <canvas id="pieChart2"></canvas>
                </div>
            </div>
        </div> 
     </div>
   </div>

   <script>
        // เมื่อคลิกปุ่มที่เลือก ช่วงเวลาจะเปลี่ยน
        document.getElementById('dayBtn').addEventListener('click', function() {
            setActiveButton('day');
            loadData('day');
        });

        document.getElementById('weekBtn').addEventListener('click', function() {
            setActiveButton('week');
            loadData('week');
        });

        document.getElementById('monthBtn').addEventListener('click', function() {
            setActiveButton('month');
            loadData('month');
        });

        document.getElementById('yearBtn').addEventListener('click', function() {
            setActiveButton('year');
            loadData('year');
        });

        // ฟังก์ชันเปลี่ยนปุ่มที่เลือกให้มีการเปลี่ยนสี
        function setActiveButton(selected) {
            const buttons = document.querySelectorAll('.time-buttons button');
            buttons.forEach(button => {
                button.classList.remove('active');
            });
            document.getElementById(selected + 'Btn').classList.add('active');
        }

        // ฟังก์ชันโหลดข้อมูลตามช่วงเวลา
        function loadData(period) {
            console.log("โหลดข้อมูลช่วงเวลา: " + period);
            // ที่นี่สามารถเพิ่มโค้ดดึงข้อมูลจาก API หรือการอัพเดทกราฟได้
            // ตัวอย่าง: ถ้าเลือก 'day', 'week', 'month', หรือ 'year' แล้วแสดงกราฟใหม่
        }
    </script>
    
   <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
    var ctx = document.getElementById("lineChart2").getContext("2d");
    var lineChart = new Chart(ctx, {
        type: "line",
        data: {
            labels: <?= json_encode($line_labels); ?>,
            datasets: [{
                label: "Revenue Today (THB)",
                data: <?= json_encode(array_values($line_data)); ?>,
                backgroundColor: "rgba(69, 90, 32, 0.1)",          // สีพื้นจาง
                borderColor: "#455A20",                            // สีเส้น
                borderWidth: 2,
                pointRadius: 3,
                pointBackgroundColor: "#455A20",                   // จุด
                pointBorderColor: "#455A20",                       // ขอบจุด
                fill: true
            }]
        },

        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: "Sales (THB)" // เปลี่ยนเป็นภาษาอังกฤษ
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: "Time (Hour)" // เปลี่ยนเป็นภาษาอังกฤษ
                    }
                }
            }
        }
    });


    // กราฟวงกลม: ประเภทชา
    const pieCtx2 = document.getElementById('pieChart2').getContext('2d');
    new Chart(pieCtx2, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($pie_labels); ?>,
            datasets: [{
                data: <?php echo json_encode($pie_data); ?>,
                backgroundColor: [
                '#455A20', '#F2DEC2', '#DEC89E',
                '#222222', '#999999', '#f6ebdd',
                '#6b8e23', '#8c7b6b', '#c2b280'
            ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: { display: true, text: 'Tea Type Proportion' }
            }
        }
    });


    </script>

   <!-- ======== Scripts ======= -->
   <script src="assets/js/main.js"></script>

   <!-- ====== ionicons ====== -->
   <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
   <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</body>
</html>
