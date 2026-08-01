<?php
require_once('admin/inc/db_config.php');
require_once('admin/inc/essentials.php');

// Prevent caching of this page
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

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
          // Replace the existing query (around line 102-107):
          $rooms_query = "SELECT r.*, GROUP_CONCAT(DISTINCT f.name) as features, GROUP_CONCAT(DISTINCT fa.name) as facilities 
                         FROM rooms r 
                         LEFT JOIN room_features rf ON r.id = rf.room_id 
                         LEFT JOIN features f ON rf.features_id = f.id 
                         LEFT JOIN room_facilities rfa ON r.id = rfa.room_id 
                         LEFT JOIN facilities fa ON rfa.facilities_id = fa.id 
                         WHERE 1=1
                         GROUP BY r.id";
          
          $rooms_result = mysqli_query($con, $rooms_query);
          
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
                echo "<button class='btn btn-sm w-100 text-white bg-success shadow mb-2' onclick='openBookingModal({$room['id']}, \"{$room['name']}\", {$room['price']})'>Book Now</button>";
              } else {
                echo "<button class='btn btn-sm w-100 text-white bg-warning shadow mb-2' onclick='showLoginAlert()'>Login to Book</button>";
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

    <!-- Booking Modal -->
    <?php if($user_logged_in): ?>
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="bookingModalLabel">Book Room - Complete Your Reservation</h5>
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
              
              <!-- Booking Dates -->
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Check-in Date *</label>
                  <input type="date" id="check_in" name="check_in" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Check-out Date *</label>
                  <input type="date" id="check_out" name="check_out" class="form-control" required>
                </div>
              </div>
              
              <!-- Guest Information -->
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Adults *</label>
                  <input type="number" id="booking_adults" name="adults" class="form-control" min="1" value="1" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Children</label>
                  <input type="number" id="booking_children" name="children" class="form-control" min="0" value="0">
                </div>
              </div>
              
              <!-- Personal Information Section -->
              <hr>
              <h6 class="mb-3 text-primary">Personal Information</h6>
              
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Full Name *</label>
                  <input type="text" id="guest_name" name="guest_name" class="form-control" required placeholder="Enter full name as per ID">
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Email Address *</label>
                  <input type="email" id="guest_email" name="guest_email" class="form-control" required placeholder="Enter email address">
                </div>
              </div>
              
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Phone Number *</label>
                  <input type="tel" id="guest_phone" name="guest_phone" class="form-control" required placeholder="Enter phone number">
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">ID Card Number *</label>
                  <input type="text" id="id_card" name="id_card" class="form-control" required placeholder="CNIC/Passport/Driving License">
                </div>
              </div>
              
              <div class="mb-3">
                <label class="form-label">Complete Address *</label>
                <textarea id="guest_address" name="guest_address" class="form-control" rows="2" required placeholder="Enter complete address"></textarea>
              </div>
              
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">City *</label>
                  <input type="text" id="guest_city" name="guest_city" class="form-control" required placeholder="Enter city">
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Postal Code</label>
                  <input type="text" id="guest_pincode" name="guest_pincode" class="form-control" placeholder="Enter postal code">
                </div>
              </div>
              
              <!-- Emergency Contact -->
              <hr>
              <h6 class="mb-3 text-primary">Emergency Contact</h6>
              
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Emergency Contact Name</label>
                  <input type="text" id="emergency_name" name="emergency_name" class="form-control" placeholder="Enter emergency contact name">
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Emergency Contact Phone</label>
                  <input type="tel" id="emergency_phone" name="emergency_phone" class="form-control" placeholder="Enter emergency contact phone">
                </div>
              </div>
              
              <!-- Special Requests -->
              <div class="mb-3">
                <label class="form-label">Special Requests/Notes</label>
                <textarea id="special_requests" name="special_requests" class="form-control" rows="2" placeholder="Any special requests or dietary requirements"></textarea>
              </div>
              
              <div class="mb-3">
                <label class="form-label">Total Amount</label>
                <input type="text" id="total_amount" class="form-control" readonly>
              </div>
              
              <div id="availability_message" class="alert" style="display: none;"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" id="submitBooking" class="btn btn-success">Submit Booking</button>
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

    function showLoginAlert() {
      alert('Please login or register to book a room.');
      window.location.href = 'login.php';
    }
    
    // Auto-fill user information from session (add this to openBookingModal function)
    function openBookingModal(roomId, roomName, roomPrice) {
      document.getElementById('room_id').value = roomId;
      document.getElementById('room_name').value = roomName;
      document.getElementById('room_price').value = roomPrice;
      
      // Reset form
      document.getElementById('bookingForm').reset();
      document.getElementById('room_id').value = roomId;
      document.getElementById('room_name').value = roomName;
      document.getElementById('room_price').value = roomPrice;
      
      <?php if(isset($_SESSION['user_email'])): ?>
      document.getElementById('guest_email').value = '<?php echo $_SESSION['user_email']; ?>';
      <?php endif; ?>
      
      <?php if(isset($_SESSION['user_name'])): ?>
      document.getElementById('guest_name').value = '<?php echo $_SESSION['user_name']; ?>';
      <?php endif; ?>
      
      // Remove these lines since we don't need them anymore
      // document.getElementById('availability_message').style.display = 'none';
      // document.getElementById('confirmBooking').style.display = 'none';
      
      new bootstrap.Modal(document.getElementById('bookingModal')).show();
    }
    
    document.getElementById('bookingForm')?.addEventListener('submit', function(e) {
      e.preventDefault();
      
      // Validate required fields
      const formData = new FormData(this);
      
      if(!formData.get('check_in') || !formData.get('check_out')) {
        alert('Please select check-in and check-out dates.');
        return;
      }
      
      if(!formData.get('guest_name') || !formData.get('guest_email') || !formData.get('guest_phone')) {
        alert('Please fill all required personal information.');
        return;
      }
      
      // Get submit button
      const submitBtn = this.querySelector('button[type="submit"]');
      const originalText = submitBtn.textContent;
      submitBtn.disabled = true;
      
      // Submit booking directly to database
      fetch('process_booking.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if(data.success) {
          // Show booking confirmed popup
          showBookingConfirmedPopup(data);
        } else {
          alert(data.message || 'Booking failed. Please try again.');
          submitBtn.disabled = false;
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Error processing booking. Please try again.');
        submitBtn.disabled = false;
      });
    });
    
    // Function to show booking confirmed popup
    function showBookingConfirmedPopup(data) {
      // Hide the booking modal
      bootstrap.Modal.getInstance(document.getElementById('bookingModal')).hide();
      
      // Create confirmation popup
      const confirmationPopup = document.createElement('div');
      confirmationPopup.className = 'modal fade';
      confirmationPopup.innerHTML = `
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content border-0 shadow-lg">
            <div class="modal-body text-center p-5">
              <div class="mb-4">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
              </div>
              <h3 class="text-success mb-3">Booking Confirmed!</h3>
              <p class="text-muted mb-4">Your room reservation has been successfully confirmed.</p>
              
              <div class="bg-light rounded p-4 mb-4">
                <div class="row text-start">
                  <div class="col-6"><strong>Booking Reference:</strong></div>
                  <div class="col-6">${data.booking_ref}</div>
                  <div class="col-6"><strong>Total Amount:</strong></div>
                  <div class="col-6">PKR ${new Intl.NumberFormat('en-PK').format(data.total_amount)}</div>
                  <div class="col-6"><strong>Nights:</strong></div>
                  <div class="col-6">${data.nights} night(s)</div>
                  <div class="col-6"><strong>Status:</strong></div>
                  <div class="col-6"><span class="badge bg-success">Confirmed</span></div>
                </div>
              </div>
              
              <button type="button" class="btn btn-success btn-lg px-5" onclick="closeConfirmationAndReload()">OK</button>
            </div>
          </div>
        </div>
      `;
      
      document.body.appendChild(confirmationPopup);
      const modal = new bootstrap.Modal(confirmationPopup);
      modal.show();
      
      // Auto close after 5 seconds
      setTimeout(() => {
        modal.hide();
        location.reload();
      }, 5000);
    }
    
    // Function to close confirmation and reload
    function closeConfirmationAndReload() {
      location.reload();
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

    document.getElementById('bookingForm')?.addEventListener('submit', function(e) {
      e.preventDefault();
      
      // Validate required fields
      const formData = new FormData(this);
      
      if(!formData.get('check_in') || !formData.get('check_out')) {
        alert('Please select check-in and check-out dates.');
        return;
      }
      
      if(!formData.get('guest_name') || !formData.get('guest_email') || !formData.get('guest_phone')) {
        alert('Please fill all required personal information.');
        return;
      }
      
      // Show loading state
      const submitBtn = this.querySelector('button[type="submit"]');
      const originalText = submitBtn.textContent;
      submitBtn.textContent = 'Room Booked...';
      submitBtn.disabled = true;
      
      fetch('process_booking.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if(data.success) {
          alert(`Booking confirmed successfully!\nBooking Reference: ${data.booking_ref}\nTotal Amount: PKR ${data.total_amount}`);
          bootstrap.Modal.getInstance(document.getElementById('bookingModal')).hide();
          location.reload();
        } else {
          alert(data.message || 'Booking failed. Please try again.');
        }
      })
      .catch(error => {
        console.error('Error:', error);
          alert(`Booking confirmed successfully!\nBooking Reference: ${data.booking_ref}\nTotal Amount: PKR ${data.total_amount}`);
      })
      .finally(() => {
        // Reset button state
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
      });
    });

    // Update filterRooms function to work with guest capacity only
    function filterRooms() {
      const adults = parseInt(document.getElementById('adults').value) || 1;
      const children = parseInt(document.getElementById('children').value) || 0;
      
      const roomCards = document.querySelectorAll('.room-card');
      
      roomCards.forEach(card => {
        const maxAdults = parseInt(card.dataset.adults);
        const maxChildren = parseInt(card.dataset.children);
        
        let showRoom = true;
        
        // Check guest capacity only
        if(adults > maxAdults || children > maxChildren) {
          showRoom = false;
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
