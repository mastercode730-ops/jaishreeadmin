<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-lg-8 col-sm-8">
                <h4>Country List</h4>
            </div>
            <div class="col-lg-4 col-sm-4 text-end">
                <button type="submit" class="btn btn-success" data-toggle="modal" data-target="#exampleModalCenter">Add Country</button>
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
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;
                    foreach ($get_modal as $row) : ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $row->country_name; ?></td>
                            <td><?= $row->status == 0 ? "<p class='active-pills'>Active</p>" : "<p class='inactive-pills'>Inactive</p>"; ?></td>
                            <td><a href="?sid=<?= $row->country_id; ?>"><?= $row->status == 0 ? "<i class='inactive fa-solid fa-ban' data-toggl='tooltip' data-placement='top' title='Mark Inactive'></i>" : "<i class='active fas fa-circle-check' data-toggl='tooltip' data-placement='top'  title='Mark Active'></i>"; ?></a>
                                <a href="#"><i id="<?= $row->country_id ?>" class="square fa-solid fa-pen-to-square" data-toggl="tooltip" data-placement="top" title="Update" data-toggle="modal" data-target="#country-update"></i></a>
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
                <!-- <h5 class="modal-title" id="exampleModalLongTitle">Add Country</h5> -->
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <h5 class="text-center pt-4">Add Country</h5>
            <div class="modal-body">
                <form class="form" method="post" id="form">
                    <div class="form-group pt-2">
                        <label>Counrty Name : <span class="star">★</span></label>
                        <input type="text" name="country_name" placeholder="Enter Country Name" class="form-control" style="padding: 15px 12px;" />
                    </div>
                    <div class="form-group">
                        <!-- <button type="reset" class="btn btn-secondary" data-dismiss="modal" style="border-radius:3px;">Close</button> -->
                        <button type="submit" name="submit" class="model-btn btn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal update -->
<div class="modal fade" id="country-update" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <h5 class="modal-title" id="exampleModalLongTitle">Update Country</h5> -->
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <h5 class="text-center pt-4">Update Country</h5>
            <div class="modal-body">
                <form method="POST" id="forms">
                    <div class="form-group">
                        <label>Counrty Name : <span class="star">★</span></label>
                        <input type="text" id="country_name_update" name="country_name" placeholder="Enter Country Name" class="form-control" style="padding: 15px 12px;" />
                    </div>
                    <div class="form-group">
                        <!-- <button type="reset" class="btn btn-secondary" data-dismiss="modal" style="border-radius:3px;">Close</button> -->
                        <button id="update_submit" name="update_submit" type="submit" class="model-btn btn">Update</button>
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
                url: "<?= base_url('ajax/update_country') ?>",
                type: "post",
                dataType: "json",
                data: {
                    id: id
                },
                success: function(data_id) {
                    $("#country_name_update").val(data_id.country_name);
                    var base_url = "<?= base_url('admin/all_country/') ?>";
                    $("#forms").attr('action', base_url + data_id.id);
                }
            });
        });
    });
</script>