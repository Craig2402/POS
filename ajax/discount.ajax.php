<?php

require_once "../controllers/discount.controller.php";
require_once "../models/discount.models.php";

class AjaxDiscountd{

	/*=============================================
	EDIT Discount
	=============================================*/	

	public $idDiscount;
	public $barcode;

	public function ajaxEditDiscount(){

		if ($this->idDiscount != "") {
			
			$item = "disId";
			$value = $this->idDiscount;
	
			$answer = discountController::ctrShowDiscount($item, $value);
	
			echo json_encode($answer);

		} else {

			$item = "product";
			$value = $this->barcode;
	
			$answer = discountController::ctrShowDiscount($item, $value);
	
			echo json_encode($answer);
			
		}
		



	}
}

/*=============================================
EDIT Discount
=============================================*/	
if(isset($_POST["idDiscount"])){

	$Discount = new AjaxDiscountd();
	$Discount -> idDiscount = $_POST["idDiscount"];
	$Discount -> ajaxEditDiscount();
}

if(isset($_GET["barcode"])){

	$getDiscount = new AjaxDiscountd();
	$getDiscount -> barcode = $_GET["barcode"];
	$getDiscount -> ajaxEditDiscount();
}
