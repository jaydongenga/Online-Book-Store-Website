<?php
// Start the session only if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


include 'config.php';

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header('location:login.php');
    exit;
}

if (isset($message)) {
    foreach ($message as $msg) {
        echo '
        <div class="message">
            <span>' . htmlspecialchars($msg) . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>';
    }
}
?>

<header class="header">

    <div class="header-1">
        <div class="flex">
            <div class="share">
                <a href="#" class="fab fa-facebook-f"></a>
                <a href="#" class="fab fa-twitter"></a>
                <a href="#" class="fab fa-instagram"></a>
                <a href="#" class="fab fa-linkedin"></a>
            </div>
            <p> new <a href="login.php">login</a> | <a href="register.php">register</a> </p>
        </div>
    </div>

    <div class="header-2">
        <div class="flex">
            <a href="home.php" class="logo">J-Books</a>

            <nav class="navbar">
                <a href="home.php">home</a>
                <a href="about.php">about</a>
                <a href="shop.php">shop</a>
                <a href="contact.php">contact</a>
                <a href="orders.php">orders</a>
            </nav>

            <div class="icons">
                <div id="menu-btn" class="fas fa-bars"></div>
                <a href="search_page.php" class="fas fa-search"></a>
                <div id="user-btn" class="fas fa-user"></div>
                <?php
                $select_cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'");
                if (!$select_cart_number) {
                    error_log("Query failed: " . mysqli_error($conn));
                    $cart_rows_number = 0; // Default to 0 if query fails
                } else {
                    $cart_rows_number = mysqli_num_rows($select_cart_number);
                }
                ?>
                <a href="cart.php"> <i class="fas fa-shopping-cart"></i> <span>(<?php echo $cart_rows_number; ?>)</span> </a>
            </div>

            <?php if (isset($_SESSION['user_name']) && isset($_SESSION['user_email'])) : ?>
                <div class="user-box">
                    <p>username : <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span></p>
                    <p>email : <span><?php echo htmlspecialchars($_SESSION['user_email']); ?></span></p>
                    <a href="logout.php" class="delete-btn">logout</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

</header>
