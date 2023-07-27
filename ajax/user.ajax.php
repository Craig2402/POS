<?php

require_once "../controllers/user.controller.php";
require_once "../models/user.models.php";

class AjaxUsers{

	/*=============================================
	EDIT USER
	=============================================*/

	public $userId;

	public function ajaxEditUser(){

		$item = "userId";
		$value = $this->userId;

		$answer = userController::ctrShowUsers($item, $value);

		echo json_encode($answer);
	}


	/*=============================================
	ACTIVATE USER
	=============================================*/

	public $activateUser;
	public $activateId;	

	public function ajaxActivateUser(){

		$table = "users";
		$item1 = "status";
		$value1 = $this->activateUser;

		$item2 = "userId";
		$value2 = $this->activateId;

		$answer = userModel::mdlUpdateUser($table, $item1, $value1, $item2, $value2);


	}


	/*=============================================
	VALIDATE IF USER ALREADY EXISTS
	=============================================*/

	public $validateUser;

	public function ajaxValidateUser(){

		$item = "username";
		$value = $this->validateUser;

		$answer = userController::ctrShowUsers($item, $value);

		echo json_encode($answer);

	}

		/*=============================================
	VALIDATE IF EMAIL ALREADY EXISTS
	=============================================*/

	public $validateEmail;

	public function ajaxValidateEmail(){

		$item = "email";
		$value = $this->validateEmail;

		$answer = userController::ctrShowUsers($item, $value);

		echo json_encode($answer);

	}

}






/*=============================================
EDIT USER
=============================================*/

if (isset($_POST["userId"])) {

	$edit = new AjaxUsers();
	$edit -> userId = $_POST["userId"];
	$edit -> ajaxEditUser();
}

/*=============================================
ACTIVATE USER
=============================================*/

if (isset($_POST["activateUser"])) {

	$activateUser = new AjaxUsers();
	$activateUser -> activateUser = $_POST["activateUser"];
	$activateUser -> activateId = $_POST["activateId"];
	$activateUser -> ajaxActivateUser();
}


/*=============================================
VALIDATE IF USER ALREADY EXISTS
=============================================*/


if (isset($_POST["validateUser"])) {

	$valUser = new AjaxUsers();
	$valUser -> validateUser = $_POST["validateUser"];
	$valUser -> ajaxValidateUser();
}


if (isset($_POST["validateEmail"])) {

	$valEmail = new AjaxUsers();
	$valEmail -> validateEmail = $_POST["validateEmail"];
	$valEmail -> ajaxValidateEmail();
}
