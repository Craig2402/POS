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
                <h5 class="m-0">Add User</h5>
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
                                <label for="exampleSelectBorder">Role</label>
                                <select class="form-control" name="roleOptions" id="roleOptions" required>
                                    <option value="" disabled selected>Select role</option>
                                    <option value="Administrator">Administrator</option>
                                    <option value="Seller">Seller</option>
                                    <option value="Store">Store keeper</option>
                                </select>
                            </div>
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
            <div class="card card-danger card-outline">
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
                      <th>Status</th>
                      <th>Last login</th>
                      <th>Actions</th>

                    </tr> 

                    </thead>
  
                  <tbody>
                  <?php

                        $item = null; 
                        $value = null;

                        $user = userController::ctrShowUsers($item, $value);

                        //var_dump($user);

                        foreach ($user as $key => $value) {

                        echo '

                            <tr>
                            <td>'.($key+1).'</td>
                            <td>'.$value["name"].'</td>
                            <td>'.$value["username"].'</td>';

                            if ($value["userphoto"] != ""){

                                echo '<td><img src="'.$value["userphoto"].'" class="img-thumbnail" width="40px"></td>';

                            }else{

                                echo '<td><img src="views/img/default/users/anonymous.png" class="img-thumbnail" width="40px"></td>';
                            
                            }

                            echo '<td>'.$value["role"].'</td>';

                            if($value["status"] != 0){

                                echo '<td><button class="btn btn-success btnActivate btn-xs" userId="'.$value["userId"].'" status="0">Activated</button></td>';

                            }else{

                                echo '<td><button class="btn btn-danger btnActivate btn-xs" userId="'.$value["userId"].'" status="1">Deactivated</button></td>';
                            }
                            
                            echo '<td>'.$value["lastlogin"].'</td>

                            <td>

                                <div class="btn-group">
                                    
                                <button class="btn btn-warning btnEditUser" userId="'.$value["userId"].'" data-toggle="modal" data-target="#editUser"><i class="fa fa-edit"></i></button>

                                <button class="btn btn-danger btnDeleteUser" userId="'.$value["userId"].'" username="'.$value["username"].'" userPhoto="'.$value["userphoto"].'"><i class="fa fa-times"></i></button>

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
                                <label for="exampleSelectBorder">Role</label>
                                <select class="form-control" name="editRoleOptions">
                                    <option value="" id="editRoleOptions"></option>
                                    <option value="Administrator">Administrator</option>
                                    <option value="Seller">Seller</option>
                                    <option value="Store">Store keeper</option>
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