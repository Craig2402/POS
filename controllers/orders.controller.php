<?php

class orderController{
    

    /*=============================================
    CREATE ORDER
    =============================================*/
    static public function ctrCreateOrder(){

        if(isset($_POST['addproducts'])){

            $table = 'orders';

            $data = array("supplier" => $_POST["supplier"],
                            "products" => $_POST["products"]);
            
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

}