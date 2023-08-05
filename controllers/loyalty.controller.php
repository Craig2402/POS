<?php
    class loyaltyController{
        /*=============================================
        SHOW LOYALTY VALUE
        =============================================*/
    
        static public function ctrShowLoyaltyValue(){
    
            $table = "loyaltysettings";
    
            $answer = LoyaltyModel::mdlShowLoyaltyValue($table);
    
            return $answer;
        }

        /*=============================================
        ADD LOYALTY POINTs
        =============================================*/
    
        static public function ctrAddLoyaltyPoints($data){
    
            $table = "loyalty-points";
    
            $answer = LoyaltyModel::mdlAddLoyaltyPoints($table, $data);
    
            return $answer;
        }


        /*=============================================
        SHOW LOYALTY POINTs
        =============================================*/

        static public function ctrShowLoyaltyPoints($item,$value, $fetchAll=false){

            $table = "loyalty-points";

            if ($fetchAll) {

                $answer = LoyaltyModel::mdlShowAllLoyaltyPoints($table, $item, $value);

            } else {
    
                $answer = LoyaltyModel::mdlShowLoyaltyPoints($table, $item, $value);
                
            }
    
            return $answer;
        }


        /*=============================================
        SHOW LOYALTY POINT CONVERSION VALUE
        =============================================*/
        static public function ctrShowLoyaltyPointConversionValue($item, $value){

            $table = "loyaltysettings";
    
            $answer = LoyaltyModel::mdlShowLoyaltyPointConversionValue($table, $item, $value);
    
            return $answer;

        }

    }