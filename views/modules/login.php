 <style>
  .link  {
  text-decoration: none;
  }
  </style>
<body class="hold-transition login-page">
  <div class="login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="#" class="h1 link"><b>Inventory</b>SYSTEM</a>
      </div>
      <div class="card">
        <div class="card-body login-card-body">
          <p class="login-box-msg">Login to start your session</p>
          <form action="" method="post">
            <div class="input-group mb-3">
              <input type="email" class="form-control" placeholder="Enter Email" name="txt_user" required>
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-user"></span>
                </div>
              </div>
            </div>
            <div class="input-group mb-3">
              <input type="password" class="form-control" placeholder="Enter Password" name="txt_password" required>
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block" name="btn_login">Login</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

  <?php
  $login= new userController();
  $login->ctrUserLogin();
?>