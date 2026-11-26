<div class="card">
    <div class="card-header">
        <div class="container-fluid">
            <div class="row justify-content-start">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <h4>All Users</h4>
                    <?php if(!$aid){ ?>
                    <p>Total User: <?= $this->Crud_model->fetch('users')->num_rows(); ?></p>
                    <p>Total Balance: <?= number_format($this->Crud_model->sum_wallet(),2); ?></p>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="scroll">
            <table class="table table-striped table-bordered zero-configuration">
                <thead class="table_head">
                    <tr>
                        <th>ID.</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Wallet</th>
                        <th>Reffer Code</th>
                        <th>Referred By</th>
                        <th>OTP</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;
                    foreach ($users->result() as $row) : ?>
                        <tr>
                            <td><?= $row->id; ?></td>
                            <td><?= $row->name; ?></td>
                            <td><?= $row->mobile; ?></td>
                            <td><?= $row->wallet; ?></td>
                            <td><?= $row->my_reffer_code; ?></td>
                            <td><?= $row->reffer_code; ?></td>
                            <td><?= $row->last_otp; ?></td>
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
                                <a href="#" data-ids="<?= $row->id; ?>" data-toggle="modal" data-target="#exampleModalCenterrate<?= $row->id; ?>"><i style="margin: 0; background-color: #008000;" class="fa fa-percent" data-toggl='tooltip' data-placement='top' title='Rate List'></i></a>
                                <a href="#" data-ids="<?= $row->id; ?>" data-toggle="modal" data-target="#exampleModalCenter"><i style="margin: 0; background-color: #008000;" class="fa fa-plus-square" data-toggl='tooltip' data-placement='top' title='Add money'></i></a>
                                <a href="#" data-idss="<?= $row->id; ?>" data-toggle="modal" data-target="#exampleModalCenterCut"><i style="margin: 0; background-color: #ff0000;" class='fa fa-minus-square' data-toggl='tooltip' data-placement='top' title='Cut money'></i></a>
                                <a href="<?= base_url('admin/referred_users') ?>?reffer_id=<?= $row->my_reffer_code ?>"><i style="margin: 0; background-color: #006fff;" class='fa fa-user-plus' data-toggl='tooltip' data-placement='top' title='Referred Users'></i></a>
                                <a href="<?= base_url('admin/transaction_history') ?>?user_id=<?= $row->id ?>"><i style="margin: 0; background-color: #ff7f00;" class='fa fa-line-chart' data-toggl='tooltip' data-placement='top' title='Transaction History'></i></a>
                            </td>
                        </tr>
<!-- Rate List -->
<div class="modal fade" id="exampleModalCenterrate<?= $row->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rate list for <?= $row->name; ?>(<?= $row->mobile; ?>)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <input type="hidden" value="<?= $row->id; ?>" name="id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="comper">Commission Percentage</label>
                        <input type="text" name="commission_percentage" class="form-control p-2" id="" placeholder="Please Commission Percentage" value="<?= $row->commission_percentage; ?>">
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="jodirate">Jodi Rate</label>
                        <input type="text" name="jodirate" class="form-control p-2" id="" placeholder="Please enter jodi rate" value="<?= $row->jodi_rate; ?>">
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="harufrate">Haruf Rate</label>
                        <input type="text" name="harufrate" class="form-control p-2" id="" placeholder="Please enter Haruf Rate" value="<?= $row->haruf_rate; ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="submit_ratelist" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Add Money -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Money</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="formGroupExampleInput">Amount</label>
                        <input type="text" name="amount" class="form-control p-2" id="formGroupExampleInput" placeholder="Please enter amount">
                        <input type="hidden" name="id_hidden">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Cut Money -->
<div class="modal fade" id="exampleModalCenterCut" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Cut Money</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="formGroupExampleInput">Amount</label>
                        <input type="text" name="amount" class="form-control p-2" id="formGroupExampleInput" placeholder="Please enter amount">
                        <input type="hidden" name="id_hidden_cut">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="submit_cut" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $("tr td a").on("click", function() {
            var id = $(this).attr("data-ids");
            var ids = $(this).attr("data-idss");
            $('[name="id_hidden"]').val(id);
            $('[name="id_hidden_cut"]').val(ids);
        });
    });
</script>