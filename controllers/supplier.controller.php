<?php

 class supplierController{

 	/*=============================================
	CREATE SUPPLIER
	=============================================*/
	
	static public function ctrCreateSupplier(){

		if(isset($_POST['addSupplier'])){

            $table = "suppliers";

            $data = array("name" => $_POST["Supplier"],
                           "address" => $_POST["Address"],
                           "email" => $_POST["Email"],
                           "contact" => $_POST["Contact"]);

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

	/*=============================================
	EDIT SUPPLIERS
	=============================================*/

	static public function ctrEditSupplier(){

		if(isset($_POST["editsupplier"])){

            $table = "suppliers";

            $data = array("name" => $_POST["newSupplier"],
                            "supplierid" => $_POST["supplierId"],
                            "address" => $_POST["newAddress"],
                            "email" => $_POST["newEmail"],
                            "contact" => $_POST["newContact"]);

            $answer = supplierModel::mdlEditSupplier($table, $data);

            if($answer == "ok"){

                echo'<script>

                Swal.fire({
                            icon: "success",
                            title: "The supplier has been edited",
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
	DELETE SUPPLIER
	=============================================*/
	static public function ctrDeleteSupplier(){

		if(isset($_GET["id"])){

			$table ="suppliers";
			$data = $_GET["id"];

			$answer = supplierModel::mdlDeleteSupplier($table, $data);

			if($answer == "ok"){

				echo'<script>

				Swal.fire({
					  icon: "success",
					  title: "The supplier has been successfully deleted",
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

}