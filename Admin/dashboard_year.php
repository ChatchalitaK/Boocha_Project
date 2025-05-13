

<?php
// เริ่มต้นการเชื่อมต่อกับฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "boocha";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ====== ยอดคำสั่งซื้อในปีนี้
$sql = "SELECT COUNT(*) AS total_sales FROM `order` WHERE YEAR(orderdate) = YEAR(CURDATE())";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_sales = $row['total_sales'] ?? 0;

// ====== ยอดรายได้รายเดือนในปีนี้
$sql_earnings = "
    SELECT MONTH(orderdate) AS month, SUM(p.total_price) AS total
    FROM `order` o
    JOIN payment p ON o.orderID = p.orderID
    WHERE YEAR(orderdate) = YEAR(CURDATE())
    GROUP BY MONTH(orderdate)
    ORDER BY MONTH(orderdate)
";
$result_earnings = $conn->query($sql_earnings);

$monthly_earnings = [];
while ($row = $result_earnings->fetch_assoc()) {
    $monthly_earnings[(int)$row['month']] = $row['total'];
}

// คำนวณยอดรวมรายปี
$total_earning_year_raw = array_sum($monthly_earnings);  // รวมยอดรายปี
$total_earning_year = $total_earning_year_raw ? "฿" . number_format($total_earning_year_raw, 2) : "$0.00";

// ====== Best Selling Tea (This Year)
$sql_best_tea = "
    SELECT tt.TeaTypeName, IFNULL(SUM(od.Quantity), 0) AS total_sold
    FROM tea_type tt
    LEFT JOIN product p ON p.TeaTypeID = tt.TeaTypeID
    LEFT JOIN order_detail od ON p.ProductID = od.ProductID
    LEFT JOIN `order` o ON od.OrderID = o.OrderID
    WHERE YEAR(o.orderdate) = YEAR(CURDATE())  -- เงื่อนไขให้ดึงข้อมูลจากปีนี้
    GROUP BY tt.TeaTypeID, tt.TeaTypeName
    ORDER BY total_sold DESC  -- เรียงลำดับจากยอดขายมากไปหาน้อย
    LIMIT 1
";

// Execute SQL query
$result_best_tea = $conn->query($sql_best_tea);

if ($result_best_tea && $result_best_tea->num_rows > 0) {
    $row_best_tea = $result_best_tea->fetch_assoc();
    $best_tea = $row_best_tea['TeaTypeName'] ?? "No data";
} else {
    $best_tea = "No data";  // กรณีที่ไม่มีการสั่งซื้อชาในปีนี้
}


// ====== Least Selling Tea (This Year) 
$sql_least_tea = "
    SELECT tt.TeaTypeName, IFNULL(SUM(od.Quantity), 0) AS total_sold
    FROM tea_type tt
    LEFT JOIN product p ON p.TeaTypeID = tt.TeaTypeID
    LEFT JOIN order_detail od ON p.ProductID = od.ProductID
    LEFT JOIN `order` o ON od.OrderID = o.OrderID
    WHERE YEAR(o.orderdate) = YEAR(CURDATE())
    GROUP BY tt.TeaTypeName
    ORDER BY total_sold ASC -- เรียงลำดับจากยอดขายน้อยไปหามาก
    LIMIT 1
";


$result_least_tea = $conn->query($sql_least_tea);

if ($result_least_tea && $result_least_tea->num_rows > 0) {
    $row_least_tea = $result_least_tea->fetch_assoc();
    $least_tea = $row_least_tea['TeaTypeName'] ?? "No data";
} else {
    $least_tea = "No data";  // กรณีที่ไม่มีการสั่งซื้อชาในปีนี้
}


