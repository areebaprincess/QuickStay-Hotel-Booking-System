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
  <title>AM-HOTEL CONTACT</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="common.css">
</head>

<body class="bg-light">

  <?php include('inc/header.php'); ?>

  <div class="my-5 px-4">
    <h2 class="fw-bold h-font text-center">CONTACT US</h2>
    <div class="h-line bg-dark"></div>
  </div>

  <?php
    $contact_q = "SELECT * FROM `contact_details` WHERE `sr_no` = ?";
    $values = [1];
    $contact_r = mysqli_fetch_assoc(select($contact_q, $values, 'i'));
  ?>

  <div class="container">
    <div class="row">

      <!-- Left Column: Map and Contact Info -->
      <div class="col-lg-6 col-md-6 mb-5 px-4">
        <div class="bg-white rounded shadow p-4">

          <!-- Google Map -->
          <iframe class="w-100 rounded mb-4" height="320px"
            src="<?= $contact_r['iframe'] ?>"
            loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

          <!-- Address -->
          <h5>Address</h5>
          <a href="<?= $contact_r['gmap'] ?>" target="_blank" class="d-inline-block text-decoration-none text-dark mb-2">
            <i class="bi bi-geo-alt-fill"></i> <?= $contact_r['address'] ?>
          </a>

          <!-- Phone Numbers -->
          <h5 class="mt-4">Call us</h5>
          <a href="tel:<?= $contact_r['pn1'] ?>" class="d-inline-block mb-2 text-decoration-none text-dark">
            <i class="bi bi-telephone-fill"></i> <?= $contact_r['pn1'] ?>
          </a>
          <br>
          <a href="tel:<?= $contact_r['pn2'] ?>" class="d-inline-block text-decoration-none text-dark">
            <i class="bi bi-telephone-fill"></i> <?= $contact_r['pn2'] ?>
          </a>

          <!-- Email -->
          <h5 class="mt-4">Email</h5>
          <a href="mailto:<?= $contact_r['email'] ?>" class="d-inline-block text-decoration-none text-dark">
            <i class="bi bi-envelope-fill"></i> <?= $contact_r['email'] ?>
          </a>

          <!-- Social Media -->
          <h5 class="mt-4">Follow us</h5>
          <a href="<?= $contact_r['tw'] ?>" class="d-inline-block text-dark fs-5 me-2" target="_blank">
            <i class="bi bi-twitter me-1"></i>
          </a>
          <a href="<?= $contact_r['fb'] ?>" class="d-inline-block text-dark fs-5 me-2" target="_blank">
            <i class="bi bi-facebook me-1"></i>
          </a>
          <a href="<?= $contact_r['insta'] ?>" class="d-inline-block text-dark fs-5" target="_blank">
            <i class="bi bi-instagram me-1"></i>
          </a>

        </div>
      </div>

      <!-- Right Column: Contact Form -->
      <div class="col-lg-6 col-md-6 px-4">
        <div class="bg-white rounded shadow p-4">
     <form method="post">
  <h5>Send a message</h5>

  <div class="mt-3">
    <label class="form-label fw-medium">Name</label>
    <input name="name" type="text" class="form-control shadow-none" required>
  </div>

  <div class="mt-3">
    <label class="form-label fw-medium">Email</label>
    <input name="email" type="email" class="form-control shadow-none" required>
  </div>

  <div class="mt-3">
    <label class="form-label fw-medium">Subject</label>
    <input name="subject" type="text" class="form-control shadow-none" required>
  </div>

  <div class="mt-3">
    <label class="form-label fw-medium">Message</label>
    <textarea name="message" class="form-control shadow-none" rows="5" style="resize: none;" required></textarea>
  </div>

  <button type="submit" name="send" class="btn text-white custom-bg mt-3">SEND</button>
</form>
        </div>
      </div>

    </div>
  </div>
  <?php




if(isset($_POST['send']))
{
    $frm_data = filteration($_POST);

    $q = "INSERT INTO `user_queries`(`name`, `email`, `subject`, `message`) VALUES (?,?,?,?)";
    $values = [$frm_data['name'], $frm_data['email'], $frm_data['subject'], $frm_data['message']];

    $res = insert($q, $values, 'ssss');
    if($res == 1) {
        echo alert('success', 'Mail sent!');
    }
    else {
        echo alert('error', 'Server Down! Try again later.');
    }
}

// if(isset($_POST['send']))
// {
//     $frm_data = filteration($_POST);

//     $q = "INSERT INTO `user_queries`(`name`, `email`, `subject`, `message`) VALUES (?,?,?,?)";
//     $values = [$frm_data['name'], $frm_data['email'], $frm_data['subject'], $frm_data['message']];

//     $res = insert($q, $values, 'ssss');
//     if($res == 1) {
//         alert('success', 'Mail sent!');
//     }
//     else {
//         alert('error', 'Server Down! Try again later.');
//     }
// }
?>


  <?php include('inc/footer.php'); ?>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
