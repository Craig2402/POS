 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Registration</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
              <li class="breadcrumb-item active">Registration</li>
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
          <div class="col-lg-4">
          <!-- /.col-md-6 -->

            <div class="card card-primary card-outline">
              <div class="card-header">
                <?php
                  if ($_SESSION['role'] == 'Administrator') {
                    echo '<h5 class="m-0">Add Supervisor</h5>';
                  }elseif ($_SESSION['role'] == 'Supervisor') {
                    echo '<h5 class="m-0">Add User</h5>';
                  }
                ?>
                
              </div>
              <div class="card-body">
                <div class="col-md-12">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Name</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Enter Fullname" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Username</label>
                                <input type="text" class="form-control" name="username" id="username" placeholder="Enter username" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Password</label>
                                <input type="password" class="form-control" name="userpassword" id="userpassword" placeholder="Password" required>
                            </div>
                            <div class="form-group">
                              <label for="Selectstore">Store</label>
                              <select class="form-control" name="Selectstore" id="Selectstore" required>
                              <option value="" disabled selected>Select a store</option>
                              <?php
                                $item = null;
                                $value = null;
                                $stores = storeController::ctrShowStores($item, $value);
                                foreach ($stores as $key => $value) {
                                  echo '<option value="'.$value["store_id"].'">'.$value["store_name"].'</option>';
                                }
                              ?>
                              </select>
                            </div>
                          <?php
                            if ($_SESSION['role'] == 'Administrator') {
                              echo '
                              <div class="form-group" style="display: none;>
                                <label for="exampleSelectBorder">Role</label>
                                <select class="form-control" name="roleOptions" id="roleOptions" required>
                                    <option value="Supervisor" selected>Supervisor</option>
                                </select>
                              </div>';
                            }elseif ($_SESSION['role'] == "Supervisor"){
                              echo '
                              <div class="form-group">
                                <label for="exampleSelectBorder">Role</label>
                                <select class="form-control" name="roleOptions" id="roleOptions" required>
                                    <option value="" disabled selected>Select role</option>
                                    <option value="Administrator">Administrator</option>
                                    <option value="Seller">Cashier</option>
                                    <option value="Store">Store keeper</option>
                                    <option value="Supervisor">Supervisor</option>
                                </select>
                              </div>';
                            }
                          ?>
                            <div class="form-group">
                                <div class="panel"><label for="exampleInputPassword1">Photo</label></div>
                                <input type="file" class="userphoto" name="userphoto" id="userphoto" >
                                <p class="help-block">Maximum file size 2mb</p>
                                <img src="views/img/users/default/anonymous.png" class="thumbnail preview" width="100px">
                            </div>
                        </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                      <div class="text-center">
                        <button type="submit" class="btn btn-primary" name="userReg">Register</button>
                      </div>
                    </div>
                        <?php
                        $createUser= new userController();
                        $createUser->ctrCreateUser();
                        ?>

                    </form>
                </div>
                    <!-- end of col-md-12 -->
              </div>
            </div>
    
          </div>
          <!-- /.col-md-4 -->
          <div class="col-lg-8">
          <!-- /.col-md-6 -->
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h5 class="m-0">Users</h5>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped tables">
                  <thead>
           
                    <tr>
                      
                      <th>#</th>
                      <th>Name</th>
                      <th>Username</th>
                      <th>Photo</th>
                      <th>Role</th>
                      <th>Store</th>
                      <th>Status</th>
                      <th>Last login</th>
                      <th>Actions</th>

                    </tr> 

                    </thead>
  
                  <tbody>
                  <?php
                    $item = null;
                    $value = null;
                    
                    if ($_SESSION['role'] == "Supervisor") {
                        $item = "store_id";
                        $value = $_SESSION['storeid'];
                    } elseif ($_SESSION['role'] == "Administrator" || $_SESSION['role'] == "Owner") {
                        $item = "role";
                        $value = "Supervisor";
                    }

                    $user = userController::ctrShowAllUsers($item, $value);
                    // var_dump($user);

                    foreach ($user as $key => $val) {
                      $item1 = "store_id";
                      $value1 = $val['store_id'];
                      $store = storeController::ctrShowStores($item1, $value1);
                      echo '

                      <tr>
                      <td>'.($key+1).'</td>
                      <td>'.$val["name"].'</td>
                      <td>'.$val["username"].'</td>';

                      if ($val["userphoto"] != ""){

                          echo '<td><img src="'.$val["userphoto"].'" class="img-thumbnail" width="40px"></td>';

                      }else{

                          echo '<td><img src="views/img/default/users/anonymous.png" class="img-thumbnail" width="40px"></td>';
                      
                      }

                      echo '<td>'.$val["role"].'</td>
                            <td>'.$store["store_name"].'</td>';

                      if($val["status"] != 0){

                          echo '<td><button class="btn btnActivate btn-success btn-xs" userId="'.$val["userId"].'" status="0">Activated</button></td>';

                      }else{

                          echo '<td><button class="btn btnActivate btn-danger btn-xs" userId="'.$val["userId"].'" status="1">Deactivated</button></td>';
                      }
                      
                      echo '<td>'.$val["lastlogin"].'</td>

                      <td>

                          <div class="btn-group">
                              
                          <button class="btn btnEditUser" userId="'.$val["userId"].'" data-toggle="modal" data-target="#editUser"><i class="fa fa-edit"></i></button>

                          <button class="btn btnDeleteUser" userId="'.$val["userId"].'" username="'.$val["username"].'" userPhoto="'.$val["userphoto"].'"><i class="fa fa-times"></i></button>

                          </div>  

                      </td>

                      </tr>';
                      
                    }
                  ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
    
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <div class="modal fade" id="editUser">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Edit Product</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
                        
            <form action="" method="post" enctype="multipart/form-data">
              <div class="card-body">
                  <div class="form-group">
                      <label for="exampleInputEmail1">Name</label>
                      <input type="text" class="form-control" name="editName" id="editName" value="">
                  </div>
                  <div class="form-group">
                      <label for="exampleInputEmail1">Username</label>
                      <input type="taxt" class="form-control" name="editUsername" id="editUsername" value="" readonly>
                  </div>
                  <div class="form-group">
                      <label for="exampleInputPassword1">Password</label>
                      <input type="password" class="form-control" name="editUserpassword" id="editUserpassword" placeholder="New Password">
                      <input type="hidden" name="actualPassword" id="actualPassword">
                  </div>
                  <div class="form-group">
                    <label for="Editstore">Store</label>
                    <select class="form-control" name="Editstore" id="Editstore" required>
                    <?php
                      $item = null;
                      $value = null;
                      $stores = storeController::ctrShowStores($item, $value);
                      foreach ($stores as $key => $value) {
                        echo '<option value="'.$value["store_id"].'">'.$value["store_name"].'</option>';
                      }
                    ?>
                    </select>
                  </div>
                  <div class="form-group">
                      <label for="exampleSelectBorder">Role</label>
                      <select class="form-control" name="editRoleOptions">
                          <option value="Administrator">Administrator</option>
                          <option value="Seller">Seller</option>
                          <option value="Store">Store keeper</option>
                          <option value="Supervisor">Supervisor</option>
                      </select>
                  </div>
                  <div class="form-group">
                      <div class="panel"><label for="exampleInputPassword1">Photo</label></div>
                      <input type="file" class="userphoto" name="editUserphoto" id="editUserphoto" >
                      <p class="help-block">Maximum file size 2mb</p>
                      <img src="views/img/default/users/anonymous.png" class="thumbnail preview" width="100px">
                      <input type="hidden" name="actualPhoto" id="actualPhoto">
                  </div>
              </div>
          <!-- /.card-body -->
              <?php
              $editUser= new userController();
              $editUser->ctrEditUser();
              ?>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" name="editUser" class="btn btn-primary">Save changes</button>
              </div>
          </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
      <?php
      $delUser= new userController();
      $delUser->ctrDeleteUser();
      ?>
      <?php
          $markRead = new notificationController();
          $markRead -> ctrMarkNotificationsRead();
      ?>