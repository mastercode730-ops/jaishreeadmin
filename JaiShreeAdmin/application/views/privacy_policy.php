<div class="card">
    <div class="new">
        <h3 class="mb-3">Privacy Policy</h3>
        <form method="POST">
            <label>Privacy Policy</label>
            <?php $data_get = $this->Crud_model->fetch('privacy_policy'); ?>
            <textarea name="description" class="ckeditor"><?= $data_get->row('description')?></textarea>
            <button name="submit" type="submit" class="btn mt-4 float-right" style="background-color: #a33bf2;color:#ffffff">Submit</button>
        </form>
    </div>
</div>