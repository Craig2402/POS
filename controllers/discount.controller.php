<?php

 class discountController{

 	/*=============================================
	CREATE CATEGORY
	=============================================*/
	
	static public function ctrCreateDiscount(){

		if(isset($_POST['adddiscount'])){


            $table = 'discount';

			$data = array("product" => $_POST["product"],
                            "discount" => $_POST["discountname"],
                            "amount" => $_POST["discountamount"],
                            "startdate" => $_POST["startdate"],
                            "enddate" => $_POST["enddate"]);

							// var_dump($data);

            $answer = DiscountModel::mdlAddDiscount($table, $data);
            // var_dump($answer);

            if($answer == 'ok'){

                echo '<script>
                    
                Swal.fire({
                        icon: "success",
                        title: "Discount has been successfully saved ",
                        showConfirmButton: true,
                        confirmButtonText: "Close"

                        }).then(function(result){
                            if (result.value) {

                                window.location = "discount";

                            }
                        });
                    
                </script>';
            }
				
		}
	}

	/*=============================================
	SHOW CATEGORIES
	=============================================*/

	static public function ctrShowDiscount($item, $value){

		$table = "discount";

		$answer = DiscountModel::mdlShowDiscount($table, $item, $value);

		return $answer;
	}

	/*=============================================
	EDIT CATEGORY
	=============================================*/

	static public function ctrEditDiscount(){

		if(isset($_POST["editdiscount"])){

			$table = "discount";

			$data = array("product" => $_POST["barcode"],
							"discount" => $_POST["editdiscountname"],
                            "amount" => $_POST["editdiscountamount"],
                            "startdate" => $_POST["editstartdate"],
                            "enddate" => $_POST["editenddate"],
                            "status" => 0);

			$answer = DiscountModel::mdlEditDiscount($table, $data);
			// var_dump($answer);

			if($answer == "ok"){

				echo'<script>

				Swal.fire({
						icon: "success",
						title: "Discount has been successfully saved",
						showConfirmButton: true,
						confirmButtonText: "Close"
						}).then(function(result){
								if (result.value) {

								window.location = "discount";

								}
							})

				</script>';

			}

		}

	}

	/*=============================================
	DELETE CATEGORY
	=============================================*/

	static public function ctrDeleteDiscount(){

		if(isset($_GET["idDiscount"])){

			$table ="discount";
			$data = $_GET["idDiscount"];

			$answer = DiscountModel::mdlDeleteDiscount($table, $data);
			var_dump($answer);

			if($answer == "ok"){

				echo'<script>

                Swal.fire({
						  icon: "success",
						  title: "The discount has been successfully deleted",
						  showConfirmButton: true,
						  confirmButtonText: "Close"
						  }).then(function(result){
									if (result.value) {

										window.location = "discount";

									}
								})

					</script>';
			}
		
		}
		
	}

}