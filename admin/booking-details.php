<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    // Handle cancellation of booking
    if (isset($_REQUEST['eid'])) {
        $eid = intval($_GET['eid']);
        $status = "2";
        $sql = "UPDATE tblbooking SET Status=:status WHERE id=:eid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':eid', $eid, PDO::PARAM_STR);
        $query->execute();
        echo "<script>alert('Booking Successfully Cancelled');</script>";
        echo "<script type='text/javascript'> document.location = 'cancelled-bookings.php'; </script>";
    }

    // Handle confirmation of booking
    if (isset($_REQUEST['aeid'])) {
        $aeid = intval($_GET['aeid']);
        $status = 1;
        $sql = "UPDATE tblbooking SET Status=:status WHERE id=:aeid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':aeid', $aeid, PDO::PARAM_STR);
        $query->execute();
        echo "<script>alert('Booking Successfully Confirmed');</script>";
        echo "<script type='text/javascript'> document.location = 'confirmed-bookings.php'; </script>";
    }
?>

<!doctype html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">
    <title>UniRide Portal | New Bookings</title>

    <!-- CSS Links -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-social.css">
    <link rel="stylesheet" href="css/bootstrap-select.css">
    <link rel="stylesheet" href="css/fileinput.min.css">
    <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .errorWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
        .succWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #5cb85c;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
    </style>
</head>
<body>
    <?php include('includes/header.php'); ?>
    <div class="ts-main-content">
        <?php include('includes/leftbar.php'); ?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="page-title">Booking Details</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">Bookings Info</div>
                            <div class="panel-body">
                                <div id="print">
                                    <table border="1" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                        <tbody>
                                        <?php
                                            $bid = intval($_GET['bid']);
                                            $sql = "SELECT tblvehicles.VehiclesTitle, 
                                                    tblbooking.FromDate, tblbooking.ToDate, 
                                                    tblbooking.message, tblbooking.VehicleId as vid, 
                                                    tblbooking.Status, tblbooking.PostingDate, 
                                                    tblbooking.id, tblbooking.BookingNumber, 
                                                    DATEDIFF(tblbooking.ToDate, tblbooking.FromDate) as totalnodays, 
                                                    tblvehicles.PricePerDay, tblbooking.LastUpdationDate, 
                                                    tblbooking.id_card, tblbooking.driving_license
                                                FROM tblbooking
                                                JOIN tblvehicles ON tblvehicles.id = tblbooking.VehicleId
                                                WHERE tblbooking.id = :bid";
                                            $query = $dbh->prepare($sql);
                                            $query->bindParam(':bid', $bid, PDO::PARAM_STR);
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_OBJ);

                                            if ($query->rowCount() > 0) {
                                                foreach ($results as $result) {
                                                    echo '<h3 style="text-align:center; color:red">#' . htmlentities($result->BookingNumber) . ' Booking Details</h3>';
                                                    ?>
                                                    <tr>
                                                        <th>Vehicle Title</th>
                                                        <td><?php echo htmlentities($result->VehiclesTitle); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>From Date</th>
                                                        <td><?php echo htmlentities($result->FromDate); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>To Date</th>
                                                        <td><?php echo htmlentities($result->ToDate); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Message</th>
                                                        <td><?php echo htmlentities($result->message); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Status</th>
                                                        <td><?php echo htmlentities($result->Status == 0 ? 'Pending' : ($result->Status == 1 ? 'Confirmed' : 'Cancelled')); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Last Updation Date</th>
                                                        <td><?php echo htmlentities($result->LastUpdationDate ?? 'Not available'); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Booking Number</th>
                                                        <td><?php echo htmlentities($result->BookingNumber); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total Days</th>
                                                        <td><?php echo htmlentities($result->totalnodays); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Price Per Day</th>
                                                        <td><?php echo htmlentities($result->PricePerDay); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>ID Card</th>
                                                        <td>
                                                            <?php if (!empty($result->id_card)): ?>
                                                                <a href="../<?php echo htmlentities($result->id_card); ?>" target="_blank">View ID Card</a>
                                                            <?php else: ?>
                                                                Not Uploaded
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>License</th>
                                                        <td>
                                                            <?php if (!empty($result->driving_license)): ?>
                                                                <a href="../<?php echo htmlentities($result->driving_license); ?>" target="_blank">View License</a>
                                                            <?php else: ?>
                                                                Not Uploaded
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                    <?php if ($result->Status == 0) { ?>
                                                        <tr>
                                                            <td style="text-align:center" colspan="4">
                                                                <a href="booking-details.php?aeid=<?php echo htmlentities($result->id); ?>" onclick="return confirm('Do you really want to Confirm this booking?')" class="btn btn-primary"> Confirm Booking</a> 
                                                                <a href="booking-details.php?eid=<?php echo htmlentities($result->id); ?>" onclick="return confirm('Do you really want to Cancel this Booking?')" class="btn btn-danger"> Cancel Booking</a>
                                                            </td>
                                                        </tr>
                                                    <?php }
                                                }
                                            } ?>
                                        </tbody>
                                    </table>
                                    <form method="post">
                                        <input name="Submit2" type="submit" class="txtbox4" value="Print" onClick="return f3();" style="cursor: pointer;" />
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- JS Links -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap.min.js"></script>
    <script src="js/bootbox.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
<?php } ?>
