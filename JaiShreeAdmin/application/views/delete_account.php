<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
<style>
    .center {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #F3F3F3;
    }

    .text-size {
        font-size: 21px;
    }

    img {
        width: 80%;
        border-top-left-radius: calc(0.25rem - 1px);
        border-top-right-radius: calc(0.25rem - 1px);
    }
</style>

<div class="center">
    <div class="card p-5 col-sm-8 border-0">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <center>
                        <img src="<?= base_url('assets/images/casino-logo.png') ?>" alt="Image">
                    </center>
                </div>
                <div class="col-md-6">
                    <h3>Delete Account Request</h3>
                    <form>

                        <div class="form-group">
                            <label>Mobile Number</label>
                            <input type="number" class="form-control" placeholder="Please enter your mobile number" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary float-right">Delete Account</button>
                        </div>
                    </form>
                    <br><br>
                    <div class="form-group">
                        <p><b>Steps for Account Delete:</b></p>
                        <ol>
                            <li>Enter Mobile Number on Above Text Box.</li>
                            <li>Click on Delete Buttton.</li>
                            <li>Your Account will be Deleted in Next 30 days.</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>