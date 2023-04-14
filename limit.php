<?php
$allowed_requests = 10; // number of allowed requests
$interval = 60; // time interval in seconds

// get client IP address
$ip = $_SERVER['REMOTE_ADDR'];

// get current time in seconds
$now = time();

// get file name with IP address
$filename = 'ratelimit_' . $ip . '.txt';

// check if file exists and read the contents
if (file_exists($filename)) {
    $file_contents = file_get_contents($filename);
    $data = json_decode($file_contents, true);

    // check if last request was within the time interval
    if (($now - $data['last_request_time']) < $interval) {
        // check if the number of requests exceeds the allowed limit
        if ($data['num_requests'] >= $allowed_requests) {
            http_response_code(429); // too many requests
            echo 'Too many requests. Please try again later.';
            exit();
        } else {
            // increment the number of requests
            $data['num_requests']++;
        }
    } else {
        // reset the number of requests
        $data['num_requests'] = 1;
    }
} else {
    // create a new file with initial data
    $data = array(
        'last_request_time' => $now,
        'num_requests' => 1
    );
}

// update the last request time
$data['last_request_time'] = $now;

// save the data to the file
file_put_contents($filename, json_encode($data));

// process the request
// ...
?>
