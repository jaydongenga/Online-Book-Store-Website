<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
   exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>About Us</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>About Us</h3>
   <p> <a href="home.php">Home</a> / About </p>
</div>

<section class="about">

   <div class="flex">

      <div class="image">
         <img src="images/about.jpg" alt="Company Overview">
      </div>

      <div class="content">
         <h3>Why Choose Us?</h3>
         <p>At J-Books, we are committed to offering top-quality products and an exceptional customer experience. Our diverse range of carefully curated items, from best-selling books to limited editions, is designed to meet the needs of every reader.</p>
         <p>With fast shipping, easy returns, and unbeatable prices, we strive to make every shopping experience a delight. Join our growing family of satisfied customers who trust J-Books for all their reading needs!</p>
         <a href="contact.php" class="btn">Contact Us</a>
      </div>

   </div>

</section>

<section class="reviews">

   <h1 class="title">Client's Reviews</h1>

   <div class="box-container">

      <div class="box">
         <img src="images/pic-1.png" alt="Client Photo">
         <p>"I love shopping at J-Books! Their customer service is outstanding, and their selection of books is always up-to-date. I keep coming back for more!"</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>John Doe</h3>
      </div>

      <div class="box">
         <img src="images/pic-2.png" alt="Client Photo">
         <p>"A fantastic shopping experience! The website is easy to navigate, and my order arrived promptly. I couldn't be happier with my purchase from J-Books."</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
         </div>
         <h3>Jane Smith</h3>
      </div>

      <div class="box">
         <img src="images/pic-3.png" alt="Client Photo">
         <p>"Great quality books at affordable prices. I’ve bought several titles from J-Books, and they never disappoint!"</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
         </div>
         <h3>Mark Lee</h3>
      </div>

   </div>

</section>

<section class="authors">

   <h1 class="title">Meet Our Experts</h1>

   <div class="box-container">

      <div class="box">
         <img src="images/author-1.jpg" alt="Author Photo">
         <div class="share">
            <a href="#" class="fab fa-facebook-f"></a>
            <a href="#" class="fab fa-twitter"></a>
            <a href="#" class="fab fa-instagram"></a>
            <a href="#" class="fab fa-linkedin"></a>
         </div>
         <h3>John Doe</h3>
         <p>Founder & CEO</p>
      </div>

      <div class="box">
         <img src="images/author-2.jpg" alt="Author Photo">
         <div class="share">
            <a href="#" class="fab fa-facebook-f"></a>
            <a href="#" class="fab fa-twitter"></a>
            <a href="#" class="fab fa-instagram"></a>
            <a href="#" class="fab fa-linkedin"></a>
         </div>
         <h3>Emma Clark</h3>
         <p>Head of Marketing</p>
      </div>

      <div class="box">
         <img src="images/author-3.jpg" alt="Author Photo">
         <div class="share">
            <a href="#" class="fab fa-facebook-f"></a>
            <a href="#" class="fab fa-twitter"></a>
            <a href="#" class="fab fa-instagram"></a>
            <a href="#" class="fab fa-linkedin"></a>
         </div>
         <h3>James Taylor</h3>
         <p>Lead Product Designer</p>
      </div>

      <div class="box">
         <img src="images/author-4.jpg" alt="Author Photo">
         <div class="share">
            <a href="#" class="fab fa-facebook-f"></a>
            <a href="#" class="fab fa-twitter"></a>
            <a href="#" class="fab fa-instagram"></a>
            <a href="#" class="fab fa-linkedin"></a>
         </div>
         <h3>Sarah Williams</h3>
         <p>Customer Support Specialist</p>
      </div>

      <div class="box">
         <img src="images/author-5.jpg" alt="Author Photo">
         <div class="share">
            <a href="#" class="fab fa-facebook-f"></a>
            <a href="#" class="fab fa-twitter"></a>
            <a href="#" class="fab fa-instagram"></a>
            <a href="#" class="fab fa-linkedin"></a>
         </div>
         <h3>Michael Brown</h3>
         <p>Logistics & Operations Manager</p>
      </div>

   </div>

</section>

<?php include 'footer.php'; ?>

<!-- Custom JS file link -->
<script src="js/script.js"></script>

</body>
</html>
