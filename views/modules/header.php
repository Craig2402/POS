<div class="wrapper">
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <!-- Left navbar links -->
      <li class="nav-item d-none d-sm-inline-block">
        <!-- <a href="index3.html" class="nav-link">Home</a> -->
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <!-- <a href="#" class="nav-link">Contact</a>-->
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
      <!-- Right navbar links -->
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <!-- Notifications Dropdown Menu -->
      <?php
          // $item = null;
          // $value = null;
          // $notifications = notificationController::ctrShowNotifications($item, $value);
          // // echo '<script>
          // //   var datum = new FormData();
            
          // //   $.ajax({
          // //       url: "ajax/notifications.ajax.php",
          // //       method: "POST",
          // //       data: datum,
          // //       cache: false,
          // //       contentType: false,
          // //       processData: false,
          // //       dataType: "json",
          // //       success: function(answer) {
          // //           // Handle the AJAX success response here
          // //       }
        
          // //   });
          // // </script>';

          // $currentDateTime = new DateTime(); // Get current date and time
          // $userRole = $_SESSION['role']; // Assuming the session role is stored in the 'role' key of the $_SESSION array
          // $userId = $_SESSION['userId']; // Assuming the session ID is stored in the 'userId' key of the $_SESSION array
          
          // // Array to store unread notifications
          // $unreadNotifications = []; 

          // // Initialize an array to hold the allowed notification types for each role
          // $allowedNotificationTypes = [];
          // if ($userRole === 'Administrator') {
          //     // Admin can see all notification types
          //     $allowedNotificationTypes = ['Stock notification', 'Category deletion', 'Product deletion', 'Admin feedback'];
          // } elseif ($userRole === 'Store') {
          //     // Storekeeper can see 'Orders', 'Stock', and 'Acceptions' notification types
          //     $allowedNotificationTypes = ['Admin feedback', 'Stock', 'Acceptions'];
          // } elseif ($userRole === 'Seller') {
          //     // Seller can see 'Stock' and 'Acceptions' notification types
          //     $allowedNotificationTypes = ['Stock', 'Acceptions'];
          // }

          // // Now loop through the notifications
          // foreach ($notifications as $notification) {
          //   // define some variables to be used later
          //   $message = $notification['message'];
          //   $notification_type = $notification['notification_type'];
          //   $createDate = new DateTime($notification['create_date']);
          //   $status = $notification['status'];
          //   $name = $notification['name'];
          //   $viewedBy = $notification['viewed_by'];

          //   // Check if the notification type is allowed for the user's role
          //   $notificationTypeAllowed = false;
          //   foreach ($allowedNotificationTypes as $allowedType) {
          //       if (strpos($notification['notification_type'], $allowedType) !== false) {
          //           $notificationTypeAllowed = true;
          //           break;
          //       }
          //   }

          //   // If the notification type is not allowed for the user's role, skip this notification
          //   if (!$notificationTypeAllowed) {
          //       continue;
          //   }

          //   $productName = ''; // Initialize the variable with an empty string
          //   $categoryName = ''; // Initialize the variable with an empty string
          //   $viewUser = ''; // Initialize the variable with an empty string
          //   // Checking if the notification type is "Stock notification"
          //   if (strpos($notification['notification_type'], 'Stock notification') !== false) {
          //       // Extracting the notification type and barcode
          //       $notificationTypeParts = explode(',', $notification['notification_type']);
          //       $notificationType = trim($notificationTypeParts[0]);
          //       $barcode = trim($notificationTypeParts[1]);

          //       // Assigning the value to the variable
          //       $href = 'index.php?route=viewproduct&barcode=' . $barcode;
          //       $icon = '<i class="fa-solid fa-boxes-stacked"></i>';
          //   }elseif (strpos($notification['notification_type'], 'Category deletion') !== false) {
          //       // Extracting the notification type and barcode
          //       $notificationTypeParts = explode(',', $notification['notification_type']);
          //       $notificationType = trim($notificationTypeParts[0]);
          //       $categoryid = trim($notificationTypeParts[1]);
                
          //       // Assigning the value to the variable
          //       $item = "id";
          //       $value = $categoryid;
          //       $answer = categoriesController::ctrShowCategories($item, $value);
          //       $categoryName = $answer["Category"];

          //       $icon = '<i class="fa-solid fa-sitemap"></i>';
          //   }elseif (strpos($notification['notification_type'], 'Product deletion') !== false) {
          //       // Extracting the notification type and barcode
          //       $notificationTypeParts = explode(',', $notification['notification_type']);
          //       $notificationType = trim($notificationTypeParts[0]);
          //       $categoryid = trim($notificationTypeParts[1]);

          //       // Assigning the value to the variable
          //       $item = 'barcode';
          //       $value = $categoryid;
          //       $order = 'id';
          //       $answer = productController::ctrShowProducts($item, $value, $order);
          //       $productName = $answer['product'];
          //       $icon = '<i class="fa-solid fa-boxes-stacked"></i>';
          //   }elseif (strpos($notification['notification_type'], 'Admin feedback') !== false) {
          //     // Extracting the notification type and barcode
          //     $notificationTypeParts = explode(',', $notification['notification_type']);
          //     $notificationType = trim($notificationTypeParts[0]);
          //     $viewUser = trim($notificationTypeParts[1]);

          //     $icon = '<i class="fa-solid fa-xmark"></i>';
          //   }

          //   // Check if the current session ID is present in the 'viewable_by' column and the status is unread (0)
          //   if (strpos($viewedBy, $userId) === false) {
          //       // Get the notification datetime from your data with the timezone information
          //       $notificationDatetime = new DateTime($notification['create_date'], new DateTimeZone('africa/nairobi'));

          //       // Get the current datetime with the same timezone
          //       $currentDatetime = new DateTime('now', new DateTimeZone('africa/nairobi'));

          //       // Calculate the time difference using DateTime::diff() method
          //       $timeDifference = $currentDatetime->diff($notificationDatetime);

          //       // Get the time components
          //       $years = $timeDifference->y;
          //       $months = $timeDifference->m;
          //       $weeks = floor($timeDifference->days / 7);
          //       $days = $timeDifference->days % 7;
          //       $hours = $timeDifference->h;
          //       $minutes = $timeDifference->i;
          //       $seconds = $timeDifference->s;

          //       // Build the time difference string
          //       $timeDifferenceString = "";
          //       if ($years > 0) {
          //         $timeDifferenceString .= $years . " year";
          //         if ($years > 1) {
          //           $timeDifferenceString .= "s";
          //         }
          //       }
          //       if ($months > 0) {
          //         $timeDifferenceString .= ($timeDifferenceString !== "") ? ", " : "";
          //         $timeDifferenceString .= $months . " month";
          //         if ($months > 1) {
          //           $timeDifferenceString .= "s";
          //         }
          //       }
          //       if ($weeks > 0) {
          //         $timeDifferenceString .= ($timeDifferenceString !== "") ? ", " : "";
          //         $timeDifferenceString .= $weeks . " week";
          //         if ($weeks > 1) {
          //           $timeDifferenceString .= "s";
          //         }
          //       }
          //       if ($days > 0) {
          //         $timeDifferenceString .= ($timeDifferenceString !== "") ? ", " : "";
          //         $timeDifferenceString .= $days . " day";
          //         if ($days > 1) {
          //           $timeDifferenceString .= "s";
          //         }
          //       }
          //       if ($hours > 0) {
          //         $timeDifferenceString .= ($timeDifferenceString !== "") ? ", " : "";
          //         $timeDifferenceString .= $hours . " hour";
          //         if ($hours > 1) {
          //           $timeDifferenceString .= "s";
          //         }
          //       }
          //       if ($minutes > 0) {
          //         $timeDifferenceString .= ($timeDifferenceString !== "") ? ", " : "";
          //         $timeDifferenceString .= $minutes . " minute";
          //         if ($minutes > 1) {
          //           $timeDifferenceString .= "s";
          //         }
          //       }
          //       if ($seconds > 0) {
          //         $timeDifferenceString .= ($timeDifferenceString !== "") ? ", " : "";
          //         $timeDifferenceString .= $seconds . " second";
          //         if ($seconds > 1) {
          //           $timeDifferenceString .= "s";
          //         }
          //       }
          //       if ($timeDifferenceString === "") {
          //         $timeDifferenceString = "less than a second";
          //       }

          //       $currentRoute = $_SERVER['REQUEST_URI'];
          //       $routeParts = explode('/', $currentRoute);
          //       $lastPart = end($routeParts);

          //       $unreadNotifications[] = [
          //         'name' => $name,
          //         'message' => $message,
          //         'notificationType' => $notification_type,
          //         'href' => $href,
          //         'icon' => $icon,
          //         'value' => $value,
          //         'type' => $notificationType,
          //         'session' => $userId,
          //         'category' => $categoryName,
          //         'viewUser' => $viewUser,
          //         'product' => $productName,
          //         'formattedDuration' => $timeDifferenceString,
          //         'currentRoute' => $lastPart,
          //         'barcode' => $barcode
          //       ];

          //     }

          // }

          // $notificationCount = count($unreadNotifications); // Get the count of unread notifications
          
          // // Pass the session data to JavaScript
          // echo '<script>';
          // echo 'const sessionId = ' . json_encode($userId) . ';'; // Encode session data as JSON
          // echo '</script>';
      ?>


      <li class="nav-item">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge" id="rowCountSpan"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <div class="dropdown-header" id="rowCountSpanHeader"></div>
          <div class="dropdown-divider"></div>
          <div class="notificationItems"></div>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->