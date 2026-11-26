<div class="card">
    <div class="card-header">
        <div class="container-fluid">
            <div class="row justify-content-start">
                <div class="col-8">
                    <h4>Banner Image</h4>
                </div>
                <div class="col-4 d-flex justify-content-end">
                    <a class="btn btn-success" href="#" data-toggle="modal" data-target="#AddGameModal">Add Image</a>
                </div>
            </div>
            <br>
        </div>
        <div class="scroll">
            <table class="table table-striped table-bordered zero-configuration">
                <thead class="table_head">
                    <tr>
                        <th>S no.</th>
                        <th>Banner Image</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;
                    foreach ($data->result() as $row) : ?>
                        <tr>
                            <td><?= $i++; ?></td>

                            <td><?php if (!empty($row->banner_img)) echo "<img src=" . base_url($row->banner_img) . ">"; ?></td>
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
                                <a href="#" data-id_update="<?= $row->id ?>"><i class="square fa-solid fa-pen-to-square" data-toggl="tooltip" data-placement="top" title="Update" data-toggle="modal" data-target="#UpdateGameModal"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Banner Image Modal -->
<div class="modal fade" id="AddGameModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <h5 class="text-center pt-4">Create Image</h5>
            <div class="card-body">
                <form id="games_form" method="post" enctype="multipart/form-data">
                    <div class="form-group fontuser">
                        <label class="lab" id="a">Banner Image <span class="star">★</span></label>
                        <input type="file" name="banner_img" class="form-control" placeholder="" accept=".png,.jpg,.jpeg" required>
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


<!-- update Banner Image Modal -->
<div class="modal fade" id="UpdateGameModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <h5 class="text-center pt-4">Create Image</h5>
            <div class="card-body">
                <form id="games_form" method="post" enctype="multipart/form-data">
                    <div class="form-group fontuser">
                        <label class="lab">Banner Image <span class="star">★</span></label>
                        <input type="file" name="banner_img" class="form-control" accept=".png,.jpg,.jpeg">
                        <input type="hidden" name="id_hidden">
                        
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $("tr td a").on("click", function() {
            var id = $(this).attr("data-id_update");
            $('[name="id_hidden"]').val(id);
        });
    });
</script>