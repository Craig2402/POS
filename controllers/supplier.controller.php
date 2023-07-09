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
                // Create an array with the data for the activity log entry
                $logdata = array(
                    'UserID' => $_SESSION['userId'],
                    'ActivityType' => 'Supplier',
                    'ActivityDescription' => 'User ' . $_SESSION['username'] . ' creates supplier ' .$data['name']. '.'
                );
                // Call the ctrCreateActivityLog() function
                activitylogController::ctrCreateActivityLog($logdata);

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

            $data = array(
                "name" => $_POST["newSupplier"],
                "supplierid" => $_POST["supplierId"],
                "address" => $_POST["newAddress"],
                "email" => $_POST["newEmail"],
                "contact" => $_POST["newContact"]
            );
            
            $item = "supplierid";
            $supplierid = $_POST["supplierId"];
            $oldItem = supplierModel::mdlShowSuppliers($table, $item, $supplierid);

            $changedInfo = ''; // Initialize the changed information string

            foreach ($data as $property => $value) {
                if ($property !== 'barcode' && $oldItem[$property] !== $value) {
                    $changedInfo .= "Property $property changed from {$oldItem[$property]} to $value. ";
                }
            }
            
            // If any properties were changed, use the changed information as the log message
            if (!empty($changedInfo)) {
                $logMessage = $changedInfo;
            } else {
                $logMessage = "Supplier has been edited.";
            }           
            

            $answer = supplierModel::mdlEditSupplier($table, $data);

            if($answer == "ok"){
                // Create an array with the data for the activity log entry
                $logdata = array(
                    'UserID' => $_SESSION['userId'],
                    'ActivityType' => 'Supplier',
                    'ActivityDescription' => $logMessage,
                    'itemID' => $supplierid
                );
                // Call the ctrCreateActivityLog() function
                activitylogController::ctrCreateActivityLog($logdata);

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
            
            $item = "supplierid";
            $supplierid = $data;
            $oldItem = supplierModel::mdlShowSuppliers($table, $item, $supplierid);

			$answer = supplierModel::mdlDeleteSupplier($table, $data);

			if($answer == "ok"){
                // Create an array with the data for the activity log entry
                $logdata = array(
                    'UserID' => $_SESSION['userId'],
                    'ActivityType' => 'Supplier',
                    'ActivityDescription' => 'User ' . $_SESSION['username'] . ' deleted supplier ' .$oldItem['name']. '.',
                    'itemID' => $supplierid
                );
                // Call the ctrCreateActivityLog() function
                activitylogController::ctrCreateActivityLog($logdata);

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