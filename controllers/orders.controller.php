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
                            "status" => 1,
                            "total" => $_POST["total"]);
            
            $answer = OrdersModel::mdlAddOrder($table, $data);
            
            if($answer == 'ok'){

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
    
            $answer = OrdersModel::mdlEditOrder($table, $data);
            
    
            if($answer == "ok"){
    
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