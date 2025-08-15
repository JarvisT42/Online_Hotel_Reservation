<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $secretKey = "6LfApqArAAAAAEOmUTic4ZG3fLRVUHvdnz4hI55E"; // from Google reCAPTCHA
    $responseKey = $_POST['g-recaptcha-response'];
    $userIP = $_SERVER['REMOTE_ADDR'];

    // Send request to Google
    $url = "https://www.google.com/recaptcha/api/siteverify";
    $data = [
        'secret' => $secretKey,
        'response' => $responseKey,
        'remoteip' => $userIP
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        ]
    ];

    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    $response = json_decode($result);

    // if ($response->success) {
    //     echo "reCAPTCHA verified! ✅";
    //     // process your booking form here
    // } else {
    //     echo "Please complete the reCAPTCHA ❌";
    // }
}
