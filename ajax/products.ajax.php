<?php

require_once "../controllers/product.controller.php";
require_once "../models/product.model.php";

class AjaxProducts{

	/*=============================================
	GENERATE CODE FROM ID CATEGORY
	=============================================*/	

	public $idCategory;

	public function ajaxCreateProductCode(){

		$item = "idCategory";
		$value = $this->idCategory;
		$order='id';

		$answer = productController::ctrShowProducts($item, $value, $order);

		echo json_encode($answer);

	}

	/*=============================================
 	 EDIT PRODUCT
  	=============================================*/ 

  	public $barcodeProduct;
	
	public function ajaxEditProduct(){
		// Get product by id
		$item = "barcode";
		$value = $this->barcodeProduct;
		$order='id';
		$answer = productController::ctrShowProducts($item, $value, $order);
	
		echo json_encode($answer);
	}

}

/*=============================================
GENERATE CODE FROM ID CATEGORY
=============================================*/	

// if(isset($_POST["idCategory"])){

// 	$productCode = new AjaxProducts();
// 	$productCode -> idCategory = $_POST["idCategory"];
// 	$productCode -> ajaxCreateProductCode();

// }

/*=============================================
EDIT PRODUCT
=============================================*/ 

if(isset($_POST["barcodeProduct"])){

  $editProduct = new AjaxProducts();
  $editProduct -> barcodeProduct = $_POST["barcodeProduct"];
  $editProduct -> ajaxEditProduct();

}



