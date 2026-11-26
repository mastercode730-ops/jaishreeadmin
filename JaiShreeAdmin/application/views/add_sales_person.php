<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-12">
                <h4>Add Sales Person</h4>
            </div>
        </div>
    </div>
    <div>
        <form id="sales_form" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="form-group col-6 fontuser">
                    <label class="lab">Sales Person Code <span class="star">★</span></label>
                    <input type="text" name="emp_ids" class="form-control" placeholder="Enter the Sales Person Code">
                    <i class="fa fa-file-code fa-lg"></i>
                </div>
                <div class="form-group col-6 fontuser">
                    <label class="lab">Sales Person Name <span class="star">★</span></label>
                    <input type="text" class="form-control" name="saler_names" placeholder="Enter the Sales Person Name">
                    <i class="fa fa-user fa-lg"></i>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-6 fontuser">
                    <label class="lab">Mother Name <span class="star">★</span></label>
                    <input type="text" class="form-control" name="mother_names" placeholder="Enter the Mother Name">
                    <i class="fa fa-male fa-lg"></i>
                </div>
                <div class="form-group col-6 fontuser">
                    <label class="lab">Father Name <span class="star">★</span></label>
                    <input type="text" class="form-control" name="father_names" placeholder="Enter the Father Name">
                    <i class="fa fa-female fa-lg"></i>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-6 fontuser">
                    <label class="lab">D.O.B <span class="star">★</span></label>
                    <input type="date" class="form-control" name="dobs" placeholder="Enter the Date of Birth">
                    <i class="fa fa-calendar fa-lg"></i>
                </div>
                <div class="form-group col-6 fontuser">
                    <label class="lab">Family Detail <span class="star">★</span></label>
                    <input type="text" class="form-control" name="family_details" placeholder="Enter the Sales Person Family Detail">
                    <i class="fa fa-indent fa-lg"></i>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-12">
                    <label class="lab">Married <span class="star">★</span></label>
                    <select class="form-control" name="married">
                        <option value="">--Select Married Status--</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </div>
            </div>
            <div class="font">
                <h4>Location</h4>
            </div>
            <div class="row pt-2">
                <div class="form-group col-6 fontselect">
                    <label class="lab">Country Name <span class="star">★</span></label>
                    <select name="country_id" class="form-control" id="country_id" style="color:#777777; outline: 1px solid #ced4da;padding-top:8px">
                        <option value="">--Select country name--</option>
                        <?php foreach ($country as $row) : ?>
                            <option value="<?= $row->country_id; ?>"><?= $row->country_name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <i class="fa fa-globe fa-lg "></i>
                </div>
                <div class="form-group col-6 fontselect">
                    <label class="lab">State Name <span class="star">★</span></label>
                    <select name="state_id" class="form-control" id="state_id" style="color:#777777; outline: 1px solid #ced4da;padding-top:8px">
                        <option value="">--Select State name--</option>
                    </select>
                    <i class="fa fa-map-marker-alt fa-lg"></i>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-6 fontselect">
                    <label class="lab">City Name <span class="star">★</span></label>
                    <select name="city_id" class="form-control" id="city_id" style="color:#777777; outline: 1px solid #ced4da;">
                        <option value="">--Select City name--</option><i class="fa fa-location fa-lg"></i>
                    </select>
                    <i class="fa fa-map-marker-alt fa-lg "></i>
                </div>
                <div class="form-group col-6 fontselect pt-2">
                    <label class="lab">Area Name<span class="star">★</span></label>
                    <select name="area_id[]" id="area_id" class="form-control" style="margin-left: 32px;" multiple required>
                        <option value="">--Select Area name--</option>
                    </select>
                    <i class="fa fa-map-marker-alt fa-lg"></i>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-6 fontselect">
                    <label class="lab">Post <span class="star">★</span></label>
                    <select name="sales_post" class="form-control" id="post" style="color:#777777; outline: 1px solid #ced4da">
                        <option value="">--Select Post--</option>
                        <option value="asm">ASM</option>
                        <option value="sr">SR</option>
                        <option value="office">Office</option>
                        <option value="sales_person">Sales Person</option>
                        <option value="others">Others</option>
                    </select>
                    <i class="fa fa-map-marker-alt fa-lg"></i>
                </div>
                <div class="form-group col-6 fontuser ">
                    <label class="lab">Address <span class="star">★</span></label>
                    <input type="text" class="form-control" name="addresss">
                    <i class="fa fa-home fa-lg"></i>
                </div>
            </div>
            <div class="font">
                <h4>Documentation</h4>
            </div>
            <div class="row pt-1">
                <div class="form-group col-4 fontselect">
                    <label class="lab">ID <span class="star">★</span></label>
                    <select name="id" class="form-control" id="idd" style="color:#777777; outline: 1px solid #ced4da;margin-top: 10px">
                        <option value="">--Select Your ID Card--</option>
                        <option value="adhar_card">Adhar Card</option>
                        <option value="voter_id">Voter Id</option>
                        <option value="pan_card">PAN Card</option>
                        <option value="dir_lic">Driving Lic.</option>
                    </select>
                    <i class="fa fa-map-marker-alt fa-lg "></i>
                </div>
                <div id="adhaar" style="display: none;" class="col-4 form-group fontuser">
                    <label class="lab">Adhar Card <span class="star">★</span></label>
                    <i class="fa fa-id-card fa-lg"></i>
                    <input type="text" class="form-control" name="adhar_card" placeholder=" Enter the Adhar Card">
                </div>
                <div id="adhaar_pic" class="col-4 form-group fontuser" style="display: none;">
                    <label class="lab pt-1">Adhar Pic. <span class="star">★</span></label></br>
                    <input type="file" accept=".jpg,.png,.jpeg" name="adhar_pic" class="image-input form-control">
                </div>

                <div id="voter" style="display: none;" class="col-4 form-group fontuser">
                    <label>Voter Id <span class="star">★</span></label>
                    <input type="text" class="form-control" name="voter_id" placeholder=" Enter the Voter Id">
                    <i class="fa fa-id-badge fa-lg"></i>
                </div>
                <div id="voter_pic" style="display: none;" class="col-4 form-group fontuser">
                    <label class="lab pt-1">Voter Pic <span class="star">★</span></label></br>
                    <input type="file" class="image-input form-control" accept=".jpg,.png,.jpeg" name="voter_pic">
                </div>
                <div id="pan" style="display: none;" class="col-4 form-group fontuser">
                    <label>Pan Card <span class="star">★</span></label>
                    <input type="text" class="form-control" name="pan_card" placeholder=" Enter the Pan Card">
                    <i class="fa fa-address-card fa-lg"></i>
                </div>
                <div id="pan_pic" style="display: none;" class="col-4 form-group fontuser">
                    <label class="lab pt-1">Pan Card Pic <span class="star">★</span></label></br>
                    <input type="file" class="image-input form-control" accept=".jpg,.png,.jpeg" name="pan_pic">
                </div>
                <div id="driving" style="display: none;" class="col-4 form-group fontuser">
                    <label>Driving Licence <span class="star">★</span></label>
                    <input type="text" class="form-control" name="dir_lic" placeholder=" Enter the Driving Licence">
                    <i class="fa fa-id-card fa-lg"></i>
                </div>
                <div id="driving_pic" style="display: none;" class="col-4 form-group fontuser">
                    <label class="lab pt-1">Driving Licence Pic <span class="star">★</span></label></br>
                    <input type="file" class="image-input form-control" accept=".jpg,.png,.jpeg" name="driving_pic">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-6 fontselect">
                    <label class="lab">Qualification <span class="star">★</span></label>
                    <select name="qualification" class="form-control" id="qualification" style="color:#777777; outline: 1px solid #ced4da;padding-top:8px">
                        <option>--Select Qualification --</option>
                        <option value="high_school">High School</option>
                        <option value="intermediate">Intermediate</option>
                        <option value="diploma">Diploma</option>
                        <option value="degree">Degree</option>
                        <option value="other">Others</option>
                    </select>
                    <i class="fa fa-list-alt fa-lg"></i>
                </div>
                <div class="form-group col-6 fontuser">
                    <label class="lab">Report To <span class="star">★</span></label>
                    <input type="text" class="form-control" name="reports" placeholder="Enter the Sales Person Report">
                    <i class="fa fa-list-alt fa-lg"></i>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-6 fontuser">
                    <label class="lab">Upload Image <span class="star">★ (Select Only Docx & PDF)</span></label>
                    <input type="file" class="image-input form-control" name="upload_image" accept=".pdf,.docx" placeholder="Enter the Upload Image">
                </div>
                <div class="form-group col-6 fontuser">
                    <label class="lab">Upload Document <span class="star">★ (Select Only Docx & PDF)</span></label>
                    <input type="file" class="image-input form-control" name="document" accept=".pdf,.docx" placeholder="Enter the Upload Document">
                </div>
            </div>
            <div class="row d-flex justify-content-end">
                <div class="form-group col-2 fonbtn">
                    <a href="<?= base_url('admin/sales_person'); ?>" class="form-control btn btn-danger" style="padding: 12px;">Cancel</a>
                    <i class="fa fa-ban fa-lg"></i>
                </div>
                <div class="form-group col-2 fonbtn">
                    <input type="submit" class="form-control btn btn-lg btn-success" style="padding: 4px;" name="submit">
                    <i class="fa fa-check-circle fa-lg"></i>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('#area_id').select2();

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

        $("#city_id").on("change", function() {
            var area_id = $(this).val();
            $.ajax({
                url: "<?php echo base_url(); ?>ajax/area_load",
                type: "POST",
                data: {
                    area_id_all: area_id
                },
                beforeSend: function() {
                    $("#area_id").html("<option value=''>--Loading Area--</option>");
                },
                success: function(data) {
                    $("#area_id").html(data);
                }
            });
        });

        // $("#city_id").on("change", function() {
        //     var city_id = $(this).val();
        //     $.ajax({
        //         url: "<?php echo base_url(); ?>ajax/distributor_load",
        //         type: "POST",
        //         data: {
        //             city_id: city_id
        //         },
        //         beforeSend: function() {
        //             $("#distributor").html("<option value=''>--Loading Distributor--</option>");
        //         },
        //         success: function(data) {
        //             $("#distributor").html(data);
        //         }
        //     });
        // });


    });
