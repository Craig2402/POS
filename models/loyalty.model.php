<?php

require_once "connection.php";

class LoyaltyModel{

	static public function mdlShowLoyaltyValue($table){

        $stmt = connection::connect()->prepare("SELECT * FROM $table");

        $stmt -> execute();

        return $stmt -> fetchAll();

        $stmt -> closeCursor();

        $stmt = null;

    }

	static public function mdlAddLoyaltyPoints($table, $data){

        $stmt = connection::connect()->prepare("INSERT INTO `$table` (Phone, PointsEarned) VALUES (:Phone, :PointsEarned) ON DUPLICATE KEY UPDATE PointsEarned = PointsEarned + VALUES(PointsEarned);");

        $stmt->bindParam(':Phone', $data['Phone']);
        $stmt->bindParam(':PointsEarned', $data['PointsEarned']);

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

}
