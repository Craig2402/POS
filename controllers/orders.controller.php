<?php

class orderController{
    

    /*=============================================
    CREATE ORDER
    =============================================*/
    static public function ctrCreateOrder(){

        if(isset($_POST['addproducts'])){

            $table = 'orders';

            $data = array("supplier" => $_POST["supplier"],
                            "products" => $_POST["products"],
                            "status" => 0,
                            "total" => $_POST["total"]);
            
            $answer = OrdersModel::mdlAddOrder($table, $data);
        
            $suppliertable = "suppliers";
            $item = "supplierid";
            $value = $data['supplier'];
		    $supplier = supplierModel::mdlShowSuppliers($suppliertable, $item, $value);
            
            if($answer == 'ok'){
                // Create an array with the data for the activity log entry
                $logdata = array(
                    'UserID' => $_SESSION['userId'],
                    'ActivityType' => 'Order',
                    'ActivityDescription' => 'User ' . $_SESSION['username'] . ' created an order to supplier ' .$supplier['name']. ' for the following products; ' . $data['products'] . '.'
                );
                // Call the ctrCreateActivityLog() function
                activitylogController::ctrCreateActivityLog($logdata);

                echo '<script>
                    
                Swal.fire({
                        icon: "success",
                        title: "Order has been successfully created",
                        showConfirmButton: true,
                        confirmButtonText: "Close"

                        }).then(function(result){
                            if (result.value) {

                                window.location = "orders";

                            }
                        });
                    
                </script>';
            }

        }

    }

    /*=============================================
    SHOW ORDERS
    =============================================*/
    static public function ctrShowOrders($item, $value){
        
        $table = 'orders';
        
        $data = OrdersModel::mdlShowOrders($table, $item, $value);
        
        return $data;
    }

    /*=============================================
    EDIT ORDERS
    =============================================*/
    static public function ctrEditOrders(){
        
        if (isset($_POST["editStatus"])){ 

            $table = 'orders';

            $data = array("status" => $_POST["status"],
                            "id" => $_POST["order_id"]);
            
            if ($data["status"] == 1) {
                $status = "Delivered";
            }elseif ($data["status"] == 2) {
                $status = "Canceled";
            }
    
            $answer = OrdersModel::mdlEditOrder($table, $data);
            
    
            if($answer == "ok"){
                // Create an array with the data for the activity log entry
                $logdata = array(
                    'UserID' => $_SESSION['userId'],
                    'ActivityType' => 'Order',
                    'ActivityDescription' => 'User ' . $_SESSION['username'] . ' changed the status of order ' .$data['id']. ' to ' . $status . '.',
                    'itemID' => $data['id']
                );
                // Call the ctrCreateActivityLog() function
                activitylogController::ctrCreateActivityLog($logdata);
    
                echo'<script>
    
                Swal.fire({
                            icon: "success",
                            title: "Status changed succesfully!",
                            showConfirmButton: true,
                            confirmButtonText: "Close"
                            }).then(function(result){
                                    if (result.value) {
    
                                    window.location = "vieworders";
    
                                    }
                                })
    
                    </script>';
    
            }

        }

    }
    
}