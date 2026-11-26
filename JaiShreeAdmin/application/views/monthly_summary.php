<style>
    /* Styling for the summary table */
    .summary-table th,
    .summary-table td {
        text-align: center;
        vertical-align: middle;
        padding: 12px 8px;
    }
    .summary-table thead th {
        background-color: #2c3e50;
        color: white;
        font-weight: 600;
        border-color: #2c3e50;
    }
    .summary-table tbody tr:hover {
        background-color: #f5f5f5;
        cursor: default;
    }
    .summary-table .month-cell {
        font-weight: bold;
    }
    .summary-table .profit {
        color: green;
        font-weight: bold;
    }
    .summary-table .loss {
        color: red;
        font-weight: bold;
    }
    .summary-table .view-btn {
        padding: 5px 10px;
    }
</style>

<div class="card">
    <div class="card-header">
        <h4>Monthly Profit/Loss Summary</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered summary-table">
                <thead class="table_head">
                    <tr>
                        <th class="month-cell">Month</th>
                        <?php foreach ($games as $game) : ?>
                            <th><?= $game->game_name; ?></th>
                        <?php endforeach; ?>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($summary)) : ?>
                        <?php foreach ($summary as $month_year => $amounts) : ?>
                            <tr>
                                <td class="month-cell"><?= date('M-Y', strtotime($month_year . '-01')); ?></td>
                                <?php foreach ($games as $game) : ?>
                                    <td>
                                        <?php
                                        $amount = isset($amounts[$game->id]) ? $amounts[$game->id] : 0;
                                        $class = ($amount > 0) ? 'profit' : (($amount < 0) ? 'loss' : '');
                                        echo '<span class="' . $class . '">' . number_format($amount, 2) . '</span>';
                                        ?>
                                    </td>
                                <?php endforeach; ?>
                                <td>
                                    <?php 
                                    list($year, $month) = explode('-', $month_year);
                                    ?>
                                    <a href="<?= base_url('admin/game_data_entry?month=' . $month . '&year=' . $year); ?>" class="btn btn-primary btn-sm view-btn">View</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="<?= count($games) + 2; ?>">No data available for any month.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>