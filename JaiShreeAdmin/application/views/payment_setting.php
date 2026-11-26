<?php if($agentId == false){ ?>
<style>
    .card{
        padding:6px !important;
    }
</style>
<div class="card">
    <div class="col-md-12 my-4">
        <h4>Gateway Setting</h4>
    </div>
    <form method="POST">
        <?php $data_get = $this->Crud_model->fetch('game_setting'); ?>
        <div class="form-group">
            <label for="example-email" class="col-md-12">Select Gateway</label>
            <div class="col-md-12">
                <select class="form-control p-2" name="gateway" required>
                    <option value="">Select Gateway</option>
                    <option value="1" <?= $data_get->row('gateway') == 1 ? 'selected' : '' ?>>UpiGateway</option>
                    <option value="2" <?= $data_get->row('gateway') == 2 ? 'selected' : '' ?>>Payshop</option>
                    <option value="3" <?= $data_get->row('gateway') == 3 ? 'selected' : '' ?>>Rahul Gateway</option>
                </select>
                <!--<input type="number" placeholder="Please enter minimum deposit amount" value='<?= $data_get->row('gateway'); ?>' class="form-control p-2" required name="amount">-->
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12 d-flex justify-content-end">
                <button class="btn btn-success" name="submit_gateway" type="submit">Update</button>
            </div>
        </div>
    </form>
</div>
<!-- Reffer Withdrawal Setting-->
<div class="card">
    <div class="col-md-12 my-4">
        <h4>Withdraw Setting</h4>
    </div>
    <form method="POST">
        <?php $data_get = $this->Crud_model->fetch('game_setting'); ?>
        <div class="form-group">
            <label for="example-email" class="col-md-12">Minimum Withdraw Amount</label>
            <div class="col-md-12">
                <input type="number" placeholder="Please enter minimum withdraw amount" value='<?= $data_get->row('withdrawal_amount'); ?>' class="form-control p-2" required name="amount">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12 d-flex justify-content-end">
                <button class="btn btn-success" name="submit_withdrawal" type="submit">Update</button>
            </div>
        </div>
    </form>
</div>
<!-- Reffer Deposit Setting-->

<div class="card">
    <div class="col-md-12 my-4">
        <h4>Deposit Setting</h4>
    </div>
    <form method="POST">
        <?php $data_get = $this->Crud_model->fetch('game_setting'); ?>
        <div class="form-group">
            <label for="example-email" class="col-md-12">Minimum Deposit Amount</label>
            <div class="col-md-12">
                <input type="number" placeholder="Please enter minimum deposit amount" value='<?= $data_get->row('deposit_amount'); ?>' class="form-control p-2" required name="amount">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12 d-flex justify-content-end">
                <button class="btn btn-success" name="submit_deposit" type="submit">Update</button>
            </div>
        </div>
    </form>
</div>
<!-- Bidding Amount Setting-->
<div class="card">
    <div class="col-md-12 my-4">
        <h4>Bidding Setting</h4>
    </div>
    <form method="POST">
        <?php $data_get = $this->Crud_model->fetch('game_setting'); ?>
        <div class="form-group">
            <label for="example-email" class="col-md-12">Minimum Bidding Amount</label>
            <div class="col-md-12">
                <input type="number" placeholder="Please enter minimum bidding amount" value='<?= $data_get->row('bidding_amount'); ?>' class="form-control p-2" required name="amount">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12 d-flex justify-content-end">
                <button class="btn btn-success" name="submit_bidding" type="submit">Update</button>
            </div>
        </div>
    </form>
</div>
<!-- Win Amount Setting-->
<?php } ?>
<div class="card">
    <div class="col-md-12 my-4">
        <h4>Winning Amount Setting</h4>
    </div>
    <form method="POST">
        <?php 
        if($agentId){
            $data_get = $this->Crud_model->fetch('admin_login', array('id'=>$agentId));
        }else{
            $data_get = $this->Crud_model->fetch('game_setting');
        }
        ?>
        <div class="form-group">
            <label for="example-email" class="col-md-12">JODI winning Amount</label>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-1">
                        <h3 style="margin-top: 15px; margin-left: 15px;">1 x </h3>
                    </div>
                    <div class="col-11">
                        <input type="number" placeholder="JODI winning Amount" value='<?= $data_get->row('winning_amount'); ?>' class="form-control p-2" required name="Jamount">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="example-email" class="col-md-12">HARUF Winning Amount Setting</label>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-1">
                        <h3 style="margin-top: 15px; margin-left: 15px;">1 x </h3>
                    </div>
                    <div class="col-11">
                        <input type="text" placeholder="HARUF Winning Amount" value='<?= $data_get->row('withdrawal_hold_amt'); ?>' class="form-control p-2" required name="Hamount">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="example-email" class="col-md-12">Moti jodi max amount</label>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-12">
                        <input type="text" placeholder="Moti jodi max amount" value='<?= $data_get->row('motijodimax'); ?>' class="form-control p-2" required name="amount">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12 d-flex justify-content-end">
                <button class="btn btn-success" name="submit_motijodimax" type="submit">Update</button>
            </div>
        </div>
    </form>

<?php if($agentId){ ?>
    <div class="col-md-12 my-4">
        <h4>UPI GATEWAY Token Setting</h4>
    </div>
    <form method="POST">
        <?php 
        if($agentId){
            $data_get = $this->Crud_model->fetch('admin_login', array('id'=>$agentId));
        }
        ?>
        <div class="form-group">
            <label for="example-email" class="col-md-12">UPI GATEWAY Token</label>
            <div class="col-md-12">
                <p>Webhook URL: <span style="color:red">https://admin.aghorimatka.com/admin/add_money_callback</span></p>
                <div class="row">
                    <div class="col-12">
                        <input type="text" placeholder="UPI GATEWAY Token" value='<?= $data_get->row('upigatewaytoken'); ?>' class="form-control p-2" required name="token">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12 d-flex justify-content-end">
                <button class="btn btn-success" name="submit_upigateway_token" type="submit">Update</button>
            </div>
        </div>
    </form>
<?php } ?>
</div>