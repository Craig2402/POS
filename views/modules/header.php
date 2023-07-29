<style>
  /* Custom CSS for the profile image in the dropdown menu */
  .user-profile-img {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 10px;
  }
</style>
   
   
<?php
  // check if button is pressed
  $username=$_SESSION['username'];
  $pdo=connection::connect();
  if(isset($_POST['btnupdate'])){
    $update=$pdo->prepare("UPDATE users set userpassword=:pass where username=:username");
    $update->bindParam(':pass',$newpassword,PDO::PARAM_STR);
    $update->bindParam(':username',$username,PDO::PARAM_STR);
    if($update->execute()){
      // Create an array with the data for the activity log entry
      $logdata = array(
          'UserID' => $_SESSION['userId'],
          'ActivityType' => 'Password Change',
          'ActivityDescription' => 'User ' . $_SESSION['username'] . ' changed password.'
      );
      // Call the ctrCreateActivityLog() function
      activitylogController::ctrCreateActivityLog($logdata);

      $_SESSION['status']="Password changed";
      $_SESSION['status_code']="success";
      // session_destroy();
      // echo'
      // <script>
      //     window.location.reload();
      // </script>
      // ';
    }
  }
 ?>
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
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
          <img src="<?php echo $_SESSION['userphoto']; ?>" class="user-profile-img" alt="User Image" ><?php echo $_SESSION['username']; ?>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
          <?php
            echo '<a href="#" class="dropdown-item view-profile-link" userid="'.$_SESSION['userId'].'">Profile</a>';
          ?>
          <a href="#" class="dropdown-item" data-toggle="modal" data-target="#switchStoreModal">Switch Store</a>
          <?php
            if ($_SESSION['storeid'] !== null && $_SESSION['role'] == "Administrator") {
              echo '<a href="#" id="exit_store" value="'.$_SESSION['storeid'].'" class="dropdown-item">Exit store</a>';
            }
          ?>
          <div class="dropdown-divider"></div>
          <a href="logout" class="dropdown-item">Logout</a>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

<!-- Add the modal -->
<div class="modal fade" id="switchStoreModal" tabindex="-1" role="dialog" aria-labelledby="switchStoreModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="switchStoreModalLabel">Switch Store</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php
          $item = null;
          $value = null;
          $stores = storeController::ctrShowStores($item, $value);
          // Assuming the $stores array contains the store names and IDs from the database
          if (!empty($stores)) {
            foreach ($stores as $store) {
              $storeName = $store['store_name'];
              $storeID = $store['store_id'];
              // echo '<form action="ajax/session.ajax.php" method="post">';
              echo '<button class="btn switch-store-btn" id="store_id" value="' . $storeID . '">' . $storeName . '</button>';
              // echo '</form>';
            }
          } else {
            echo '<p>No stores found.</p>';
          }
        ?>
      </div>
    </div>
  </div>
</div>

<!-- Add the modal inside the body tag -->
<div class="modal fade" id="userProfileModal" tabindex="-1" role="dialog" aria-labelledby="userProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userProfileModalLabel">User Profile</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="modalBodyContent">
          <!-- Display user profile information here -->
          <img id="profilePicture" src="#" class="img-fluid rounded-circle mx-auto d-block" style="width: 100px;" alt="User Profile Picture">
          <h4 id="userName" class="text-center"></h4>
          <p id="userEmail" class="text-center"></p>
          <p id="userRole" class="text-center"></p>
          <?php if ($_SESSION['storeid'] !== null) { ?>
            <p id="userStore" class="text-center"></p>
          <?php } ?>
        </div>

        <!-- Content for password change -->
        <div class="passwordChangeContent" style="display: none;">
          <!-- Add the form or content for changing password here -->
          <div class="col-lg-12">
            <!-- Horizontal Form -->
            <!-- /.card-header -->
            <!-- form start -->
            <form class="form-horizontal" id="passwordChangeForm" action="" method="post">
              <div class="card-body">
                <div class="form-floating mb-3">
                  <input type="password" class="form-control oldpass" id="floatingInput" name="oldpass">
                  <input type="hidden" class="userId" value="<?php echo $_SESSION['userId'] ?>">
                  <label for="floatingInput">Old Password</label>
                </div>
                <div class="form-floating mb-3">
                  <input type="password" class="form-control newpass" id="floatingInput" name="newpass">
                  <label for="floatingInput">New Password</label>
                </div>
                <div class="form-floating mb-3">
                  <input type="password" class="form-control rnewpass" id="floatingInput" name="rnewpass">
                  <label for="floatingInput">Repeat New Password</label>
                </div>
                
              </div>
              <div class="alert alert-danger" id="passwordMismatchWarning" role="alert" style="display: none;">Passwords do not match!</div>
              <div class="alert alert-danger" id="oldpasswordMismatchWarning" role="alert" style="display: none;">Old Passwords is incorrect!</div>
              <div class="alert alert-danger" id="emptyFieldWarning" role="alert" style="display: none;">Empty field(s) found!</div>
              <!-- /.card-body -->
              <!-- /.card -->
              <div class="modal-footer justify-content-between updatePasswordFooter2" style="display: none;">
                <button type="button" class="btn btn-secondary closeBtn" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" name="btnupdate">Update password</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="modal-footer justify-content-between updatePasswordFooter1">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary changePasswordButton">Change password</button>
      </div>
    </div>
  </div>
