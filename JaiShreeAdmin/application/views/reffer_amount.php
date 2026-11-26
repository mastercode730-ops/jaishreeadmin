<!-- Reffer Amount Setting-->
<div class="card">
    <div class="col-md-12 my-4">
        <h4>Message Change Method</h4>
    </div>
    <form method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="csv_file" class="col-md-12">Upload CSV File</label>
        <div class="col-md-12">
            <input type="file" class="form-control p-2" name="csv_file" required>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12 d-flex justify-content-end">
            <button class="btn btn-success" name="submit_changemethod" type="submit">Update</button>
        </div>
    </div>
</form>

<?php if (!empty($numbers)): ?>
    <div class="mt-4">
        <h5>Extracted Numbers:</h5>
        <p><?= implode(',', $numbers) ?></p>
    </div>
<?php endif; ?>

</div>

<div class="card">
    <div class="col-md-12 my-4">
        <h4>Reffer Setting</h4>
    </div>
    <form method="POST">
        <?php $data_get = $this->Crud_model->fetch('game_setting'); ?>
        <div class="form-group">
            <label for="example-email" class="col-md-12">Minimum Reffer Amount</label>
            <div class="col-md-12">
                <input type="number" placeholder="Please enter minimum reffer amount" value='<?= $data_get->row('reffer_amount'); ?>' class="form-control p-2" required name="amount">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12 d-flex justify-content-end">
                <button class="btn btn-success" name="submit_reffer" type="submit">Update</button>
            </div>
        </div>
    </form>
</div>
<!-- Reffer commition Setting-->
<div class="card">
    <div class="col-md-12 my-4">
        <h4>Commission Setting</h4>
    </div>
    <form method="POST">
        <?php $data_get = $this->Crud_model->fetch('game_setting'); ?>
        <div class="form-group">
            <label for="example-email" class="col-md-12">Commission Precentage</label>
            <div class="col-md-12">
                <input type="text" placeholder="Please enter commission precentage" value='<?= $data_get->row('commission_amount'); ?>' class="form-control p-2" required name="amount">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12 d-flex justify-content-end">
                <button class="btn btn-success" name="submit_commission" type="submit">Update</button>
            </div>
        </div>
    </form>
</div>