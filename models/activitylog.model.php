<?php

require_once "connection.php";
class activitylogModel{

    /*=============================================
    CREATE ACTIVITYLOG
    =============================================*/
    public static function mdlCreateActivityLog($table, $data){

		$stmt = connection::connect()->prepare("INSERT INTO $table(UserID, ActivityType, ActivityDescription, itemID, store_id) VALUES (:UserID, :ActivityType, :ActivityDescription, :itemID, :storeid)");

		$stmt -> bindParam(":UserID", $data['UserID'], PDO::PARAM_STR);
		$stmt -> bindParam(":ActivityType", $data['ActivityType'], PDO::PARAM_STR);
		$stmt -> bindParam(":ActivityDescription", $data['ActivityDescription'], PDO::PARAM_STR);
		$stmt -> bindParam(":itemID", $data['itemID'], PDO::PARAM_STR);
		$stmt -> bindParam(":storeid", $data['storeid'], PDO::PARAM_STR);

		if ($stmt->execute()) {

			// Close the statement and set it to null
			$stmt->closeCursor();

			$stmt = null;

			return 'ok';
			
		} else {

			// Close the statement and set it to null
			$stmt->closeCursor();

			$stmt = null;

			return 'error';

		}

    }

}