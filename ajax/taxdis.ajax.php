<?php

require_once "../controllers/taxdis.controller.php";
require_once "../models/taxdis.models.php";

class AjaxTaxdis{

	/*=============================================
	EDIT CATEGORY
	=============================================*/	

	public $idTaxdis;

	public function ajaxEditTax(){

		$item = "taxId";
		$valor = $this->idTaxdis;

		$answer = taxdisController::ctrShowTaxdis($item, $valor);

		echo json_encode($answer);

	}
}

/*=============================================
EDITAR CATEGORÍA
=============================================*/	
if(isset($_POST["idTaxdis"])){

	$taxdis = new AjaxTaxdis();
	$taxdis -> idTaxdis = $_POST["idTaxdis"];
	$taxdis -> ajaxEditTax();
}
