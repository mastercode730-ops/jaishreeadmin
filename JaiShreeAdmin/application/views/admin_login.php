<!DOCTYPE html>
<html lang="en">

<head>
  <title>Admin Login</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="#">
  <meta name="keywords" content="#">
  <meta name="author" content="#">
  <!-- Favicon icon -->
  <link rel="icon" href="<?php echo base_url('assets/images/casino-logo.png'); ?>" type="image/x-icon">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/css/login_page.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/css/bootstrap_login.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/fafa_desian.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
  <section class="login-block">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">

        <form class="pt-3" method="post" action="<?php echo base_url(); ?>" id="admin_form">
            <div class="auth-box card" style="border-radius: 8px; border: 1px solid #80808066">
              <div class="card-block" style="padding: 40px">
                <div class="m-b-25 text-center">
                <img src="<?php echo base_url('assets/images/casino-logo.png'); ?>" style="width:35%;">
                </div>
                <?php if ($this->session->flashdata('error')) : ?>
                  <div class="alert alert-danger text-center mb-3 p-2" role="alert">
                    <?= htmlspecialchars($this->session->flashdata('error')); ?>
                  </div>
                <?php endif; ?>
                <div class="fontlogin">
                  <input type="email" id="typeEmailX-2" name="email" class="form-control form-control-lg mb-4" placeholder="  Please Enter Email" />
                  <i class="fa fa-user-o fa-lg" style="top: 16px;"></i>
                </div>
                <div class="fontpassword">
                  <input type="password" name="password" id="typePasswordX-2" class="form-control form-control-lg my-3" placeholder="Please Enter Password" style="display: inline-block; padding: 12px 40px;" />
                  <i class="fa fa-key fa-lg"></i>
                </div>
                <div class="row m-t-30">
                  <div class="col-md-12">
                  <button type="submit" name="submit" class="btn btn-success btn-md btn-block waves-effect waves-light text-center m-b-20">Login</button>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
  <script>
    jQuery('#admin_form').validate({
      rules: {
        email: "required",
        password: {
          required: true,
          minlength: 6,
        //   maxlength: 6
        }
      },
      messages: {
        email: "<p class='text-danger'>Please Enter Email ID*</p>",
        password: {
          required: "<p class='text-danger'>Please Enter Admin password*</p>",
          minlength: "<p class='text-danger'>Please Enter minimum 6 Digit your password</p>",
        //   maxlength: "<p class='text-danger'>Please Enter maximum 6 Digit your password</p>"
        }
      }
    });
  </script>
</body>

</html>