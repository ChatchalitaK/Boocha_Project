<?php 
session_start();  // อย่าลืมเปิด session
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM user WHERE UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

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

    <section class="profile-container">
        <div class="profile-box">
            <h3>Customer ID: <?= htmlspecialchars($user['UserID']) ?></h3>
            <form action="update_profile.php" form id="editProfileForm" method="post">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="Username" value="<?= htmlspecialchars($user['Username']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Firstname</label>
                    <input type="text" name="Firstname" value="<?= htmlspecialchars($user['Firstname']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Lastname</label>
                    <input type="text" name="Lastname" value="<?= htmlspecialchars($user['Lastname']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="Email" value="<?= htmlspecialchars($user['Email']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Tel. Number</label>
                    <input type="text" name="Tel_number" value="<?= htmlspecialchars($user['Tel_number']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Shipping Address</label>
                    <textarea name="Shipping_Address" rows="3" required><?= htmlspecialchars($user['Shipping_Address']) ?></textarea>
                </div>
                <div class="form-buttons">
                    <button type="button" class="change-password-btn" onclick="openPasswordModal()">Change Password</button>
                    <button type="submit" class="save-btn">Save</button>
                </div>
            </form>
        </div>
    </section>

    <!-- Password Check Modal -->
    <div id="passwordCheckModal" class="modal" style="display:none;">
    <div class="modal-content">
        <h4>Enter Your Current Password</h4>
        <form id="passwordCheckForm">
            <div class="form-group">
                <label for="current_password">Password :</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>
            <span id="passwordError" style="color:red; display:none;">Incorrect password</span>
            <div class="button-group">
                <button type="button" onclick="closePasswordModal()">Cancel</button>
                <button type="submit">Next</button>
            </div>
        </form>
    </div>
    </div>

    <!-- Modal แจ้งผล -->
    <div id="profileSuccessModal" class="modal" style="display:none;">
    <div class="modal-content">
        <h3>Save Changes Successfully!</h3>
        <div class="button-group">
        <button onclick="closeProfileModal()">Close</button>
        </div>
    </div>
    </div>

    <!-- Modal & AJAX Script -->
    <style>
    .modal {
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background: rgba(0,0,0,0.6);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: #F2DEC2;
        padding: 40px 50px;
        border-radius: 16px;
        text-align: center;
        width: 550px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .button-group {
        margin-top: 20px;
    }
    .button-group button {
        margin: 10px;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        color: #455A20;
    }
    .button-group button:first-child {
        background-color: #DEC89E;
        color: #1E140B;
        font-weight: 600;
    }
    .button-group button:last-child {
        background-color: #BEC39E;
        color: #1E140B;
        font-weight: 600;
    }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    function closeProfileModal() {
        document.getElementById('profileSuccessModal').style.display = 'none';
    }
    function goToProfile() {
        window.location.href = 'customer.php';
    }
    function showProfileSuccessModal() {
        document.getElementById('profileSuccessModal').style.display = 'flex';
    }

    // ใช้ AJAX ส่งข้อมูล
    $('#editProfileForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: 'update_profile.php',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.trim() === 'success') {
                    showProfileSuccessModal();
                } else {
                    alert('เกิดข้อผิดพลาด: ' + response);
                }
            },
            error: function() {
                alert('เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์');
            }
        });
    });
    </script>

    <script>
    function openPasswordModal() {
        document.getElementById('passwordCheckModal').style.display = 'flex';
    }
    function closePasswordModal() {
        document.getElementById('passwordCheckModal').style.display = 'none';
        $('#passwordError').hide();
        $('#passwordCheckForm')[0].reset();
    }

    $('#passwordCheckForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'verify_password.php',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.trim() === 'valid') {
                    window.location.href = 'change_password.php';
                } else {
                    $('#passwordError').show();
                }
            },
            error: function() {
                alert('Server error. Please try again.');
            }
        });
    });
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