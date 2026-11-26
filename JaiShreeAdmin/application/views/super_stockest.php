<div id="main-wrapper">
    <div class="container-fluid">
        <div class="row sp">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="container-fluid">
                            <div class="row justify-content-start sp">
                                <div class="col-4">
                                    <h4>Super Stockist</h4>
                                </div>
                                <div class="col-8 d-flex justify-content-end">
                                    <a class="btn btn-success" href="<?= base_url('admin/add_super_stockest'); ?>">Add Super Stockist</a>
                                </div>
                            </div>
                        </div>
                        <div class="scroll">
                            <table class="table table-striped table-bordered zero-configuration">
                                <thead class="table_head">
                                    <tr>
                                        <th>S no.</th>
                                        <th>Business Name</th>
                                        <th>Nature of Bussiness</th>
                                        <th>Country Name.</th>
                                        <th>State Name</th>
                                        <th>City Name</th>
                                        <th>Contact Person Name</th>
                                        <th>Contact Person Number</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1;
                                    foreach ($super_stockist->result() as $row) : ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo $row->business_name; ?></td>
                                            <td><?php echo $row->nature_of_bussiness; ?></td>
                                            <?php $country = $this->Crud_model->fetch('country', array('country_id' => $row->country)); ?>
                                            <td><?= $country->row('country_name'); ?></td>
                                            <?php $state = $this->Crud_model->fetch('state', array('id' => $row->state)); ?>
                                            <td><?= $state->row("state_name"); ?></td>
                                            <?php $city = $this->Crud_model->fetch('city', array('id' => $row->city)); ?>
                                            <td><?= $city->row("city_name"); ?></td>
                                            <td><?php echo $row->contact_person; ?></td>
                                            <td><?php echo $row->contact_person_no; ?></td>
                                            <td>
                                                <?php
                                                if ($row->status == 0) {
                                                    echo '<p class="active-pills">Active</p>';
                                                } else {
                                                    echo '<p class="inactive-pills">Inactive</p>';
                                                }
                                                ?></td>
                                            <td>
                                                <a href="?sid=<?= $row->id; ?>"><?= $row->status == 0 ? "<i class='inactive fa-solid fa-ban' data-toggl='tooltip' data-placement='top' title='Mark Inactive'></i>" :  "<i class='active fas fa-circle-check' data-toggl='tooltip' data-placement='top' title='Mark Active'></i>"; ?></a>
                                                <a href="<?= base_url('admin/update_super_stockest/' . $row->id); ?>"><i class="square fa-solid fa-pen-to-square" data-toggl="tooltip" data-placement="top" title="Update" data-toggle="modal" data-target="#state-update"></i></a>
                                                <a href="<?= base_url('admin/super_stockest_detail/' . $row->id); ?>"><i class="fa-regular fa-address-card" data-toggl="tooltip" data-placement="top" title="View Profile" data-toggle="modal" style="background-color: #404E67;margin: 0 5px; padding: 5px;color: white;border-radius: 2px;" data-target="#state-update"></i></a>
                                                <a href="#" data-toggle="dropdown" class="dropdown"><i class="fa-sharp fa-solid fa-bars" data-toggl="tooltip" data-placement="top" title="List" data-toggle="modal" style="background-color: #277FB2;margin: 0 5px; padding: 5px;color: white;border-radius: 2px;" data-target="#state-update"></i></a>
                                                <ul class="dropdown-menu p-3" style="font-weight:bold;">
                                                    <li><a href="#">Stock</a></li>
                                                    <li><a href="#">Orders</a></li>
                                                    <li><a href="#">Send Orders</a></li>
                                                    <li><a href="#">Distributor</a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $("td a").click(function(){
            var a = $(this).attr("id");

            $.ajax({
                url: "<?= base_url('ajax/super_stockist_formdata')?>",
                type: "POST",
                dataType: "JSON",
                data: {id: a},
                success: function(data){
                    console.log(data);
                    $("#customer_id").val(data.cus_id);
                    $("#costumer_reason").val(data.reson);
                    $("#bussiness_name").val(data.business_name);
                    $("#nature_bussiness").val(data.nature_of_bussiness);
                    $("#nature_buss_pic").val(data.nature_buss_pic);
                    $("#gst").val(data.gst);
                    $("#gst_pic").val(data.gst_pic);
                    $("#pan").val(data.pan);
                    $("#pan_pic").val(data.pan_pic);
                    $("#fssai").val(data.fssai);
                    $("#fssai_pic").val(data.fssai_pic);
                    $("#min_od").val(data.min_od);
                    $("#max_od").val(data.max_od);
                    $("#contact_person").val(data.contact_person);
                    $("#contact_person_number").val(data.contact_person_no);
                    $("#country_id").val(data.country);
                    $("#state_id").val(data.state);
                    $("#city_id").val(data.city);
                    $("#address").val(data.address);
                    $("#state_id").val(data.pan_pic);
                    $("#latt").val(data.latitude);
                    $("#long").val(data.longitude);
                    $("#document").val(data.document);
                    $("#upload_image").val(data.upload_image);
                }

            });
            
        });

    });
</script> -->