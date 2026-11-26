<div class="card">
    <div class="card-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-8">
                    <h4>State List</h4>
                </div>
                <div class="col-4 text-end ">
                    <button type="submit" class="btn btn-success" data-toggle="modal" data-target="#exampleModalCenter">Add State</button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-1">
        <div class="scroll">
            <table class="table table-striped table-bordered zero-configuration">
                <thead class="table_head">
                    <tr>
                        <th>S no.</th>
                        <th>Country Name</th>
                        <th>State Name</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;
                    foreach ($country_name as $row) : ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $row->country_name; ?></td>
                            <td><?= $row->state_name; ?></td>
                            <td><?= $row->status == 0 ? '<p class="active-pills">Active</p>' : '<p class="inactive-pills">Inactive</p>'; ?></td>
                            <td><a href="?sid=<?= $row->id; ?>"><?= $row->status == 0 ? "<i class='inactive fa-solid fa-ban' data-toggl='tooltip' data-placement='top' title='Mark Inactive'></i>" :  "<i class='active fas fa-circle-check' data-toggl='tooltip' data-placement='top' title='Mark Active'></i>"; ?></a>
                                <a href="#"><i id="<?= $row->id ?>" class="square fa-solid fa-pen-to-square" data-toggl="tooltip" data-placement="top" title="Update" data-toggle="modal" data-target="#state-update"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal insert -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <h5 class="text-center pt-4">Add State</h5>
            <div class="modal-body">
                <form class="form" method="post" id="form">
                    <div class="form-group">
                        <label>Country Name : <span class="star">★</span></label>
                        <select name="country_id" class="form-control">
                            <option value="">--Select country name--</option>
                            <?php foreach ($country as $row) : ?>
                                <option value="<?= $row->country_id; ?>"><?= $row->country_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>State Name : <span class="star">★</span></label>
                        <input type="text" name="state_name" placeholder="Enter State Name" class="form-control" style="padding: 15px 12px;" />
                    </div>
                    <div class="form-group">
                       <button type="submit" name="submit" class="model-btn btn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal update -->
<div class="modal fade" id="state-update" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <h5 class="text-center pt-4">Update State</h5>
            <div class="modal-body">
                <form method="post" id="forms">
                    <div class="form-group">
                        <label>Country Name : <span class="star">★</span></label>
                        <select name="country_id" class="form-control" id="country_id">
                            <option value="">--Select country name--</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>State Name : <span class="star">★</span></label>
                        <input type="text" id="state_name_update" name="state_name" placeholder="Enter State Name" class="form-control" style="padding: 15px 12px;" />
                    </div>
                    <div class="form-group">
                        <button name="update_submit" id="update_submit" type="submit" class="model-btn btn">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("tr td a i").on("click", function() {
            var id = $(this).attr("id");
            $.ajax({
                url: "<?= base_url('ajax/update_state') ?>",
                type: "post",
                dataType: "json",
                data: {id: id},
                success: function(data_id) {
                    $("#state_name_update").val(data_id.state_name);
                    $("#country_id").html(data_id.option);
                    var base_url = "<?= base_url('admin/states/') ?>";
                    $("#forms").attr('action', base_url + data_id.id);
                }
            });
        });
    });
</script>