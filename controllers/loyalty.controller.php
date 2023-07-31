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

    }