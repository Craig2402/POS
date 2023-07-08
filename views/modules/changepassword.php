   
   
  <?php
  
  

   // check if button is pressed
   
   if(isset($_POST['btnupdate'])){

    $oldpassword=crypt($_POST['oldpass'], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');;
    $newpassword=crypt($_POST["newpass"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
    $rnewpassword=crypt($_POST["rnewpass"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
    
    
   // fetching records from database
   $username=$_SESSION['username'];
   $pdo=connection::connect();
   $select= $pdo ->prepare("SELECT * FROM users WHERE username = '$username'");
   $select->execute();
   
   $row = $select->fetch(PDO::FETCH_ASSOC);
  
  // comparing input with values in the database
  if($oldpassword==$row['userpassword']){
    if($newpassword=$rnewpassword){
        $update=$pdo->prepare("UPDATE users set userpassword=:pass where username=:username");
        $update->bindParam(':pass',$newpassword,PDO::PARAM_STR);
        $update->bindParam(':username',$username,PDO::PARAM_STR);
        if($update->execute()){
            $_SESSION['status']="Password changed successfully";
            $_SESSION['status_code']="success";
            session_destroy();
           echo'
            <script>
                window.location.reload();
            </script>
           ';
        }
    }
  }else{
            $_SESSION['status']="Error updating your password please check your inputs and try again";
            $_SESSION['status_code']="error";

   }
  }
  ?>
   
   
   <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Change password</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
              <li class="breadcrumb-item active">Change password </li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">


            <!-- Horizontal Form -->
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Change password</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form class="form-horizontal" action="" method="post">
                <div class="card-body">
                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Old Password</label>
                    <div class="col-sm-10">
                      <input type="password" class="form-control" id="inputPassword3" placeholder="Old Password" name="oldpass">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">New Password</label>
                    <div class="col-sm-10">
                      <input type="password" class="form-control" id="inputPassword3" placeholder="New Password" name="newpass">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Repeat New Password</label>
                    <div class="col-sm-10">
                      <input type="password" class="form-control" id="inputPassword3" placeholder="Repeat new Password" name="rnewpass">
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-info" name="btnupdate">Update password</button>
                </div>
                <!-- /.card-footer -->
              </form>
            </div>
            <!-- /.card -->
    
          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  <?php

   include_once 'footer.php';
  ?>

<?php
if (isset($_SESSION['status'])&& $_SESSION['status']!=''){
  ?>
<script>

      Swal.fire({
        icon: '<?php echo $_SESSION['status_code'] ?>',
        title: '<?php echo $_SESSION['status'] ?>'
      });
</script>


<?php
unset($_SESSION['status']);
}

?>
<?php
    $markRead = new notificationController();
    $markRead -> ctrMarkNotificationsRead();
?>