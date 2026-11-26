<style>
    /* Styling as per the new interactive design */
    body { 
        background-image: url('https://i.ibb.co/L5k6k33/lottery-background.jpg'); 
        background-size: cover; 
        background-position: center center; 
        background-attachment: fixed; 
        background-repeat: no-repeat; 
    }
    body::before { 
        content: ""; 
        position: fixed; 
        top: 0; 
        left: 0; 
        width: 100%; 
        height: 100%; 
        background: rgba(0, 0, 0, 0.4); 
        z-index: -1; 
    }
    .card { 
        background-color: rgba(255, 255, 255, 0.95); 
        border-radius: 15px; 
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2); 
        overflow: hidden; 
    }
    .interactive-table { 
        width: 100%; 
        border-collapse: separate; 
        border-spacing: 0; 
    }
    .interactive-table th, 
    .interactive-table td { 
        padding: 15px 10px; 
        text-align: center; 
        vertical-align: middle; 
        border: 1px solid #e0e0e0; 
        font-family: 'Arial', sans-serif; 
    }
    .interactive-table thead th { 
        background-color: #2c3e50; 
        color: #ecf0f1; 
        font-weight: 600; 
        font-size: 1.1em; 
        border-color: #2c3e50; 
    }
    .interactive-table tbody tr { 
        background-color: #fff; 
        transition: background-color 0.3s ease; 
    }
    .interactive-table tbody tr:hover { 
        background-color: #e8f6e8; 
        cursor: pointer; 
    }
    .amount-input { 
        width: 100%; 
        min-width: 80px; 
        border: 1px solid #dcdcdc; 
        border-radius: 5px; 
        padding: 8px; 
        text-align: center; 
        background-color: #fff; 
        transition: all 0.3s ease; 
    }
    .amount-input:focus { 
        border-color: #3498db; 
        box-shadow: 0 0 8px rgba(52, 152, 219, 0.5); 
        outline: none; 
        background-color: #f8faff; 
    }
    .amount-input.profit { 
        background-color: #d4edda; 
        border-color: #c3e6cb; 
        color: #155724; 
    }
    .amount-input.loss { 
        background-color: #f8d7da; 
        border-color: #f5c6cb; 
        color: #721c24; 
    }
</style>

<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h4>Data Entry for: <?= htmlspecialchars($user_info->name); ?></h4>
            </div>
            <div class="col-sm-6 text-right">
                <button type="submit" form="game_data_form" class="btn btn-success" id="save-all-top-btn">Save All</button>
                <button type="button" class="btn btn-info" id="share-btn">Share</button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div id="ajax-message-area" class="mb-3"></div>

        <div id="loading-indicator" style="display: none;">
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

        <form id="game_data_form">
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
                <button class="btn btn-success" name="submit_results" type="submit" id="save-all-bottom-btn">Save All</button>
                <button type="button" class="btn btn-info ml-2" id="share-btn-bottom">Share</button>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
        // Function to update input field styling for profit/loss
        function updateInputStyle(input) {
            const value = parseFloat(input.value);
            input.classList.remove('profit', 'loss');
            if (value > 0) {
                input.classList.add('profit');
            } else if (value < 0) {
                input.classList.add('loss');
            }
        }

        // Apply initial styling on page load and on input
        const inputs = document.querySelectorAll('.amount-input');
        inputs.forEach(input => {
            updateInputStyle(input); 
            input.addEventListener('input', function() {
                updateInputStyle(this);
            });
        });
        
        // Share Button Logic
        $('#share-btn, #share-btn-bottom').on('click', function() {
            const userName = '<?= htmlspecialchars($user_info->name); ?>';
            const monthText = $('#month option:selected').text().trim();
            const year = $('#year').val();
            
            // Format the header
            let shareContent = `${userName} (${monthText} ${year})\n\n`;
            let totalAmount = 0;
            let hasData = false;

            $('.amount-input').each(function() {
                const value = parseFloat($(this).val());
                if (!isNaN(value) && value !== 0) {
                    hasData = true;
                    const day = $(this).closest('tr').find('td:first').text().trim();
                    const sign = value > 0 ? '++++' : '----';
                    shareContent += `${day.replace(/^0+/, '')}=${Math.abs(value)}${sign}\n`;
                    totalAmount += value;
                }
            });

            if (!hasData) {
                shareContent += "No data entered for this month.";
            } else {
                const totalSign = totalAmount >= 0 ? '(PROFIT)' : '(LOSS)';
                shareContent += `\n\nTotal: ${totalAmount} ${totalSign}`;
            }

            // Copy to clipboard
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(shareContent).then(function() {
                    alert('Shared content copied to clipboard!');
                }).catch(function(err) {
                    console.error('Could not copy text: ', err);
                    alert('Could not copy text to clipboard. Please copy manually:\n' + shareContent);
                });
            } else {
                // Fallback for older browsers
                const textArea = document.createElement("textarea");
                textArea.value = shareContent;
                document.body.appendChild(textArea);
                textArea.select();
                try {
                    document.execCommand('copy');
                    alert('Shared content copied to clipboard!');
                } catch (err) {
                    alert('Could not copy text to clipboard. Please copy manually:\n' + shareContent);
                }
                document.body.removeChild(textArea);
            }
        });
        
        // ... (existing form submission logic for save button) ...
        $('#game_data_form').on('submit', function(e) {
            e.preventDefault(); 
            const form = $(this);
            const formData = form.serialize();
            const saveButtons = $('#save-all-top-btn, #save-all-bottom-btn');
            const loadingIndicator = $('#loading-indicator');
            const messageArea = $('#ajax-message-area');

            messageArea.empty();
            loadingIndicator.show();
            saveButtons.prop('disabled', true);

            $.ajax({
                url: '<?= base_url('admin/save_user_amounts_ajax'); ?>',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        displayMessage(response.message, 'success');
                    } else {
                        displayMessage(response.message, 'danger');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error, xhr.responseText);
                    displayMessage('An error occurred while saving data. Please try again.', 'danger');
                },
                complete: function() {
                    loadingIndicator.hide();
                    saveButtons.prop('disabled', false);
                }
            });
        });

        function displayMessage(message, type) {
            const messageArea = $('#ajax-message-area');
            messageArea.html(`
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            `);
            setTimeout(() => {
                messageArea.find('.alert').alert('close');
            }, 5000);
        }
    });
</script>