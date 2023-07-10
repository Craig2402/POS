<?php
    session_start();
    require_once "../controllers/activitylog.controller.php";
    require_once "../models/activitylog.model.php";

    class AjaxActivityLog{

        public $barcodeProduct;

        public function ajaxCreateLog(){

            $barcode = $this->barcodeProduct;
            
            // Create an array with the data for the activity log entry
            $logdata = array(
                'UserID' => $_SESSION['userId'],
                'ActivityType' => 'Product',
                'ActivityDescription' => 'User ' . $_SESSION['username'] . ' viewed product.',
                'itemID' => $barcode
            );
            // Call the ctrCreateActivityLog() function
            $answer = activitylogController::ctrCreateActivityLog($logdata);

            echo json_encode($answer);


        }

    }
    if(isset($_POST["barcodeProduct"])){

        $log = new AjaxActivityLog();
        $log -> barcodeProduct = $_POST["barcodeProduct"];
        $log -> ajaxCreateLog();

    }