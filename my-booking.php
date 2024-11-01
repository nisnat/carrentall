<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('includes/config.php'); // Include database configuration file

// Check if user is logged in
if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
    exit;
}

// Initialize booking number variable
$bookingNumber = null;

// Retrieve booking number from GET parameters or session
if (isset($_GET['bookingNumber'])) {
    $bookingNumber = $_GET['bookingNumber'];
} elseif (isset($_SESSION['bookingNumber'])) {
    $bookingNumber = $_SESSION['bookingNumber'];
}

// Set default booking number if not available
if ($bookingNumber === null) {
    $bookingNumber = 'Not available';
}

// Handle return button click
if (isset($_POST['returnBtn'])) {
    $bookingNumber = $_POST['bookingNumber']; // Get booking number from form

    // Fetch the user email based on the booking number
    $sql = "SELECT userEmail FROM tblbooking WHERE BookingNumber = :bookingNumber";
    $query = $dbh->prepare($sql);
    $query->bindParam(':bookingNumber', $bookingNumber, PDO::PARAM_STR);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_OBJ);

    if ($user) {
        $userEmail = $user->userEmail; // Fetch user email

        // Delete the booking
        $sql = "DELETE FROM tblbooking WHERE BookingNumber = :bookingNumber";
        $query = $dbh->prepare($sql);
        $query->bindParam(':bookingNumber', $bookingNumber, PDO::PARAM_STR);
        
        if ($query->execute()) {
            // Send email to admin using Formspree
            $formspreeUrl = 'https://formspree.io/f/xqakplvw'; // Replace with your Formspree ID
            $data = [
                'userEmail' => $userEmail,
                'bookingNumber' => $bookingNumber,
                'message' => 'Booking has been returned successfully.'
            ];

            $options = [
                'http' => [
                    'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => http_build_query($data),
                ],
            ];
            $context  = stream_context_create($options);
            $result = file_get_contents($formspreeUrl, false, $context);

            // Alert user based on email sending result
            if ($result !== false) {
                echo "<script>alert('Booking returned successfully and email sent to admin!');</script>";
            } else {
                echo "<script>alert('Booking returned successfully but failed to send email to admin.');</script>";
            }
        } else {
            echo "<script>alert('Something went wrong. Please try again later.');</script>";
        }
    } else {
        echo "<script>alert('No booking found for this number.');</script>";
    }
}

// Handle payment confirmation
if (isset($_POST['confirmPaymentBtn'])) {
    $bookingNumber = $_POST['bookingNumber']; // Get booking number from form

    // Fetch the user email based on the booking number
    $sql = "SELECT userEmail FROM tblbooking WHERE BookingNumber = :bookingNumber";
    $query = $dbh->prepare($sql);
    $query->bindParam(':bookingNumber', $bookingNumber, PDO::PARAM_STR);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_OBJ);

    if ($user) {
        $userEmail = $user->userEmail; // Fetch user email

        // Send email to admin for payment confirmation
        $formspreeUrl = 'https://formspree.io/f/xqakplvw'; // Replace with your Formspree ID
        $data = [
            'userEmail' => $userEmail,
            'bookingNumber' => $bookingNumber,
            'message' => 'Payment for booking has been confirmed.'
        ];

        $options = [
            'http' => [
                'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
            ],
        ];
        $context  = stream_context_create($options);
        $result = file_get_contents($formspreeUrl, false, $context);

        // Alert user based on email sending result
        if ($result !== false) {
            echo "<script>alert('Payment confirmed successfully and email sent to admin!');</script>";
        } else {
            echo "<script>alert('Payment confirmed successfully but failed to send email to admin.');</script>";
        }
    } else {
        echo "<script>alert('No booking found for this number.');</script>";
    }
}
//var_dump($bookingNumber)
?>

<!DOCTYPE HTML>
<html lang="en">

