<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-12">
                <h4>Add Sub User</h4>
            </div>
        </div>
    </div>
    <div>
        <form id="sales_form" method="post" action="">
            <div class="row">
                <div class="form-group col-6 fontuser">
                    <label class="lab">Name <span class="star">★</span></label>
                    <input type="text" name="name" value="" class="form-control" placeholder="Enter Name" required>
                </div>
                <div class="form-group col-6 fontuser">
                    <label class="lab">Email <span class="star">★</span></label>
                    <input type="text" name="email" value="" class="form-control" placeholder="Enter Email">
                </div>
                 <div class="form-group col-6 fontuser">
                    <label class="lab">Password <span class="star">★</span></label>
                    <input type="text" class="form-control" value="" name="password" placeholder="Enter Password" required>
                </div>
                <div class="form-group col-6 fontuser">
                    <label class="lab">Confirm Password <span class="star">★</span></label>
                    <input type="text" class="form-control" value="" name="cpassword" placeholder="Enter Confirm Password" required>
                </div>
            </div>
            <p style="color:red;"><?php echo isset($error)?$error:""; ?></p>
            <p style="color:green;"><?php echo isset($success) ? $success : ""; ?></p>
            <div class="row d-flex justify-content-end">
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

      
    });
</script>
