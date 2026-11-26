<style>
    .table td, .table th {
            padding: 0 !important;
    width: 60px;
    height: 60px;
    }
    p {
        margin-bottom: 0;
    }
</style>
<?php 
$AllGameBets = "";
$constvarless = 0;
if(isset($_GET['less']) && $_GET['less'] != ""){
    $constvarless = $_GET['less'];
}
?>
<div class="card">
    <div class="card-header">
        <div class="container-fluid">
            <?php $game_id_fetch = $this->input->get('game_id'); ?>
            <?php $get_game = $this->Crud_model->fetch('create_game', array('id' => $game_id_fetch));
            $get_game_name = $this->Crud_model->fetch('game', array('id' => $get_game->row('game_id')));
            $arr=array('game_id' => $game_id_fetch,'status'=>0);
            if($Agnet){
                $arr['aid'] = $Agnet;
            }
            $grand_total = $this->db->select_sum('money')->get_where('game_bet', $arr);
            $game_result = $this->db->get_where('game_result', array('create_game_id' => $game_id_fetch));
            if ($grand_total->row('money') > 0) {
                $final_grand_total = $grand_total->row('money');
            } else {
                $final_grand_total = 0;
            }
            ?>
            <div class="row">
                <div class="col-lg-8 col-sm-8">
                    <div class="col-lg-12 col-sm-12">
                        <span><b> Game Name</b></span> : <span><?= $get_game_name->row('game_name') ?></span>
                    </div>
                </div>
                
                <div class="col-lg-4 col-sm-4 text-end">
                <?php if(!$Agnet && $get_game->row('status') == 0){ ?>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#AnnounceResultModal">Announce</button>
                <?php }elseif(!$Agnet){ ?>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#AResultModalResultDateUPDATE">Result Date Update</button>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#AnnounceResultModalUPDATE">Result Update</button>
                    <button class="btn btn-success">Match Over</button>
                <?php } ?>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="col-lg-12">
                        <span><b> Start Date</b></span> : <span><?= $get_game->row('start_date') ?></span>
                    </div>
                    <div class="col-lg-12">
                        <span><b> Start Time</b></span> : <span><?= date("h:i a", strtotime($get_game->row('start_time'))) ?></span>
                    </div>
                    <div class="col-lg-12">
                        <span><b> Moti Jodi end Date</b></span> : <span><?= $get_game->row('result_date') ?></span>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="col-lg-12">
                        <span><b> End Date</b></span> : <span><?= $get_game->row('end_date') ?></span>
                    </div>
                    <div class="col-lg-12">
                        <span><b> End Time</b></span> : <span><?= date("h:i a", strtotime($get_game->row('end_time'))) ?></span>
                    </div>
                    <div class="col-lg-12">
                        <span><b> Moti Jodi end Time</b></span> : <span><?= date("h:i a", strtotime($get_game->row('result_time'))) ?></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="row">
                    <hr>
                    <div class="col-6">
                        <span><b> Total Bid Amount</b></span> : <span><?= $final_grand_total ?></span>
                    </div>
                    <?php if ($get_game->row('status') == 1) { ?>
                        <div class="col-6">
                            <span><b> Result Number</b></span> : <span><?= $game_result->row('number') ?></span>
                        </div>
                    <?php } ?>
                    <hr>
                </div>
            </div>
        </div>
        <div class="scroll">
            <a href="/admin/bet_history?id=<?= $get_game->row('id') ?>" class="btn btn-primary">View All Bets</a>
            <button class="btn btn-primary" id="copyToClipboard">COPY BETTING NUMBERS</button>
            <h3>Less In Janteri</h3>
            <form method="get">
                <input type="number" name="less" value="<?php echo $constvarless; ?>">
                <input type="hidden" name="game_id" value="<?= $get_game->row('id') ?>">
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
            <table class="table table-bordered">
                <h1>JODI</h1>
                <tbody>
                    <tr>
                    <?php
                    function adarsh($arr,$Agnet){
                        if($Agnet){
                            // echo $Agnet;
                            $arr['aid'] = $Agnet;
                        }
                        return $arr;
                    }
                    $is = 1;
                    $totalThisLine = 0;
                    if ($data->num_rows() > 0) {
                        for ($i = 1; $i <= 100; $i++) {
                            $get_money = $this->db->get_where('game_bet', adarsh(array('game_number' => $i, 'type'=>'1', 'game_id' => $game_id_fetch,'status'=>0, 'cancel_at'=>null),$Agnet));
                            $money_sum = array();
                            $user_count = array();
                            foreach ($get_money->result() as $arr) {
                                $money_sum[] = $arr->money;
                                $user_count[] = $arr->user_id;
                            }
                            $money_sum[] = $constvarless-($constvarless*2);
                            $totalThisLine += array_sum($money_sum)>0?array_sum($money_sum):0;
                            $totalThisLines = array_sum($money_sum)>0?array_sum($money_sum):0;
                            if($totalThisLines>0){
                                $AllGameBets .= " ".($i>9?$i:'0'.$i)."(".$totalThisLines.")";
                            }
                            // echo array_sum($money_sum);
                    ?>
                    
                    <td>
                        <?= $i>9?$i:'0'.$i; ?>
                        <?php if(array_sum($money_sum)>0){ ?>
                        <p style="color:<?= array_sum($money_sum)>0?'red':'' ?>">₹<?= array_sum($money_sum) ?></p> <a href="#" data-game_id="<?= $game_id_fetch ?>" data-game_number="<?= $i ?>" data-game_type="1" data-toggl='tooltip' data-placement='top' title='View Details'><i style="padding: 4px; background-color: #006162;" class="fa fa-eye" aria-hidden="true" data-toggle="modal" data-target="#UserModal"></i></a>
                        <?php } ?>
                    </td>
                    <?php
                    if(($i) % 10 == 0){ ?>
                    <td><b><?= $totalThisLine>0?$totalThisLine:0 ?></b></td>
                    </tr>
                    <tr>
                    <?php $totalThisLine = 0; } }
                    } ?>
                </tbody>
                </table>
                <table class="table table-bordered">
                <h1>ANDER Haruf</h1>
                <tbody>
                    <tr>
                    <?php
                    $is = 1;
                    $totalThisLine = 0;
                    if ($data->num_rows() > 0) {
                        for ($i = 0; $i < 10; $i++) {
                            $betNum = $i;
                            if($i == 0){
                                $betNum = 100;
                            }
                            $get_money = $this->db->get_where('game_bet', adarsh(array('game_number' => $betNum, 'type'=>'2', 'game_id' => $game_id_fetch,'status'=>0,'cancel_at'=>null),$Agnet));
                            $money_sum = array();
                            $user_count = array();
                            foreach ($get_money->result() as $arr) {
                                $money_sum[] = $arr->money;
                                $user_count[] = $arr->user_id;
                            }
                            $money_sum[] = $constvarless-($constvarless*2);
                            $totalThisLine += array_sum($money_sum)>0?array_sum($money_sum):0;
                            $totalThisLines = array_sum($money_sum)>0?array_sum($money_sum):0;
                            if($totalThisLines>0){
                                $AllGameBets .= ' a'.($i)."(".$totalThisLines.")";
                            }
                    ?>
                    
                    <td><?= $i>9?$i:'0'.$i; ?> 
                    <?php if(array_sum($money_sum)>0){ ?>
                    <p style="color:<?= array_sum($money_sum)>0?'red':'' ?>">₹<?= array_sum($money_sum) ?></p> <a href="#" data-game_id="<?= $game_id_fetch ?>" data-game_number="<?= $i ?>" data-game_type="2" data-toggl='tooltip' data-placement='top' title='View Details'><i style="padding: 4px; background-color: #006162;" class="fa fa-eye" aria-hidden="true" data-toggle="modal" data-target="#UserModal"></i></a>
                    <?php } ?>
                    </td>
                    <?php
                    if(($i+1) % 10 == 0){ ?>
                    <td><b><?= $totalThisLine>0?$totalThisLine:0 ?></b></td>
                    </tr>
                    <tr>
                    <?php $totalThisLine = 0; } }
                    } ?>
                </tbody>
            </table>
            <table class="table table-bordered">
                <h1>BAHAR Haruf</h1>
                <tbody>
                    <tr>
                    <?php
                    $is = 1;
                    $totalThisLine = 0;
                    if ($data->num_rows() > 0) {
                        for ($i = 0; $i < 10; $i++) {
                            $betNum = $i;
                            if($i == 0){
                                $betNum = 100;
                            }
                            $get_money = $this->db->get_where('game_bet', adarsh(array('game_number' => $betNum, 'type'=>'3', 'game_id' => $game_id_fetch,'status'=>0,'cancel_at'=>null),$Agnet));
                            $money_sum = array();
                            $user_count = array();
                            foreach ($get_money->result() as $arr) {
                                $money_sum[] = $arr->money;
                                $user_count[] = $arr->user_id;
                            }
                            $money_sum[] = $constvarless-($constvarless*2);
                            $totalThisLine += array_sum($money_sum)>0?array_sum($money_sum):0;
                            $totalThisLines = array_sum($money_sum)>0?array_sum($money_sum):0;
                            if($totalThisLines>0){
                                $AllGameBets .= ' b'.($i)."(".$totalThisLines.")";
                            }
                    ?>
                    
                    <td><?= $i>9?$i:'0'.$i; ?>
                    <?php if(array_sum($money_sum)>0){ ?>
                    <p style="color:<?= array_sum($money_sum)>0?'red':'' ?>">₹<?= array_sum($money_sum) ?></p> <a href="#" data-game_id="<?= $game_id_fetch ?>" data-game_number="<?= $i ?>" data-game_type="3" data-toggl='tooltip' data-placement='top' title='View Details'><i style="padding: 4px; background-color: #006162;" class="fa fa-eye" aria-hidden="true" data-toggle="modal" data-target="#UserModal"></i></a>
                    <?php } ?>
                    </td>
                    
                    <?php
                    if(($i+1) % 10 == 0){ ?>
                    <td><b><?= $totalThisLine>0?$totalThisLine:0 ?></b></td>
                    </tr>
                    <tr>
                    <?php $totalThisLine = 0; } }
                    } 
                    
                                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Announce Result Modal -->
