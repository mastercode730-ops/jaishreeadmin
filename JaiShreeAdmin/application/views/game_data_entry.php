<style>
    /* Styling as per the old game_data_entry page but simplified */
    body { background-image: url('https://i.ibb.co/L5k6k33/lottery-background.jpg'); background-size: cover; background-position: center center; background-attachment: fixed; background-repeat: no-repeat; }
    body::before { content: ""; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.4); z-index: -1; }
    .card { background-color: rgba(255, 255, 255, 0.95); border-radius: 15px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2); overflow: hidden; }
    .interactive-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .interactive-table th, .interactive-table td { padding: 15px 10px; text-align: center; vertical-align: middle; border: 1px solid #e0e0e0; font-family: 'Arial', sans-serif; }
    .interactive-table thead th { background-color: #2c3e50; color: #ecf0f1; font-weight: 600; font-size: 1.1em; border-color: #2c3e50; }
    .interactive-table tbody tr { background-color: #fff; transition: background-color 0.3s ease; }
    .interactive-table tbody tr:hover { background-color: #e8f6e8; cursor: pointer; }
    .amount-input { width: 100%; min-width: 80px; border: 1px solid #dcdcdc; border-radius: 5px; padding: 8px; text-align: center; background-color: #fff; transition: all 0.3s ease; }
    .amount-input:focus { border-color: #3498db; box-shadow: 0 0 8px rgba(52, 152, 219, 0.5); outline: none; background-color: #f8faff; }
    .amount-input.profit { background-color: #d4edda; border-color: #c3e6cb; color: #155724; }
    .amount-input.loss { background-color: #f8d7da; border-color: #f5c6cb; color: #721c24; }
</style>

<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h4>Data Entry for: <?= htmlspecialchars($user_info->name); ?></h4>
            </div>
            <div class="col-sm-6 text-right">
                <button type="submit" form="game_data_form" class="btn btn-success">Save All</button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div id="ajax-message-area" class="mb-3">
            <?php if ($this->session->flashdata('success_message')) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $this->session->flashdata('success_message'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
        </div>

        <div id="loading-indicator">
            <i class="fa fa-spinner fa-spin"></i> Saving data...
        </div>

        <form id="month_selector_form" method="get" action="<?= base_url('admin/user_data_entry'); ?>">
            <input type="hidden" name="user_id" value="<?= $user_id; ?>">
            <div class="row mb-4 align-items-end">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="month">Select Month</label>
                        <select name="month" id="month" class="form-control">
                            <?php for ($m = 1; $m <= 12; $m++) : ?>
                                <option value="<?= sprintf('%02d', $m); ?>" <?= ($m == $month) ? 'selected' : ''; ?>>
                                    <?= date('F', mktime(0, 0, 0, $m, 10)); ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="year">Select Year</label>
                        <select name="year" id="year" class="form-control">
                            <?php for ($y = date('Y') - 5; $y <= date('Y') + 5; $y++) : ?>
                                <option value="<?= $y; ?>" <?= ($y == $year) ? 'selected' : ''; ?>>
                                    <?= $y; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Go</button>
                </div>
            </div>
        </form>

        <form id="game_data_form" method="post" action="<?= base_url('admin/user_data_entry'); ?>">
            <input type="hidden" name="user_id" value="<?= $user_id; ?>">
            <input type="hidden" name="month" value="<?= $month; ?>">
            <input type="hidden" name="year" value="<?= $year; ?>">
            <div class="table-responsive">
                <table class="interactive-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Loss/Profit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $results_map = [];
                        foreach ($results as $result) {
                            $day = (int)date('d', strtotime($result['result_date']));
                            $results_map[$day] = $result['amount'];
                        }
                        for ($day = 1; $day <= $days_in_month; $day++) :
                        ?>
                            <tr>
                                <td><?= sprintf('%02d', $day); ?></td>
                                <td>
                                    <?php
                                    $existing_amount = isset($results_map[$day]) ? $results_map[$day] : '';
                                    $class = (is_numeric($existing_amount) && $existing_amount > 0) ? 'profit' : ((is_numeric($existing_amount) && $existing_amount < 0) ? 'loss' : '');
                                    ?>
                                    <input type="number" step="0.01" name="amounts[<?= $day; ?>]"
                                           class="amount-input <?= $class; ?>"
                                           value="<?= htmlspecialchars($existing_amount); ?>">
                                </td>
                            </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="form-group d-flex justify-content-end mt-4">
                <button class="btn btn-success" name="submit_results" type="submit">Save All</button>
            </div>
        </form>
    </div>
</div>