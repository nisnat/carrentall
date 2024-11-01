<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('includes/config.php'); // Include your database connection file

// Get booking_id from the URL and trim whitespace
$booking_id = isset($_GET['booking_id']) ? trim($_GET['booking_id']) : '';

// Check if a booking ID was provided and it is valid
if (empty($booking_id)) {
    echo "No booking ID specified.";
    exit;
}

// Prepare SQL statement
$sql = "SELECT 
        tblvehicles.Vimage1 as Vimage1, 
        tblvehicles.VehiclesTitle, 
        tblvehicles.id as vid, 
        tblbrands.BrandName, 
        tblbooking.FromDate, 
        tblbooking.ToDate, 
        tblbooking.message, 
        tblbooking.Status, 
        tblvehicles.PricePerDay, 
        DATEDIFF(tblbooking.ToDate, tblbooking.FromDate) as totaldays, 
        tblbooking.BookingNumber  
        FROM tblbooking 
        JOIN tblvehicles ON tblbooking.VehicleId = tblvehicles.id 
        JOIN tblbrands ON tblbrands.id = tblvehicles.VehiclesBrand 
        WHERE tblbooking.BookingNumber = :booking_id"; 

$query = $dbh->prepare($sql);
$query->bindParam(':booking_id', $booking_id, PDO::PARAM_STR);
$query->execute();

$result = $query->fetch(PDO::FETCH_OBJ); // Fetching the result

// Check if a result was returned
if (!$result) {
    echo "No results found for this booking ID.";
    exit; // Stop further execution if no result
}

// Debugging output (optional, remove in production)
echo "<pre>";
print_r($result);
echo "</pre>";
var_dump($result); 
?>

<!DOCTYPE HTML>
<html lang="en">

<head>
    <title>UniRide Portal - Payment</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
</head>

<body>
    <?php include('includes/header.php'); ?>

    <section class="page-header profile_page">
        <div class="container">
            <div class="page-header_wrap">
                <div class="page-heading">
                    <h1>Payment</h1>
                </div>
                <ul class="coustom-breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li>Payment</li>
                </ul>
            </div>
        </div>
        <div class="dark-overlay"></div>
    </section>

    <section class="user_profile inner_pages">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-8">
                    <h5 class="uppercase underline">Payment Details</h5>
                    <div class="my_vehicles_list">
                        <ul class="vehicle_listing">
                            <li>
                                <h4 style="color:red">Booking No
                                    #<?php echo htmlentities($result->BookingNumber ?? 'N/A'); ?></h4>
                                <div class="vehicle_img">
                                    <img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage1 ?? ''); ?>"
                                        alt="image">
                                </div>
                                <div class="vehicle_title">
                                    <h6><?php echo htmlentities($result->BrandName ?? ''); ?>,
                                        <?php echo htmlentities($result->VehiclesTitle ?? ''); ?></h6>
                                    <p><b>From:</b> <?php echo htmlentities($result->FromDate ?? 'N/A'); ?> <b>To:</b>
                                        <?php echo htmlentities($result->ToDate ?? 'N/A'); ?></p>
                                    <p><b>Total Days:</b> <?php echo htmlentities($result->totaldays ?? 'N/A'); ?></p>
                                    <p><b>Rent per Day:</b> $<?php echo htmlentities($result->PricePerDay ?? 0); ?></p>
                                    <p><b>Total Amount:</b>
                                        $<?php echo htmlentities(($result->totaldays ?? 0) * ($result->PricePerDay ?? 0)); ?>
                                    </p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <h5 class="uppercase underline">Select Payment Method</h5>
                    <form method="POST" action="process_payment.php">
                        <div class="form-group">
                            <label for="payment_method">Choose Payment Method:</label>
                            <select name="payment_method" id="payment_method" class="form-control">
                                <option value="paypal">PayPal</option>
                                <option value="stripe">Stripe</option>
                                <option value="razorpay">Razorpay</option>
                            </select>
                        </div>
                        <input type="hidden" name="bookingNumber"
                            value="<?php echo htmlentities($result->BookingNumber ?? ''); ?>">
                        <button type="submit" class="btn btn-primary">Proceed to Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <?php include('includes/footer.php'); ?>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</body>

</html>