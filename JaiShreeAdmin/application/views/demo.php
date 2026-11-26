<!-- <style>
    .c1 {
        border: 2px red ridge;
    }
</style>
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-12">
                <h4>Create Game</h4>
            </div>
        </div>
    </div>
    <div>
        <form id="games_form" method="post">
           
            <div class="row">
                <div class="form-group col-6 fontuser">
                    <label class="lab">Start Number: <span class="star">★</span></label>
                    <input type="text" class="form-control" name="start_num" placeholder="">
                    <i class="fa fa-sort-numeric-asc fa-lg"></i>
                </div>
                <div class="form-group col-6 fontuser">
                    <label class="lab">End Number: <span class="star">★</span></label>
                    <input type="email" class="form-control" name="end_num" placeholder="">
                    <i class="fa fa-sort-numeric-desc fa-lg"></i>
                </div>
            </div>
            
            <div class="row d-flex justify-content-end">
                <div class="form-group col-2 fonbtn">
                    <a href="" class="form-control btn btn-danger" style="padding: 12px;">Cancel</a>
                    <i class="fa fa-ban fa-lg"></i>
                </div>
                <div class="form-group col-2 fonbtn">
                    <input type="submit" class="form-control btn btn-lg btn-success" style="padding: 4px;" name="submit">
                    <i class="fa fa-check-circle fa-lg"></i>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    jQuery('#games_form').validate({
        rules: {
            start_num: "required",
            end_num: "required"
        },
        highlight:function(element) {
            $(element).addClass("c1");
        },
        unhighlight:function(element) {
            $(element).removeClass("c1");
        },
        messages: {
            start_num: "<p class='text-danger'>Please Enter game name.*</p>",
            end_num: "<p class='text-danger'>Please Enter result date*</p>"
        }
    });
</script> -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

    <script>
        var form = new FormData();
        form.append("name", "Satyam");
        form.append("mobile", "12333");
        form.append("password", "123456");
        form.append("reffer_code", "reff544");

        var settings = {
            "url": "https://lottery.developerbrothersproject.com/webservices/signup",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "LOTTERYPROJECT_API_KEY": "6acccc176c09326a9e46c9971a6a5d745243cf56",
                "Cookie": "ci_session=8873584690e69fdf46360ce70aad671fe64fc9cc"
            },
            "processData": false,
            "mimeType": "multipart/form-data",
            "contentType": false,
            "data": form
        };

        $.ajax(settings).done(function(response) {
            console.log(response);
        });
    </script>
</body>

</html>




<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Admin Login । Sales Kanpur</title>
  <link rel="stylesheet" href="<?php echo base_url('/assets/css/style.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/fafa_desian.css">
  <!-- End layout styles -->
  <link rel="shortcut icon" href="<?php echo base_url('assets/images/casino.png'); ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    .aside {
      padding: 28px;
      width: 40%;
      color: #ffffff;
      text-align: center;
      font-size: 14px;
      width: 31%;
      /* margin-left: 20%; */
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
    }
    .c1{
      border: red 2px ridge;
    }
    @media only screen and (min-width: 1000px) {
      .col-s-3 {
        width: 60%;
        /* margin-left: 21%; */
      }

      .col-s-12 {
        width: 100%;
      }
    }

    @media only screen and (min-width: 425px) {
      .col-3 {
        width: 50%;
      }

      .col-12 {
        width: 100%;
      }
    }

    @media only screen and (min-width: 768px) {
      .col-3 {
        width: 50%;
      }

      .col-12 {
        width: 100%;
      }

    }

    @media only screen and (min-width: 375px) {
      .col-3 {
        width: 93%;
        /* margin-left: 21% */
      }

      .col-12 {
        width: 100%;
      }
    }

    p {
      margin-top: -12px; margin-right: 110px;font-size: 13px;
    }
  </style>
</head>

<body>
  <div class="d-flex justify-content-center" style="padding-top: 4%;">
    <div class="col-3 col-s-12 d-flex justify-content-center">
      <div class="aside" style="border-radius: 8px;border:1px solid #80808066">
        <div class="brand-logo d-flex justify-content-center">
          <img src="<?php echo base_url('assets/images/casino-logo.png'); ?>" style="width:35%;">
        </div>
        <h4 class="text-uppercase font-bold m-b-0 text-center mb-2">ADMIN LOGIN</h4>
        <form class="pt-3" method="post" action="<?php echo base_url(); ?>" id="admin_form">
          <div class="form-group">
            <div class="fontlogin">
              <input type="email" id="typeEmailX-2" name="email" class="form-control form-control-lg mb-4" placeholder="  Please Enter Your Mobile No" autocomplete="off" />
              <i class="fa fa-user-o fa-lg"></i>
            </div>
            <div class="fontpassword">
              <input type="password" name="password" id="typePasswordX-2" class="form-control form-control-lg my-3" placeholder="Please enter your password" style="display: inline-block; padding: 12px 40px;" autocomplete="off" />
              <i class="fa fa-key fa-lg"></i>
            </div>
            <div class="d-flex justify-content-end mt-4 mb-3">
              <button name="submit" type="submit" class="btn btn-flat m-b-30 m-t-30 w-100 text-light" style="background-color: #0093E9; background-image: linear-gradient(160deg, #0093E9 0%, #80D0C7 100%);">Login</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
  <script>
    jQuery('#admin_form').validate({
      rules: {
        mobile: {
          required: true,
          mobile: true,
          minlength: 10,
          maxlength: 10
        },
        password: {
          required: true,
          minlength: 6,
          maxlength: 6
        }
      },
      messages: {
        mobile: {
          required: "<p class='text-danger'>Please Enter Admin Mobile No.*</p>",
          mobile: "<p class='text-danger'>Please Enter Valid Admin Mobile No.*</p>",
          minlength: "<p class='text-danger'>Please Enter minimum 10 Digit your Mobile No.</p>",
          maxlength: "<p class='text-danger'>Please Enter maximum 10 Digit your Mobile No.</p>"
        },
        highlight:function(element){
          $(element).addClass("c1");
        },
        unhighlight:function(element){
          $(element).removeClass("c1");
        },
        password: {
          required: "<p class='text-danger'>Please Enter Admin password*</p>",
          minlength: "<p class='text-danger'>Please Enter minimum 6 Digit your password</p>",
          maxlength: "<p class='text-danger'>Please Enter maximum 6 Digit your password</p>"
        }
      }
    });
  </script>
</body>

</html>