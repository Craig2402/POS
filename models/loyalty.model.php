<?php

require_once "connection.php";

class LoyaltyModel{
	/*=============================================
	SHOW LOYALTY VALUE
	=============================================*/

	static public function mdlShowLoyaltyValue($table){

        $stmt = connection::connect()->prepare("SELECT * FROM $table");

        $stmt -> execute();

        return $stmt -> fetchAll();

        $stmt -> closeCursor();

        $stmt = null;

    }

	/*=============================================
	ADD LOYALTY POINTs
	=============================================*/

	static public function mdlAddLoyaltyPoints($table, $data){

        $stmt = connection::connect()->prepare("INSERT INTO `$table` (pointId, Phone, PointsEarned, PointsRedeemed) VALUES (:loyaltyid, :Phone, :PointsEarned, :PointsRedeemed)");

        $stmt->bindParam(':loyaltyid', $data['loyaltyid']);
        $stmt->bindParam(':Phone', $data['Phone']);
        $stmt->bindParam(':PointsEarned', $data['PointsEarned']);
        $stmt->bindParam(':PointsRedeemed', $data['PointsRedeemed']);

		if ($stmt->execute()) {

			// Close the statement and set it to null
			$stmt->closeCursor();

			$stmt = null;

			return true;
			
		} else {

			// Close the statement and set it to null
			$stmt->closeCursor();

			$stmt = null;

			return false;

		}

    }


	/*=============================================
	SHOW LOYALTY POINTs
	=============================================*/
	static public function mdlShowLoyaltyPoints($table, $item, $value){

        $stmt = connection::connect()->prepare("SELECT * FROM `$table` WHERE $item =:$item");

		$stmt->bindParam(':'.$item, $value, PDO::PARAM_INT);

        $stmt -> execute();

        return $stmt -> fetch();

        $stmt -> closeCursor();

        $stmt = null;

    }
    static public function mdlShowAllLoyaltyPoints($table, $item, $value){

        $stmt = connection::connect()->prepare("SELECT * FROM `$table` WHERE $item =:$item");

		$stmt->bindParam(':'.$item, $value, PDO::PARAM_INT);

        $stmt -> execute();

        return $stmt -> fetchAll();

        $stmt -> closeCursor();

        $stmt = null;

    }

	/*=============================================
	SHOW LOYALTY POINT CONVERSION VALUE
	=============================================*/
	static public function mdlShowLoyaltyPointConversionValue($table, $item, $value){

        $stmt = connection::connect()->prepare("SELECT * FROM `$table` WHERE $item =:$item");

		$stmt->bindParam(':'.$item, $value, PDO::PARAM_INT);

        $stmt -> execute();

        return $stmt -> fetch();

        $stmt -> closeCursor();

        $stmt = null;

	}

}
