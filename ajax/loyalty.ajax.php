<?php

require_once "../controllers/loyalty.controller.php";
require_once "../models/loyalty.model.php";

class AjaxLoyalty{
    public $phoneNumber;
    public $LoyaltyConversionName;

    public function ajaxFetchPoints(){
        $phoneNumber = $this->phoneNumber;
        $item = "Phone";

        $answer = loyaltyController::ctrShowLoyaltyPoints($item, $phoneNumber, true);

		echo json_encode($answer);
    }
    
    public function ajaxFetchPointConversionValue(){
        $LoyaltyConversionName = $this->LoyaltyConversionName;
        $item = "SettingName";

        $answer = loyaltyController::ctrShowLoyaltyPointConversionValue($item, $LoyaltyConversionName);

		echo json_encode($answer);
    }

}
	if(isset($_POST["phoneNumber"])){

		$loyalty = new AjaxLoyalty();
		$loyalty -> phoneNumber = $_POST["phoneNumber"];
		$loyalty -> ajaxFetchPoints();

	}

    if(isset($_POST["LoyaltyConversionName"])){

		$loyalty = new AjaxLoyalty();
		$loyalty -> LoyaltyConversionName = $_POST["LoyaltyConversionName"];
		$loyalty -> ajaxFetchPointConversionValue();

	}