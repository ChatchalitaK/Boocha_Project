<?php
$conn = new mysqli("localhost", "root", "", "boocha");

$sql = "
    DELETE o, od, s, p
    FROM `order` o
    LEFT JOIN order_detail od ON o.orderID = od.orderID
    LEFT JOIN shipment s ON o.orderID = s.orderID
    LEFT JOIN payment p ON o.orderID = p.orderID
    WHERE p.Payment_status = 'Pending'
    AND o.orderdate < DATE_SUB(CURDATE(), INTERVAL 3 DAY)
";

$conn->query($sql);
?>
