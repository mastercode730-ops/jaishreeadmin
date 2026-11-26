<div class="card">
    <div class="card-header">
        <div class="container-fluid">
            <div class="row justify-content-start my-2">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <h4><i class="fa fa-money text-success mr-2"></i>Pending Withdraw Requests</h4>
                    <p class="text-muted small mb-0">Review user bank/UPI details, transfer the funds via your banking app/portal, and approve the request.</p>
                </div>
            </div>
        </div>
        <div class="scroll">
            <table class="table table-striped table-bordered zero-configuration">
                <thead class="table_head">
                    <tr>
                        <th>S no.</th>
                        <th>Date & Time</th>
                        <th>User Name</th>
                        <th>Mobile</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Transaction ID</th>
                        <th>Beneficiary Details</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;
                    foreach ($data->result() as $row) :
                        $user = $this->Crud_model->fetch('users', array('id' => $row->user_id))->row();
                        $dateTimeString = $row->date . " " . $row->time;
                        $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $dateTimeString);
                    ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $dateTime ? $dateTime->format('d-m-Y h:i A') : htmlspecialchars($row->date . ' ' . $row->time); ?></td>
                            <td><strong><?= htmlspecialchars($user->name ?? 'User #' . $row->user_id); ?></strong></td>
                            <td><code><?= htmlspecialchars($user->mobile ?? '-'); ?></code></td>
                            <td><span class="badge badge-success px-2 py-1" style="font-size: 14px;">₹<?= number_format($row->amount, 2); ?></span></td>
                            <td>
                                <?php if (!empty($row->ifsc_code)) : ?>
                                    <span class="badge badge-info p-1"><i class="fa fa-university mr-1"></i> Bank Transfer</span>
                                    <div class="small text-muted font-weight-bold mt-1"><?= htmlspecialchars($row->bank_name); ?></div>
                                <?php else : ?>
                                    <span class="badge badge-secondary p-1"><i class="fa fa-mobile mr-1"></i> <?= htmlspecialchars($row->bank_name); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><small class="text-muted"><?= htmlspecialchars($row->transaction_id); ?></small></td>
                            <td>
                                <?php if (!empty($row->ifsc_code)) : ?>
                                    <div class="p-2 border rounded bg-white" style="font-size: 12px; min-width: 220px; line-height: 1.6;">
                                        <div><span class="text-muted">A/C Holder:</span> <strong><?= htmlspecialchars($row->ac_holder_name); ?></strong></div>
                                        <div><span class="text-muted">Bank Name:</span> <?= htmlspecialchars($row->bank_name); ?></div>
                                        <div class="mt-1">
                                            <span class="text-muted">A/C No:</span> <code class="text-dark font-weight-bold" style="font-size: 13px;"><?= htmlspecialchars($row->bank_ac_no); ?></code>
                                            <button type="button" class="btn btn-xs btn-outline-secondary ml-1 py-0 px-1 copy-btn" data-copy="<?= htmlspecialchars($row->bank_ac_no); ?>" title="Copy A/C Number">
                                                <i class="fa fa-copy"></i> Copy
                                            </button>
                                        </div>
                                        <div class="mt-1">
                                            <span class="text-muted">IFSC Code:</span> <code class="text-primary font-weight-bold" style="font-size: 13px;"><?= htmlspecialchars($row->ifsc_code); ?></code>
                                            <button type="button" class="btn btn-xs btn-outline-secondary ml-1 py-0 px-1 copy-btn" data-copy="<?= htmlspecialchars($row->ifsc_code); ?>" title="Copy IFSC">
                                                <i class="fa fa-copy"></i> Copy
                                            </button>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <div class="p-2 border rounded bg-white" style="font-size: 12px;">
                                        <div><span class="text-muted"><?= htmlspecialchars($row->bank_name); ?> Number:</span> <code class="text-dark font-weight-bold"><?= htmlspecialchars($row->bank_ac_no); ?></code>
                                            <button type="button" class="btn btn-xs btn-outline-secondary ml-1 py-0 px-1 copy-btn" data-copy="<?= htmlspecialchars($row->bank_ac_no); ?>" title="Copy Number">
                                                <i class="fa fa-copy"></i> Copy
                                            </button>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-warning p-2">Pending</span>
                            </td>
                            <td style="white-space: nowrap;">
                                <button type="button" class="btn btn-sm btn-success mr-1 approve-btn" 
                                    data-id="<?= $row->id; ?>" 
                                    data-user_id="<?= $row->user_id; ?>" 
                                    data-user_name="<?= htmlspecialchars($user->name ?? 'User #' . $row->user_id); ?>"
                                    data-user_mobile="<?= htmlspecialchars($user->mobile ?? ''); ?>"
                                    data-amount="<?= $row->amount; ?>"
                                    data-bank_name="<?= htmlspecialchars($row->bank_name ?? ''); ?>"
                                    data-bank_ac_no="<?= htmlspecialchars($row->bank_ac_no ?? ''); ?>"
                                    data-ifsc_code="<?= htmlspecialchars($row->ifsc_code ?? ''); ?>"
                                    data-ac_holder_name="<?= htmlspecialchars($row->ac_holder_name ?? ''); ?>"
                                    data-toggle="modal" data-target="#ApproveModal" title="Approve & Transfer">
                                    <i class="fa fa-check mr-1"></i> Approve
                                </button>
                                <button type="button" class="btn btn-sm btn-danger reject-btn" 
                                    data-idss="<?= $row->id ?>" 
                                    data-ids="<?= $row->user_id ?>" 
                                    data-amount="<?= $row->amount; ?>"
                                    data-toggle="modal" data-target="#ViewImageModalComment" title="Reject">
                                    <i class="fa fa-times mr-1"></i> Reject
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Approve Withdrawal -->
<div class="modal fade" id="ApproveModal" tabindex="-1" role="dialog" aria-labelledby="ApproveModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title text-white" id="ApproveModalTitle"><i class="fa fa-check-circle mr-1"></i> Confirm & Approve Withdrawal</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <input type="hidden" name="approve_id" id="approve_id">
                    <input type="hidden" name="approve_user_id" id="approve_user_id">

                    <div class="alert alert-success text-center py-2 mb-3">
                        <div class="small text-muted font-weight-bold">AMOUNT TO TRANSFER</div>
                        <h3 class="mb-0 font-weight-bold" id="approve_amount_display">₹0.00</h3>
                    </div>

                    <div class="card bg-light p-3 border mb-3">
                        <h6 class="font-weight-bold text-dark mb-2"><i class="fa fa-university mr-1 text-primary"></i> Beneficiary Bank Details</h6>
                        <table class="table table-sm table-borderless mb-0" style="font-size: 13px;">
                            <tr>
                                <td class="text-muted" style="width: 40%;">User Name:</td>
                                <td class="font-weight-bold" id="approve_user_name">-</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Account Holder:</td>
                                <td class="font-weight-bold" id="approve_ac_holder">-</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Bank Name:</td>
                                <td id="approve_bank_name">-</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Account Number:</td>
                                <td>
                                    <code class="font-weight-bold text-dark" id="approve_bank_ac">-</code>
                                    <button type="button" class="btn btn-xs btn-outline-secondary ml-1 py-0 px-1 copy-btn" id="copy_ac_btn">
                                        <i class="fa fa-copy"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr id="approve_ifsc_row">
                                <td class="text-muted">IFSC Code:</td>
                                <td>
                                    <code class="font-weight-bold text-primary" id="approve_ifsc">-</code>
                                    <button type="button" class="btn btn-xs btn-outline-secondary ml-1 py-0 px-1 copy-btn" id="copy_ifsc_btn">
                                        <i class="fa fa-copy"></i>
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="form-group fontuser">
                        <label class="font-weight-bold">Bank Transfer UTR / Ref Number (Optional)</label>
                        <input type="text" name="admin_txn" class="form-control" placeholder="e.g. 12-digit UTR from your Netbanking/UPI app">
                        <small class="form-text text-muted">This reference number will be saved and visible to the user as proof of payment.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="approve_submit" class="btn btn-success font-weight-bold">
                        <i class="fa fa-check mr-1"></i> Approve & Mark as Transferred
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Reject Comment -->
<div class="modal fade" id="ViewImageModalComment" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title text-white" id="exampleModalLongTitle"><i class="fa fa-times-circle mr-1"></i> Reject Withdrawal Request</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="card-body">
                <form method="post">
                    <input type="hidden" name="idss" id="idss">
                    <input type="hidden" name="ids" id="ids">
                    <div class="alert alert-warning py-2 small mb-3">
                        <i class="fa fa-info-circle mr-1"></i> Rejecting will automatically refund the withdrawal amount back to the user's wallet.
                    </div>
                    <div class="form-group fontuser">
                        <label class="lab">Reason for Rejection <span class="text-danger">*</span></label>
                        <textarea name="comment" class="form-control" required rows="3" placeholder="e.g. Incorrect bank account number or invalid IFSC code"></textarea>
                    </div>
                    <div class="form-group mb-0 d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-danger" name="update_submit" type="submit">Confirm Rejection</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        // Quick copy buttons
        $(document).on("click", ".copy-btn", function() {
            var text = $(this).attr("data-copy");
            if (text) {
                navigator.clipboard.writeText(text);
                var origHtml = $(this).html();
                $(this).html('<i class="fa fa-check text-success"></i> Copied');
                var btn = $(this);
                setTimeout(function() {
                    btn.html(origHtml);
                }, 1500);
            }
        });

        // Reject modal setup
        $(".reject-btn").on("click", function() {
            var idss = $(this).data('idss');
            var ids = $(this).data('ids');
            $("#idss").val(idss);
            $("#ids").val(ids);
        });

        // Approve modal setup
        $(".approve-btn").on("click", function() {
            var id = $(this).data('id');
            var user_id = $(this).data('user_id');
            var user_name = $(this).data('user_name');
            var amount = $(this).data('amount');
            var bank_name = $(this).data('bank_name');
            var bank_ac_no = $(this).data('bank_ac_no');
            var ifsc_code = $(this).data('ifsc_code');
            var ac_holder_name = $(this).data('ac_holder_name');

            $("#approve_id").val(id);
            $("#approve_user_id").val(user_id);
            $("#approve_amount_display").text("₹" + parseFloat(amount).toFixed(2));
            $("#approve_user_name").text(user_name || "User #" + user_id);
            $("#approve_ac_holder").text(ac_holder_name || user_name || "-");
            $("#approve_bank_name").text(bank_name || "-");
            $("#approve_bank_ac").text(bank_ac_no || "-");
            $("#copy_ac_btn").attr("data-copy", bank_ac_no || "");

            if (ifsc_code) {
                $("#approve_ifsc_row").show();
                $("#approve_ifsc").text(ifsc_code);
                $("#copy_ifsc_btn").attr("data-copy", ifsc_code);
            } else {
                $("#approve_ifsc_row").hide();
            }
        });
    });
</script>