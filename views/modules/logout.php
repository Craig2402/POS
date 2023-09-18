<?php

if ($_SESSION['userId'] != 404) {
    // Create an array with the data for the activity log entry
    $data = array(
        'UserID' => $_SESSION['userId'],
        'ActivityType' => 'Logout',
        'ActivityDescription' => 'User ' . $_SESSION['username'] . ' logged out.'
    );
    // Call the ctrCreateActivityLog() function
    activitylogController::ctrCreateActivityLog($data);
}

session_destroy();

echo '<script>
 window.location= "login"
 </script>';

?>
'

?>