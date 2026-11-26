<div class="card">
        <div class="scroll">
            <table class="table table-striped table-bordered zero-configuration">
                <thead class="table_head">
                    <tr>
                        <th>S no.</th>
                        <th>Game Name</th>
                        <th>Start Date</th>
                        <th>Start Time</th>
                        <th>End Date</th>
                        <th>End Time</th>
                        <th>Result Date</th>
                        <th>Result Time</th>
                        <th>Result Number</th>
                        <th>Total Bid Amount</th>
                        <th>Total Cancel Bid Amount</th>
                        <th>Total Distribute Amount</th>
                        <th>Total P/L</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;
                    foreach ($data->result() as $row) :
                    ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $row->game_name; ?></td>
                            <td><?= $row->start_date; ?></td>
                            <td><?= date("h:i a", strtotime($row->start_time)) ?></td>
                            <td><?= $row->end_date; ?></td>
                            <td><?= date("h:i a", strtotime($row->end_time)) ?></td>
                            <td><?= $row->result_date; ?></td>
                            <td><?= date("h:i a", strtotime($row->result_time)) ?></td>
                            <td><?= $row->number ?></td>
                            <td><?= $row->total_bid_amount ?></td>
                            <td><?= $row->cancel_bet ?></td>
                            <td><?= $row->total_distribute_amount ?></td>
                            <td style="color:<?php echo ($row->total_bid_amount-$row->cancel_bet)-$row->total_distribute_amount>0?'green':'red'; ?>"><?= ($row->total_bid_amount-$row->cancel_bet)-$row->total_distribute_amount ?></td>
                            <td><?php
                                if ($row->status == 1) {
                                    echo '<p class="inactive-pills">Ended</p>';
                                }
                                ?></td>
                            <td>
                                <a href="<?= base_url('admin/game_details') ?>?game_id=<?= $row->game_id ?>">
                                    <i id="<?= $row->game_id ?>" class="fa fa-gamepad" style="background-color: #f8a232; margin: 0px 2px;" data-toggl="tooltip" data-placement="top" title="Game Details"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>