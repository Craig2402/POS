<?php
// Set the default timezone to Nairobi
date_default_timezone_set('Africa/Nairobi');
require_once "../controllers/orders.controller.php";
require_once "../models/orders.model.php";
require_once "../models/product.model.php";

class AjaxOrders{
    public $orderid;
    public $batchId;
    public $batchitems;
    public $batchquantity;
    public $orderId;
    public $productId;
    public $serialNumber;

    public function ajaxFetchOrder(){
        
        $table = "order_items";
        $item = "order_id";
        $value = $this->orderid;
        $fetchAll=true;

        $orders = OrdersModel::mdlShowOrderitems($table, $item, $value, $fetchAll);

		echo json_encode($orders);

    }

    public function ajaxFetchBatchItems(){
        
        $table = "batch_items";
        $item = "serialNumber";
        $value = $this->serialNumber;
        $options = null;

        $batchitems = OrdersModel::mdlShowBatch($table, $item, $value, $options);

		echo json_encode($batchitems);

    }


    public function ajaxFetchOrderproducts(){
        
        $orderid = $this->orderid;
        $query = "SELECT oi.product_id, oi.quantity, p.product FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        WHERE oi.order_id = :orderid";
        $params = array(':orderid' => $orderid);
        $orderitems = OrdersModel::ctrCustomQuery($query, $params);
		echo json_encode($orderitems);

    }

    public function ajaxCreateBatch(){
        $table = "batches";
        // Create a DateTime object with the current date and time in Nairobi timezone
        $dateTime = new DateTime();
        // Format the DateTime as a string
        $datecreated = $dateTime->format('Y-m-d H:i:s');
        $batchData = array(
            'batch_id' => $this->batchId,
            'quantity' => $this->batchquantity,
            'orderId' => $this->orderId,
            'product_id' => $this->productId,
            'datecreated' => $datecreated
            // Add other batch-related data here
        );
        $item = ["order_id", "product_id"];
        $value = [$this->orderId, $this->productId];
        $options = "duplicates";
        // Check if a batch with the same orderId already exists
        $existingBatch = OrdersModel::mdlShowBatch($table, $item, $value, $options);

        if (!$existingBatch) {
            // Create the batch
            $batch = OrdersModel::mdlCreateBatch($table, $batchData); // Replace with your actual model function

            // update product quantity
            $table = "products";
            $item1 = 'stock';
            $incrementBy = $this->batchquantity;
            $value = $this->productId;
            $stmt = Connection::connect()->prepare("UPDATE $table SET $item1 = $item1 + :incrementBy WHERE id = :id");
            $stmt->bindParam(":incrementBy", $incrementBy, PDO::PARAM_INT);
            $stmt->bindParam(":id", $value, PDO::PARAM_STR);
            $stmt->execute();

            if ($this->batchitems) {
                if ($batch == "ok") {
                    $table = "batch_items";
                    // Retrieve batchItems from the FormData
                    $batchItemsJson = $this->batchitems;

                    // Decode the JSON string into an array
                    $batchItems = json_decode($batchItemsJson, true);
                    // Batch created successfully, now add batch items
                    foreach ($batchItems as $product) {
                        $batchItemData = array(
                            'batch_id' => $this->batchId,
                            'serial_number' => $product['serialNumber'],
                            'manufacturing_date' => $product['manufacturingDate'],
                            'expiry_date' => $product['expiryDate'],
                            // Add other batch item-related data here
                        );

                        // Add the batch item
                        OrdersModel::mdlAddBatchitems($table, $batchItemData); // Replace with your actual model function
                        
                    }

                    echo json_encode(array('status' => 'success', 'message' => 'Batch created successfully'));
                } else {
                    echo json_encode(array('status' => 'error', 'message' => 'Error creating batch.'));
                }

            } else {
                echo json_encode(array('status' => 'success', 'message' => 'Batch created successfully'));
            }

        } else {
            echo json_encode(array('status' => 'error', 'message' => 'The Batch has already been created.'));
        }

    }
}

if (isset($_POST['orderid'])) {
    $fetchOrder = new AjaxOrders();
    $fetchOrder -> orderid = $_POST["orderid"];
    $fetchOrder -> ajaxFetchOrder();
}

if (isset($_POST['serialNumber'])) {
    $fetchOrder = new AjaxOrders();
    $fetchOrder -> serialNumber = $_POST["serialNumber"];
    $fetchOrder -> ajaxFetchBatchItems();
}

if (isset($_POST["orderId"])) {
    $fetchOrderproducts = new AjaxOrders();
    $fetchOrderproducts -> orderid = $_POST["orderId"];
    $fetchOrderproducts -> ajaxFetchOrderproducts();
}

if (isset($_POST["batchId"]) && isset($_POST["batchItems"]) && isset($_POST["quantity"]) && isset($_POST["OrderId"]) && isset($_POST["productId"])){
    $createBatch = new AjaxOrders();
    $createBatch -> batchId = $_POST["batchId"];
    $createBatch -> batchitems = $_POST["batchItems"];
    $createBatch -> batchquantity = $_POST["quantity"];
    $createBatch -> orderId = $_POST["OrderId"];
    $createBatch -> productId = $_POST["productId"];
    $createBatch -> ajaxCreateBatch();
}

if (isset($_POST["batchId"]) && isset($_POST["quantity"]) && isset($_POST["OrderId"]) && isset($_POST["productId"])){
    $createBatch = new AjaxOrders();
    $createBatch -> batchId = $_POST["batchId"];
    $createBatch -> batchquantity = $_POST["quantity"];
    $createBatch -> orderId = $_POST["OrderId"];
    $createBatch -> productId = $_POST["productId"];
    $createBatch -> ajaxCreateBatch();
}