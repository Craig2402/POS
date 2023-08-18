<?php

require_once "../controllers/payments.controller.php";
require_once "../models/payment.model.php";

class AjaxInvoices{

	/*=============================================
	GENERATE CODE FROM ID CATEGORY
	=============================================*/	

	public $idInvoice;
	public $invoiceid;
	public $receiptNumber;
	public $paymentid;

	public function ajaxAddPayment(){

		$item = "invoiceId";
		$value = $this->idInvoice;

		$answer = PaymentController::ctrShowInvoices($item, $value);

		echo json_encode($answer);

	}


	public function ajaxFetchPayments(){

		$item = "invoiceId";
		$value = $this->invoiceid;

		$answer = PaymentController::ctrfetchGroupedPayments($item, $value);

		echo json_encode($answer);

	}

	public function ajaxFetchPayment(){

		$item = "receiptNumber";
		$value = $this->receiptNumber;

		$answer = PaymentController::ctrShowPayments($item, $value);

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
    $fetchPayment -> ajaxFetchPayments();
  
}

if(isset($_POST["receiptNumber"])){

    $fetchPayment = new AjaxInvoices();
    $fetchPayment -> receiptNumber = $_POST["receiptNumber"];
    $fetchPayment -> ajaxFetchPayment();
  
}

  
