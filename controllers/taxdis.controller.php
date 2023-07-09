<?php

 class taxdisController{

 	/*=============================================
	CREATE taxdis
	=============================================*/
	
	static public function ctrCreateTaxdis(){

		if(isset($_POST['addTaxdis'])){

            if(isset($_POST["VAT"]) == "" || ($_POST["discount"] == "" )){

				echo '<script>
						
                Swal.fire({
							icon: "error",
							title: "Invalid parameters",
							text: "You must pass the VAT values to create a new tax and discount",
							showConfirmButton: true,
							confirmButtonText: "Close"
				
							 }).then(function(result){

								if (result.value) {
									window.location = "taxdis";
								}
							});
						
				</script>';

			}else{
				
				$table = 'taxes';

				$data = array("VAT" => $_POST["VAT"],
							   "discount" => $_POST["discount"]);

				$answer = TaxdisModel::mdlAddTaxdis($table, $data);

				if($answer == "ok"){
					
					// Create an array with the data for the activity log entry
					$logdata = array(
						'UserID' => $_SESSION['userId'],
						'ActivityType' => 'Taxes',
						'ActivityDescription' => 'User ' . $_SESSION['username'] . ' created tax type '.$data['discount'].'.'
					);
					// Call the ctrCreateActivityLog() function
					activitylogController::ctrCreateActivityLog($logdata);

					echo '<script>
						
                    Swal.fire({
							icon: "success",
							title: "Tax has been successfully saved ",
							showConfirmButton: true,
							confirmButtonText: "Close"

							}).then(function(result){
								if (result.value) {

									window.location = "taxdis";

								}
							});
						
					</script>';
				}

			}
			
		}

	}

	/*=============================================
	SHOW CATEGORIES
	=============================================*/

	static public function ctrShowTaxdis($item, $value){

		$table = "taxes";

		$answer = TaxdisModel::mdlShowTaxdis($table, $item, $value);

		return $answer;
	}

	/*=============================================
	EDIT CATEGORY
	=============================================*/

	static public function ctrEditTaxdis(){

		if(isset($_POST["editTaxdis"])){

			if(isset($_POST["editVAT"]) == "" || ($_POST["editdiscount"] == "" )){

				echo'<script>

                Swal.fire({
						  icon: "error",
						  title: "No especial characters or blank fields",
						  showConfirmButton: true,
						  confirmButtonText: "Close"
						  }).then(function(result){
							if (result.value) {

							window.location = "taxdis";

							}
						})

			  	</script>';

			}else{

				$table = "taxes";

				$data = array("VAT" => $_POST["editVAT"],
								"taxId" => $_POST["actualtaxId"],
							   "VATName" => $_POST["editdiscount"]);
			
				$item = "taxId";
				$taxid = $_POST["actualtaxId"];
				$oldItem = TaxdisModel::mdlShowTaxdis($table, $item, $taxid);

				$changedInfo = ''; // Initialize the changed information string
	
				foreach ($data as $property => $value) {
					if ($property !== 'taxId' && $oldItem[$property] !== $value) {
						$changedInfo .= "Property $property changed from {$oldItem[$property]} to $value. ";
					}
				}
				
				// If any properties were changed, use the changed information as the log message
				if (!empty($changedInfo)) {
					$logMessage = $changedInfo;
				} else {
					$logMessage = "Tax has been edited.";
				}
							   

				$answer = TaxdisModel::mdlEditTaxdis($table, $data);

				if($answer == "ok"){
					
					// Create an array with the data for the activity log entry
					$logdata = array(
						'UserID' => $_SESSION['userId'],
						'ActivityType' => 'Taxes',
						'ActivityDescription' => $logMessage,
						'itemID' => $taxid
					);
					// Call the ctrCreateActivityLog() function
					activitylogController::ctrCreateActivityLog($logdata);

					echo'<script>

					Swal.fire({
						  icon: "success",
						  title: "Tax has been successfully edited ",
						  showConfirmButton: true,
						  confirmButtonText: "Close"
						  }).then(function(result){
									if (result.value) {

									window.location = "taxdis";

									}
								})

					</script>';

				}

			}

		}

	}

	/*=============================================
	DELETE CATEGORY
	=============================================*/

	static public function ctrDeleteTaxdis(){

		if(isset($_GET["idTaxdis"])){

			$table ="taxes";
			$data = $_GET["idTaxdis"];

			$item = "taxId";
			$tax = TaxdisModel::mdlShowTaxdis($table, $item, $data);

			$answer = TaxdisModel::mdlDeleteTaxdis($table, $data);
			// var_dump($answer);

			if($answer == "ok"){
					
				// Create an array with the data for the activity log entry
				$logdata = array(
					'UserID' => $_SESSION['userId'],
					'ActivityType' => 'Taxes',
					'ActivityDescription' => 'User ' . $_SESSION['username'] . ' deleted tax type '.$tax['VATName'].'.',
					'itemID' => $data
				);
				// Call the ctrCreateActivityLog() function
				activitylogController::ctrCreateActivityLog($logdata);

				echo'<script>

                Swal.fire({
						  icon: "success",
						  title: "The tax has been successfully deleted",
						  showConfirmButton: true,
						  confirmButtonText: "Close"
						  }).then(function(result){
									if (result.value) {

									window.location = "taxdis";

									}
								})

					</script>';
			}
		
		}
		
	}

}