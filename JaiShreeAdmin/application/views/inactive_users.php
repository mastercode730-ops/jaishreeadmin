<div class="card">
    <div class="card-header">
        <div class="container-fluid">
            <div class="row justify-content-start sp">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <h4>Inactive Users</h4>
                </div>
            </div>
        </div>
        <div class="scroll">
            <table class="table table-striped table-bordered zero-configuration">
                <thead class="table_head">
                    <tr>
                        <th>S no.</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Wallet</th>
                        <th>Reffer Code</th>
                        <th>Reffer By</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;
                    foreach ($users->result() as $row) : ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $row->name; ?></td>
                            <td><?= $row->mobile; ?></td>
                            <td><?= $row->wallet; ?></td>
                            <td><?= $row->my_reffer_code; ?></td>
                            <td><?= $row->reffer_code; ?></td>
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
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
