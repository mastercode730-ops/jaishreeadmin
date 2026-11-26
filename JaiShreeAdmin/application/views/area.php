
<div id="main-wrapper">
    <div class="container-fluid">
        <div class="row sp">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="container-fluid">
                            <div class="row justify-content-start sp">
                                <div class="col-4">
                                    <h4>Area List</h4>
                                </div>
                                <div class="col-8 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-success" data-toggle="modal" data-target="#exampleModalCenter">Add Area</button>
                                </div>
                            </div>
                        </div>
                        <div class="scroll">

                            <table class="table table-striped table-bordered zero-configuration">
                                <thead class="table_head">
                                    <tr>
                                        <th>S no.</th>
                                        <th>Country Name</th>
                                        <th>State Name</th>
                                        <th>City Name</th>
                                        <th>Area Name</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1;
                                    foreach ($aria as $row) : ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo $row->country_name; ?></td>
                                            <td><?php echo $row->state_name; ?></td>
                                            <td><?php echo $row->city_name; ?></td>
                                            <td><?php echo $row->area_name; ?></td>
                                            <td><?php
                                                if ($row->status == 0) {
                                                    echo '<p class="active-pills">Active</p>';
                                                } else {
                                                    echo '<p class="inactive-pills">Inactive</p>';
                                                }
                                                ?></td>
                                            <td>
                                                <a href="?sid=<?= $row->id; ?>">
                                                    <?php if ($row->status == 0) {
                                                        echo "<i class='inactive fa-solid fa-ban' data-toggl='tooltip' data-placement='top' title='Mark Inactive'></i>";
                                                    } else {
                                                        echo "<i class='active fas fa-circle-check' data-toggl='tooltip' data-placement='top' title='Mark Active'></i>";
                                                    } ?></a>
                                                <a href="<?= base_url(); ?>admin/user_all_enquiry/<?= $row->id; ?>"></a>
                                                <a href="#"><i id="<?= $row->id ?>" class="square fa-solid fa-pen-to-square" data-toggl="tooltip" data-placement="top" title="Update" data-toggle="modal" data-target="#city-update"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <!-- Modal -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Insert Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <h5 class="text-center pt-4">Add Area</h5>
            <div class="modal-body">
                <form class="form" method="post" id="form">
                    <div class="form-group">
                        <label class="mt-3">Country Name : <span class="star">★</span></label>
                        <select name="country_id" class="form-control" id="country_id">
                            <option value="">--Select country name--</option>
                            <?php foreach ($country as $row) : ?>
                                <option value="<?= $row->country_id; ?>"><?= $row->country_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>State Name : <span class="star">★</span></label>
                        <select name="state_id" class="form-control" id="state_id">
                            <option value="">--Select State name--</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>City Name : <span class="star">★</span></label>
                        <select name="city_id" class="form-control" id="city_id">
                            <option value="">--Select City name--</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Area : <span class="star">★</span></label>
                        <input type="text" placeholder="Enter Area Name" name="area_name" class="form-control" style="padding: 15px 12px;" />
                    </div>
                    <div class="form-group">
                        <button type="submit" name="submit" class="model-btn btn">Save</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<!--update Modal -->
<div class="modal fade" id="city-update" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <h5 class="text-center pt-4">Update Area</h5>
            <div class="modal-body">
                <form class="form" method="post" id="forms">
                    <div class="form-group">
                        <label class="mt-3">Country Name : <span class="star">★</span></label>
                        <select name="country_id" class="form-control" id="country_id_update">
                            <option value="">--Select country name--</option>
                            <?php foreach ($country as $row) : ?>
                                <option value="<?= $row->country_id; ?>"><?= $row->country_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>State Name : <span class="star">★</span></label>
                        <select name="state_id" class="form-control" id="state_id_update">
                            <option value="">--Select State name--</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>City Name : <span class="star">★</span></label>
                        <select name="city_id" class="form-control" id="city_id_update">
                            <option value="">--Select City name--</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Area : <span class="star">★</span></label>
                        <input type="text" placeholder="Enter Area Name" name="area_name" id="area_name" class="form-control" style="padding: 15px 12px;" />
                    </div>
                    <div class="form-group">
                        <button type="submit" name="update_submit" class="model-btn btn">Update</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $("#country_id").on("change", function() {
            var country_id = $(this).val();
            $.ajax({
                url: "<?php echo base_url(); ?>ajax/state_load",
                type: "POST",
                data: {
                    id: country_id
                },
                beforeSend: function() {
                    $("#state_id").html("<option value=''>--Loading State--</option>");
                },
                success: function(data) {
                    $("#state_id").html(data);
                }
            });
        });
        $("#state_id").on("change", function() {
            var state_id = $(this).val();
            $.ajax({
                url: "<?php echo base_url(); ?>ajax/city_load",
                type: "POST",
                data: {
                    id: state_id
                },
                beforeSend: function() {
                    $("#city_id").html("<option value=''>--Loading State--</option>");
                },
                success: function(data) {
                    $("#city_id").html(data);
                }
            });
        });

        $("tr td a i").on("click", function() {
            var id = $(this).attr("id");
            var country_id = $("#country_id").val();
            $.ajax({
                url: "<?= base_url('ajax/update_area') ?>",
                type: "post",
                dataType: "json",
                data: {
                    id: id,
                    country_id: country_id
                },
                success: function(data_id) {
                    $("#country_id_update").html(data_id.country_name);
                    $("#state_id_update").html(data_id.state_name);
                    $("#city_id_update").html(data_id.city_name);
                    $("#area_name").val(data_id.area_name);
                    var base_url = "<?= base_url('admin/area/') ?>";
                    $("#forms").attr('action', base_url + data_id.id);
                }
            });
        });
    });
</script>