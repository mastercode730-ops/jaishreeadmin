<div class="card">
    <div class="col-md-12 my-4">
        <h4>Notice Text</h4>
    </div>
    <form method="POST">
        <?php $data_get = $this->Crud_model->fetch('heading_text'); ?>
        <div class="form-group">
            <label for="example-email" class="col-md-12">Notice Text</label>
            <div class="col-md-12">
                <textarea class="form-control ckeditor" name="heading_text" name="heading_text" required><?= $data_get->row('text_msg'); ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12 d-flex justify-content-end">
                <button class="btn btn-success" name="submit" type="submit">Update</button>
            </div>
        </div>
    </form>
</div>