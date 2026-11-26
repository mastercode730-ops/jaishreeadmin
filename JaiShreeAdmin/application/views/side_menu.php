    <div class="container-scroller">
      <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center pt-1">
          <a class="navbar-brand brand-logo pr-5" href=""><img style="height: 100%; width:37%;" src="<?php echo base_url('assets/images/casino-logo.png'); ?>" alt="logo" /></a>
          <a class="navbar-brand brand-logo-mini" href=""><img style="height: 100%; width:80%;" src="<?php echo base_url('assets/images/casino-logo.png'); ?>" alt="logo" /></a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-stretch">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
          </button>
          <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
              <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                <div class="nav-profile-img">
                  <img src="<?php echo base_url('assets/images/admin_logo1.png'); ?>" alt="image">
                  <span class="availability-status online"></span>
                </div>
                <div class="nav-profile-text">
                    <?php $admin_data = $this->Crud_model->fetch('admin_login', array('id' => $this->session->userdata('id')))->row(); ?>
                  <p class="mb-1 text-black"><?php echo $admin_data->name; ?></p>
                </div>
              </a>
              <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                <a class="dropdown-item" data-toggle="modal" data-target="#exampleModalCenterPro">
                  <i class="mdi mdi-account mr-2 text-success"></i> Profile </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="<?php echo base_url(); ?>admin/logout">
                  <i class="mdi mdi-power mr-2 text-danger"></i>Logout</a>
              </div>
            </li>
            <li class="nav-item d-none d-lg-block full-screen-link">
              <a class="nav-link">
                <i class="mdi mdi-fullscreen" id="fullscreen-button"></i>
              </a>
            </li>
          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
          </button>
        </div>
      </nav>
      <div class="container-fluid page-body-wrapper">
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item">
                <br>
              <a class="nav-link" href="<?php echo base_url('admin/dashboard'); ?>">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
              </a>
            </li>
            <li class="nav-item">
    <a class="nav-link" href="<?php echo base_url('admin/monthly_user_list'); ?>">
        <span class="menu-title">Monthly User List</span>
        <i class="mdi mdi-calendar-range menu-icon"></i> 
    </a>
</li>
            <?php if($admin_data->level == 1 || $admin_data->level == 0){ ?>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#UserMaster" aria-expanded="false" aria-controls="UserMaster">
                <span class="menu-title">User Master</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-account-switch menu-icon"></i>
              </a>
              <div class="collapse" id="UserMaster">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="<?php echo base_url('admin/all_users'); ?>">All Users</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo base_url('admin/all_users?status=0'); ?>">Active Users</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo base_url('admin/all_users?status=1'); ?>">Inactive Users</a></li>
                </ul>
              </div>
            </li>
            
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#SubUserMaster" aria-expanded="false" aria-controls="SubUserMaster">
                <span class="menu-title">Sub-User Master</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-account-switch menu-icon"></i>
              </a>
              <div class="collapse" id="SubUserMaster">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="<?php echo base_url('admin/subusers'); ?>">All Sub Users</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo base_url('admin/add_subusers'); ?>">Add Sub User</a></li>
                </ul>
              </div>
            </li>

            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#PaymentRequest" aria-expanded="false" aria-controls="PaymentRequest">
                <span class="menu-title">Payment Request</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-cash-multiple menu-icon"></i>
              </a>
              <div class="collapse" id="PaymentRequest">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="<?= base_url('admin/pending_req');?>">Pending</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?= base_url('admin/accept_req');?>">Accept</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?= base_url('admin/reject_req');?>">Reject</a></li>
                </ul>
              </div>
            </li>

            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#WithdrawalRequest" aria-expanded="false" aria-controls="WithdrawalRequest">
                <span class="menu-title">Withdrawal Request</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-message-draw menu-icon"></i>
              </a>
              <div class="collapse" id="WithdrawalRequest">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="<?= base_url('admin/pending_withdraw')?>">Pending</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?= base_url('admin/accept_withdraw')?>">Accept</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?= base_url('admin/reject_withdraw')?>">Reject</a></li>
                </ul>
              </div>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url('admin/wallet_details'); ?>">
                <span class="menu-title">Manage QR Codes</span>
                <i class="mdi mdi-qrcode-scan menu-icon"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url('admin/payment_setting'); ?>">
                <span class="menu-title">Payment Setting</span>
                <i class="mdi mdi-contrast-box menu-icon"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url('admin/notificationsend'); ?>">
                <span class="menu-title">Notification send</span>
                <i class="mdi mdi-contrast-box menu-icon"></i>
              </a>
            </li>
            <?php } ?>
            <?php if($admin_data->level == 0){ ?>
             
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#AgentMaster" aria-expanded="false" aria-controls="AgentMaster">
                <span class="menu-title">Agent Master</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-account-switch menu-icon"></i>
              </a>
              <div class="collapse" id="AgentMaster">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="<?php echo base_url('admin/subusers?agent=yes'); ?>">All Agent</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo base_url('admin/add_subusers?agent=yes'); ?>">Add Agent</a></li>
                </ul>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url('admin/banner_image'); ?>">
                <span class="menu-title">Banner Images</span>
                <i class="mdi fa-address-card menu-icon"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url('admin/heading_text'); ?>">
                <span class="menu-title">Notice Text</span>
                <i class="mdi fa-address-card menu-icon"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url('admin/reffer_amount'); ?>">
                <span class="menu-title">Reffer Setting</span>
                <i class="mdi mdi-settings menu-icon"></i>
              </a>
            </li>

            

            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#Setting" aria-expanded="false" aria-controls="Setting">
                <span class="menu-title">Setting</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-settings-box menu-icon"></i>
              </a>
              <div class="collapse" id="Setting">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="<?= base_url('admin/terms_condition')?>">Terms And Condition</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?= base_url('admin/privacy_policy')?>">Privacy Policy</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?= base_url('admin/game_rule')?>">Game Rules</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?= base_url('admin/reffer_rule')?>">Reffer Rules</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?= base_url('admin/app_update')?>">App Update</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?= base_url('admin/about')?>">About</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?= base_url('admin/help')?>">Help</a></li>
                </ul>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#GameSetting" aria-expanded="false" aria-controls="GameSetting">
                <span class="menu-title">Game Setting</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-google-controller menu-icon"></i>
              </a>
              <div class="collapse" id="GameSetting">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="<?= base_url('admin/game_list')?>">Game List</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?= base_url('admin/pending_game')?>">All Pending Game</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?= base_url('admin/today_game')?>">Today Game</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?= base_url('admin/upcomming_game')?>">Upcomming Game</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?= base_url('admin/result_game')?>">End Game</a></li>
                </ul>
              </div>
            </li>
            <?php } ?>
          </ul>
        </nav>