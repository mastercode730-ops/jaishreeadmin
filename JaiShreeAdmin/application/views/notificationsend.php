<div class="card">
    <div class="col-md-12 my-4">
        <h4>Notification Send</h4>
    </div>
    <form method="POST">
        <div class="form-group">
            <label for="example-email" class="col-md-12">Select User</label>
            <div class="col-md-12">
                <select class="form-control p-2" name="user" required>
                    <option value="">Select Users</option>
                    <option value="0">All User</option>
                    <?php $i = 1;
                    foreach ($users->result() as $row) : ?>
                    <option value="<?= $row->id ?>"><?= $row->mobile ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" placeholder="Please enter Title" class="form-control p-2 mt-3" required name="title">
                <textarea placeholder="Please enter Message" class="form-control p-2 mt-3" required name="message"></textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12 d-flex justify-content-end">
                <button class="btn btn-success" name="submit" type="submit">Send</button>
            </div>
        </div>
    </form>
</div>