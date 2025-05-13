<?php
session_start();
session_destroy(); // เคลียร์ session ทั้งหมด
header('Location: home.php'); // หรือเปลี่ยนเป็นหน้าที่อยากให้ไปหลัง logout
exit;
?>
