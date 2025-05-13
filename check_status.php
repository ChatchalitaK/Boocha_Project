<?php 
session_start();  // อย่าลืมเปิด session
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "
SELECT 
    o.OrderID, o.OrderDate, o.Note, o.CustomerID,
    p.Payment_status, p.Total_price, p.Paydate,
    p.Payment_method,
    s.Shipment_status, s.Ship_date,
    od.OrderDetailID, od.ProductID, od.Quantity, od.Total_productPrice,
    pr.ProductName
FROM `order` o
JOIN payment p ON o.OrderID = p.OrderID
JOIN shipment s ON o.OrderID = s.OrderID
JOIN order_detail od ON o.OrderID = od.OrderID
JOIN product pr ON od.ProductID = pr.ProductID
WHERE o.CustomerID = ?
ORDER BY o.OrderID DESC, o.OrderID
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[$row['OrderID']]['info'] = [
        'OrderDate' => $row['OrderDate'],
        'Note' => $row['Note'],
        'Payment_status' => $row['Payment_status'],
        'Total_price' => $row['Total_price'],
        'Paydate' => $row['Paydate'],
        'Shipment_status' => $row['Shipment_status'],
        'Ship_date' => $row['Ship_date']
    ];
    $orders[$row['OrderID']]['items'][] = [
        'OrderDetailID' => $row['OrderDetailID'],
        'ProductID' => $row['ProductID'],
        'ProductName' => $row['ProductName'],
        'Quantity' => $row['Quantity'],
        'Total_productPrice' => $row['Total_productPrice']
    ];
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
    <link rel="stylesheet" href="customer.css">
</head>
<body>

    <!-- Header -->
    <section id="header">
        <a href="#"><img src="https://res.cloudinary.com/drcyehkac/image/upload/v1745313675/logo_color_dzdxzc.png" class="logo" alt=""></a>
        <div> 
            <ul id="navbar">
                <li><a class="Available" href="home.php">Home</a></li>
                <li><a href="product.php">Product</a></li>
                <li><a href="about.php">About us</a></li>
                <li><a href="contact.php">Contact us</a></li>
                <li><a href="cart.php"><i class="fa-solid fa-cart-shopping"></i></a></li>
                <li><a href="customer.php"><i class="fa-solid fa-user"></i></a></li>
            </ul>
        </div>
    </section>

    <h3 style="text-align:center; margin-top:30px;">Your Orders</h3>

    <?php if (empty($orders)): ?>
        <div class="empty-order-message" style="text-align:center; padding: 50px 0; font-size: 1.2rem;">
            You haven't placed any orders yet
        </div>
    <?php else: ?>
        <?php foreach ($orders as $orderID => $data): 
            $info = $data['info'];
            $items = $data['items'];
            $isPaid = ($info['Payment_status'] === 'Paid');
            $isDelivered = ($info['Shipment_status'] === 'Delivered');

            // ตรวจสอบว่าภายใน 5 วันนับจาก Ship_date
            $canReview = false;
            if ($isDelivered && $info['Ship_date']) {
                $ship_date = new DateTime($info['Ship_date']);
                $now = new DateTime();
                $interval = $now->diff($ship_date)->days;
                $canReview = $interval <= 7;
            }
        ?>
        <div class="order-box">
            <div class="order-summary">
                <div>
                    <strong>Order ID</strong><br>
                    <span><?= $orderID ?></span><br>
                    <span class="order-date"><?= $info['OrderDate'] ?></span>
                </div>
                <div>
                    <strong>Payment Status</strong><br>
                    <span class="<?= $isPaid ? 'status-green' : 'status-yellow' ?>"><?= $info['Payment_status'] ?></span>
                </div>
                <div>
                    <strong>Shipment Status</strong><br>
                    <span class="<?= $isDelivered ? 'status-green' : 'status-yellow' ?>"><?= $info['Shipment_status'] ?></span>
                </div>
            </div>
            
            <div class="btn-toggle" onclick="toggleDetail('detail-<?= $orderID ?>')">View detail &#9660;</div>
            <div id="detail-<?= $orderID ?>" class="detail">
                <hr>
                <div class="item-list">
                    <?php foreach ($items as $item): ?>
                        <div class="item-row">
                            <span><?= htmlspecialchars($item['ProductName']) ?> × <?= $item['Quantity'] ?></span>
                            <span class="item-price"><?= $item['Total_productPrice'] ?> THB</span>

                            <?php if ($canReview): ?>
                                <a href="review.php?order=<?= $orderID ?>&product=<?= $item['ProductID'] ?>&orderdetailid=<?= $item['OrderDetailID'] ?>" class="review-btn-small">Review</a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                    <div class="note-row">
                        <strong>Note:</strong> <?= nl2br(htmlspecialchars($info['Note'])) ?>
                    </div>
                    <div class="total-price-row">
                        <strong>Total Price:</strong>
                        <span><?= $info['Total_price'] ?> THB</span>
                    </div>
                </div>

                <?php if ($canReview): ?>
                <div class="report-buttons">
                    <a href="report.php?order=<?= $orderID ?>" class="report-btn">Report</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <script>
    function toggleDetail(id) {
        const el = document.getElementById(id);
        if (el.style.display === "block") {
            el.style.display = "none";
        } else {
            el.style.display = "block";
        }
    }
    </script>

    <!-- Footer -->
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

</body>
</html>