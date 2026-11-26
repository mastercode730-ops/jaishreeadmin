<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-12">
                <h4>View Super Stockist Details</h4>
            </div>
        </div>
    </div>
    <div>
        <form id="form" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="form-group col-6 fontuser">
                    <label class="lab">Customer ID <span class="star">★</span></label>
                    <input type="text" name="emp_id" value="<?= $detail_stockist->cus_id; ?>" class="form-control" disabled/>
                    <i class="fa fa-id-card fa-lg"></i>
                </div>
                <div class="form-group col-6 fontuser">
                    <label class="lab">Customer Reason <span class="star">★</span></label>
                    <input type="text" value="<?= $detail_stockist->reson; ?>" name="costumer_reason" class="form-control" disabled/>
                    <i class="fa fa-users fa-lg "></i>
                </div>

            </div>
            <div class="font">
                <h4>Bussiness Details</h4>
            </div>
            <div class="row pt-2">
                <div class="form-group col-6 fontuser">
                    <label class="lab">Business Name <span class="star">★</span></label>
                    <input type="text" value="<?= $detail_stockist->business_name; ?>" class="form-control" name="bussiness_name" disabled/>
                    <i class="fa fa-id-card fa-lg"></i>
                </div>
                <div class="form-group col-6">
                    <label class="lab">Nature of Bussiness Image <span class="star">★ </span></label></br>
                    <a href="#" style="padding: left 10px;" class="btn btn-primary click-data mt-2" data-img="business_img" data-id="<?= $detail_stockist->id; ?>" data-toggle="modal" data-target="#exampleModal" disabled/>View Bussiness Image</a>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-6 fontuser">
                    <label class="lab">GST No. <span class="star">★</span></label>
                    <input type="text" class="form-control" value="<?= $detail_stockist->gst; ?>" id="gst" name="gst" disabled/>
                    <i class="fa fa-file-alt fa-lg"></i>
                </div>
                <div class="form-group col-6">
                    <label class="lab">GST No. Pic <span class="star">★ </span></label></br>
                    <a href="#" data-img="gst_img" data-id="<?= $detail_stockist->id ?>" class="btn btn-primary click-data mt-2" data-toggle="modal" data-target="#exampleModal">View GST Image</a>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-6 fontuser">
                    <label class="lab">Pan Card <span class="star">★</span></label>
                    <input type="text" class="form-control" value="<?= $detail_stockist->pan; ?>" name="pan" disabled/>
                    <i class="fa fa-passport fa-lg"></i>
                </div>
                <div class="form-group col-6">
                    <label class="lab">Pan Card Pic <span class="star">★ </span></label></br>
                    <a href="#" class="btn btn-primary click-data mt-2" data-img="pan_img" data-id="<?= $detail_stockist->id ?>" data-toggle="modal" data-target="#exampleModal">View Pan Card Image</a>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-6 fontuser">
                    <label class="lab">FSSAI <span class="star">★</span></label>
                    <input type="text" class="form-control" value="<?= $detail_stockist->fssai; ?>" id="fssai" name="fssai" disabled/>
                    <i class="fa fa-passport fa-lg"></i>
                </div>
                <div class="form-group col-6">
                    <label class="lab">FSSAI Pic <span class="star">★ </span></label></br>
                    <a href="#" class="btn btn-primary click-data mt-2" data-img="fssai_img" data-id="<?= $detail_stockist->id; ?>" data-toggle="modal" data-target="#exampleModal">View FSSAI Image</a>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-6 fontuser">
                    <label class="lab">Min.OD Amount(Overdraft) <span class="star">★</span></label>
                    <input type="number" name="min_od" class="form-control" value="<?= $detail_stockist->min_od; ?>" disabled/>
                    <i class="fa fa-money-bill-1-wave fa-lg"></i>
                </div>
                <div class="form-group col-6 fontuser">
                    <label class="lab">Max.OD Amount(Overdraft) <span class="star">★</span></label>
                    <input type="number" name="max_od" class="form-control" value="<?= $detail_stockist->max_od; ?>" disabled/>
                    <i class="fa fa-money-bill-1-wave fa-lg"></i>
                </div>
            </div>
            <div class="font">
                <h4>Personal Details</h4>
            </div>
            <div class="row pt-2">
                <div class="form-group col-6 fontuser">
                    <label class="lab">Contact Person <span class="star">★</span></label>
                    <input type="text" class="form-control" value="<?= $detail_stockist->contact_person; ?>" name="contact_person" disabled/>
                    <i class="fa fa-user fa-lg"></i>
                </div>
                <div class="form-group col-6 fontuser">
                    <label class="lab">Contact Person Number <span class="star">★</span></label>
                    <input type="number" class="form-control" value="<?= $detail_stockist->contact_person_no; ?>" name="contact_person_number" disabled/>
                    <i class="fa fa-mobile-android-alt fa-lg"></i>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-12 fontuser">
                    <label class="lab">D.O.B <span class="star">★</span></label>
                    <input type="date" value="<?= $detail_stockist->dob; ?>" class="form-control" name="dob" disabled/>
                    <i class="fa fa-calendar-check fa-lg"></i>
                </div>
            </div>
            <div class="font">
                <h4>Location</h4>
            </div>
            <div class="row pt-2">
                <div class="form-group col-6 fontuser">
                    <label class="lab">Country Name <span class="star">★</span></label>
                    <?php $country = $this->Crud_model->fetch('country', array('country_id' => $detail_stockist->country)); ?>
                    <input type="text" class="form-control" value="<?= $country->row('country_name'); ?>" disabled/>
                    <i class="fa fa-globe fa-lg "></i>
                </div>
                <div class="form-group col-6 fontselect">
                    <label class="lab">State Name <span class="star">★</span></label>
                    <?php $state = $this->Crud_model->fetch('state', array('id' => $detail_stockist->state)); ?>
                    <input type="text" class="form-control" value="<?= $state->row('state_name'); ?>" disabled/>
                    <i class="fa fa-map-marker-alt fa-lg "></i>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-6 fontselect">
                    <label class="lab">City Name <span class="star">★</span></label>
                    <?php $city = $this->Crud_model->fetch('city', array('id' => $detail_stockist->city)); ?>
                    <input type="text" class="form-control" value="<?= $city->row('city_name'); ?>" disabled/>
                    <i class="fa fa-map-marker-alt fa-lg "></i>
                </div>
                <div class="form-group col-6 fontuser ">
                    <label class="lab">Address <span class="star">★</span></label>
                    <input type="text" name="address" value="<?= $detail_stockist->address; ?>" class="form-control" disabled/>
                    <i class="fa fa-home fa-lg"></i>
                </div>
            </div>
            <div class="form-group col-12">
                <span><label class="lab">Geo Location (Pic Your Current Location*) <span class="star">★</span></label>
                    <div>
                        <i class='fas fa-map-marker-alt' style='font-size:32px;color:red;background-color:white' ></i>
                    </div>
                </span>
                <div class="row p-2">
                    <div class="col-6 form-group fontuser">
                        <label class="lab">Latitude <span class="star">★</span></label>
                        <input type="text" class="form-control" value="<?= $detail_stockist->latitude; ?>" disabled/>
                        <i class="fa fa-map-pin fa-lg"></i>
                    </div>
                    <div class="col-6 form-group fontuser">
                        <label class="lab">Longitude <span class="star">★</span></label>
                        <input type="text" value="<?= $detail_stockist->longitude; ?>" class="form-control" disabled/>
                        <i class="fa fa-map-pin fa-lg"></i>
                    </div>
                </div>
            </div>
            <div class="font">
                <h4>Documents</h4>
            </div>
            <div class="row pt-2">

                <div class="form-group col-6">
                    <label class="lab">Upload Document <span class="star">★ </span></label></br>
                    <a target="_blank" href='<?= base_url("$detail_stockist->update_form"); ?>' class="btn btn-primary mt-2">View Upload Document</a>
                </div>
                <div class="form-group col-6">
                    <label class="lab">Agrement <span class="star">★ </span></label></br>
                    <a target="_blank" href='<?= base_url("$detail_stockist->aggriment_copy"); ?>' class="btn btn-primary mt-2">View Upload Agrement</a>
                </div>
            </div>
            <div class="row d-flex justify-content-end">
                <div class="form-group col-2 fonbtn">
                    <a href="<?= base_url('admin/super_stockest'); ?>" class="form-control btn btn-danger" style="padding: 12px;">Cancel</a>
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
            var table = "super_stockist_details";
            // alert(img_type);
            // alert(id);
            // alert(table);
            $.ajax({
                url: "<?= base_url('ajax/show_image') ?>",
                type: "POST",
                dataType: "JSON",
                data: {id: id, img_type: img_type, table: table},
                success: function(data) {
                    // alert("hello");
                    // alert(data.img);
                    $("#imgs").html(data.img);
                }

            });

        });

    });
</script>