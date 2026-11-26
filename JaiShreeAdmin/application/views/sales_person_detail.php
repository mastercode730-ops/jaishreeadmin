<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-12">
                <h4>Sales Person Details</h4>
            </div>
        </div>
    </div>
    <div>
        <form id="sales_form" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="form-group col-6 fontuser">
                    <label class="lab">Sales Person Code <span class="star">★</span></label>
                    <input type="text" name="emp_ids" value="<?= $sales_person_detail->saler_code; ?>" class="form-control" disabled/>
                    <i class="fa fa-file-code fa-lg"></i>
                </div>
                <div class="form-group col-6 fontuser">
                    <label class="lab">Sales Person Name <span class="star">★</span></label>
                    <input type="text" class="form-control" value="<?= $sales_person_detail->saler_name ?>" name="saler_names" disabled/>
                    <i class="fa fa-user fa-lg"></i>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-6 fontuser">
                    <label class="lab">Mother Name <span class="star">★</span></label>
                    <input type="text" class="form-control" value="<?= $sales_person_detail->mother_name; ?>" name="mother_names" disabled/>
                    <i class="fa fa-male fa-lg"></i>
                </div>
                <div class="form-group col-6 fontuser">
                    <label class="lab">Father Name <span class="star">★</span></label>
                    <input type="text" class="form-control" value="<?= $sales_person_detail->father_name ?>" name="father_names" disabled/>
                    <i class="fa fa-female fa-lg"></i>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-6 fontuser">
                    <label class="lab">D.O.B <span class="star">★</span></label>
                    <input type="date" class="form-control" value="<?= $sales_person_detail->dob; ?>" name="dobs" disabled/>
                    <i class="fa fa-calendar fa-lg"></i>
                </div>
                <div class="form-group col-6 fontuser">
                    <label class="lab">Family Detail <span class="star">★</span></label>
                    <input type="text" class="form-control" value="<?= $sales_person_detail->family_detail ?>" name="family_details" disabled/>
                    <i class="fa fa-indent fa-lg"></i>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-12 fontuser">
                    <label class="lab">Married <span class="star">★</span></label>
                    <input type="text" class="form-control" value="<?= $sales_person_detail->married; ?>" disabled/>
                    <i class="fa fa-indent fa-lg"></i>
                </div>
            </div>
            <div class="font">
                <h4>Location</h4>
            </div>
            <div class="row pt-2">
                <div class="form-group col-6 fontuser">
                    <label class="lab">Country Name <span class="star">★</span></label>
                    <?php $country = $this->Crud_model->fetch('country', array('country_id' => $sales_person_detail->country)); ?>
                    <input type="text" class="form-control" value="<?= $country->row('country_name'); ?>" disabled/>
                    <i class="fa fa-globe fa-lg "></i>
                </div>
                <div class="form-group col-6 fontuser">
                    <label class="lab">State Name <span class="star">★</span></label>
                    <?php $state = $this->Crud_model->fetch('state', array('id' => $sales_person_detail->state)); ?>
                    <input type="text" class="form-control" value="<?= $state->row('state_name'); ?>" disabled/>
                    <i class="fa fa-map-marker-alt fa-lg"></i>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-6 fontselect fontuser">
                    <label class="lab">City Name <span class="star">★</span></label>
                    <?php $city = $this->Crud_model->fetch('city', array('id' => $sales_person_detail->city)); ?>
                    <input type="text" class="form-control" value="<?= $city->row('city_name'); ?>" disabled/>
                    <i class="fa fa-map-marker-alt fa-lg "></i>
                </div>
                <div class="form-group col-6 fontuser pt-2">
                    <label class="lab">Area Name<span class="star">★</span></label>
                    <?php $area = $this->Crud_model->fetch('aria', array('id' => $sales_person_detail->area_id)); ?>
                    <input type="text" class="form-control" value="<?= $area->row('area_name'); ?>" disabled/>
                    <i class="fa fa-map-marker-alt fa-lg"></i>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-6 fontuser">
                    <label class="lab">Post <span class="star">★</span></label>
                    <input type="text" class="form-control" value="<?= $sales_person_detail->post; ?>" disabled/>
                    <i class="fa fa-map-marker-alt fa-lg"></i>
                </div>
                <div class="form-group col-6 fontuser ">
                    <label class="lab">Address <span class="star">★</span></label>
                    <input type="text" value="<?= $sales_person_detail->address; ?>" class="form-control" disabled/>
                    <i class="fa fa-home fa-lg"></i>
                </div>
            </div>
            <div class="font">
                <h4>Documentation</h4>
            </div>
            <div class="row pt-1">
                <div class="form-group col-12 fontuser">
                    <label class="lab">ID <span class="star">★</span></label>
                </div>
            </div>
            <div class="row">
                <div id="adhaar" class=" col-6 form-group fontuser">
                    <label class="lab">Adhar Card <span class="star">★</span></label>
                    <i class="fa fa-id-card fa-lg"></i>
                    <input type="text" value="<?= $sales_person_detail->adhar; ?>" class="form-control" name="adhar_card" disabled/>
                </div>
                <div id="adhaar_pic" class=" col-6 form-group fontuser">
                    <label class="lab pt-1">Adhar Pic. <span class="star">★</span></label></br>
                    <a href="#" class="btn btn-primary click-data mt-1" data-img="adhar_img" data-id="<?= $sales_person_detail->id ?>" data-toggle="modal" data-target="#exampleModal">View Adhar Card Image</a>
                </div>
            </div>
            <div class="row">
                <div id="voter" class=" col-6 form-group fontuser">
                    <label>Voter Id <span class="star">★</span></label>
                    <input type="text" class="form-control" value="<?= $sales_person_detail->voter_id; ?>" disabled/>
                    <i class="fa fa-id-badge fa-lg"></i>
                </div>
                <div id="voter_pic" class=" col-6 form-group fontuser">
                    <label class="lab pt-1">Voter Pic <span class="star">★</span></label></br>
                    <a href="#" class="btn btn-primary click-data mt-1" data-img="voter_img" data-id="<?= $sales_person_detail->id ?>" data-toggle="modal" data-target="#exampleModal">View Voter Card Image</a>
                </div>
            </div>
            <div class="row ">
                <div id="pan" class=" col-6 form-group fontuser">
                    <label>Pan Card <span class="star">★</span></label>
                    <input type="text" value="<?= $sales_person_detail->pan_no ?>" class="form-control" disabled/>
                    <i class="fa fa-address-card fa-lg"></i>
                </div>
                <div id="pan_pic" class=" col-6 form-group fontuser">
                    <label class="lab pt-1">Pan Card Pic <span class="star">★</span></label></br>
                    <a href="#" class="btn btn-primary click-data mt-1" data-img="pan_img" data-id="<?= $sales_person_detail->id ?>" data-toggle="modal" data-target="#exampleModal">View Pan Card Image</a>
                </div>
            </div>
            <div class="row pt-1">
                <div id="driving" class=" col-6 form-group fontuser">
                    <label>Driving Licence <span class="star">★</span></label>
                    <input type="text" value="<?= $sales_person_detail->driving_lic; ?>" class="form-control" disabled/>
                    <i class="fa fa-id-card fa-lg"></i>
                </div>
                <div id="driving_pic" class=" col-6 form-group fontuser">
                    <label class="lab pt-1">Driving Licence Pic <span class="star">★</span></label></br>
                    <a href="#" class="btn btn-primary click-data mt-1" data-img="driving_img" data-id="<?= $sales_person_detail->id ?>" data-toggle="modal" data-target="#exampleModal">View Driving Licence Image</a>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-6 fontselect">
                    <label class="lab">Qualification <span class="star">★</span></label>
                    <input type="text" value="<?= $sales_person_detail->qualification; ?>" class="input form-control" disabled/>
                    <i class="fa fa-list-alt fa-lg"></i>
                </div>
                <div class="form-group col-6 fontuser">
                    <label class="lab">Report To <span class="star">★</span></label>
                    <input type="text" value="<?= $sales_person_detail->report_to; ?>" class="form-control" disabled/>
                    <i class="fa fa-list-alt fa-lg"></i>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-6 fontuser">
                    <label class="lab">Upload Image <span class="star">★</span></label></br>
                    <a target="_blank" href='<?= base_url("$sales_person_detail->uplode_image"); ?>' class="btn btn-primary mt-1">View Upload Image</a>
                </div>
                <div class="form-group col-6 fontuser">
                    <label class="lab">Upload Resume <span class="star">★</span></label></br>
                    <a target="_blank" href='<?= base_url("$sales_person_detail->uplode_resume"); ?>' class="btn btn-primary mt-1">Download Resume</a>
                </div>
            </div>
            <div class="row d-flex justify-content-end">
                <div class="form-group col-2 fonbtn">
                    <a href="<?= base_url('admin/sales_person'); ?>" class="form-control btn btn-danger" style="padding: 12px;">Cancel</a>
                    <i class="fa fa-ban fa-lg"></i>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="">
                    <div id="imgs"></div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $(".click-data").click(function() {
            var img_type = $(this).data("img");
            var id = $(this).data("id");
            var table = "sales_person_details";
            $.ajax({
                url: "<?= base_url('ajax/show_image'); ?>",
                type: "POST",
                dataType: "JSON",
                data: {
                    id: id,
                    img_type: img_type,
                    table: table
                },
                success: function(data) {
                    $("#imgs").html(data.img);
                },
            });
        });
    });
</script>