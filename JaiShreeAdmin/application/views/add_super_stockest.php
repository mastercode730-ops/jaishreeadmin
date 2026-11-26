<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-12">
                <h4>Add Super Stockist</h4>
            </div>
        </div>
    </div>
    <div>
        <form id="form" method="post" enctype="multipart/form-data">

            <div class="row">
                <div class="form-group col-6 fontuser">
                    <label class="lab">Customer ID <span class="star">★</span></label>
                    <input type="text" name="emp_id" class="form-control" placeholder="Please Enter Customer ID">
                    <i class="fa fa-id-card fa-lg"></i>
                </div>
                <div class="form-group col-6 fontselect">
                    <label class="lab">Customer Reason <span class="star">★</span></label>
                    <select name="costumer_reason" class="form-control" id="costumer_reason" style="color:#777777; outline: 1px solid #ced4da;padding-top:8px">
                        <option value="">--Select the Option--</option>
                        <option value="new">New</option>
                        <option value="area_cut">Area Cut</option>
                        <option value="replacement">Replacement</option>
                        <option value="name_change">Name Change</option>
                    </select>
                    <i class="fa fa-users fa-lg "></i>
                </div>

            </div>
            <div class="font">
                <h4>Bussiness Details</h4>
            </div>
            <div class="row pt-2">
                <div class="form-group col-12 fontuser">
                    <label class="lab">Business Name <span class="star">★</span></label>
                    <input type="text" class="form-control" name="bussiness_name" placeholder="Please Enter Business Name">
                    <i class="fa fa-id-card fa-lg"></i>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-6 fontselect">
                    <label class="lab">Nature of Bussiness <span class="star">★</span></label>
                    <i class="fa fa-business-time fa-lg"></i>
                    <select name="nature_bussiness" class="form-control" id="nature_bussiness" style="color:#777777; outline: 1px solid #ced4da;padding-top:8px">
                        <option value="">--Select the Option--</option>
                        <option value="partnership">Partnership</option>
                        <option value="propritor">Propritor</option>
                        <option value="compony">Compony</option>
                        <option value="firm/llp">Firm/LLP</option>
                    </select>
                </div>
                <div class="form-group col-6">
                    <label class="lab">Nature of Bussiness Image <span class="star">★ (Select Only JPEG, JPG & PNG)</span></label>
                    <input type="file" name="nature_buss_pic" accept=".jpg,.png,.jpeg" class="image-input form-control">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-6 fontuser">
                    <label class="lab">GST No. <span class="star">★</span></label>
                    <input type="text" class="form-control sup" id="gst" name="gst" placeholder="Please Enter GST Number">
                    <i class="fa fa-file-alt fa-lg"></i>
                </div>
                <div class="form-group col-6">
                    <label class="lab">GST No. Pic <span class="star">★ (Select Only JPEG, JPG & PNG)</span></label>
                    <input type="file" name="gst_pic" id="gst_pic" accept=".jpg,.png,.jpeg" class="image-input form-control sup">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-6 fontuser">
                    <label class="lab">Pan Card <span class="star">★</span></label>
                    <input type="text" class="form-control sup" name="pan" placeholder="Please Enter pan card details">
                    <i class="fa fa-passport fa-lg"></i>
                </div>
                <div class="form-group col-6">
                    <label class="lab">Pan Card Pic <span class="star">★ (Select Only JPEG, JPG & PNG)</span></label>
                    <input type="file" name="pan_pic" accept=".jpg,.png,.jpeg" class="image-input form-control">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-6 fontuser">
                    <label class="lab">FSSAI <span class="star">★</span></label>
                    <input type="text" class="form-control" id="fssai" name="fssai" placeholder="Please Enter FSSAI number">
                    <i class="fa fa-passport fa-lg"></i>
                </div>
                <div class="form-group col-6">
                    <label class="lab">FSSAI Pic <span class="star">★ (Select Only JPEG, JPG & PNG)</span></label>
                    <input type="file" name="fssai_pic" id="fssai_pic" accept=".jpg,.png,.jpeg" class="image-input form-control">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-6 fontuser">
                    <label class="lab">Min.OD Amount(Overdraft) <span class="star">★</span></label>
                    <input type="number" name="min_od" class="form-control" placeholder="Enter Min. OD Amount">
                    <i class="fa fa-money-bill-1-wave fa-lg"></i>
                </div>
                <div class="form-group col-6 fontuser">
                    <label class="lab">Max.OD Amount(Overdraft) <span class="star">★</span></label>
                    <input type="number" name="max_od" class="form-control" placeholder="Enter Max. OD Amount">
                    <i class="fa fa-money-bill-1-wave fa-lg"></i>
                </div>
            </div>
            <div class="font">
                <h4>Personal Details</h4>
            </div>
            <div class="row pt-2">
                <div class="form-group col-6 fontuser">
                    <label class="lab">Contact Person <span class="star">★</span></label>
                    <input type="text" class="form-control" name="contact_person" placeholder="Please Enter Contact Person">
                    <i class="fa fa-user fa-lg"></i>
                </div>
                <div class="form-group col-6 fontuser">
                    <label class="lab">Contact Person Number <span class="star">★</span></label>
                    <input type="number" class="form-control" name="contact_person_number" placeholder="Please Enter Contact Person Number" />
                    <i class="fa fa-mobile-android-alt fa-lg"></i>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-12 fontuser">
                    <label class="lab">D.O.B <span class="star">★</span></label>
                    <input type="date" class="form-control" name="dob" placeholder="Enter the Date of Birth">
                    <i class="fa fa-calendar-check fa-lg"></i>
                </div>
            </div>
            <div class="font">
                <h4>Location</h4>
            </div>
            <div class="row pt-2">
                <div class="form-group col-6 fontselect">
                    <label class="lab">Country Name <span class="star">★</span></label>
                    <select name="country_id" class="form-control" id="country_id" style="color:#777777; outline: 1px solid #ced4da">
                        <option value="">--Select country name--</option>
                        <?php foreach ($country as $row) : ?>
                            <option value="<?= $row->country_id; ?>"><?= $row->country_name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <i class="fa fa-globe fa-lg "></i>
                </div>
                <div class="form-group col-6 fontselect">
                    <label class="lab">State Name <span class="star">★</span></label>
                    <select name="state_id" class="form-control" id="state_id" style="color:#777777; outline: 1px solid #ced4da">
                        <option value="">--Select State name--</option>
                    </select>
                    <i class="fa fa-map-marker-alt fa-lg "></i>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-6 fontselect">
                    <label class="lab">City Name <span class="star">★</span></label>
                    <select name="city_id" class="form-control" id="city_id" style="color:#777777; outline: 1px solid #ced4da">
                        <option value="">--Select City name--</option><i class="fa fa-location fa-lg"></i>
                    </select>
                    <i class="fa fa-map-marker-alt fa-lg "></i>
                </div>
                <div class="form-group col-6 fontuser ">
                    <label class="lab">Address <span class="star">★</span></label>
                    <input type="text" name="address" class="form-control" placeholder="Enter Your Address">
                    <i class="fa fa-home fa-lg"></i>
                </div>
            </div>
            <div class="form-group col-12">
                <span><label class="lab">Geo Location (Pic Your Current Location*) <span class="star">★</span></label>
                    <div>
                        <i class='fas fa-map-marker-alt' style='font-size:32px;color:red;background-color:white' data-bs-toggle="modal" data-bs-target="#exampleModal"></i>
                    </div>
                </span>
                <div class="row p-2">
                    <div class="col-6 form-group fontuser">
                        <label class="lab">Latitude <span class="star">★</span></label>
                        <input type="text" class="form-control" name="latt" id="onClickPositionView">
                        <i class="fa fa-map-pin fa-lg"></i>
                    </div>
                    <div class="col-6 form-group fontuser">
                        <label class="lab">Longitude <span class="star">★</span></label>
                        <input type="text" class="form-control" name="long" id="df">
                        <i class="fa fa-map-pin fa-lg"></i>
                    </div>
                </div>
            </div>
            <div class="font">
                <h4>Documents</h4>
            </div>
            <div class="row pt-2">
                <div class="form-group col-6">
                    <label class="lab">Upload Document <span class="star">★ </span></label>
                    <input type="file" class="image-input form-control" name="document" accept=".docx,.pdf" placeholder="Enter the Upload Document">
                    <i class="fa fa-location fa-lg"></i>
                </div>
                <div class="form-group col-6">
                    <label class="lab">Agreement <span class="star">★ </span></label>
                    <input type="file" class="image-input form-control" name="upload_image" accept=".docx,.pdf" placeholder="Enter the Upload Image">
                    <i class="fa fa-location fa-lg"></i>
                </div>
            </div>
            <div class="row d-flex justify-content-end">
                <div class="form-group col-2 fonbtn">
                    <a href="<?= base_url('admin/super_stockest'); ?>" class="form-control btn btn-danger" style="padding: 12px;">Cancel</a>
                    <i class="fa fa-ban fa-lg"></i>
                </div>
                <div class="form-group col-2 fonbtn">
                    <input type="submit" class="form-control btn btn-success" id="submit" name="submit" style="padding: 4px;">
                    <i class="fa fa-check-circle fa-lg"></i>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Map</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="map">
                </div>
                <br>
                <div class="modal-footer">
                    <button id="confirmPosition" type="button" class="btn btn-info" data-bs-dismiss="modal" aria-label="Close">Confirm Position</button>
                </div>
            </div>
        </div>
    </div>
