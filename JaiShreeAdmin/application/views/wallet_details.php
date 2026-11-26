<div class="card">
    <div class="card-header">
        <div class="container-fluid">
            <div class="row align-items-center justify-content-between my-2">
                <div class="col-md-6">
                    <h4>QR Codes & Payment Details</h4>
                </div>
                <div class="col-md-6 text-right">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenterAdd">
                        <i class="fa fa-plus-circle mr-1"></i> Add New QR Code
                    </button>
                </div>
            </div>
        </div>
        <div class="scroll">
            <table class="table table-striped table-bordered zero-configuration">
                <thead class="table_head">
                    <tr>
                        <th>S no.</th>
                        <th>Tag / Name</th>
                        <th>UPI ID</th>
                        <th>Bank Transfer Details</th>
                        <th>QR Code Image</th>
                        <th>UPI Logo</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;
                    foreach ($data->result() as $row) : ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><strong><?= htmlspecialchars($row->name); ?></strong></td>
                            <td><?= htmlspecialchars($row->upi_num); ?></td>
                            <td>
                                <?php if (!empty($row->account_number) || !empty($row->ifsc_code)) : ?>
                                    <?php if (!empty($row->bank_name)) : ?>
                                        <div><strong class="text-primary"><?= htmlspecialchars($row->bank_name); ?></strong></div>
                                    <?php endif; ?>
                                    <?php if (!empty($row->account_number)) : ?>
                                        <div><small class="text-muted font-weight-bold">A/C:</small> <code><?= htmlspecialchars($row->account_number); ?></code></div>
                                    <?php endif; ?>
                                    <?php if (!empty($row->ifsc_code)) : ?>
                                        <div><small class="text-muted font-weight-bold">IFSC:</small> <code><?= htmlspecialchars($row->ifsc_code); ?></code></div>
                                    <?php endif; ?>
                                    <?php if (!empty($row->account_holder_name)) : ?>
                                        <div><small class="text-muted font-weight-bold">Holder:</small> <?= htmlspecialchars($row->account_holder_name); ?></div>
                                    <?php endif; ?>
                                <?php else : ?>
                                    <span class="badge badge-light">Not Set</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($row->qr_img)) : ?>
                                    <a href="<?= base_url($row->qr_img); ?>" target="_blank">
                                        <img src="<?= base_url($row->qr_img); ?>" style="max-width: 65px; max-height: 65px; border: 1px solid #ddd; border-radius: 4px; padding: 2px;" alt="QR Code">
                                    </a>
                                <?php else : ?>
                                    <span class="badge badge-secondary">No QR</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($row->upi_company_img)) : ?>
                                    <img src="<?= base_url($row->upi_company_img); ?>" style="max-width: 45px; max-height: 45px;" alt="Logo">
                                <?php else : ?>
                                    <span class="badge badge-light">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                if ($row->status == 0) {
                                    echo '<span class="badge badge-success p-2">Active (Shown to Users)</span>';
                                } else {
                                    echo '<span class="badge badge-secondary p-2">Inactive</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <?php if ($row->status == 0) : ?>
                                    <span class="badge badge-info mr-1" title="Currently Active"><i class="fa fa-check-circle"></i> Active</span>
                                <?php else : ?>
                                    <a href="?sid=<?= $row->id; ?>" class="btn btn-sm btn-success mr-1" onclick="return confirm('Set this QR code as the active payment method?');" title="Make Active">
                                        <i class="fa fa-check"></i> Set Active
                                    </a>
                                <?php endif; ?>
                                <a href="#" class="btn btn-sm btn-primary edit-qr-btn mr-1" 
                                    data-id_update="<?= $row->id ?>" 
                                    data-name_update="<?= htmlspecialchars($row->name) ?>" 
                                    data-name_number="<?= htmlspecialchars($row->upi_num) ?>" 
                                    data-account_number="<?= htmlspecialchars($row->account_number ?? '') ?>" 
                                    data-ifsc_code="<?= htmlspecialchars($row->ifsc_code ?? '') ?>" 
                                    data-bank_name="<?= htmlspecialchars($row->bank_name ?? '') ?>" 
                                    data-account_holder_name="<?= htmlspecialchars($row->account_holder_name ?? '') ?>" 
                                    data-toggle="modal" data-target="#exampleModalCenterUpdate" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="?did=<?= $row->id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this QR code?');" title="Delete">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Add -->
<div class="modal fade" id="exampleModalCenterAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterAddTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitleAdd">Add New Payment QR Code & Bank Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>UPI ID / Phone Number <small class="text-muted">(Optional)</small></label>
                            <input type="text" name="upi_num" class="form-control" placeholder="e.g. user@upi or 9876543210">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>UPI QR Code Image <small class="text-muted">(Optional)</small></label>
                            <input type="file" name="qr_img" class="form-control-file" accept="image/*">
                        </div>
                    </div>

                    <div class="card bg-light p-3 mb-3 border">
                        <h6 class="font-weight-bold text-dark mb-2"><i class="fa fa-university mr-1 text-primary"></i> Bank Account Transfer Details <small class="text-muted">(Optional)</small></h6>
                        <p class="text-muted small mb-3">Add bank account details so users can transfer funds directly to your bank account along with QR / UPI.</p>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Bank Account Number <small class="text-muted">(Optional)</small></label>
                                <input type="text" name="account_number" class="form-control" placeholder="e.g. 123456789012">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>IFSC Code <small class="text-muted">(Optional)</small></label>
                                <input type="text" name="ifsc_code" class="form-control" placeholder="e.g. SBIN0001234" style="text-transform:uppercase;">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Bank Name <small class="text-muted">(Optional)</small></label>
                                <input type="text" name="bank_name" class="form-control" placeholder="e.g. State Bank of India / HDFC Bank">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Account Holder Name <small class="text-muted">(Optional)</small></label>
                                <input type="text" name="account_holder_name" class="form-control" placeholder="e.g. Business Name / Account Holder">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="submit_add" class="btn btn-success">Save Payment Details</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Update -->
<div class="modal fade" id="exampleModalCenterUpdate" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Update Payment & Bank Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id_hidden">
                    <input type="hidden" name="name_update">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>UPI ID / Phone Number <small class="text-muted">(Optional)</small></label>
                            <input type="text" name="upi_update" class="form-control" placeholder="e.g. user@upi or 9876543210">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Update QR Code Image <small class="text-muted">(Leave blank to keep existing)</small></label>
                            <input type="file" name="qr_img" class="form-control-file" accept="image/*">
                        </div>
                    </div>

                    <div class="card bg-light p-3 mb-3 border">
                        <h6 class="font-weight-bold text-dark mb-2"><i class="fa fa-university mr-1 text-primary"></i> Bank Account Transfer Details <small class="text-muted">(Optional)</small></h6>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Bank Account Number <small class="text-muted">(Optional)</small></label>
                                <input type="text" name="account_number_update" class="form-control" placeholder="e.g. 123456789012">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>IFSC Code <small class="text-muted">(Optional)</small></label>
                                <input type="text" name="ifsc_code_update" class="form-control" placeholder="e.g. SBIN0001234" style="text-transform:uppercase;">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Bank Name <small class="text-muted">(Optional)</small></label>
                                <input type="text" name="bank_name_update" class="form-control" placeholder="e.g. State Bank of India / HDFC Bank">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Account Holder Name <small class="text-muted">(Optional)</small></label>
                                <input type="text" name="account_holder_name_update" class="form-control" placeholder="e.g. Business Name / Account Holder">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="submit_update" class="btn btn-primary">Update Details</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $(".edit-qr-btn").on("click", function() {
            var id = $(this).attr("data-id_update");
            var name = $(this).attr("data-name_update");
            var number = $(this).attr("data-name_number");
            var account_number = $(this).attr("data-account_number");
            var ifsc_code = $(this).attr("data-ifsc_code");
            var bank_name = $(this).attr("data-bank_name");
            var account_holder_name = $(this).attr("data-account_holder_name");

            $('[name="id_hidden"]').val(id);
            $('[name="name_update"]').val(name);
            $('[name="upi_update"]').val(number);
            $('[name="account_number_update"]').val(account_number || '');
            $('[name="ifsc_code_update"]').val(ifsc_code || '');
            $('[name="bank_name_update"]').val(bank_name || '');
            $('[name="account_holder_name_update"]').val(account_holder_name || '');
        });
    });
</script>