<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON data from the request
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['paymentMethod']) && $data['paymentMethod'] === 'paypal') {
        // Define the email parameters
        $to = "izzrieqilhan@gmail.com"; // Replace with the admin email
        $subject = "Payment Notification";
        $message = "Payment received via PayPal.";
        $headers = "From: no-reply@example.com"; // Replace with your domain

        // Send the email
        if (mail($to, $subject, $message, $headers)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Email could not be sent.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid payment method.']);
    }
}
?>
