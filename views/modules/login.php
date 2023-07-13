 <style>
    body {
      background-color: #f8f9fa;
    }

    .login-page {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .login-logo {
      font-size: 36px;
      font-weight: bold;
      color: #17a2b8;
    }

    .login-box {
      width: 360px;
    }

    .login-card-body {
      border-radius: 10px;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
      background-color: #ffffff;
      padding: 30px;
    }

    .login-box-msg {
      font-size: 18px;
      margin-bottom: 30px;
    }

    .input-group-text {
      background-color: #ffffff;
      border-left: none;
    }

    .input-group-text .fas {
      color: #17a2b8;
    }

    .btn-primary {
      background-color: #17a2b8;
      border-color: #17a2b8;
    }

    .btn-primary:hover {
      background-color: #138496;
      border-color: #138496;
    }

    .social-auth-links .btn {
      margin-bottom: 10px;
    }

    .social-auth-links .fab {
      margin-right: 10px;
      font-size: 18px;
    }

    .mb-0 a {
      color: #17a2b8;
      font-weight: 600;
    }

    .mb-0 a:hover {
      text-decoration: underline;
    }
  </style>
<body class="hold-transition login-page">
  <div class="login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="#" class="h1"><b>Inventory</b>SYSTEM</a>
      </div>
      <div class="card">
        <div class="card-body login-card-body">
          <p class="login-box-msg">Sign in to start your session</p>
          <form action="" method="post">
            <div class="input-group mb-3">
              <input type="text" class="form-control" placeholder="Username" name="txt_user" required>
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-user"></span>
                </div>
              </div>
            </div>
            <div class="input-group mb-3">
              <input type="password" class="form-control" placeholder="Password" name="txt_password" required>
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block" name="btn_login">Login In</button>
              </div>
            </div>
          </form>
          <p class="mb-1 text-center">
            <a href="forgot-password.html">I forgot my password</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</body>

  <?php
  $login= new userController();
  $login->ctrUserLogin();
?>