<head>
  <title>UniRide Portal - My Booking</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
  <!-- Custom Style -->
  <link rel="stylesheet" href="assets/css/style.css" type="text/css">
  <!-- Other CSS Files -->
  <link rel="stylesheet" href="assets/css/owl.carousel.css" type="text/css">
  <link rel="stylesheet" href="assets/css/owl.transitions.css" type="text/css">
  <link href="assets/css/slick.css" rel="stylesheet">
  <link href="assets/css/bootstrap-slider.min.css" rel="stylesheet">
  <link href="assets/css/font-awesome.min.css" rel="stylesheet">
  <link rel="stylesheet" id="switcher-css" type="text/css" href="assets/switcher/css/switcher.css" media="all" />
  <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/red.css" title="red" media="all"
    data-default-color="true" />
  <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/orange.css" title="orange" media="all" />
  <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/blue.css" title="blue" media="all" />
  <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/pink.css" title="pink" media="all" />
  <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/green.css" title="green" media="all" />
  <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/purple.css" title="purple" media="all" />
  <link rel="apple-touch-icon-precomposed" sizes="144x144"
    href="assets/images/favicon-icon/apple-touch-icon-144-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114"
    href="assets/images/favicon-icon/apple-touch-icon-114-precomposed.html">
  <link rel="apple-touch-icon-precomposed" sizes="72x72"
    href="assets/images/favicon-icon/apple-touch-icon-72-precomposed.png">
  <link rel="apple-touch-icon-precomposed" href="assets/images/favicon-icon/apple-touch-icon-57-precomposed.png">
  <link rel="shortcut icon" href="assets/images/favicon-icon/favicon.png">
  <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">
  <!-- HTML5 Shim and Respond.js for IE8 -->
  <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
  <?php include('includes/colorswitcher.php');?>

  <?php include('includes/header.php');?>

  <section class="page-header profile_page">
    <div class="container">
      <div class="page-header_wrap">
        <div class="page-heading">
          <h1>My Booking</h1>
        </div>
        <ul class="coustom-breadcrumb">
          <li><a href="#">Home</a></li>
          <li>My Booking</li>
        </ul>
      </div>
    </div>
    <div class="dark-overlay"></div>
  </section>

  <?php 
$useremail = $_SESSION['login'];
$sql = "SELECT * FROM tblusers WHERE EmailId = :useremail";
$query = $dbh->prepare($sql);
$query->bindParam(':useremail', $useremail, PDO::PARAM_STR);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);

if($query->rowCount() > 0) {
  foreach($results as $result) { ?>

  <section class="user_profile inner_pages">
    <div class="container">
      <div class="user_profile_info gray-bg padding_4x4_40">
        <div class="upload_user_logo"> <img src="assets/images/dealer-logo.jpg" alt="image">
        </div>

        <div class="dealer_info">
          <h5><?php echo htmlentities($result->FullName);?></h5>
          <p><?php echo htmlentities($result->Address);?><br>
            <?php echo htmlentities($result->City);?>&nbsp;<?php echo htmlentities($result->Country); }}?></p>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3 col-sm-3">
          <?php include('includes/sidebar.php');?>
          <div class="col-md-8 col-sm-8">
            <div class="profile_wrap">
              <h5 class="uppercase underline">My Bookings </h5>
              <div class="my_vehicles_list">
                <ul class="vehicle_listing">
                  <?php 
$sql = "SELECT tblvehicles.Vimage1 as Vimage1, tblvehicles.VehiclesTitle, tblvehicles.id as vid, tblbrands.BrandName, tblbooking.FromDate, tblbooking.ToDate, tblbooking.message, tblbooking.Status, tblvehicles.PricePerDay, DATEDIFF(tblbooking.ToDate, tblbooking.FromDate) as totaldays, tblbooking.BookingNumber  
        FROM tblbooking 
        JOIN tblvehicles ON tblbooking.VehicleId = tblvehicles.id 
        JOIN tblbrands ON tblbrands.id = tblvehicles.VehiclesBrand 
        WHERE tblbooking.userEmail = :useremail 
        ORDER BY tblbooking.id DESC";
$query = $dbh->prepare($sql);
$query->bindParam(':useremail', $useremail, PDO::PARAM_STR);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);

