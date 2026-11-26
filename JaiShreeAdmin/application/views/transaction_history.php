<div class="card">
    <div class="card-header">
        <div class="container-fluid">
            <h4>Transaction History</h4>
        </div>
        <div class="scroll">
            <table class="table table-striped table-bordered zero-configuration">
                <thead class="table_head">
                    <tr>
                        <th>S no.</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Amount</th>
                        <th>Description</th>
                        <th>Before balance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;
                    foreach ($data->result() as $row) :
                        $get_user = $this->Crud_model->fetch('users', array('id' => $row->user_id));
                    ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $row->date ?></td>
                            <td><?= date("h:i a", strtotime($row->time)) ?></td>
                            <td><?= $get_user->row('name'); ?></td>
                            <td><?= $get_user->row('mobile'); ?></td>
                            <td><?= $row->add_amount ?></td>
                            <td><?= $row->description ?></td>
                            <td><?= $row->remaining_amount ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>