<div class="modal fade" id="AnnounceResultModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <h5 class="text-center pt-4">Announce Result</h5>
            <div class="card-body">
                <form id="games_form" method="post" class="was-validated">
                    <div class="form-group fontuser">
                        <label class="lab">Result Number <span class="star">★</span></label>
                        <input type="number" name="number" class="form-control" style="padding-left: 40px;" required>
                        <div class="invalid-feedback">
                            Please enter result number.
                        </div>
                        <i class="fa fa-gamepad fa-lg" style="left: -8px; top: 58px;"></i>
                    </div>
                    <div class="form-group fontuser">
                        <label class="lab">Grand Total <span class="star">★</span></label>
                        <input type="hidden" name="grand_total" value="<?= $final_grand_total ?>">
                        <input type="number" value="<?= $final_grand_total ?>" disabled class="form-control" style="padding-left: 40px;" required>
                        <i class="fa fa-gamepad fa-lg" style="left: -8px; top: 58px;"></i>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="game_checkbox" id="game_checkbox" class="form-check-input" checked>
                        <label class="form-check-label" for="game_checkbox" style="margin: 0;">You are replicate this game</label>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button class="btn btn-success" name="submit" type="submit">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Announce Result Modal AnnounceResultModalUPDATE -->
<div class="modal fade" id="AnnounceResultModalUPDATE" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <h5 class="text-center pt-4">Announce Update Result</h5>
            <div class="card-body">
                <form id="" method="post" class="was-validated">
                    <div class="form-group fontuser">
                        <label class="lab">Result Number <span class="star">★</span></label>
                        <input type="number" name="number" class="form-control" style="padding-left: 40px;" required>
                        <div class="invalid-feedback">
                            Please enter result number.
                        </div>
                        <i class="fa fa-gamepad fa-lg" style="left: -8px; top: 58px;"></i>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button class="btn btn-success" name="submitUPdate" type="submit">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="AResultModalResultDateUPDATE" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <h5 class="text-center pt-4">Announce Update Result</h5>
            <div class="card-body">
                <form id="" method="post" class="was-validated">
                    <div class="form-group fontuser">
                        <label class="lab">Result Number <span class="star">★</span></label>
                        <input type="date" name="dates" class="form-control" style="padding-left: 40px;" required>
                        <div class="invalid-feedback">
                            Please Select Result Date.
                        </div>
                        <i class="fa fa-gamepad fa-lg" style="left: -8px; top: 58px;"></i>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button class="btn btn-success" name="submitUPdateResultDate" type="submit">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- User Details Modal -->
