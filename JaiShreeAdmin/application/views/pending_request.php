<div class="card">
    <div class="card-header">
        <div class="container-fluid">
            <div class="row justify-content-start">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <h4>Pending Payment Request</h4>
                </div>
            </div>
        </div>
        <div class="scroll">
            <table class="table table-striped table-bordered zero-configuration">
                <thead class="table_head">
                    <tr>
                        <th>S no.</th>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Amount</th>
                        <th>UTR Number</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;
                    foreach ($data->result() as $row) :
                        $user = $this->Crud_model->fetch('users', array('id' => $row->user_id))->row();
                    ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $row->date; ?></td>
                            <td><?= $user->name; ?></td>
                            <td><?= $user->mobile; ?></td>
                            <td><?= $row->amount; ?></td>
                            <td><?= $row->transaction_id; ?></td>
                            <td>
                                <?php
                                if ($row->status == 0) {
                                    echo '<p class="active-pills">Pending</p>';
                                }
                                ?></td>
                            <td>
                                <a href="?id=<?= $row->id; ?>&ids=<?= $row->user_id ?>"><i style="margin: 0; background-color: #008000;" class="fa fa-check" data-toggl="tooltip" data-placement="top" title="Accept"></i></a>
                                <a href="#" data-id="<?= $row->id ?>" data-toggle="modal" data-target="#ViewImageModalComment"><i style="margin: 0; padding: 5px 8px; background-color: #ff0000;" class="fa fa-times" data-toggl="tooltip" data-placement="top" title="Reject"></i></a>
                                <a href="#" data-imagePath="<?= base_url($row->transaction_image ?? ''); ?>"><i style="margin: 0; padding: 5px; background-color: #c000ff;" class="fa fa-eye" data-toggl="tooltip" data-placement="top" title="View Image" data-toggle="modal" data-target="#ViewImageModal"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="ViewImageModal" tabindex="-1" role="dialog" aria-labelledby="ViewImageModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ViewImageModalTitle"><i class="fa fa-receipt mr-1"></i> Payment Receipt Screenshot</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center p-3">
                <div class="image">
                    <img style="max-width:100%; max-height:70vh; object-fit:contain; border-radius:4px;" src="" id="image_preview" alt="Payment Receipt">
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" id="view_image_full" target="_blank" class="btn btn-info">
                    <i class="fa fa-external-link"></i> Open Full Image
                </a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ViewImageModalComment" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Reject Comment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="form-group fontuser">
                        <label class="lab">Comment <span class="star">★</span></label>
                        <input type="hidden" name="comment_id" id="comment_id">
                        <textarea name="comment" class="form-control" required rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button class="btn btn-success" name="update" type="submit">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Combined the click handlers for better performance
        $("tr td a").on("click", function() {
            var image = $(this).attr("data-imagePath");
            if (image) {
                $("img#image_preview").attr('src', image);
                $("#view_image_full").attr('href', image);
            }
            
            var id = $(this).data('id');
            if (id) {
                $("#comment_id").val(id);
            }
        });
    });
</script>