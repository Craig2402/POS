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

				if($answer == 'ok'){

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
							   "discount" => $_POST["editdiscount"]);

				$answer = TaxdisModel::mdlEditTaxdis($table, $data);
				var_dump($answer);

				if($answer == "ok"){

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

			$answer = TaxdisModel::mdlDeleteTaxdis($table, $data);
			// var_dump($answer);

			if($answer == "ok"){

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