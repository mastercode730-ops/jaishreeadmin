<!-- Contact Info Setting-->
<div class="card">
    <div class="col-md-12 my-4">
        <h4>Help Number</h4>
    </div>
    <form method="POST">
        <?php $data_get = $this->Crud_model->fetch('game_setting'); ?>
        <div class="form-group">
            <label for="example-email" class="col-md-12">Help Number</label>
            <div class="col-md-12">
                <input type="number" placeholder="Please enter help number" value='<?= $data_get->row('contact_info'); ?>' class="form-control p-2" required name="amount">
            </div>
        </div>
        <div class="form-group">
            <label for="example-email" class="col-md-6">Start withdraw time</label>
            <div class="col-md-6">
                <input type="time" placeholder="" value='<?= $data_get->row('startwith'); ?>' class="form-control p-2" required name="startwith">
            </div>
        </div>
        <div class="form-group">
            <label for="example-email" class="col-md-6">End withdraw time</label>
            <div class="col-md-6">
                <input type="time" placeholder="" value='<?= $data_get->row('endwith'); ?>' class="form-control p-2" required name="endwith">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12 d-flex justify-content-end">
                <button class="btn btn-success" name="submit_info" type="submit">Update</button>
            </div>
        </div>
    </form>
</div>