</div>
<?php
if (isset($_SESSION['status'])&& $_SESSION['status']!=''){
  ?>
<script>
  Swal.fire({
    icon: '<?php echo $_SESSION['status_code'] ?>',
    title: '<?php echo $_SESSION['status'] ?>',
    showConfirmButton: false,
    timer: 2000 // Close the alert after 2 seconds
    }).then(function() {
      session_destroy()
      window.location.reload();
    });
</script>


<?php
unset($_SESSION['status']);
}

?>
<script>


  // Get the input fields for new password and repeat password
  // var userId = document.querySelector(".userId");
  var oldPasswordInput = document.querySelector(".oldpass");
  var newPasswordInput = document.querySelector(".newpass");
  var repeatPasswordInput = document.querySelector('.rnewpass');
  var oldpasswordMismatchWarning = document.getElementById('oldpasswordMismatchWarning');
  var emptyFieldWarning = document.getElementById('emptyFieldWarning');
// Variable to keep track of ongoing AJAX request for old password check
var checkingOldPassword = false;

// Function to check if the old password is correct
function checkOldPassword() {
  var oldpassword = document.querySelector(".oldpass").value;
  var userIdInput = document.querySelector(".userId");
  var userId = userIdInput.value;

  var data = new FormData();
  data.append("oldpassword", oldpassword);
  data.append("user-id", userId);

  // Set the checkingOldPassword flag to true while AJAX request is ongoing
  checkingOldPassword = true;

  // Make an AJAX request to fetch the user profile data
  $.ajax({
    type: "POST",
    url: "ajax/user.ajax.php",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    dataType: "json",
    success: function(answer) {
      console.log(answer);
      checkingOldPassword = false; // Reset the flag when AJAX request is completed
      if (!answer) {
        oldpasswordMismatchWarning.style.display = 'block';
      } else {
        oldpasswordMismatchWarning.style.display = 'none';
      }
    }

  });
}

// Event listener to trigger the check when the user types in the old password field
oldPasswordInput.addEventListener('keyup', checkOldPassword);

// Function to check if the new and repeat passwords match
function checkPasswordMatch() {
  var newPassword = newPasswordInput.value;
  var repeatPassword = repeatPasswordInput.value;
  if (newPassword !== repeatPassword) {
    passwordMismatchWarning.style.display = 'block';
  } else {
    passwordMismatchWarning.style.display = 'none';
  }
}

// Event listener to trigger the check when the user types in the repeat password field
repeatPasswordInput.addEventListener('input', checkPasswordMatch);

 // Function to check if all required fields are filled
 function checkFormFields() {
    var oldPassword = oldPasswordInput.value.trim();
    var newPassword = newPasswordInput.value.trim();
    var repeatPassword = repeatPasswordInput.value.trim();

    if (oldPassword === "" || newPassword === "" || repeatPassword === "") {
      event.preventDefault();
      emptyFieldWarning.style.display = 'block';
      return false;
    }

    // If all checks pass, return true to allow form submission
    return true;
  }

  // Add an event listener to the "Update password" button
  $(".updatePasswordFooter2 .btn-primary").on("click", function(event) {
    // Check if old password is being checked through AJAX request
    if (checkingOldPassword) {
      event.preventDefault();
      return;
    }

    // Check if any warning message is being displayed
    if (oldpasswordMismatchWarning.style.display === 'block' || passwordMismatchWarning.style.display === 'block') {
      event.preventDefault();
      return;
    }

    // Check if all required fields are filled
    if (!checkFormFields()) {
      event.preventDefault();
      return;
    }

    // If all checks pass, submit the form
    $("#passwordChangeForm").submit();
  });
  
$(document).on("click", "#store_id", function(){

  var storeid = $(this).attr("value");

  var data= new FormData();
  data.append("store_id", storeid);

  $.ajax({
      type: "POST",
      url: "ajax/session.ajax.php",
      data: data, // Send the userId as a query parameter
      contentType:false,
      caches:false,
      processData:false,
      dataType: "json",
      success: function(answer) {
        window.location = "dashboard"
      }
    });
});
$(document).on("click", "#exit_store", function(){

var storeid = $(this).attr("value");

var data= new FormData();
data.append("exit_store", storeid);

$.ajax({
    type: "POST",
    url: "ajax/session.ajax.php",
    data: data, // Send the userId as a query parameter
    contentType:false,
    caches:false,
    processData:false,
    dataType: "json",
    success: function(answer) {
      window.location = "dashboard"
    }
  });
});

// Wait for the document to be ready
$(document).ready(function() {
  // Add an event listener to the "Change password" button
  $(".changePasswordButton").on("click", function() {
    // Hide the current content (profile information)
    $(".modalBodyContent, .updatePasswordFooter1").fadeOut(200, function() {
      // Display the password change content
      $(".passwordChangeContent").fadeIn(200);
      $(".updatePasswordFooter2").fadeIn(200);
    });
  });
  
    // Add an event listener to the modal's hidden.bs.modal event
    $('#userProfileModal').on('hidden.bs.modal', function() {
      $("#passwordChangeForm")[0].reset();
      // Reset the modal content when the modal is closed
      $(".passwordChangeContent, .updatePasswordFooter2").fadeOut(200, function() {
        // Display the profile information content
        oldpasswordMismatchWarning.style.display = 'none';
        passwordMismatchWarning.style.display = 'none';
        emptyFieldWarning.style.display = 'none';
        $(".modalBodyContent, .updatePasswordFooter1").fadeIn(200);
      });
    });
});
</script>