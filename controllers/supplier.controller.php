<?php

 class supplierController{

 	/*=============================================
	CREATE SUPPLIER
	=============================================*/
	
	static public function ctrCreateSupplier(){

		if(isset($_POST['addSupplier'])){

            $table = "suppliers";

            $data = array("name" => $_POST["newSupplier"],
                           "address" => $_POST["newAddress"],
                           "email" => $_POST["newEmail"],
                           "contact" => $_POST["newContact"]);

            $answer = supplierModel::mdlAddsSupplier($table, $data);

            if($answer == "ok"){

                echo'<script>

                Swal.fire({
                          icon: "success",
                          title: "Supplier added succesfully!",
                          showConfirmButton: true,
                          confirmButtonText: "Close"
                          }).then(function(result){
                                    if (result.value) {

                                    window.location = "suppliers";

                                    }
                                })

                    </script>';

            }

        }

    }
    /*=============================================
	SHOW SUPPLIERS
	=============================================*/

	static public function ctrShowSuppliers($item, $value){

		$table = "suppliers";

		$answer = supplierModel::mdlShowSuppliers($table, $item, $value);

		return $answer;
	}


}