if($query->rowCount() > 0) {
  foreach($results as $result) {  ?>


                  <li>
                    <h4 style="color:red">Booking No #<?php echo htmlentities($result->BookingNumber);?></h4>
                    <div class="vehicle_img"> <a
                        href="vehical-details.php?vhid=<?php echo htmlentities($result->vid);?>"><img
                          src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage1);?>" alt="image"></a>
                    </div>
                    <div class="vehicle_title">

                      <h6><a href="vehical-details.php?vhid=<?php echo htmlentities($result->vid);?>">
                          <?php echo htmlentities($result->BrandName);?> ,
                          <?php echo htmlentities($result->VehiclesTitle);?></a></h6>
                      <p><b>From </b> <?php echo htmlentities($result->FromDate);?> <b>To </b>
                        <?php echo htmlentities($result->ToDate);?></p>
                      <div style="float: left">
                        <p><b>Message:</b> <?php echo htmlentities($result->message);?> </p>
                      </div>
                    </div>
                    <?php if($result->Status==1)
                { ?>
                    <div class="vehicle_status">
                      <a href="#" class="btn outline btn-xs active-btn">Confirmed</a>
                      <div class="clearfix"></div>
                    </div>

                    <?php } else if($result->Status==2) { ?>
                    <div class="vehicle_status"> <a href="#" class="btn outline btn-xs">Cancelled</a>
                      <div class="clearfix"></div>
                    </div>



                    <?php } else { ?>
                    <div class="vehicle_status"> <a href="#" class="btn outline btn-xs">Not Confirm yet</a>
                      <div class="clearfix"></div>
                    </div>
                    <?php } ?>

                  </li>

                  <h5 style="color:blue">Invoice</h5>
                  <table>
                    <tr>
                      <th>Car Name</th>
                      <th>From Date</th>
                      <th>To Date</th>
                      <th>Total Days</th>
                      <th>Rent / Day</th>
                    </tr>
                    <tr>
                      <td><?php echo htmlentities($result->VehiclesTitle);?>,
                        <?php echo htmlentities($result->BrandName);?></td>
                      <td><?php echo htmlentities($result->FromDate);?></td>
                      <td> <?php echo htmlentities($result->ToDate);?></td>
                      <td><?php echo htmlentities($tds=$result->totaldays);?></td>
                      <td> <?php echo htmlentities($ppd=$result->PricePerDay);?></td>
                    </tr>
                    <tr>
                      <th colspan="4" style="text-align:center;"> Grand Total</th>
                      <th><?php echo htmlentities($tds*$ppd);?></th>
                    </tr>
                    <?php if($result->Status==1) { ?>
                    <tr>
                      <th colspan="2">
                        <a href="#" class="btn outline btn-xs active-btn" data-toggle="modal"
                          data-target="#paymentModal">Proceed to Payment</a>
                      </th>
                      <th colspan="3">
                        <form method="post" action="" onsubmit="return confirmReturn()">
                          <input type="hidden" name="bookingNumber"
                            value="<?php echo htmlentities($result->BookingNumber);?>">
                          <button type="submit" name="returnBtn" class="btn outline btn-xs">Return</button>
                        </form>
                      </th>
                    </tr>
                    <?php } ?>
                  </table>
                  <hr />
                  <?php }}  else { ?>
                  <h5 align="center" style="color:red">No booking yet</h5>
                  <?php } ?>


                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>

  </section>
  <div id="paymentModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="paymentModalLabel">Confirm Payment</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Select your payment method:</p>
          <div class="payment-methods">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="paymentMethod" id="paypalOption" value="paypal"
                checked>
              <label class="form-check-label" for="paypalOption">
                <i class="fa fa-paypal"></i> PayPal
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="paymentMethod" id="bankTransferOption" value="bank">
              <label class="form-check-label" for="bankTransferOption">
                <i class="fa fa-university"></i> Bank Transfer
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="paymentMethod" id="creditCardOption"
                value="creditcard">
              <label class="form-check-label" for="creditCardOption">
                <i class="fa fa-credit-card"></i> Credit Card
              </label>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <form method="post" action="">
            <input type="hidden" name="bookingNumber" value="<?php echo htmlentities($result->BookingNumber);?>">
            <button class="btn outline btn-xs active-btn" type="submit" name="confirmPaymentBtn">Confirm
              Payment</button>
          </form>

        </div>
      </div>
    </div>
  </div>


  <!--/my-vehicles-->
  <?php include('includes/footer.php');?>

  <!-- Scripts -->
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
  <script src="assets/js/interface.js"></script>
  <!--Switcher-->
  <script src="assets/switcher/js/switcher.js"></script>
  <!--bootstrap-slider-JS-->
  <script src="assets/js/bootstrap-slider.min.js"></script>
  <!--Slider-JS-->
  <script src="assets/js/slick.min.js"></script>
  <script src="assets/js/owl.carousel.min.js"></script>
  <script>
    function confirmReturn() {
      return confirm("Are you sure you want to return?")
    }

    document.getElementById('confirmPaymentBtn').addEventListener('click', function () {
      const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked').value;

      if (paymentMethod === 'paypal') {
        fetch('send_email.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({
              paymentMethod: paymentMethod
            })
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              alert('Payment confirmed via PayPal. Notification sent to admin.');
            } else {
              alert('There was an error confirming the payment.');
            }
          })
          .catch((error) => {
            console.error('Error:', error);
            alert('Error sending notification.');
          });
      } else {
        alert('Payment method selected: ' + paymentMethod);
      }
    });
  </script>

</body>

</html>