<?php

require_once "../controllers/categories.controller.php";
require_once "../models/categories.models.php";

class AjaxCategories{

	/*=============================================
	EDIT CATEGORY
	=============================================*/	

	public $idCategory;

	public function ajaxEditCategory(){

		$item = "id";
		$valor = $this->idCategory;

		$answer = categoriesController::ctrShowCategories($item, $valor);

		echo json_encode($answer);

	}
}

/*=============================================
EDITAR CATEGORÍA
=============================================*/	
if(isset($_POST["idCategory"])){

	$category = new AjaxCategories();
	$category -> idCategory = $_POST["idCategory"];
	$category -> ajaxEditCategory();
}
