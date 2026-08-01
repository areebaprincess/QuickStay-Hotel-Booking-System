<?php
require_once('admin/inc/db_config.php');
require_once('admin/inc/essentials.php');

// Prevent caching
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Check if website is in shutdown mode
$shutdown_query = "SELECT shutdown FROM settings WHERE sr_no = 1";
$shutdown_result = mysqli_query($con, $shutdown_query);
if ($shutdown_result) {
    $shutdown_data = mysqli_fetch_assoc($shutdown_result);
    if ($shutdown_data['shutdown'] == 1) {
        // Display maintenance page
        echo '<!DOCTYPE html><html><head><title>Maintenance</title></head><body style="text-align:center;padding:50px;"><h1>Website Under Maintenance</h1><p>We are currently performing scheduled maintenance. Please check back later.</p></body></html>';
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickStay</title>
    <!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Merienda&family=Poppins&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
  <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
      *{
        font-family: 'Poppins',sans-serif;
      }
      .h-font{
        font-family:'Meriends',cursive
      }
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
input[type=number] {
  -moz-appearance: textfield;
}
.swiper {
  width: 100%;
  height: 500px;
}

.swiper-slide img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.custom-bg
{
  background-color: rgb(6, 160, 13);
}
.custom-bg:hover
{
  background-color: rgb(6, 160, 13);
}
.availability-form {
    margin-top: -58px;
    z-index: 2;
    position: relative;
}

@media screen and (max-width: 575px) {
    .availability-form {
        margin-top: 8px;
        padding: 0 35px;
    }
}


</style>

  </head>

<body class="bg-light">
    <?php include('inc/header.php'); ?>

<div class="swiper mySwiper">
  <div class="swiper-wrapper">
    <div class="swiper-slide"><img src="image/swiper/img1.png"  alt="Image 1"></div>
    <div class="swiper-slide"><img src="image/swiper/img2.png"  alt="Image2"></div>
    <div class="swiper-slide"><img src="image/swiper/img3.png" alt="Image 3"></div>
    <div class="swiper-slide"><img src="image/swiper/img4.png" alt="Image 4"></div>
    <div class="swiper-slide"><img src="image/swiper/img5.png" alt="Image 5"></div>
    <div class="swiper-slide"><img src="image/swiper/img6.png" alt="Image 6"></div>
  </div>
</div>
<!-- Booking availability form removed - now only shown in room booking modal -->

  <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">OUR ROOMS</h2>

<div class="container">
  <div class="row">
    <?php
    // Fetch featured rooms from QuickStay hotel (limit to 3 for home page)
    $featured_rooms_query = "SELECT r.*, GROUP_CONCAT(DISTINCT f.name) as features, GROUP_CONCAT(DISTINCT fa.name) as facilities 
                            FROM rooms r 
                            LEFT JOIN room_features rf ON r.id = rf.room_id 
                            LEFT JOIN features f ON rf.features_id = f.id 
                            LEFT JOIN room_facilities rfa ON r.id = rfa.room_id 
                            LEFT JOIN facilities fa ON rfa.facilities_id = fa.id 
                            WHERE r.id IN (1, 2, 3, 4, 15) 
                            GROUP BY r.id 
                            ORDER BY r.id";
    
    $featured_rooms = mysqli_query($con, $featured_rooms_query);
    
    if(mysqli_num_rows($featured_rooms) > 0) {
        while($room = mysqli_fetch_assoc($featured_rooms)) {
            $room_images = ['image/img2.avif', 'image/img4.jpg', 'image/img10.avif'];
            $image_index = ($room['id'] - 1) % 3;
            
            echo '<div class="col-lg-4 col-md-6 mb-4">';
            echo '<div class="card border-0 shadow" style="max-width: 350px; margin: auto;">';
            echo '<img src="' . $room_images[$image_index] . '" class="card-img-top" style="height: 200px; object-fit: cover;">';
            echo '<div class="card-body">';
            echo '<h5>' . htmlspecialchars($room['name']) . '</h5>';
            echo '<h6 class="mb-4">PKR ' . number_format($room['price']) . ' per night</h6>';
            
            // Room Type Badge
            $room_type = '';
            if($room['adult'] == 1) $room_type = 'Single Bed Room';
            elseif($room['adult'] == 2) $room_type = 'Double Bed Room';
            elseif($room['adult'] >= 3) $room_type = 'Family Suite';
            
            echo '<div class="mb-3">';
            echo '<span class="badge bg-primary">' . $room_type . '</span>';
            if(strpos(strtolower($room['name']), 'pool') !== false || strpos(strtolower($room['description']), 'pool') !== false) {
                echo ' <span class="badge bg-info">Swimming Pool</span>';
            }
            echo '</div>';
            
            // Features
            echo '<div class="features mb-4">';
            echo '<h6 class="mb-1">Features</h6>';
            echo '<span class="badge rounded-pill bg-light text-dark text-wrap">' . $room['adult'] . ' Adults</span>';
            echo '<span class="badge rounded-pill bg-light text-dark text-wrap">' . $room['children'] . ' Children</span>';
            echo '<span class="badge rounded-pill bg-light text-dark text-wrap">' . $room['area'] . ' sq ft</span>';
            
            if($room['features']) {
                $features_array = explode(',', $room['features']);
                foreach(array_slice($features_array, 0, 2) as $feature) {
                    echo '<span class="badge rounded-pill bg-light text-dark text-wrap">' . trim($feature) . '</span>';
                }
            }
            echo '</div>';
            
            // Facilities
            echo '<div class="facilities mb-4">';
            echo '<h6 class="mb-1">Facilities</h6>';
            if($room['facilities']) {
                $facilities_array = explode(',', $room['facilities']);
                foreach($facilities_array as $facility) {
                    echo '<span class="badge rounded-pill bg-light text-dark text-wrap">' . trim($facility) . '</span>';
                }
            }
            echo '</div>';
            
            // Rating
            echo '<div class="rating mb-4">';
            echo '<h6 class="mb-1">Rating</h6>';
            echo '<span class="badge rounded-pill bg-light">';
            for($i = 0; $i < 4; $i++) {
                echo '<i class="bi bi-star-fill text-warning"></i>';
            }
            echo '</span>';
            echo '</div>';
            
            // Buttons - Modified to check login status
            echo '<div class="d-flex justify-content-evenly mb-2">';
            // Check if user is logged in
            if(isset($_SESSION['user_id'])) {
                echo '<a href="roomslogin.php" class="btn btn-sm text-white custom-bg shadow">View All Rooms</a>';
            } else {
                echo '<button onclick="showLoginPrompt()" class="btn btn-sm text-white custom-bg shadow">View All Rooms</button>';
            }
            echo '<button class="btn btn-sm btn-outline-dark shadow" data-bs-toggle="modal" data-bs-target="#roomModal' . $room['id'] . '">More details</button>';
            echo '</div>';
            
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo '<div class="col-12 text-center">';
        echo '<p>No rooms available at the moment.</p>';
        echo '</div>';
    }
    ?>
  </div>
</div>

<!-- Room Details Modals -->
<?php
// Reset the result pointer to create modals
mysqli_data_seek($featured_rooms, 0);
while($room = mysqli_fetch_assoc($featured_rooms)) {
    echo '<div class="modal fade" id="roomModal' . $room['id'] . '" tabindex="-1" aria-labelledby="roomModalLabel' . $room['id'] . '" aria-hidden="true">';
    echo '<div class="modal-dialog modal-lg">';
    echo '<div class="modal-content">';
    echo '<div class="modal-header">';
    echo '<h5 class="modal-title" id="roomModalLabel' . $room['id'] . '">' . htmlspecialchars($room['name']) . '</h5>';
    echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
    echo '</div>';
    echo '<div class="modal-body">';
    
    // Room Image
    $room_images = ['image/img2.avif', 'image/img4.jpg', 'image/img10.avif'];
    $image_index = ($room['id'] - 1) % 3;
    echo '<img src="' . $room_images[$image_index] . '" class="img-fluid mb-3" style="height: 300px; width: 100%; object-fit: cover;">';
    
    // Room Description
    echo '<p class="mb-3">' . htmlspecialchars($room['description']) . '</p>';
    
    // Price
    echo '<h4 class="text-primary mb-3">PKR ' . number_format($room['price']) . ' per night</h4>';
    
    // Room Features
    echo '<div class="row">';
    echo '<div class="col-md-6">';
    echo '<h6 class="fw-bold mb-2">Room Features</h6>';
    echo '<ul class="list-unstyled">';
    echo '<li><i class="bi bi-people-fill text-primary"></i> ' . $room['adult'] . ' Adults</li>';
    echo '<li><i class="bi bi-person-fill text-primary"></i> ' . $room['children'] . ' Children</li>';
    echo '<li><i class="bi bi-house-fill text-primary"></i> ' . $room['area'] . ' sq ft</li>';
    
    if($room['features']) {
        $features_array = explode(',', $room['features']);
        foreach($features_array as $feature) {
            echo '<li><i class="bi bi-check-circle-fill text-success"></i> ' . trim($feature) . '</li>';
        }
    }
    echo '</ul>';
    echo '</div>';
    
    // Room Facilities
    echo '<div class="col-md-6">';
    echo '<h6 class="fw-bold mb-2">Room Facilities</h6>';
    echo '<ul class="list-unstyled">';
    if($room['facilities']) {
        $facilities_array = explode(',', $room['facilities']);
        foreach($facilities_array as $facility) {
            echo '<li><i class="bi bi-gear-fill text-info"></i> ' . trim($facility) . '</li>';
        }
    }
    echo '</ul>';
    echo '</div>';
    echo '</div>';
    
    echo '</div>';
    echo '<div class="modal-footer">';
    echo '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>';
    echo '<a href="room.php" class="btn btn-primary">Book Now</a>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}
?>
  
 
 
    <h2 class="mt-5 pt-4 text-center fw-bold h-font">OUR FACILITIES</h2>
    <div class="container">
      <div class="row justify-content-evenly px-lg-0 px-md-0 px-5">
        <?php
        // Fetch facilities from database
        $facilities_q = "SELECT * FROM facilities WHERE name != 'star' ORDER BY id LIMIT 6";
        $facilities_r = mysqli_query($con, $facilities_q);
        
        // Map facility names to their corresponding icons
        $facility_icons = [
            'wifi' => 'wifi.svg',
            'AC' => 'ac.svg',
            'Free Electricity' => 'electricity.svg',
            'LCD TV' => 'tv.svg',
            'Mini Fridge' => 'fridge.svg',
            'Room Service' => 'service.svg'
        ];
        
        while($facility = mysqli_fetch_assoc($facilities_r)) {
            $icon = isset($facility_icons[$facility['name']]) ? $facility_icons[$facility['name']] : 'wifi.svg';
            echo '<div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">';
            echo '<img src="image/' . $icon . '" width="80px">';
            echo '<h5 class="mt-3">' . $facility['name'] . '</h5>';
            echo '</div>';
        }
        ?>
      </div>
    </div>
   <!-- Reach us -->
<?php
$contact_q = "SELECT * FROM `contact_details` WHERE `sr_no` = ?";
$values = [1];
$contact_r = mysqli_fetch_assoc(select($contact_q, $values, 'i'));
?>

<h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">REACH US</h2>

<div class="container">
  <div class="row">
    <!-- Google Map -->
    <div class="col-lg-8 col-md-8 p-4 mb-lg-0 mb-3 bg-white rounded">
      <iframe class="w-100 rounded" height="320px"
        src="<?= $contact_r['iframe'] ?>"
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <!-- Contact Info -->
    <div class="col-lg-4 col-md-4">
      <div class="bg-white p-4 rounded mb-4">
        <h5>Call us</h5>
        <a href="tel:<?= $contact_r['pn1'] ?>" class="d-inline-block mb-2 text-decoration-none text-dark">
          <i class="bi bi-telephone-fill"></i> <?= $contact_r['pn1'] ?>
        </a>
        <br>
        <a href="tel:<?= $contact_r['pn2'] ?>" class="d-inline-block text-decoration-none text-dark">
          <i class="bi bi-telephone-fill"></i> <?= $contact_r['pn2'] ?>
        </a>
      </div>
     
      <!-- Follow us -->
      <div class="bg-white p-4 rounded mb-4">
        <h5>Follow us</h5>

        <a href="<?= $contact_r['tw'] ?>" target="_blank" class="d-inline-block mb-3">
          <span class="badge bg-light text-dark fs-6 p-2">
            <i class="bi bi-twitter me-1"></i> Twitter
          </span>
        </a>
        <br>

        <a href="<?= $contact_r['fb'] ?>" target="_blank" class="d-inline-block mb-3">
          <span class="badge bg-light text-dark fs-6 p-2">
            <i class="bi bi-facebook me-1"></i> Facebook
          </span>
        </a>
        <br>

        <a href="<?= $contact_r['insta'] ?>" target="_blank" class="d-inline-block">
          <span class="badge bg-light text-dark fs-6 p-2">
            <i class="bi bi-instagram me-1"></i> Instagram
          </span>
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Login/Register Prompt Modal -->
<div class="modal fade" id="loginPromptModal" tabindex="-1" aria-labelledby="loginPromptModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="loginPromptModalLabel">Access Required</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <p class="mb-4">Please login or register to view all rooms and make bookings.</p>
        <div class="d-grid gap-2">
          <a href="login.php" class="btn btn-primary" onclick="setRedirectAfterLogin()">Login</a>
          <a href="register.php" class="btn btn-outline-primary" onclick="setRedirectAfterLogin()">Register</a>
        </div>
      </div>
    </div>
  </div>
</div>

       <!-- footer -->
         <?php include('inc/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="ha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

         <!-- Initialize Swiper -->
<script>
  var swiper = new Swiper(".mySwiper", {
    loop: true,
    autoplay: {
      delay: 3000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
  });
</script>

<script>
// Function to show login prompt modal
function showLoginPrompt() {
  var loginPromptModal = new bootstrap.Modal(document.getElementById('loginPromptModal'));
  loginPromptModal.show();
}

// Function to set redirect URL after login
function setRedirectAfterLogin() {
  sessionStorage.setItem('redirectAfterLogin', 'roomslogin.php');
}

// Check if loginModal exists before adding event listener
const loginModalForm = document.querySelector('#loginModal form');
if (loginModalForm) {
  loginModalForm.addEventListener('submit', function(e) {
    e.preventDefault();

    let email = document.querySelector('#loginModal input[type="email"]').value;
    let password = document.querySelector('#loginModal input[type="password"]').value;

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "login.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
      if (this.responseText.trim() == '1') {
        alert("Login successful!");
        window.location.href = "user_dashboard.php"; // Change as needed
      } else if (this.responseText.trim() == 'invalid_password') {
        alert("Incorrect password.");
      } else if (this.responseText.trim() == 'no_user') {
        alert("User not found.");
      } else {
        alert("Something went wrong!");
      }
    };

    xhr.send("email=" + encodeURIComponent(email) + "&password=" + encodeURIComponent(password));
  });
}
</script>

</body>
</html>