</div>
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

        $("#submit").click(function() {
            var gst = $("#gst").val();
            var gst_pic = $("#gst_pic");
            var fssai = $("#fssai").val();
            var fssai_pic = $("#fssai_pic");

            if (gst_pic.val() !== "" || gst !== "") {
                if (gst_pic.val() == "") {
                    alert("Please Select GST Image....");
                }
                if (gst == "") {
                    alert("Please Enter GST Number....");
                }
            }

            if (fssai_pic.val() !== "" || fssai !== "") {
                if (fssai_pic.val() == "") {
                    alert("Please Select fssai Image....");
                }
                if (fssai == "") {
                    alert("Please Select fssai Number....");
                }
            }
        });

    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key="></script>
<script src="https://unpkg.com/location-picker/dist/location-picker.min.js"></script>
<script>
    var confirmBtn = document.getElementById('confirmPosition');
    var onClickPositionView = document.getElementById('onClickPositionView');
    var onIdlePositionView = document.getElementById('onIdlePositionView');
    var lp = new locationPicker('map', {
        setCurrentPosition: true,
    }, {
        zoom: 15 
    });
    confirmBtn.onclick = function() {
        var location = lp.getMarkerPosition();
        onClickPositionView.value = location.lat;
        document.getElementById('df').value = location.lng;
    };

    google.maps.event.addListener(lp.map, 'idle', function(event) {
        var location = lp.getMarkerPosition();
        onIdlePositionView.innerHTML = 'The chosen location is ' + location.lat + ',' + location.lng;
    });
