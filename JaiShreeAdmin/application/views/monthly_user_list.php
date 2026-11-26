<style>
    .summary-table th, .summary-table td { text-align: center; vertical-align: middle; }
    .summary-table .profit { color: green; font-weight: bold; }
    .summary-table .loss { color: red; font-weight: bold; }
</style>

<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h4>Monthly User Summary</h4>
            </div>
            <div class="col-sm-6 text-right">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">
                    Add New User
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div id="ajax-message-area"></div>
        <div class="table-responsive">
            <table class="table table-bordered summary-table">
                <thead>
                    <tr>
                        <th>S. No.</th>
                        <th>User Name</th>
                        <th>This Month's Profit/Loss</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)) : ?>
                        <?php $i = 1; foreach ($users as $user) : ?>
                            <tr id="user-row-<?= $user->id; ?>">
                                <td><?= $i++; ?></td>
                                <td><?= htmlspecialchars($user->name); ?></td>
                                <td>
                                    <?php 
                                    $amount = isset($monthly_summary[$user->id]) ? $monthly_summary[$user->id] : 0;
                                    $class = ($amount > 0) ? 'profit' : (($amount < 0) ? 'loss' : '');
                                    echo '<span class="' . $class . '">' . number_format($amount, 2) . '</span>';
                                    ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('admin/user_data_entry?user_id=' . $user->id); ?>" class="btn btn-sm btn-info">View & Add Data</a>
                                    <button type="button" class="btn btn-sm btn-danger delete-user-btn" data-user-id="<?= $user->id; ?>">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="4">No users found. Please add a user.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="addUserForm" method="post" action="<?= base_url('admin/add_user'); ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="userName">User Name</label>
                        <input type="text" class="form-control" id="userName" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveUserBtn">Save User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // ... (existing add user form submission logic) ...
        $('#addUserForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const btn = $('#saveUserBtn');
            const btnHtml = btn.html();

            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: form.serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert(response.message);
                        window.location.reload(); 
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                },
                complete: function() {
                    btn.prop('disabled', false).html(btnHtml);
                }
            });
        });

        // New logic for delete button
        $('.delete-user-btn').on('click', function() {
            const userId = $(this).data('user-id');
            const userName = $(this).closest('tr').find('td:nth-child(2)').text();

            if (confirm(`Are you sure you want to delete the user "${userName}"? This action cannot be undone.`)) {
                $.ajax({
                    url: '<?= base_url('admin/delete_user_ajax'); ?>',
                    type: 'POST',
                    data: { user_id: userId },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            alert(response.message);
                            // Remove the row from the table
                            $('#user-row-' + userId).remove();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert('An error occurred while deleting the user.');
                    }
                });
            }
        });
    });
</script>