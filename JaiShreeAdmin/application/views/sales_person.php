<div id="main-wrapper">
    <div class="container-fluid">
        <div class="row sp">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="container-fluid">
                            <div class="row justify-content-start sp">
                                <div class="col-4">
                                    <h4>Sales Person List</h4>
                                </div>
                                <div class="col-8 d-flex justify-content-end">
                                    <a class="btn btn-success" href="<?= base_url('admin/add_sales_person') ?>">Add Sales Person</a>
                                </div>
                            </div>
                        </div>
                        <div class="scroll">

                            <table class="table table-striped table-bordered zero-configuration">
                                <thead class="table_head">
                                    <tr>
                                        <th>S no.</th>
                                        <th>Sales Person Name</th>
                                        <th>Mother Name</th>
                                        <th>Father Name</th>
                                        <th>Post</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1;
                                    foreach ($sales_person_details as $row) : ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo $row->saler_name; ?></td>
                                            <td><?php echo $row->mother_name; ?></td>
                                            <td><?php echo $row->father_name; ?></td>
                                            <td><?php echo $row->post; ?></td>
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
                                                        echo "<i class='inactive fa-solid fa-ban' data-toggle='tooltip' data-placement='top' title='Change'></i>";
                                                    } else {
                                                        echo "<i class='active fas fa-circle-check' data-toggle='tooltip' data-placement='top' title='Change'></i>";
                                                    } ?></a>
                                                <a href="<?= base_url('admin/update_sales_person/' . $row->id); ?>"><i class="square fa-solid fa-pen-to-square" data-toggl="tooltip" data-placement="top" title="Update" data-toggle="modal" data-target="#state-update"></i></a>
                                                <a href="<?= base_url('admin/sales_person_detail/' . $row->id); ?>"><i class="fa-regular fa-address-card" data-toggl="tooltip" data-placement="top" title="View Profile" data-toggle="modal" style="background-color: #404E67;margin: 0 5px; padding: 5px;color: white;border-radius: 2px;" data-target="#state-update"></i></a>
                                                <a href="" data-toggle="dropdown" class="dropdown"><i id="" class="fa-sharp fa-solid fa-bars" data-toggl="tooltip" data-placement="top" title="List" data-toggle="modal" style="background-color: #277FB2;margin: 0 5px; padding: 5px;color: white;border-radius: 2px;" data-target="#state-update"></i></a>
                                                <ul class="dropdown-menu p-3" style="font-weight:bold;">
                                                    <li><a href="#">Stock</a></li>
                                                    <li><a href="#">Orders</a></li>
                                                    <li><a href="#">Send Orders</a></li>
                                                    <li><a href="#">Retailer</a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <!-- Modal -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->