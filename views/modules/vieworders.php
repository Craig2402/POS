<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Orders</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                        <li class="breadcrumb-item active">Order list</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                <!-- /.col-md-6 -->
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="m-0">List of orders</h5>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table-striped tables display" style="width:100%">
                                <thead>
                                    <th>#</th>
                                    <th>Products</th>
                                    <th>Supplier</th>
                                    <th>Total</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </thead>
                                <tbody>
                                <?php

                                    $item = "store_id";
                                    $value = $_SESSION['storeid'];

                                    $orders = orderController::ctrShowOrders($item, $value);
                                    // var_dump($orders);
                                    foreach ($orders as $key => $val) {
                                        $item = "supplierid";
                                        $value = $val["supplier"];

                                        $supplier = supplierController::ctrShowSuppliers($item, $value);

                                        $jsonArray = $val["products"];
                                        $data = json_decode($jsonArray, true);

                                        echo '
                                        <tr>
                                            <td>'.($key+1).'</td>
                                            <td>';
                                                for ($i = 0; $i < count($data); $i++) {
                                                    $productName = $data[$i]['productName'];
                                                    $quantity = $data[$i]['Quantity'];
                                                    echo $productName . ' (' . $quantity . ')' . " , ";
                                                }
                                        echo '</td>
                                            <td>'.$supplier["name"].'</td>
                                            <td>'.$val["total"].'</td>
                                            <td>'.$val["date"].$val["status"].'</td>
                                            <td>';
                                                if ($val["status"] == 1) {
                                                    echo '<button class="btn btn-sm btn-success">Delivered</button>';
                                                }elseif ($val["status"] == 2) {
                                                    echo '<button class="btn btn-sm btn-secondary">Canceled</button>';
                                                }else {
                                                    echo '<button class="btn btn-sm btn-warning editStatusBtn" data-toggle="modal" data-target="#statusModal" data-order-id="'.$val['orderid'].'">Pending</button>';
                                                }
                                        echo '</td>
                                        </tr>';
                                    }
                                ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <!-- /.col-md-6 -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- // Modal for changing status -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Change Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="order_id" id="order_id">
                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select class="form-control" name="status" id="status">
                            <option disabled selected value="">--Select new status--</option>
                            <option value="1">Delivered</option>
                            <option value="2">Canceled</option>
                        </select>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-primary" name="editStatus">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    <?php
                        $editStatus = new orderController();
                        $editStatus -> ctrEditOrders();
                    ?>
                </form>
            </div>
        </div>
    </div>
</div>
 

<script>
    $(document).ready(function() {
        $('.editStatusBtn').click(function() {
            var orderId = $(this).data('order-id');
            $('#order_id').val(orderId);
        });
    });
</script>

