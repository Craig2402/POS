<?php

require_once "connection.php";
class activitylogModel{

    /*=============================================
    CREATE ACTIVITYLOG
    =============================================*/
    public static function mdlCreateActivityLog($table, $data){

		$stmt = connection::connect()->prepare("INSERT INTO $table(UserID, ActivityType, ActivityDescription) VALUES (:UserID, :ActivityType, :ActivityDescription)");

		$stmt -> bindParam(":UserID", $data['UserID'], PDO::PARAM_STR);
		$stmt -> bindParam(":ActivityType", $data['ActivityType'], PDO::PARAM_STR);
		$stmt -> bindParam(":ActivityDescription", $data['ActivityDescription'], PDO::PARAM_STR);

		if ($stmt->execute()) {

			return 'ok';

		} else {

			return 'error';

		}

		$stmt -> close();

		$stmt = null;

    }

}