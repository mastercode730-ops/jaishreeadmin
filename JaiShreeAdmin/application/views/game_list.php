<div class="card">
    <div class="card-header">
        <div class="container-fluid">
            <div class="row justify-content-start sp">
                <div class="col-8">
                    <h4>All Games</h4>
                </div>
                <div class="col-4 d-flex justify-content-end">
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
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;
                    foreach ($games->result() as $row) :
                    ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $row->game_name; ?></td>
                            <td><?php
                                if ($row->status == 0) {
                                    echo '<p class="active-pills">Active</p>';
                                } else {
                                    echo '<p class="inactive-pills">Inactive</p>';
                                }
                                ?></td>
                            <td>
                                <a href="?sid=<?= $row->id; ?>">
                                    <?php if ($row->status == 0) {
                                        echo "<i class='inactive fa-solid fa-ban' data-toggl='tooltip' data-placement='top' title='Mark Inactive'></i>";
                                    } else {
                                        echo "<i class='active fas fa-circle-check' data-toggl='tooltip' data-placement='top' title='Mark Active'></i>";
                                    } ?></a>
                                <a href="#" data-update_id="<?= $row->id; ?>" data-update_name="<?= $row->game_name; ?>">
                                    <i id="<?= $row->id ?>" class="square fa-solid fa-pen-to-square" data-toggl="tooltip" data-placement="top" title="Update" data-toggle="modal" data-target="#UpdateGameModal"></i>
                                </a>
                                <a href="?ids=<?= $row->id;?>"><i style="margin: 0 3px; background-color: #494949;" class="fa fa-trash" data-toggl="tooltip" data-placement="top" title="Delete Game"></i></a>
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
                        <label class="lab" id="a">Game Name <span class="star">★</span></label>
                        <input type="text" name="game_name" class="form-control" placeholder="">
                        <i class="fa fa-gamepad fa-lg" style="left: -10px;"></i>
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
                <form id="games_form" method="post">
                    <input type="hidden" name="id_hidden">
                    <div class="form-group fontuser">
                        <label class="lab">Game Name <span class="star">★</span></label>
                        <input type="text" name="game_name" class="form-control" placeholder="">
                        <i class="fa fa-gamepad fa-lg" style="left: -10px;"></i>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button class="btn btn-success" name="submit_update" type="submit">Submit</button>
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
            var name = $(this).attr("data-update_name");
            $('[name="id_hidden"]').val(id);
            $('[name="game_name"]').val(name);
        });
    });
</script>