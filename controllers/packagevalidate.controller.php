<?php

class packagevalidateController {
    
    /*=============================================
    VALIDATE ELEMENTS ACCORDING TO PACKAGE
    =============================================*/

    static public function ctrPackageValidate($element, $table, $countAll, $organisationcode) {

        /*=============================================
        fetch the customer details
        =============================================*/
        $organisation = packagevalidateModel::mdlfetchCustomerDetails($table, $organisationcode);

        $table = "packages";
        $item = "packageid";
        $packageid = $organisation[0]['packageid'];

        // fetch the package information
        $package = packagevalidateModel::mdlfetchPackageDetails($table, $item, $packageid);

        // check for the elemet for validation
        if ($element == "product") {
            // validate the products limit
            $productLimit = intval($package['products']);

            if ($package["products"] == "unlimited") {
                return true;
            } elseif ($productLimit > $countAll){
                return true;
            } else{
                return false;
            }
            
        } elseif ($element == "categories") {
            // validate the categories limit
            $categoryLimit = intval($package['categories']);

            if ($package["categories"] == "unlimited") {
                return true;
            } elseif ($categoryLimit > $countAll){
                return true;
            } else{
                return false;
            }
        }

    }

}