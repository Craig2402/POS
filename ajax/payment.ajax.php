<?php

require_once "../controllers/payments.controller.php";
require_once "../models/payment.model.php";

class AjaxInvoices{

	/*=============================================
	GENERATE CODE FROM ID CATEGORY
	=============================================*/	

	public $idInvoice;

	public function ajaxAddPayment(){

		$item = "invoiceId";
		$value = $this->idInvoice;

		$answer = PaymentController::ctrShowInvoices($item, $value);

		echo json_encode($answer);

	}

}

/*=============================================
MAKE PAYMENT
=============================================*/ 

if(isset($_POST["idInvoice"])){

    $addPayment = new AjaxInvoices();
    $addPayment -> idInvoice = $_POST["idInvoice"];
    $addPayment -> ajaxAddPayment();
  
  }
  
