<div class="card">
    <div class="card-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-8 m-3">
                    <h4>Pending Running Games</h4>
                </div>
                <div class="col-3 m-3 d-flex justify-content-end">
                    <a class="btn btn-success" href="#" data-toggle="modal" data-target="#AddGameModal">Add Games</a>
                </div>
            </div>
        </div>
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
                        <th>Moti Jodi end Date</th>
                        <th>Moti Jodi end Time</th>
                        <th>Result end Date</th>
                        <th>Result end Time</th>
                        <th>Total Bid Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;
                    foreach ($data->result() as $row) :
                        $get_game = $this->Crud_model->fetch('game', array('id' => $row->game_id));
                    ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $get_game->row('game_name'); ?></td>
                            <td><?= $row->start_date; ?></td>
                            <td><?= !empty($row->start_time) ? date("h:i a", strtotime($row->start_time)) : '' ?></td>
                            <td><?= $row->end_date; ?></td>
                            <td><?= !empty($row->end_time) ? date("h:i a", strtotime($row->end_time)) : '' ?></td>
                            <td><?= $row->result_date; ?></td>
                            <td><?= !empty($row->result_time) ? date("h:i a", strtotime($row->result_time)) : '' ?></td>
                            <td><?= $row->res_date; ?></td>
                            <td><?= !empty($row->res_time) ? date("h:i a", strtotime($row->res_time)) : '' ?></td>
                            <?php $grand_total = $this->db->select_sum('money')->get_where('game_bet', array('game_id' => $row->id)); ?>
                            <td><?= $grand_total->row('money') > 0 ? $grand_total->row('money') : 0 ?></td>
                            <td><?php
                                if ($row->status == 0) {
                                    echo '<p class="active-pills">Active</p>';
                                }
                                ?></td>
                            <td>
                                <a href="?sid=<?= $row->id; ?>">
                                    <?php if ($row->status == 0) {
                                        echo "<i class='inactive fa-solid fa-ban' data-toggl='tooltip' data-placement='top' title='Mark Inactive'></i>";
                                    } else {
                                        echo "<i class='active fas fa-circle-check' data-toggl='tooltip' data-placement='top' title='Mark Active'></i>";
                                    } ?></a>
                               
                                <a href="<?= base_url('admin/game_details') ?>?game_id=<?= $row->id ?>">
                                    <i id="<?= $row->id ?>" class="fa fa-gamepad" style="background-color: #f8a232; margin: 0px 2px;" data-toggl="tooltip" data-placement="top" title="Game Details"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Add Game Modal -->
<div class="modal fade" id="AddGameModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <h5 class="text-center pt-4">Create Game</h5>
            <div class="card-body">
                <form id="games_form" method="post">
                    <div class="form-group fontuser">
                        <label class="lab">Game Name <span class="star">★</span></label>
                        <select name="game_list_id" required class="form-control">
                            <?php $game_list = $this->Crud_model->fetch('game', array('status' => 0));
                            foreach ($game_list->result() as $item) : ?>
                                <option value="<?= $item->id ?>"><?= $item->game_name ?></option>
                            <?php endforeach; ?>
                        </select>
                        <!-- <i class="fa fa-gamepad fa-lg" style="left: -10px;"></i> -->
                    </div>
                    <div class="form-group fontuser">
                        <label class="lab">Start Date <span class="star">★</span></label>
                        <input type="date" name="start_date" class="form-control" style="padding-left: 40px; padding-right: 13px;" required>
                        <i class="fa fa-gamepad fa-lg" style="left: -8px; top: 55px;"></i>
                    </div>
                    <div class="form-group fontuser">
                        <label class="lab">Start Time <span class="star">★</span></label>
                        <input type="time" name="start_time" class="form-control" style="padding-left: 40px;" required>
                        <i class="fa fa-gamepad fa-lg" style="left: -8px; top: 48px;"></i>
                    </div>
                    <div class="form-group fontuser">
                        <label class="lab">End Date <span class="star">★</span></label>
                        <input type="date" name="end_date" class="form-control" style="padding-left: 40px; padding-right: 13px;" required>
                        <i class="fa fa-gamepad fa-lg" style="left: -8px; top: 55px;"></i>
                    </div>
                    <div class="form-group fontuser">
                        <label class="lab">End Time <span class="star">★</span></label>
                        <input type="time" name="end_time" class="form-control" style="padding-left: 40px;" required>
                        <i class="fa fa-gamepad fa-lg" style="left: -8px; top: 48px;"></i>
                    </div>
                    <div class="form-group fontuser">
                        <label class="lab">Moti Jodi end Date <span class="star">★</span></label>
                        <input type="date" name="result_date" class="form-control" style="padding-left: 40px; padding-right: 13px;" required>
                        <i class="fa fa-gamepad fa-lg" style="left: -8px; top: 55px;"></i>
                    </div>
                    <div class="form-group fontuser">
                        <label class="lab">Moti Jodi end Time <span class="star">★</span></label>
                        <input type="time" name="result_time" class="form-control" style="padding-left: 40px;" required>
                        <i class="fa fa-gamepad fa-lg" style="left: -8px; top: 48px;"></i>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button class="btn btn-success" name="submit" type="submit">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
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
                <form id="games_form" method="post" onsubmit="return game_copy()">
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
                        <label class="lab">Moti Jodi Date <span class="star">★</span></label>
                        <input type="date" name="result_date_update" class="form-control" style="padding-left: 40px; padding-right: 13px;" required>
                        <i class="fa fa-gamepad fa-lg" style="left: -8px; top: 55px;"></i>
                    </div>
                    <div class="form-group fontuser">
                        <label class="lab">Moti Jodi Time <span class="star">★</span></label>
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


<div class="modal fade" id="game_copy" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
    function game_copy(){
        if (confirm("Are you replicate this game in next day!") == true) {
            return true;
            $('#myModal').modal('show');
            // text = "You pressed OK!";
            alert('yes');
        } else {
            return false;
            alert('no');
            // text = "You canceled!";
        }
        return false;
    }


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