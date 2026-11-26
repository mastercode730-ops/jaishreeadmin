<div id="main-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <?php if(isset($_GET['submit'])){ ?>
                <?php if($level == 1 || $level == 0){ ?>
                
                <h4 class="mb-4">Last 10days work of Game</h4>
                <div class="scroll">
                    <table class="table table-striped table-bordered">
                        <thead class="table_head">
                            <tr>
                                <th>S no.</th>
                                <th>Date</th>
                                <th>Bet Amount</th>
                                <th>Send Amount</th>
                                <th>P/L</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i = 1;
                            foreach ($data10Days->result() as $row) :
                                // $getDistribute = $this->Crud_model->fetch('game', array('id' => $row->game_id));
                            ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $row->date; ?></td>
                                    <td><?= $row->total_bid_amount; ?></td>
                                    <td><?= $row->total_distribute_amount; ?></td>
                                    <td style="color:<?php echo $row->total_bid_amount-$row->total_distribute_amount>0?'green':'red'; ?>"><?= $row->total_bid_amount-$row->total_distribute_amount ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php }
                }else{ ?>
                    <a class="btn btn-primary" href="?submit" onclick="showLoader()">Check Date Wise Profit Loss</a>

<!-- Loader -->
<div id="loader" style="display: none;">
    <img src="https://cdn.pixabay.com/animation/2023/05/02/04/29/04-29-06-428_512.gif" alt="Loading..." />
</div>
<script>
function showLoader() {
    document.getElementById('loader').style.display = 'block';
}
</script>
                <?php }
                $arrforfilter = array();
                if($agentId){
                    $arrforfilter = array('aid'=>$agentId);
                }
                function adarshArrayManage($arr,$add=null){
                    if($add){
                        $arr['aid'] = $add;
                    }
                    
                    return $arr;
                }
                ?>
                <?php if($level == 1 || $level == 0){ ?>
                <div style="border: none;" class="card-body">
                    <div class="page-header">
                        <h4 class="page-title" style="font-size: 1.5rem">
                            <span class="page-title-icon bg-gradient-primary text-white mr-2">
                                <i class="mdi mdi-home"></i></span> <?php echo $level == 2 ? "Welcome to Agent": "Dashboard" ?>
                        </h4>
                    </div>
                    <div class="row pt-2">
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card bg-gradient-danger card-img-holder text-white">
                                <a href="<?= base_url('admin/all_users') ?>" style="text-decoration: none;">
                                    <div class="card-body" style="padding: 0%">
                                        <img src="/assets/images/circle.svg" class="card-img-absolute" alt="circle-image" />
                                        <h5 class="font-weight-normal mb-3 text-white">Total Users</h5>
                                        <h4 style='color:snow;'>+ <?= 
                                        $this->Crud_model->fetch('users', $arrforfilter)->num_rows(); ?></h4>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card bg-gradient-success card-img-holder text-white">
                                <a href="<?= base_url('admin/pending_req') ?>" style="text-decoration: none;">
                                    <div class="card-body" style="padding: 0%">
                                        <img src="/assets/images/circle.svg" class="card-img-absolute" alt="circle-image" />
                                        <h5 class="font-weight-normal mb-3 text-white">Total Pending Payment Request</h5>
                                        <h4 style='color:snow;'>+ <?= $this->Crud_model->fetch('payment_request', adarshArrayManage(array('status' => 0, 'type' => 1),$agentId))->num_rows(); ?></h4>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card bg-gradient-info card-img-holder text-white">
                                <a href="<?= base_url('admin/pending_withdraw') ?>" style="text-decoration: none;">
                                    <div class="card-body" style="padding: 0%">
                                        <img src="/assets/images/circle.svg" class="card-img-absolute" alt="circle-image" />
                                        <h5 class="font-weight-normal mb-3 text-white">Total Pending Withdrawal Request</h5>
                                        <h4 style='color:snow;'>+ <?= $this->Crud_model->fetch('payment_request', adarshArrayManage(array('status' => 0, 'type' => 2),$agentId))->num_rows(); ?></h4>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card bg-gradient-info card-img-holder text-white">
                                <a href="<?= base_url('admin/pending_withdraw') ?>" style="text-decoration: none;">
                                    <div class="card-body" style="padding: 0%">
                                        <img src="/assets/images/circle.svg" class="card-img-absolute" alt="circle-image" />
                                        <h5 class="font-weight-normal mb-3 text-white">Total Pending Withdrawal Request</h5>
                                        <h4 style='color:snow;'>+ <?= $this->Crud_model->fetch('payment_request', adarshArrayManage(array('status' => 0, 'type' => 2),$agentId))->num_rows(); ?></h4>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card bg-gradient-primary card-img-holder text-white">
                                <a href="#" style="text-decoration: none;">
                                    <div class="card-body" style="padding: 0%">
                                        <img src="/assets/images/circle.svg" class="card-img-absolute" alt="circle-image" />
                                        <h5 class="font-weight-normal mb-3 text-white">Today Payment Accept Amount</h5>
                                        <?php $payment_accept_amount_today = $this->db->select_sum('amount')->get_where('payment_request', adarshArrayManage(array('status' => 1, 'type' => 1, 'date' => date("Y-m-d")),$agentId) ); ?>
                                        <h4 style='color:snow;'>+ <?= $payment_accept_amount_today->row('amount') == "" ? 0 : $payment_accept_amount_today->row('amount') ?></h4>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card bg-gradient-primary card-img-holder text-white">
                                <a href="#" style="text-decoration: none;">
                                    <div class="card-body" style="padding: 0%">
                                        <img src="/assets/images/circle.svg" class="card-img-absolute" alt="circle-image" />
                                        <h5 class="font-weight-normal mb-3 text-white">Today Total Kamm</h5>
                                        <?php $payment_accept_amount_todays = $this->db->select_sum('money')->get_where('game_bet', adarshArrayManage(array('date(created_at)' => date("Y-m-d")),$agentId) ); ?>
                                        
                                        <h4 style='color:snow;'>+ <?= $payment_accept_amount_todays->row('money') == "" ? 0 : $payment_accept_amount_todays->row('money') ?></h4>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card bg-gradient-primary card-img-holder text-white">
                                <a href="#" style="text-decoration: none;">
                                    <div class="card-body" style="padding: 0%">
                                        <img src="/assets/images/circle.svg" class="card-img-absolute" alt="circle-image" />
                                        <h5 class="font-weight-normal mb-3 text-white">Today Total DENA</h5>
                                        <?php $payment_accept_amount_todayss = $this->db->select_sum('add_amount')->get_where('transaction_history', array('date' => date("Y-m-d"),'create_game_id!='=>'') ); ?>
                                        
                                        <h4 style='color:snow;'>+ <?= $payment_accept_amount_todayss->row('add_amount') == "" ? 0 : $payment_accept_amount_todayss->row('add_amount') ?></h4>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card bg-gradient-primary card-img-holder text-white">
                                <a href="#" style="text-decoration: none;">
                                    <div class="card-body" style="padding: 0%">
                                        <img src="/assets/images/circle.svg" class="card-img-absolute" alt="circle-image" />
                                        <h5 class="font-weight-normal mb-3 text-white">Today Cancel Kamm</h5>
                                        <?php $payment_accept_amount_todays = $this->db->select_sum('money')->get_where('game_bet', adarshArrayManage(array('date(created_at)' => date("Y-m-d"), 'cancel_at !='=> ''),$agentId) ); ?>
                                        
                                        <h4 style='color:snow;'>+ <?= $payment_accept_amount_todays->row('money') == "" ? 0 : $payment_accept_amount_todays->row('money') ?></h4>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card bg-gradient-primary card-img-holder text-white">
                                <a href="#" style="text-decoration: none;">
                                    <div class="card-body" style="padding: 0%">
                                        <img src="/assets/images/circle.svg" class="card-img-absolute" alt="circle-image" />
                                        <h5 class="font-weight-normal mb-3 text-white">Today Withdrawal Accept Amount</h5>
                                        <?php $payment_withdrawal_amount_today = $this->db->select_sum('amount')->get_where('payment_request', adarshArrayManage(array('status' => 1, 'type' => 2, 'date' => date("Y-m-d")),$agentId)); ?>
                                        <h4 style='color:snow;'>+ <?= $payment_withdrawal_amount_today->row('amount') == "" ? 0 : $payment_withdrawal_amount_today->row('amount') ?></h4>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card bg-gradient-primary card-img-holder text-white">
                                <a href="#" style="text-decoration: none;">
                                    <div class="card-body" style="padding: 0%">
                                        <img src="/assets/images/circle.svg" class="card-img-absolute" alt="circle-image" />
                                        <h5 class="font-weight-normal mb-3 text-white">Today Profit</h5>
                                        <h4 style='color:snow;'>+ <?= $payment_accept_amount_today->row('amount') - $payment_withdrawal_amount_today->row('amount') ?></h4>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card bg-gradient-primary card-img-holder text-white">
                                <a href="#" style="text-decoration: none;">
                                    <div class="card-body" style="padding: 0%">
                                        <img src="/assets/images/circle.svg" class="card-img-absolute" alt="circle-image" />
                                        <h5 class="font-weight-normal mb-3 text-white">Total Payment Accept Amount</h5>
                                        <?php $payment_accept_amount = $this->db->select_sum('amount')->get_where('payment_request', adarshArrayManage(array('status' => 1, 'type' => 1),$agentId)); ?>
                                        <h4 style='color:snow;'>+ <?= $payment_accept_amount->row('amount') ?></h4>
                                    </div>
                                </a>
                            </div>
                        </div>
                        
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card bg-gradient-primary card-img-holder text-white">
                                <a href="#" style="text-decoration: none;">
                                    <div class="card-body" style="padding: 0%">
                                        <img src="/assets/images/circle.svg" class="card-img-absolute" alt="circle-image" />
                                        <h5 class="font-weight-normal mb-3 text-white">Total Withdrawal Accept Amount</h5>
                                        <?php $payment_withdrawal_amount = $this->db->select_sum('amount')->get_where('payment_request', adarshArrayManage(array('status' => 1, 'type' => 2),$agentId)); ?>
                                        <h4 style='color:snow;'>+ <?= $payment_withdrawal_amount->row('amount') ?></h4>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card bg-gradient-primary card-img-holder text-white">
                                <a href="#" style="text-decoration: none;">
                                    <div class="card-body" style="padding: 0%">
                                        <img src="/assets/images/circle.svg" class="card-img-absolute" alt="circle-image" />
                                        <h5 class="font-weight-normal mb-3 text-white">Total Profit</h5>
                                        <h4 style='color:snow;'>+ <?= $payment_accept_amount->row('amount') - $payment_withdrawal_amount->row('amount') ?></h4>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                
                <?php } ?>
                <h4 class="mb-4">Running Game</h4>
                <div class="scroll">
                    <table class="table table-striped table-bordered">
                        <thead class="table_head">
                            <tr>
                                <th>S no.</th>
                                <th>Game Name</th>
                                <th>End Date</th>
                                <th>Result Date</th>
                                <th>Total Bid Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            
                            $data = $this->Crud_model->fetch('create_game', array('status' => 0), 'DESC');
                            foreach ($data->result() as $row) :
                                $get_game = $this->Crud_model->fetch('game', array('id' => $row->game_id));
                            ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $get_game->row('game_name'); ?></td>
                                    <td><?= $row->end_date; ?></td>
                                    <td><?= $row->result_date; ?></td>
                                    <?php 
                                    //$grand_total =  $this->db->select_sum('money')->get_where('game_bet', array('game_id' => $row->id, 'cancel_at'=>null));
                                    $this->db->select_sum('game_bet.money', 'total_bet_money');
$this->db->from('game_bet');
$this->db->where('game_bet.game_id', $row->id);
$this->db->where('game_bet.cancel_at', null); // Checks for IS NULL
if ($agentId) {
    $this->db->where('game_bet.aid', $agentId);
}
$grand_total = $this->db->get();
$result = $grand_total->row();
                                    ?>
                                    
                                    <td><?= $result->total_bet_money > 0 ? $result->total_bet_money : 0 ?></td>
                                    <td><a href="<?= base_url('admin/game_details') ?>?game_id=<?= $row->id ?>">
                                            <i id="<?= $row->id ?>" class="fa fa-gamepad" style="background-color: #f8a232; margin: 0px 2px;" data-toggl="tooltip" data-placement="top" title="Game Details"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>