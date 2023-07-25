<?php

require_once "../controllers/product.controller.php";
require_once "../models/product.model.php";

class AjaxProducts{

	// /*=============================================
	// GENERATE CODE FROM ID CATEGORY
	// =============================================*/	

	// public $idCategory;

	// public function ajaxCreateProductCode(){

	// 	$item = "idCategory";
	// 	$value = $this->idCategory;
	// 	$order='id';

	// 	$answer = productController::ctrShowProducts($item, $value, $order);

	// 	echo json_encode($answer);

	// }

	/*=============================================
 	 EDIT PRODUCT
  	=============================================*/ 

  	public $barcodeProduct;
	public $data;
	
	public function ajaxEditProduct(){
		// Get product by id
		$item = "id";
		$value = $this->barcodeProduct;
		$order='id';
		$answer = productController::ctrShowProducts($item, $value, $order, false);
	
		echo json_encode($answer);
	}
	public function ajaxShowproducts(){
		// Get product by id
        $item = $this->data['item'];
        $value = $this->data['value'];
        $order= $this->data['order'];
        $answer = productController::ctrShowProducts($item, $value, $order, true);
    
        echo json_encode($answer);
	}

}

/*=============================================
EDIT PRODUCT
=============================================*/ 

    if (isset($_POST["item"]) && isset($_POST["value"]) && isset($_POST["order"])) {

        $products = new AjaxProducts();
        $products->data = array(
            'item' => $_POST["item"],
            'value' => $_POST["value"],
            'order' => $_POST["order"]
        );
        $products->ajaxShowproducts();
    }
	
	if(isset($_POST["barcodeProduct"])){

		$editProduct = new AjaxProducts();
		$editProduct -> barcodeProduct = $_POST["barcodeProduct"];
		$editProduct -> ajaxEditProduct();
	
	}




