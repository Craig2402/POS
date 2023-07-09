<?php
    session_start();

    // Retrieve session data
    $sessionId = $_SESSION['userId'];
    $sessionRole = $_SESSION['role'];
    $sessionUsername = $_SESSION['username'];

    // Create an associative array to hold the session data
    $sessionData = array(
        'sessionId' => $sessionId,
        'sessionRole' => $sessionRole,
        'sessionUsername' => $sessionUsername
    );

    // Convert the array to JSON and echo the response
    echo json_encode($sessionData);
?>
