
<?php
require_once('admin/inc/db_config.php');
require_once('admin/inc/essentials.php');

// Prevent caching
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AM-HOTEL ABOUT</title>

  <!-- Bootstrap and Swiper CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <link rel="stylesheet" href="common.css">

  <style>
    .box {
      border-top-color: var(--teal) !important;
    }
    .swiper-slide img {
      height: 400px;
      object-fit: cover;
    }
  </style>
</head>
<body class="bg-light">

<?php include('inc/header.php'); ?>

<!-- ABOUT US Section -->
<div class="my-5 px-4">
  <h2 class="fw-bold h-font text-center">ABOUT US</h2>
  <div class="h-line bg-dark"></div>
</div>

<!-- Image + Description Row -->
<div class="container">
  <div class="row justify-content-between align-items-center">
    <div class="col-lg-6 col-md-5 mb-4 order-lg-1 order-md-1 order-2">
      <h3 class="mb-3">QuickStay Hotel</h3>
      <p>
        Welcome to QuickStay Hotel, where luxury meets comfort in the heart of the city.
        Our premium accommodation offers world-class amenities, exceptional service, and
        elegantly designed rooms that cater to both business and leisure travelers.
        Experience unparalleled hospitality with our modern facilities, fine dining,
        spa services, and personalized guest care that makes every stay memorable.
      </p>
    </div>
    <div class="col-lg-5 col-md-5 mb-4 order-lg-2 order-1">
      <img src="image/img1.jpg" class="w-100">
    </div>
  </div>
</div>

<!-- Statistics Cards -->
<div class="container mt-5">
  <div class="row">
    <div class="col-lg-3 col-md-6 mb-4 px-4">
      <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
        <img src="images/about/hotel.svg" width="70px">
        <h4 class="mt-3">100+ ROOMS</h4>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4 px-4">
      <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
        <img src="images/about/customers.svg" width="70px">
        <h4 class="mt-3">100+CUSTOMERS</h4>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4 px-4">
      <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
        <img src="images/about/rating.svg" width="70px">
        <h4 class="mt-3">150+ REVIEWS</h4>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4 px-4">
      <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
        <img src="images/about/staff.svg" width="70px">
        <h4 class="mt-3">200+ STAFF</h4>
      </div>
    </div>
  </div>
</div>


<h2 class="my-5 fw-bold h-font text-center">MANAGEMENT TEAM</h2>
<div class="container px-4">
  <div class="swiper mySwiper">
    <div class="swiper-wrapper mb-5">
      <?php
      require_once('admin/inc/db_config.php');
require_once('admin/inc/essentials.php');
       
        $res = selectAll('team_details');

        while ($row = mysqli_fetch_assoc($res)) {
          $img = $row['picture'];
          $imgPath = "admin/images/team/$img";

          // Build server path to check file
          $absolutePath = $_SERVER['DOCUMENT_ROOT'] . "/wbwebsite/$imgPath";

          // If file doesn't exist, use fallback
          if (!file_exists($absolutePath)) {
              $imgPath = "admin/images/team/default.jpg"; // fallback image
          }

          echo '
            <div class="swiper-slide bg-white text-center overflow-hidden rounded">
              <img src="'.$imgPath.'?'.time().'" class="w-100" style="height:400px; object-fit:cover;">
              <h5 class="mt-2">'.htmlspecialchars($row['name']).'</h5>
            </div>';
        }
      ?>
    </div>
    <div class="swiper-pagination"></div>
  </div>
</div>














<?php include('inc/footer.php'); ?>

<!-- JS Links -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
  var swiper = new Swiper(".mySwiper", {
    slidesPerView: 4,
    spaceBetween: 40,
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    breakpoints: {
      320: { slidesPerView: 1 },
      640: { slidesPerView: 1 },
      768: { slidesPerView: 2 },
      1024: { slidesPerView: 3 },
    }
  });
</script>

</body>
</html>