<div class="modal fade" id="UserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <h5 class="text-center pt-4">User Details</h5>
            <div class="card-body">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th scope="col"><b> Sr.</b></th>
                            <th scope="col"><b> Name</b></th>
                            <th scope="col"><b> Mobile</b></th>
                            <th scope="col"><b> Money</b></th>
                            <th scope="col"><b> Action</b></th>
                        </tr>
                    </thead>
                    <tbody id="ajaxFetch">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        const AllBettsNumber = '<?php echo $AllGameBets; ?> \n\nGrand Total: <?php echo $final_grand_total; ?>';
        $("#copyToClipboard").on('click',function(){
          // Create a temporary input
          const tempInput = document.createElement('textarea');
          tempInput.value = AllBettsNumber;
          document.body.appendChild(tempInput);
          tempInput.select();
          document.execCommand('copy');
          document.body.removeChild(tempInput);
          
          // Optional: alert or toast
          alert('Copied');
        });
        $("tr td a").on("click", function() {
            var game_id = $(this).attr("data-game_id");
            var game_number = $(this).attr("data-game_number");
            var game_type = $(this).attr("data-game_type");
            $.ajax({
                url: "<?= base_url('admin/user_get_ajax') ?>",
                type: "POST",
                data: {
                    game_id: game_id,
                    game_number: game_number,
                    game_type: game_type
                },
                success: function(data) {
                    $("#ajaxFetch").html(data);
                }
            });
        });
    });
</script>