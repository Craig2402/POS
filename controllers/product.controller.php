<?php

class productController{
    static public function ctrCreateProducts(){
        if(isset($_POST['addproduct'])){
            if(preg_match('/^[0-9]+$/', $_POST["txtstock"]) &&	
			   preg_match('/^[0-9.]+$/', $_POST["txtpurchase"]) &&
			   preg_match('/^[0-9.]+$/', $_POST["txtsale"])){

		   		/*=============================================
				VALIDATE IMAGE
				=============================================*/

			   	$route = "views/img/products/default/anonymous.png";

			   	if(isset($_FILES["txtproductimage"]["tmp_name"])){

					list($width, $height) = getimagesize($_FILES["txtproductimage"]["tmp_name"]);

					$newWidth = 500;
					$newHeight = 500;

					/*=============================================
					we create the folder to save the picture
					=============================================*/

					$folder = "views/img/products/".$_POST["txtbarcode"];

					mkdir($folder, 0755);

					/*=============================================
					WE APPLY DEFAULT PHP FUNCTIONS ACCORDING TO THE IMAGE FORMAT
					=============================================*/

					if($_FILES["txtproductimage"]["type"] == "image/jpeg"){

						/*=============================================
						WE SAVE THE IMAGE IN THE FOLDER
						=============================================*/

						$random = mt_rand(100,999);

						$route = "views/img/products/".$_POST["txtbarcode"]."/".$random.".jpg";

						$origin = imagecreatefromjpeg($_FILES["txtproductimage"]["tmp_name"]);						

						$destiny = imagecreatetruecolor($newWidth, $newHeight);

						imagecopyresized($destiny, $origin, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

						imagejpeg($destiny, $route);

					}

					if($_FILES["txtproductimage"]["type"] == "image/png"){

						/*=============================================
						WE SAVE THE IMAGE IN THE FOLDER
						=============================================*/

						$random = mt_rand(100,999);

						$route = "views/img/products/".$_POST["txtbarcode"]."/".$random.".png";

						$origin = imagecreatefrompng($_FILES["txtproductimage"]["tmp_name"]);						

						$destiny = imagecreatetruecolor($newWidth, $newHeight);

						imagecopyresized($destiny, $origin, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

						imagepng($destiny, $route);

					}

				}

				$table = "products";

				$data = array("barcode" => $_POST["txtbarcode"],
							   "product" => $_POST["txtproductname"],
							   "idCategory" => $_POST["txtcategory"],
                               "description" => $_POST["txtdescription"],
							   "stock" => $_POST["txtstock"],
							   "purchaseprice" => $_POST["txtpurchase"],
							   "saleprice" => $_POST["txtsale"],
							   "image" => $route,
							   "taxId" => $_POST["txttaxcat"]);

				$answer = productModel::mdlAddProduct($table, $data);

				if($answer == "ok"){
					// Create an array with the data for the activity log entry
					$data = array(
						'UserID' => $_SESSION['userId'],
						'ActivityType' => 'Product Add',
						'ActivityDescription' => 'User ' . $_SESSION['username'] . ' added product ' .$data['product']. '.'
					);
					// Call the ctrCreateActivityLog() function
					activitylogController::ctrCreateActivityLog($data);

					echo'<script>

					Swal.fire({
							  icon: "success",
							  title: "Product added succesfully!",
							  showConfirmButton: true,
							  confirmButtonText: "Close"
							  }).then(function(result){
										if (result.value) {

										window.location = "products";

										}
									})

						</script>';

				}


			}else{

				echo'<script>

				Swal.fire({
						  icon: "error",
						  title: "Invalid parameters passed!",
						  showConfirmButton: true,
						  confirmButtonText: "Close"
						  }).then(function(result){
							if (result.value) {

							window.location = "products";

							}
						})

			  	</script>';
			}

		}

	}
	/*=============================================
	SHOW PRODUCTS
	=============================================*/

	static public function ctrShowProducts($item, $value, $order){

		$table = "products";

		$answer = productModel::mdlShowProducts($table, $item, $value, $order);

		return $answer;
	}

	
	/*=============================================
	EDIT PRODUCT
	=============================================*/

	static public function ctrEditProduct(){

		if(isset($_POST["editbarcode"])){

			if(preg_match('/^[0-9]+$/', $_POST["editstock"]) &&	
			   preg_match('/^[0-9.]+$/', $_POST["editpurchaseprice"]) &&
			   preg_match('/^[0-9.]+$/', $_POST["editsaleprice"])){

		   		/*=============================================
				VALIDATE IMAGE
				=============================================*/

			   	$route = $_POST["currentImage"];

			   	if(isset($_FILES["editImage"]["tmp_name"]) && !empty($_FILES["editImage"]["tmp_name"])){

					list($width, $height) = getimagesize($_FILES["editImage"]["tmp_name"]);

					$newWidth = 500;
					$newHeight = 500;

					/*=============================================
					WE CREATE THE FOLDER WHERE WE WILL SAVE THE PRODUCT IMAGE
					=============================================*/

					$folder = "views/img/products/".$_POST["editbarcode"];

					/*=============================================
					WE ASK IF WE HAVE ANOTHER PICTURE IN THE DB
					=============================================*/

					if(!empty($_POST["currentImage"]) && $_POST["currentImage"] != "views/img/products/default/anonymous.png"){

						unlink($_POST["currentImage"]);

					}else{

						mkdir($folder, 0755);	
					
					}
					
					/*=============================================
					WE APPLY DEFAULT PHP FUNCTIONS ACCORDING TO THE IMAGE FORMAT
					=============================================*/

					if($_FILES["editImage"]["type"] == "image/jpeg"){

						/*=============================================
						WE SAVE THE IMAGE IN THE FOLDER
						=============================================*/

						$random = mt_rand(100,999);

						$route = "views/img/products/".$_POST["editbarcode"]."/".$random.".jpg";

						$origin = imagecreatefromjpeg($_FILES["editImage"]["tmp_name"]);						

						$destiny = imagecreatetruecolor($newWidth, $newHeight);

						imagecopyresized($destiny, $origin, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

						imagejpeg($destiny, $route);

					}

					if($_FILES["editImage"]["type"] == "image/png"){

						/*=============================================
						WE SAVE THE IMAGE IN THE FOLDER
						=============================================*/

						$random = mt_rand(100,999);

						$route = "views/img/products/".$_POST["editbarcode"]."/".$random.".png";

						$origin = imagecreatefrompng($_FILES["editImage"]["tmp_name"]);

						$destiny = imagecreatetruecolor($newWidth, $newHeight);

						imagecopyresized($destiny, $origin, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

						imagepng($destiny, $route);

					}

				}

				$table = "products";

				$data = array("barcode" => $_POST["editbarcode"],
							   "product" => $_POST["editproductname"],
							   "idCategory" => $_POST["editcategory"],
                               "description" => $_POST["editdescription"],
							   "stock" => $_POST["editstock"],
							   "purchaseprice" => $_POST["editpurchaseprice"],
							   "saleprice" => $_POST["editsaleprice"],
							   "image" => $route);

				$answer = productModel::mdlEditProduct($table, $data);

				if($answer == "ok"){
					
					// Create an array with the data for the activity log entry
					$data = array(
						'UserID' => $_SESSION['userId'],
						'ActivityType' => 'Product Edit',
						'ActivityDescription' => 'User ' . $_SESSION['username'] . ' edited product ' .$data['product']. '.'
					);
					// Call the ctrCreateActivityLog() function
					activitylogController::ctrCreateActivityLog($data);

					echo'<script>

					Swal.fire({
							  icon: "success",
							  title: "The product has been edited",
							  showConfirmButton: true,
							  confirmButtonText: "Close"
							  }).then(function(result){
										if (result.value) {

										window.location = "products";

										}
									})

						</script>';

				}


			}else{

				echo'<script>

				Swal.fire({
						  icon: "error",
						  title: "The Product cannot be empty or have special characters!",
						  showConfirmButton: true,
						  confirmButtonText: "Close"
						  }).then(function(result){
							if (result.value) {

							window.location = "products";

							}
						})

			  	</script>';
			}

		}

	}

	/*=============================================
	DELETE PRODUCT
	=============================================*/
	static public function ctrDeleteProduct(){

		if(isset($_GET["barcodeProduct"])){

			$table ="products";
			$data = $_GET["barcodeProduct"];

			if($_GET["image"] != "" && $_GET["image"] != "views/img/products/default/anonymous.png"){

				unlink($_GET["image"]);
				rmdir('views/img/products/'.$_GET["barcodeProduct"]);

			}
			
			$item = "barcode";
			$value = $data;
			$order = "id";
			$loganswer = productModel::mdlShowProducts($table, $item, $value, $order);
			$product = $loganswer['product'];

			$answer = productModel::mdlDeleteProduct($table, $data);

			if($answer == "ok"){
				// Create an array with the data for the activity log entry
				$logdata = array(
					'UserID' => $_SESSION['userId'],
					'ActivityType' => 'Product Delete',
					'ActivityDescription' => 'User ' . $_SESSION['username'] . ' deleted product ' .$product. '.'
				);
				// Call the ctrCreateActivityLog() function
				activitylogController::ctrCreateActivityLog($logdata);

				echo'<script>

				Swal.fire({
					  icon: "success",
					  title: "The Product has been successfully deleted",
					  showConfirmButton: true,
					  confirmButtonText: "Close"
					  }).then(function(result){
								if (result.value) {

								window.location = "products";

								}
							})

				</script>';

			}		
		
		}

	}



	/*=============================================
	Adding TOTAL sales
	=============================================*/

	public static function ctrAddingTotalSales(){

		$table = "products";

		$answer = productModel::mdlAddingTotalSales($table);

		return $answer;

	}
	
	/*=============================================
	Adding to stock
	=============================================*/
	public static function ctrAddingStock(){

		if (isset($_POST["addStock"])) {
	
			$table = "products";
			$quantity = $_POST['astock'] ?? '';
			$barcode = $_POST["product"] ?? '';
	
			if (empty($quantity) || empty($barcode)) {
				echo '<script>
				Swal.fire({
					icon: "warning",
					title: "Please fill in all required fields",
					showConfirmButton: true,
					confirmButtonText: "Close"
				});
				</script>';
				return;
			}
	
			$answer = productModel::mdlAddingStock($table, $quantity, $barcode);
	
			if ($answer == "ok"){
				echo '<script>
				Swal.fire({
					icon: "success",
					title: "'.$quantity.' units have been added to the current stock quantity",
					showConfirmButton: true,
					confirmButtonText: "Close"
				}).then(function(result){
					if (result.value) {
						window.location = "stock";
					}
				});
				</script>';
			}	
		}
	}

}
?>
