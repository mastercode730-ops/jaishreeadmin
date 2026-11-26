<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $page_title . " । Lottery"; ?></title>
  <link rel="icon" href="" type="image/png" sizes="16x16">
  <link rel="shortcut icon" href="<?php echo base_url('assets/images/casino-logo.png'); ?>">
  <?php include 'header_link.php'; ?>
</head>

<style>
  @media screen and (max-width: 600px) {
    .content-wrapper {
      padding: 1rem 0.10rem;
    }
    .card {
      padding: 25px 0px;
    }
    /* * {
      font-size: 0.9rem;
    } */
  }
</style>

<body>
    
  <?php include 'side_menu.php'; ?>
  <div class="content-wrapper">
    <?php include $page_name . ".php"; ?>
  </div>
  <?php include 'footer_link.php'; ?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/admin_form_validation.js"></script>

</body>
<!-- Profile-->
<div class="modal fade" id="exampleModalCenterPro" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?php $admin_data = $this->Crud_model->fetch('admin_login', array('id' => $this->session->userdata('id')))->row(); ?>
      <h5 class="text-center pt-4"><?php echo $admin_data->name; ?> Profile</h5>
      <div class="card-body">
        <center class="mt-3"> <img src="<?php echo base_url('assets/images/admin_logo1.png'); ?>" class="rounded-circle" width="140" />
          <hr style="height: 0px">
          <h6 class="mt-1">
            <?php echo $admin_data->name; ?></h6>
        </center>
        <form class="form-horizontal form-material" action="<?= base_url('admin/update_profile'); ?>" method="POST">
            <div class="form-group">
                <input type="hidden" value="<?php echo $admin_data->id; ?>" name="id"/>
              <label for="example-email" class="col-md-12">Email</label>
              <div class="col-md-12">
                <input type="email" placeholder="Enter Admin Contact" value='<?= $admin_data->email; ?>' class="form-control form-control-line" name="email" id="example-email">
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-12">Password</label>
              <div class="col-md-12">
                <input type="password" name="password" placeholder="***********" class="form-control form-control-line" autocomplete="off" />
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-12 d-flex justify-content-end">
                <button class="btn btn-success" type="submit">Update Profile</button>
              </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('[data-toggl="tooltip"]').tooltip({
      delay: {
        "show": 10,
        "hide": 10
      }
    });
  });
</script>
<script>
  ClassicEditor
    .create(document.querySelector('#editor'))
    .catch(error => {
      console.error(error);
    });
</script>

</html>