</script>
<script>
    jQuery('#form').validate({
        rules: {
            emp_id: "required",
            bussiness_name: "required",
            costumer_reason: "required",
            nature_buss_pic: "required",
            gst_pic: "required",
            pan_pic: "required",
            fssai_pic: "required",
            nature_bussiness: "required",
            gst: "required",
            pan: "required",
            fssai: "required",
            country_id: "required",
            state_id: "required",
            city_id: "required",
            contact_person: "required",
            dob: "required",
            address: "required",
            latt: "required",
            long: "required",
            document: "required",
            upload_image: "required",
            max_od: "required",
            min_od: "required",
            contact_person_number: {
                required: true,
                maxlength: 10
            }
        },
        messages: {
            emp_id: "<p class='text-danger'>Please Enter  Emp ID*</p>",
            nature_bussiness: "<p class='text-danger'>Please Enter  Nature Bussiness*</p>",
            nature_buss_pic: "<p class='text-danger pt-2'>Please Insert Nature Bussiness Pic*</p>",
            gst_pic: "<p class='text-danger pt-2'>Please Insert GST Pic*</p>",
            pan_pic: "<p class='text-danger pt-2'>Please Insert Pan Card Pic*</p>",
            fssai_pic: "<p class='text-danger pt-2'>Please Insert fssai Picture*</p>",
            bussiness_name: "<p class='text-danger'>Please Enter  Bussiness Name*</p>",
            gst: "<p class='text-danger'>Please Enter  GST*</p>",
            pan: "<p class='text-danger'>Please Enter  Pan no.*</p>",
            fssai: "<p class='text-danger'>Please Enter  fssai no.*</p>",
            costumer_reason: "<p class='text-danger'>Please Select Costumer Rreason*</p>",
            country_id: "<p class='text-danger'>Please Select Country Name*</p>",
            state_id: "<p class='text-danger'>Please Select State Name*</p>",
            city_id: "<p class='text-danger'>Please Select City Name*</p>",
            contact_person: "<p class='text-danger'>Please Enter  Contact Person Name*</p>",
            contact_person_number: {
                required: "<p class='text-danger'>Please Enter  Contact Person no.*</p>",
                maxlength: "<p class='text-danger'>Please Enter maximum 10 Digit  Contact Person no.*</p>"
            },
            dob: "<p class='text-danger'>Please Enter  Date of Birth*</p>",
            address: "<p class='text-danger'>Please Enter  Address*</p>",
            latt: "<p class='text-danger'>Please Enter  Location Lattitude*</p>",
            long: "<p class='text-danger'>Please Enter Location Longitude*</p>",
            document: "<p class='text-danger pt-2'>Please Insert  Document*</p>",
            upload_image: "<p class='text-danger pt-2'>Please Insert Uplode Image*</p>",
            max_od: "<p class='text-danger'>Please Enter Max. Over Draft Amount*</p>",
            min_od: "<p class='text-danger'>Please Enter Min. Over Draft Amount*</p>"
        }
    });
</script>