</script>
<script>
    $(document).ready(function() {
        $("#idd").change(function() {
            var selectTag = $(this).val();
            if (selectTag == "adhar_card") {
                $("#adhaar").show();
                $("#adhaar_pic").show();
                $("#voter").hide();
                $("#voter_pic").hide();
                $("#pan").hide();
                $("#pan_pic").hide();
                $("#driving").hide();
                $("#driving_pic").hide();

            } else if (selectTag == "voter_id") {
                $("#adhaar").hide();
                $("#adhaar_pic").hide();
                $("#voter").show();
                $("#voter_pic").show();
                $("#pan").hide();
                $("#pan_pic").hide();
                $("#driving").hide();
                $("#driving_pic").hide();

            } else if (selectTag == "pan_card") {
                $("#adhaar").hide();
                $("#adhaar_pic").hide();
                $("#voter").hide();
                $("#voter_pic").hide();
                $("#pan").show();
                $("#pan_pic").show();
                $("#driving").hide();
                $("#driving_pic").hide();

            } else if (selectTag == "dir_lic") {
                $("#adhaar").hide();
                $("#adhaar_pic").hide();
                $("#voter").hide();
                $("#voter_pic").hide();
                $("#pan").hide();
                $("#pan_pic").hide();
                $("#driving").show();
                $("#driving_pic").show();

            } else {
                $("#adhaar").hide();
                $("#adhaar_pic").hide();
                $("#voter").hide();
                $("#voter_pic").hide();
                $("#pan").hide();
                $("#pan_pic").hide();
                $("#driving").hide();
                $("#driving_pic").hide();

            }
        });
    });
