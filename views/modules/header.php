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
          <a href="#" class="dropdown-item">Profile</a>
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
<script>
  
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
</script>