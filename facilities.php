<?php
require_once('admin/inc/db_config.php');
require_once('admin/inc/essentials.php');

// Check if user is logged in
$user_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AM-HOTEL Facilities</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<link rel="stylesheet" href="common.css">
<style>
  .pop:hover{
    border-top-color:var(--teal)!important;
    transform: scale(1.03);
    trasition:all 0.3s;
  }
  </style>
</head>
<body class="bg-light">
   <?php include('inc/header.php'); ?>
    
          <!-- lets start -->
<div class="my-5 px-4">
  <h2 class="fw-blod h-font text-center">OUR FACILITIES</h2>
  
  <div class="h-line bg-dark"></div>
</div>
 
<!-- <div class="container">
  <div class="row">
    <?php
$res = selectALL('facilities');
$path = SITE_URL.'image/'; // Changed from FACILITIES_IMG_PATH

while($row = mysqli_fetch_assoc($res)){
    echo<<<data
    <div class="col-lg-4 col-md-6 mb-5 px-4">
        <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
            <div class="d-flex align-items-center mb-2">
                
             <img src="$path$row[icon]" width="40px">
                <h5 class="m-0 ms-3">$row[name]</h5>
            </div>
            <p>$row[description]</p>
        </div>
    </div>
data;
}
?>   
  </div>
</div> -->


<div class="container">
  <div class="row">
    <div class="col-lg-4 col-md-6 mb-5 px-4">
      <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
        <div class="d-flex align-items-center mb-2">
          <img src="image/wifi.svg" width="40px">
          <h5 class="m-0 ms-3">WiFi</h5>
        </div>
        <p>
         Provides fast internet access for browsing, streaming, and work.
        </p>
      </div>
    </div>
    <div class="col-lg-4 col-md-6 mb-5 px-4">
      <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
        <div class="d-flex align-items-center mb-2">
          <img src="image/electricity.svg" width="40px">
          <h5 class="m-0 ms-3">Electricity</h5>
        </div>
        <p>
         Ensures uninterrupted power for lights, appliances, and devices.
        </p>
      </div>
    </div>
    <div class="col-lg-4 col-md-6 mb-5 px-4">
      <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
        <div class="d-flex align-items-center mb-2">
          <img src="image/fridge.svg" width="40px">
          <h5 class="m-0 ms-3">Fridge</h5>
        </div>
        <p>
         Keeps food and drinks fresh and cool.
        </p>
      </div>
    </div>
    <div class="col-lg-4 col-md-6 mb-5 px-4">
      <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
        <div class="d-flex align-items-center mb-2">
          <img src="image/ac.svg" width="40px">
          <h5 class="m-0 ms-3">AC</h5>
        </div>
        <p>
          Maintains a comfortable room temperature.
        </p>
      </div>
    </div>
    <div class="col-lg-4 col-md-6 mb-5 px-4">
      <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
        <div class="d-flex align-items-center mb-2">
          <img src="image/tv.svg" width="40px">
          <h5 class="m-0 ms-3">TV</h5>
        </div>
        <p>
          Offers entertainment with various channels and shows.
        </p>
      </div>
    </div>
    <div class="col-lg-4 col-md-6 mb-5 px-4">
      <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
        <div class="d-flex align-items-center mb-2">
          <img src="image/service.svg" width="40px">
          <h5 class="m-0 ms-3">Room Service</h5>
        </div>
        <p>
         Delivers food, drinks, and amenities directly to the room.
        </p>
      </div>
    </div>
  </div>
</div>

  <?php include('inc/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="ha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<!-- Swiper JS -->

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

</body>
</html>