// Best Sales Province (This Year)
$sql_best_province = "
    SELECT pr.ProvinceName
    FROM province pr
    JOIN product p ON pr.ProvinceID = p.ProvinceID
    JOIN order_detail od ON p.ProductID = od.ProductID
    JOIN `order` o ON o.OrderID = od.OrderID
    WHERE YEAR(o.orderdate) = YEAR(CURDATE())  -- เงื่อนไขปีนี้
    GROUP BY pr.ProvinceName  -- กำหนดให้หายอดขายตามจังหวัด
    ORDER BY SUM(od.Total_productPrice) DESC  -- เรียงตามยอดขายจากมากไปน้อย
    LIMIT 1
";
$result_best_province = $conn->query($sql_best_province);
$row_best_province = $result_best_province->fetch_assoc();
$best_province = $row_best_province['ProvinceName'] ?? "No data";

$result_best_province = $conn->query($sql_best_province);
$row_best_province = $result_best_province->fetch_assoc();
$best_province = $row_best_province['ProvinceName'] ?? "No data";

// Least Sales Province (This Year)
$sql_least_province = "
    SELECT pr.ProvinceName
    FROM province pr
    JOIN product p ON pr.ProvinceID = p.ProvinceID
    JOIN order_detail od ON p.ProductID = od.ProductID
    JOIN `order` o ON o.OrderID = od.OrderID
    WHERE YEAR(o.orderdate) = YEAR(CURDATE())  -- เงื่อนไขปีนี้
    GROUP BY pr.ProvinceName  -- กำหนดให้หายอดขายตามจังหวัด
    ORDER BY SUM(od.Total_productPrice) ASC  -- เรียงตามยอดขายจากน้อยไปมาก
    LIMIT 1
";
$result_least_province = $conn->query($sql_least_province);
$row_least_province = $result_least_province->fetch_assoc();
$least_province = $row_least_province['ProvinceName'] ?? "No data";

$result_least_province = $conn->query($sql_least_province);
$row_least_province = $result_least_province->fetch_assoc();
$least_province = $row_least_province['ProvinceName'] ?? "No data";


// ====== Line Chart: Monthly Earnings of the Current Year (Jan - Dec)
$sql_yearly_line = "
    SELECT MONTH(orderdate) AS month, SUM(p.total_price) AS total
    FROM `order` o
    JOIN payment p ON o.orderID = p.orderID
    WHERE YEAR(orderdate) = YEAR(CURDATE())
    GROUP BY MONTH(orderdate)
    ORDER BY MONTH(orderdate)
";

$result_yearly_line = $conn->query($sql_yearly_line);
$yearly_line_labels = [];
$yearly_line_data = [];

$all_months = range(1, 12);
$month_map = [
    1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
    5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug',
    9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
];

$monthly_totals = array_fill_keys($all_months, 0);
while ($row = $result_yearly_line->fetch_assoc()) {
    $monthly_totals[(int)$row['month']] = (float)$row['total'];
}

foreach ($all_months as $m) {
    $yearly_line_labels[] = $month_map[$m];
    $yearly_line_data[] = $monthly_totals[$m];
}


// ====== กราฟ pie: สัดส่วนประเภทชา
$sql_pie = "
    SELECT tt.TeaTypeName, SUM(od.Quantity) AS total_qty
    FROM order_detail od
    JOIN product p ON od.ProductID = p.ProductID
    JOIN tea_type tt ON p.TeaTypeID = tt.TeaTypeID
    JOIN `order` o ON od.OrderID = o.OrderID
    WHERE YEAR(o.orderdate) = YEAR(CURDATE())
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
                    <div class="numbers"><?php echo $total_earning_year; ?></div>
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
    // กราฟเส้น: รายได้รายเดือนของปีนี้
    const yearlyLineCtx = document.getElementById('lineChart2').getContext('2d');
    new Chart(yearlyLineCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($yearly_line_labels); ?>,
            datasets: [{
                label: 'Monthly Earnings (Baht)',
                data: <?php echo json_encode($yearly_line_data); ?>,
                borderColor: "#455A20",
                backgroundColor: "	#95AA78",
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Earnings by Month (Current Year)'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '฿' + value.toLocaleString();
                        }
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