</script>
<script>
    jQuery('#sales_form').validate({
        rules: {
            emp_ids: "required",
            saler_names: "required",
            mother_names: "required",
            father_names: "required",
            id_nos: "required",
            dobs: "required",
            addresss: "required",
            area_assignals: "required",
            reports: "required",
            family_details: "required",
            document: "required",
            upload_image: "required",
            sales_post: "required",
            country_id: "required",
            state_id: "required",
            city_id: "required",
            qualification: "required",
            max_od: "required",
            min_od: "required",
            id: "required",
            married: "required"
        },
        messages: {
            emp_ids: "<p class='text-danger'>Please Enter Sales Person Code.*</p>",
            saler_names: "<p class='text-danger'>Please Enter Sales Person Name*</p>",
            mother_names: "<p class='text-danger'>Please Enter Mother Name*</p>",
            father_names: "<p class='text-danger'>Please Enter  Father Name*</p>",
            id_nos: "<p class='text-danger'>Please Enter ID No.*</p>",
            dobs: "<p class='text-danger pt-2'>Please Enter Date of Birth*</p>",
            addresss: "<p class='text-danger'>Please Enter Address*</p>",
            area_assignals: "<p class='text-danger'>Please Enter  Area Assignal.*</p>",
            reports: "<p class='text-danger'>Please Enter  Report*</p>",
            family_details: "<p class='text-danger'>Please Enter Family Detail*</p>",
            document: "<p class='text-danger pt-2'>Please Enter Document*</p>",
            upload_image: "<p class='text-danger pt-2'>Please Enter Upload Image*</p>",
            sales_post: "<p class='text-danger'>Please Select  Saller Post*</p>",
            country_id: "<p class='text-danger'>Please Select Country Name*</p>",
            state_id: "<p class='text-danger'>Please Select State Name*</p>",
            city_id: "<p class='text-danger'>Please Select City Name*</p>",
            qualification: "<p class='text-danger'>Please Select Qualification*</p>",
            max_od: "<p class='text-danger'>Please Enter Max. Over Draft Amount*</p>",
            min_od: "<p class='text-danger'>Please Enter Min. Over Draft Amount*</p>",
            id: "<p class='text-danger'>Please Enter ID*</p>",
            married: "<p class='text-danger pt-2'>Please Select Married Status*</p>"
        }
    });
</script>