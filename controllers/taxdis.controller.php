<?php

 class taxController{

	/*=============================================
   SET THE STORE ID
   =============================================*/
	
    static private $storeid;

	public static function initialize() {
		if ($_SESSION['role'] == "Administrator") {
			if (isset($_GET['store-id'])) {
				self::$storeid = $_GET['store-id'];
			} else {
				echo "<script>
					window.onload = function() {
						Swal.fire({
							title: 'No store is selected',
							text: 'Redirecting to Dashboard',
							icon: 'error',
							showConfirmButton: false,
							timer: 2000 // Display alert for 2 seconds
						}).then(function() {
							// After the alert is closed, redirect to the dashboard
							window.location= 'dashboard';
						});
					};
					</script>";
				exit; // Adding exit to stop further execution after the redirection
			}
		} else {
			self::$storeid = $_SESSION['storeid'];
		}
	}

 	/*=============================================
	CREATE taxdis
	=============================================*/
	
	static public function ctrCreateTax(){
        self::initialize();

		if (isset($_POST['addTax'])) {
				
			$table = 'taxes';
				
			$randomNumber = mt_rand(1000, 9999); // Generate a random 4-digit number
			$timezone = new DateTimeZone("Africa/Nairobi"); // Replace "Your_Timezone" with the desired timezone identifier, such as "America/New_York"
			$current_time = new DateTime("now", $timezone); // Get the current time in the specified timezone
			$current_time_formatted = $current_time->format("His"); // Format the current time in hours, minutes, and seconds
			$taxid = $randomNumber . "-" . $current_time_formatted;
	
			$data = array("taxid" => $taxid,
							"VAT" => $_POST["VAT"],
							"discount" => $_POST["discount"],
							"storeid" => self::$storeid);
	
			$answer = TaxModel::mdlAddTax($table, $data);
	
			if($answer == "ok"){
				
				// Create an array with the data for the activity log entry
				$logdata = array(
					'UserID' => $_SESSION['userId'],
					'ActivityType' => 'Taxes',
					'ActivityDescription' => 'User ' . $_SESSION['username'] . ' created tax type '.$data['discount'].'.',
					'storeid' => self::$storeid
				);
				// Call the ctrCreateActivityLog() function
				activitylogController::ctrCreateActivityLog($logdata);
	
				echo '<script>
					
				Swal.fire({
						icon: "success",
						title: "Tax type has been successfully created.",
						showConfirmButton: false,
						timer: 2000 // Display alert for 2 seconds
					}).then(function() {
						// After the alert is closed, redirect to the dashboard
						window.location= "taxdis";
					});
					
				</script>';
			}

		}
			
	}

	/*=============================================
	SHOW CATEGORIES
	=============================================*/

	static public function ctrShowTax($item, $value){

		$table = "taxes";

		$answer = TaxModel::mdlShowTax($table, $item, $value);

		return $answer;
	}

	/*=============================================
	EDIT CATEGORY
	=============================================*/

	static public function ctrEditTax(){

		if(isset($_POST["editTaxdis"])){

			$table = "taxes";

			$data = array("VAT" => $_POST["editVAT"],
							"taxId" => $_POST["actualtaxId"],
							"VATName" => $_POST["editdiscount"]);
		
			$item = "taxId";
			$taxid = $_POST["actualtaxId"];
			$oldItem = TaxModel::mdlShowTax($table, $item, $taxid);

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
							

			$answer = TaxModel::mdlEditTax($table, $data);

			if($answer == "ok"){
				
				// Create an array with the data for the activity log entry
				$logdata = array(
					'UserID' => $_SESSION['userId'],
					'ActivityType' => 'Taxes',
					'ActivityDescription' => $logMessage,
					'itemID' => $taxid,
					'storeid' => self::$storeid
				);
				// Call the ctrCreateActivityLog() function
				activitylogController::ctrCreateActivityLog($logdata);

				echo'<script>

				Swal.fire({
						icon: "success",
						title: "Tax has been successfully edited ",
						showConfirmButton: false,
						timer: 2000 // Display alert for 2 seconds
					}).then(function() {
						// After the alert is closed, redirect to the dashboard
						window.location= "taxdis";
					});
				</script>';

			}

		}

	}

	/*=============================================
	DELETE CATEGORY
	=============================================*/

	static public function ctrDeleteTax(){

		if(isset($_GET["idTaxdis"])){

			$table ="taxes";
			$data = $_GET["idTaxdis"];

			$item = "taxId";
			$tax = TaxModel::mdlShowTax($table, $item, $data);

			$answer = TaxModel::mdlDeleteTax($table, $data);
			// var_dump($answer);

			if($answer == "ok"){
					
				// Create an array with the data for the activity log entry
				$logdata = array(
					'UserID' => $_SESSION['userId'],
					'ActivityType' => 'Taxes',
					'ActivityDescription' => 'User ' . $_SESSION['username'] . ' deleted tax type '.$tax['VATName'].'.',
					'itemID' => $data,
					'storeid' => self::$storeid
				);
				// Call the ctrCreateActivityLog() function
				activitylogController::ctrCreateActivityLog($logdata);

				echo'<script>

                Swal.fire({
						  icon: "success",
						  title: "The tax has been successfully deleted",
						  showConfirmButton: false,
						  timer: 2000 // Display alert for 2 seconds
					  }).then(function() {
						  // After the alert is closed, redirect to the dashboard
						  window.location= "taxdis";
					  });

					</script>';
			}
		
		}
		
	}

}