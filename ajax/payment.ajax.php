<?php

require_once "../controllers/payments.controller.php";
require_once "../models/payment.model.php";

class AjaxInvoices{

	/*=============================================
	GENERATE CODE FROM ID CATEGORY
	=============================================*/	

	public $idInvoice;
	public $invoiceid;

	public function ajaxAddPayment(){

		$item = "invoiceId";
		$value = $this->idInvoice;

		$answer = PaymentController::ctrShowInvoices($item, $value);

		echo json_encode($answer);

	}


	public function ajaxFetchPayment(){

		$item = "invoiceId";
		$value = $this->invoiceid;

		$answer = PaymentController::ctrfetchGroupedPayments($item, $value);

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

if(isset($_POST["invoiceid"])){

    $fetchPayment = new AjaxInvoices();
    $fetchPayment -> invoiceid = $_POST["invoiceid"];
    $fetchPayment -> ajaxFetchPayment();
  
}
  
