<?php
require_once('admin/inc/db_config.php');
require_once('admin/inc/essentials.php');

// Check if user is logged in
$user_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>QuickStay - ROOMS</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    />
    <link rel="stylesheet" href="common.css" />
  </head>
  <body class="bg-light">
    <?php include('inc/header.php'); ?>

    <div class="my-5 px-4">
      <h2 class="fw-bold h-font text-center">OUR ROOMS</h2>
      <div class="h-line bg-dark"></div>
    </div>

    <div class="container">
      <div class="row">
        <!-- Hotel Facilities Column (replacing Filters) -->
        <div class="col-lg-3 col-md-12 mb-lg-0 mb-4 px-0">
          <nav class="navbar navbar-expand-lg navbar-light bg-white rounded shadow">
            <div class="container-fluid flex-lg-column align-items-stretch">
              <h4 class="mt-2">HOTEL FACILITIES</h4>
              <button
                class="navbar-toggler shadow-none"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#facilitiesDropdown"
                aria-controls="facilitiesDropdown"
                aria-expanded="false"
                aria-label="Toggle navigation"
              >
                <span class="navbar-toggler-icon"></span>
              </button>

              <div class="collapse navbar-collapse flex-column align-items-stretch mt-2" id="facilitiesDropdown">
                <!-- Hotel Facilities Display -->
                <div class="border bg-light p-3 rounded mb-3">
                  <h5 class="mb-3" style="font-size: 13px">OUR AMENITIES</h5>
                  <?php
                  $facilities_res = selectAll('facilities');
                  while($facility = mysqli_fetch_assoc($facilities_res)) {
                    echo "<div class='mb-2 d-flex align-items-center'>
                            <i class='bi bi-check-circle-fill text-success me-2'></i>
                            <span class='text-dark'>{$facility['name']}</span>
                          </div>";
                  }
                  ?>
                </div>

                <!-- Guest Capacity Info -->
                <div class="border bg-light p-3 rounded mb-3">
                  <h5 class="mb-3" style="font-size: 13px">GUEST CAPACITY</h5>
                  <div class="d-flex">
                    <div class="me-3">
                      <label class="form-label">Adults</label>
                      <input type="number" id="adults" class="form-control shadow-none" min="1" value="1" onchange="filterRooms()" />
                    </div>
                    <div>
                      <label class="form-label">Children</label>
                      <input type="number" id="children" class="form-control shadow-none" min="0" value="0" onchange="filterRooms()" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </nav>
        </div>

        <!-- Room Cards Column -->
        <div class="col-lg-9 col-md-12 px-4" id="rooms-container">
          <?php
          // Get all QuickStay hotel rooms with their features and facilities
          // Replace the existing query (around line 97-102):
          $rooms_query = "SELECT r.*, GROUP_CONCAT(DISTINCT f.name) as features, GROUP_CONCAT(DISTINCT fa.name) as facilities 
          FROM rooms r 
          LEFT JOIN room_features rf ON r.id = rf.room_id 
          LEFT JOIN features f ON rf.features_id = f.id 
          LEFT JOIN room_facilities rfa ON r.id = rfa.room_id 
          LEFT JOIN facilities fa ON rfa.facilities_id = fa.id 
          WHERE 1=1
          GROUP BY r.id";
          
          $rooms_result = mysqli_query($con, $rooms_query);
          
          if (!$rooms_result) {
            die("Database query failed: " . mysqli_error($con));
          }
          
          if(mysqli_num_rows($rooms_result) > 0) {
            while($room = mysqli_fetch_assoc($rooms_result)) {
              $features = $room['features'] ? explode(',', $room['features']) : [];
              $facilities = $room['facilities'] ? explode(',', $room['facilities']) : [];
              
              echo "<div class='card mb-4 border-0 shadow room-card' data-room-id='{$room['id']}' data-adults='{$room['adult']}' data-children='{$room['children']}' data-facilities='" . implode(',', $facilities) . "'>";
              echo "<div class='row g-0 p-3 align-items-center'>";
              
              // Room Image - Use different images for different rooms
              $room_images = [
                  'images/rooms/img1.jpg',
                  'images/rooms/img2.png',
                  'images/rooms/img3.png',
                  'images/rooms/img4.png',
                  'images/rooms/img5.png',
                  'images/rooms/img6.png',
                  'images/rooms/img7.png',
                  'images/rooms/img8.png'
              ];
              $image_index = ($room['id'] - 1) % count($room_images);
              echo "<div class='col-md-5 mb-lg-0 mb-md-0 mb-3'>";
              echo "<img src='{$room_images[$image_index]}' class='img-fluid rounded' alt='{$room['name']}' />";
              echo "</div>";
              
              // Room Details
              echo "<div class='col-md-5 px-lg-3 px-md-3 px-0'>";
              echo "<h5 class='mb-1'>{$room['name']}</h5>";
              echo "<p class='text-muted mb-2'>Area: {$room['area']} sq ft | Max Adults: {$room['adult']} | Max Children: {$room['children']}</p>";
              
              // Features
              if(!empty($features)) {
                echo "<div class='features mb-3'>";
                echo "<h6 class='mb-1'>Features</h6>";
                foreach($features as $feature) {
                  echo "<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>{$feature}</span>";
                }
                echo "</div>";
              }
              
              // Facilities
              if(!empty($facilities)) {
                echo "<div class='facilities mb-3'>";
                echo "<h6 class='mb-1'>Facilities</h6>";
                foreach($facilities as $facility) {
                  echo "<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>{$facility}</span>";
                }
                echo "</div>";
              }
              
              echo "<p class='text-muted mb-0'>{$room['description']}</p>";
              echo "</div>";
              
              // Booking Section
              echo "<div class='col-md-2 text-center'>";
              echo "<h6 class='mb-4'>PKR {$room['price']} per night</h6>";
              echo "<p class='text-muted mb-2'>Available: {$room['quantity']} rooms</p>";
              
              if($user_logged_in) {
                echo "<button class='btn btn-sm w-100 text-white bg-success shadow mb-2' onclick='openLoginRegisterModal({$room['id']}, \"{$room['name']}\", {$room['price']})'>Book Now</button>";
              } else {
                echo "<button class='btn btn-sm w-100 text-white bg-warning shadow mb-2' onclick='openLoginRegisterModal({$room['id']}, \"{$room['name']}\", {$room['price']})'>Book Now</button>";
              }
              
              echo "<button class='btn btn-sm w-100 btn-outline-dark shadow' data-bs-toggle='modal' data-bs-target='#roomModal{$room['id']}'>More details</button>";
              echo "</div>";
              
              echo "</div>";
              echo "</div>";
            }
          } else {
            echo "<div class='text-center'><h4>No rooms available</h4></div>";
          }
          ?>
        </div>
      </div>
    </div>

    <!-- Login/Register Modal -->
    <div class="modal fade" id="loginRegisterModal" tabindex="-1" aria-labelledby="loginRegisterModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="loginRegisterModalLabel">Login or Register to Book</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-center">
            <input type="hidden" id="selected_room_id" name="selected_room_id">
            <input type="hidden" id="selected_room_name" name="selected_room_name">
            <input type="hidden" id="selected_room_price" name="selected_room_price">
            
            <h6 class="mb-4">Please login or register to proceed with booking</h6>
            <p class="text-muted mb-4">You need to be logged in to book rooms</p>
            
            <div class="d-grid gap-2">
              <button type="button" class="btn btn-primary" onclick="redirectToLogin()">Login</button>
              <button type="button" class="btn btn-outline-primary" onclick="redirectToRegister()">Register</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Booking Modal -->
    <?php if($user_logged_in): ?>
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="bookingModalLabel">Book Room</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="bookingForm">
            <div class="modal-body">
              <input type="hidden" id="room_id" name="room_id">
              <input type="hidden" id="room_price" name="room_price">
              
              <div class="mb-3">
                <label class="form-label">Room Name</label>
                <input type="text" id="room_name" class="form-control" readonly>
              </div>
              
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Check-in Date</label>
                  <input type="date" id="check_in" name="check_in" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Check-out Date</label>
                  <input type="date" id="check_out" name="check_out" class="form-control" required>
                </div>
              </div>
              
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Adults</label>
                  <input type="number" id="booking_adults" name="adults" class="form-control" min="1" value="1" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Children</label>
                  <input type="number" id="booking_children" name="children" class="form-control" min="0" value="0">
                </div>
              </div>
              
              <div class="mb-3">
                <label class="form-label">Total Amount</label>
                <input type="text" id="total_amount" class="form-control" readonly>
              </div>
              
              <div id="availability_message" class="alert" style="display: none;"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-primary" onclick="checkAvailability()">Check Availability</button>
              <button type="submit" id="confirmBooking" class="btn btn-success" style="display: none;">Confirm Booking</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <?php include('inc/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    
    <script>
    // Set minimum date to today
    document.addEventListener('DOMContentLoaded', function() {
      const today = new Date().toISOString().split('T')[0];
      document.getElementById('check_in')?.setAttribute('min', today);
      document.getElementById('check_out')?.setAttribute('min', today);
    });

    function openLoginRegisterModal(roomId, roomName, roomPrice) {
      // Store room details for after login
      sessionStorage.setItem('selectedRoomId', roomId);
      sessionStorage.setItem('selectedRoomName', roomName);
      sessionStorage.setItem('selectedRoomPrice', roomPrice);
      
      document.getElementById('selected_room_id').value = roomId;
      document.getElementById('selected_room_name').value = roomName;
      document.getElementById('selected_room_price').value = roomPrice;
      
      new bootstrap.Modal(document.getElementById('loginRegisterModal')).show();
    }

    function redirectToLogin() {
      // Store current page and room details
      sessionStorage.setItem('redirectAfterLogin', 'roomslogin.php');
      window.location.href = 'login.php';
    }

    function redirectToRegister() {
      // Store current page and room details
      sessionStorage.setItem('redirectAfterLogin', 'roomslogin.php');
      window.location.href = 'register.php';
    }

    function showLoginAlert() {
      // Use the new modal instead of alert
      openLoginRegisterModal(0, '', 0);
    }

    function openBookingModal(roomId, roomName, roomPrice) {
      document.getElementById('room_id').value = roomId;
      document.getElementById('room_name').value = roomName;
      document.getElementById('room_price').value = roomPrice;
      
      // Reset form
      document.getElementById('bookingForm').reset();
      document.getElementById('room_id').value = roomId;
      document.getElementById('room_name').value = roomName;
      document.getElementById('room_price').value = roomPrice;
      document.getElementById('availability_message').style.display = 'none';
      document.getElementById('confirmBooking').style.display = 'none';
      
      new bootstrap.Modal(document.getElementById('bookingModal')).show();
    }

    function calculateTotal() {
      const checkIn = new Date(document.getElementById('check_in').value);
      const checkOut = new Date(document.getElementById('check_out').value);
      const roomPrice = parseFloat(document.getElementById('room_price').value);
      
      if(checkIn && checkOut && checkOut > checkIn) {
        const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
        const total = nights * roomPrice;
        document.getElementById('total_amount').value = `PKR ${total} (${nights} nights)`;
        return total;
      }
      return 0;
    }

    // Update total when dates change
    document.getElementById('check_in')?.addEventListener('change', function() {
      const checkOut = document.getElementById('check_out');
      checkOut.min = this.value;
      calculateTotal();
    });
    
    document.getElementById('check_out')?.addEventListener('change', calculateTotal);

    function checkAvailability() {
      const formData = new FormData(document.getElementById('bookingForm'));
      
      if(!formData.get('check_in') || !formData.get('check_out')) {
        alert('Please select check-in and check-out dates.');
        return;
      }
      
      fetch('check_availability.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        const messageDiv = document.getElementById('availability_message');
        messageDiv.style.display = 'block';
        
        if(data.available) {
          messageDiv.className = 'alert alert-success';
          messageDiv.textContent = 'Room is available! You can proceed with booking.';
          document.getElementById('confirmBooking').style.display = 'inline-block';
        } else {
          messageDiv.className = 'alert alert-danger';
          messageDiv.textContent = data.message || 'Room is not available for selected dates. Please choose different dates or another room.';
          document.getElementById('confirmBooking').style.display = 'none';
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Error checking availability. Please try again.');
      });
    }

    // Handle booking form submission
    document.getElementById('bookingForm')?.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      
      fetch('process_booking.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if(data.success) {
          alert('Room booked successfully!');
          bootstrap.Modal.getInstance(document.getElementById('bookingModal')).hide();
          location.reload();
        } else {
          alert(data.message || 'Booking failed. Please try again.');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Error processing booking. Please try again.');
      });
    });

    function filterRooms() {
      const adults = parseInt(document.getElementById('adults').value) || 1;
      const children = parseInt(document.getElementById('children').value) || 0;
      const selectedFacilities = Array.from(document.querySelectorAll('input[type="checkbox"]:checked')).map(cb => cb.value);
      
      const roomCards = document.querySelectorAll('.room-card');
      
      roomCards.forEach(card => {
        const maxAdults = parseInt(card.dataset.adults);
        const maxChildren = parseInt(card.dataset.children);
        const roomFacilities = card.dataset.facilities.split(',').filter(f => f.trim());
        
        let showRoom = true;
        
        // Check guest capacity
        if(adults > maxAdults || children > maxChildren) {
          showRoom = false;
        }
        
        // Check facilities
        if(selectedFacilities.length > 0) {
          const hasAllFacilities = selectedFacilities.every(facility => 
            roomFacilities.some(rf => rf.trim().toLowerCase().includes(facility.toLowerCase()))
          );
          if(!hasAllFacilities) {
            showRoom = false;
          }
        }
        
        card.style.display = showRoom ? 'block' : 'none';
      });
    }

    </script>

    <!-- Room Details Modals -->
    <?php
    // Reset the result pointer to create modals
    mysqli_data_seek($rooms_result, 0);
    while($room = mysqli_fetch_assoc($rooms_result)) {
        echo '<div class="modal fade" id="roomModal' . $room['id'] . '" tabindex="-1" aria-labelledby="roomModalLabel' . $room['id'] . '" aria-hidden="true">';
        echo '<div class="modal-dialog modal-lg">';
        echo '<div class="modal-content">';
        echo '<div class="modal-header">';
        echo '<h5 class="modal-title" id="roomModalLabel' . $room['id'] . '">' . htmlspecialchars($room['name']) . '</h5>';
        echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        echo '</div>';
        echo '<div class="modal-body">';
        
        // Room Image - Use same image as in the card
        $room_images = [
            'images/rooms/img1.jpg',
            'images/rooms/img2.png',
            'images/rooms/img3.png',
            'images/rooms/img4.png',
            'images/rooms/img5.png',
            'images/rooms/img6.png',
            'images/rooms/img7.png',
            'images/rooms/img8.png'
        ];
        $image_index = ($room['id'] - 1) % count($room_images);
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
        echo '<li><i class="bi bi-box-fill text-primary"></i> ' . $room['quantity'] . ' Rooms Available</li>';
        
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
        if($user_logged_in) {
            echo '<button class="btn btn-primary" onclick="openBookingModal(' . $room['id'] . ', \"' . $room['name'] . '\", ' . $room['price'] . ')">Book Now</button>';
        } else {
            echo '<button class="btn btn-warning" onclick="showLoginAlert()">Login to Book</button>';
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    ?>
  </body>
</html>
