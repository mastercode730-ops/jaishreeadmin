<div class="card">
    <div class="card-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-8 m-3">
                    <h4><?php echo $page_title; ?></h4>
                </div>
            </div>
        </div>
        <div class="scroll">
            <table class="table table-striped table-bordered zero-configuration">
                <thead class="table_head">
                    <tr>
                        <th>S no.</th>
                        <th>Userid</th>
                        <th>Amount</th>
                        <th>Number</th>
                        <th>Type</th>
                        <th>Created at</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;
                    foreach ($data->result() as $row) :
                        // $get_game = $this->Crud_model->fetch('game', array('id' => $row->game_id));
                    ?>
                        <tr style="<?= $row->cancel_at != "" ? "background-color:red" : "" ?>">
                            <td><?= $i++; ?></td>
                            <td><?= $row->mobile; ?></td>
                            <td><?= $row->money; ?></td>
                            <td><?= $row->game_number>9?$row->game_number:'0'.$row->game_number; ?></td>
                            <td><?= $row->type == 1 ? "JODI" : ($row->type == 2 ? "Ander Haruf" : "Bahar Haruf"); ?></td>
                            <td><?= date("d-m-Y h:i A", strtotime($row->created_at)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Update Game Modal -->
<div class="modal fade" id="UpdateGameModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <h5 class="text-center pt-4">Update Game</h5>
            <div class="card-body">
                <form id="games_form" method="post">
                    <input type="hidden" name="id_hidden">
                    <div class="form-group fontuser">
                        <label class="lab">Game Name <span class="star">★</span></label>
                        <input type="text" name="game_list_id_update" disabled class="form-control" required>
                    </div>
                    <div class="form-group fontuser">
                        <label class="lab">Start Date <span class="star">★</span></label>
                        <input type="date" name="start_date_update" class="form-control" style="padding-left: 40px; padding-right: 13px;" required>
                        <i class="fa fa-gamepad fa-lg" style="left: -8px; top: 55px;"></i>
                    </div>
                    <div class="form-group fontuser">
                        <label class="lab">Start Time <span class="star">★</span></label>
                        <input type="time" name="start_time_update" class="form-control" style="padding-left: 40px;" required>
                        <i class="fa fa-gamepad fa-lg" style="left: -8px; top: 48px;"></i>
                    </div>
                    <div class="form-group fontuser">
                        <label class="lab">End Date <span class="star">★</span></label>
                        <input type="date" name="end_date_update" class="form-control" style="padding-left: 40px; padding-right: 13px;" required>
                        <i class="fa fa-gamepad fa-lg" style="left: -8px; top: 55px;"></i>
                    </div>
                    <div class="form-group fontuser">
                        <label class="lab">End Time <span class="star">★</span></label>
                        <input type="time" name="end_time_update" class="form-control" style="padding-left: 40px;" required>
                        <i class="fa fa-gamepad fa-lg" style="left: -8px; top: 48px;"></i>
                    </div>
                    <div class="form-group fontuser">
                        <label class="lab">Moti Jodi end Date <span class="star">★</span></label>
                        <input type="date" name="result_date_update" class="form-control" style="padding-left: 40px; padding-right: 13px;" required>
                        <i class="fa fa-gamepad fa-lg" style="left: -8px; top: 55px;"></i>
                    </div>
                    <div class="form-group fontuser">
                        <label class="lab">Moti Jodi end Time <span class="star">★</span></label>
                        <input type="time" name="result_time_update" class="form-control" style="padding-left: 40px;" required>
                        <i class="fa fa-gamepad fa-lg" style="left: -8px; top: 48px;"></i>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button class="btn btn-success" name="submit_update" type="submit">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("tr td a").on("click", function() {
            var id = $(this).attr("data-update_id");
            var game_name = $(this).attr("data-game_name");
            var start_date = $(this).attr("data-start_date");
            var start_time = $(this).attr("data-start_time");
            var end_date = $(this).attr("data-end_date");
            var end_time = $(this).attr("data-end_time");
            var result_date = $(this).attr("data-result_date");
            var result_time = $(this).attr("data-result_time");
            $('[name="id_hidden"]').val(id);
            $('[name="game_list_id_update"]').val(game_name);
            $('[name="start_date_update"]').val(start_date);
            $('[name="start_time_update"]').val(start_time);
            $('[name="end_date_update"]').val(end_date);
            $('[name="end_time_update"]').val(end_time);
            $('[name="result_date_update"]').val(result_date);
            $('[name="result_time_update"]').val(result_time);
        });
    });
</script>