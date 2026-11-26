<div class="card">
    <div class="card-header">
        <div class="container-fluid">
            <div class="row justify-content-start my-2">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <h4><i class="fa fa-times-circle text-danger mr-2"></i>Rejected Withdraw Requests</h4>
                </div>
            </div>
        </div>
        <div class="scroll">
            <table class="table table-striped table-bordered zero-configuration">
                <thead class="table_head">
                    <tr>
                        <th>S no.</th>
                        <th>Date & Time</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Transaction ID</th>
                        <th>Beneficiary Details</th>
                        <th>Rejection Reason</th>
                        <th>Status</th>
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
                            <td><span class="badge badge-secondary px-2 py-1" style="font-size: 14px;">₹<?= number_format($row->amount, 2); ?></span></td>
                            <td>
                                <?php if (!empty($row->ifsc_code)) : ?>
                                    <span class="badge badge-info p-1"><i class="fa fa-university mr-1"></i> Bank Transfer</span>
                                    <div class="small text-muted font-weight-bold mt-1"><?= htmlspecialchars($row->bank_name); ?></div>
                                <?php else : ?>
                                    <span class="badge badge-secondary p-1"><i class="fa fa-mobile mr-1"></i> <?= htmlspecialchars($row->bank_name); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><small class="text-muted"><?= htmlspecialchars($row->transaction_id ?? '-'); ?></small></td>
                            <td>
                                <?php if (!empty($row->ifsc_code)) : ?>
                                    <div class="p-2 border rounded bg-white" style="font-size: 12px; line-height: 1.5;">
                                        <div><span class="text-muted">A/C Holder:</span> <strong><?= htmlspecialchars($row->ac_holder_name); ?></strong></div>
                                        <div><span class="text-muted">Bank Name:</span> <?= htmlspecialchars($row->bank_name); ?></div>
                                        <div><span class="text-muted">A/C:</span> <code class="text-dark font-weight-bold"><?= htmlspecialchars($row->bank_ac_no); ?></code></div>
                                        <div><span class="text-muted">IFSC:</span> <code class="text-primary font-weight-bold"><?= htmlspecialchars($row->ifsc_code); ?></code></div>
                                    </div>
                                <?php else : ?>
                                    <div class="p-2 border rounded bg-white" style="font-size: 12px;">
                                        <div><span class="text-muted"><?= htmlspecialchars($row->bank_name); ?>:</span> <code><?= htmlspecialchars($row->bank_ac_no); ?></code></div>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="text-danger font-weight-bold" style="font-size: 13px;">
                                    <?= htmlspecialchars($row->comment ?: 'No reason specified'); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-danger p-2">Rejected (Refunded)</span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>