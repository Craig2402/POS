<?php


require_once "connection.php";

class TaxdisModel{

	/*=============================================
	CREATE CATEGORY
	=============================================*/

	static public function mdlAddTaxdis($table, $data){

		$stmt = connection::connect()->prepare("INSERT INTO $table(VAT, VATName) VALUES (:VAT, :discount)");

		$stmt->bindParam(":VAT", $data["VAT"],PDO::PARAM_STR);
		$stmt->bindParam(":discount", $data["discount"],PDO::PARAM_STR);

		if ($stmt->execute()) {

			return 'ok';

		} else {

			return 'error';

		}
		
		$stmt -> close();

		$stmt = null;
	}

	/*=============================================
	SHOW CATEGORY 
	=============================================*/
	
	static public function mdlShowTaxdis($table, $item, $value){

		if($item != null){

			$stmt = connection::connect()->prepare("SELECT * FROM $table WHERE $item = :$item");

			$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}
		else{
			$stmt = connection::connect()->prepare("SELECT * FROM $table");

			$stmt -> execute();

			return $stmt -> fetchAll();

		}

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	EDIT CATEGORY
	=============================================*/

	static public function mdlEditTaxdis($table, $data){

		$stmt = connection::connect()->prepare("UPDATE $table SET VAT = :VAT, VATName = :discount  WHERE taxId = :taxId");

		$stmt->bindparam("taxId", $data["taxId"], PDO::PARAM_STR);
		$stmt->bindParam(":VAT", $data["VAT"],PDO::PARAM_STR);
		$stmt->bindParam(":discount", $data["discount"],PDO::PARAM_STR);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt->close();
		$stmt = null;

	}

	/*=============================================
	DELETE CATEGORY
	=============================================*/

	static public function mdlDeleteTaxdis($table, $data){

		$stmt = connection::connect()->prepare("DELETE FROM $table WHERE taxId = :Id");

		$stmt -> bindParam(":Id", $data, PDO::PARAM_INT);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;

	}
}