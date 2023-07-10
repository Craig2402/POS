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
}
?>
