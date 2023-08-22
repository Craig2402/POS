<?php

class activitylogController {

    /*=============================================
    CREATE ACTIVITYLOG
    =============================================*/
    public static function ctrCreateActivityLog($data){
        if ($data) {

            $table = "UserActivityLog";

            $answer = activitylogModel::mdlCreateActivityLog($table, $data);

            return $answer;
            
        }
    }
    
    /*=============================================
    FETCH ACTIVITYLOG
    =============================================*/
    public static function ctrFetchActivityLog($item, $value){
        
        $table = "UserActivityLog";

        $answer = activitylogModel::mdlFetchActivityLog($table, $item, $value);

        return $answer;

    }
}
?>
