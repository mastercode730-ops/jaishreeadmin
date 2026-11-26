<script>
  function copyToClipboard(td) {
    const text = td.trim();

    // Copy to clipboard
    navigator.clipboard.writeText(text).then(() => {
      alert("Copied");
    }).catch(err => {
      console.error("Failed to copy: ", err);
    });
  }
</script>

<div class="card">
    <div class="card-header">
        <div class="container-fluid">
            <div class="row justify-content-start">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <h4>All Sub Users</h4>
                </div>
            </div>
        </div>
        <div class="scroll">
            <table class="table table-striped table-bordered zero-configuration">
                <thead class="table_head">
                    <tr>
                        <th>S no.</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Access</th>
                        <?php if(isset($_GET['agent']) && $_GET['agent']=="yes"){ ?>
                        <th>App URL</th>
                        <th>URL</th>
                        <?php } ?>
                        <th>Created at</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1;
                    foreach ($users->result() as $row) : ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $row->name; ?></td>
                            <td><?= $row->email; ?></td>
                            <td><?= $row->access; ?></td>
                            <?php if(isset($_GET['agent']) && $_GET['agent']=="yes"){ ?>
                            <td><button onclick="copyToClipboard('https://aghorimatka.com/aghori.php?agentId=<?= $row->id; ?>')" class="btn btn-primary btn-sm">Copy App URL</button></td>
                            <td><button onclick="copyToClipboard('https://user.aghorimatka.com?agent=<?= $row->id; ?>')" class="btn btn-primary btn-sm">Copy agent URL</button></td>
                            <?php } ?>
                            <td><?= $row->created_at; ?></td>
                            <td>
                                <a href="?sid=<?= $row->id; ?>"><?= $row->status == 0 ? "<i class='inactive fa-solid fa-ban' data-toggl='tooltip' data-placement='top' title='Mark Inactive'></i>" :  "<i class='active fas fa-circle-check' data-toggl='tooltip' data-placement='top' title='Mark Active'></i>"; ?></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Add Money -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Money</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="formGroupExampleInput">Amount</label>
                        <input type="text" name="amount" class="form-control p-2" id="formGroupExampleInput" placeholder="Please enter amount">
                        <input type="hidden" name="id_hidden">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Cut Money -->
<div class="modal fade" id="exampleModalCenterCut" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Cut Money</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="formGroupExampleInput">Amount</label>
                        <input type="text" name="amount" class="form-control p-2" id="formGroupExampleInput" placeholder="Please enter amount">
                        <input type="hidden" name="id_hidden_cut">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="submit_cut" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $("tr td a").on("click", function() {
            var id = $(this).attr("data-ids");
            var ids = $(this).attr("data-idss");
            $('[name="id_hidden"]').val(id);
            $('[name="id_hidden_cut"]').val(ids);
        });
        
    });
</script>