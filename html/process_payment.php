<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve payment details from the form
    $amount = $_POST['amount'];
    // Additional payment details can be retrieved as needed

    // Set up PayPal API credentials
    $clientId = "AanmzB2I0RsEq9nUQfv69H4pgxx-eWwXzCR4S7iLUyaroPx7LPBV2Saf7o94-GnoNEH4A0nMXyEa4h8C";
    $clientSecret = "EJXe4PZTKlsbwqIyJ6qtCmGiOnXaVO1umKxjzrfxtruGRRCzShtPZ0_MRE1tx4YO4tPbyG2buv3leUHU";
    $environment = "sandbox"; // Change to "live" for production

    // Set up PayPal API endpoints
    $paypalApiUrl = ($environment == "sandbox") ? "https://api.sandbox.paypal.com" : "https://api.paypal.com";
    $tokenUrl = $paypalApiUrl . "/v1/oauth2/token";
    $paymentUrl = $paypalApiUrl . "/v2/checkout/orders";

    // Generate access token for PayPal API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $tokenUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Basic ' . base64_encode("$clientId:$clientSecret"),
        'Content-Type: application/x-www-form-urlencoded'
    ));
    $tokenResponse = curl_exec($ch);
    $accessToken = json_decode($tokenResponse)->access_token;
    curl_close($ch);

    // Create payment order with PayPal API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $paymentUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array(
        'intent' => 'CAPTURE',
        'purchase_units' => array(
            array(
                'amount' => array(
                    'currency_code' => 'USD',
                    'value' => $amount
                )
            )
        )
    )));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $accessToken
    ));
    $paymentResponse = curl_exec($ch);
    $paymentData = json_decode($paymentResponse);
    curl_close($ch);

    // Redirect user to PayPal for payment approval
    if (isset($paymentData->id)) {
        $approvalUrl = $paymentData->links[1]->href;
        header("Location: $approvalUrl");
        exit();
    } else {
        // Handle payment creation error
        // Redirect to failure page or display error message
        header("Location: payment_failure.php");
        exit();
    }
} else {
    // If the form is not submitted, redirect to the checkout page
    header("Location: checkout.php");
    exit();
}
?>
