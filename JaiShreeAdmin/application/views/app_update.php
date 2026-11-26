<!-- App Update Setting-->
<div class="card">
    <div class="col-md-12 my-4">
        <h4>App update setting</h4>
    </div>
    <form method="POST">
        <?php $data_get = $this->Crud_model->fetch('version_code'); ?>
        <div class="form-group">
            <label class="col-md-12">App Url</label>
            <div class="col-md-12">
                <input type="text" placeholder="Please enter url" value='<?= $data_get->row('url'); ?>' class="form-control p-2" required name="url">
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-12">App Version</label>
            <div class="col-md-12">
                <input type="text" placeholder="Please enter app version" value='<?= $data_get->row('virsion_code'); ?>' class="form-control p-2" required name="version">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12 d-flex justify-content-end">
                <button class="btn btn-success" name="submit" type="submit">Update</button>
            </div>
        </div>
    </form>
</div>
