<?php


require_once "connection.php";

class TaxModel{

	/*=============================================
	CREATE CATEGORY
	=============================================*/

	static public function mdlAddTax($table, $data){

		$stmt = connection::connect()->prepare("INSERT INTO $table(taxId, store_id, VAT, VATName) VALUES (:taxid, :store_id, :VAT, :discount)");

		$stmt->bindParam(":taxid", $data["taxid"],PDO::PARAM_STR);
		$stmt->bindParam(":VAT", $data["VAT"],PDO::PARAM_STR);
		$stmt->bindParam(":discount", $data["discount"],PDO::PARAM_STR);
		$stmt -> bindParam(":store_id", $data['storeid'], PDO::PARAM_STR);

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
	
	static public function mdlShowTax($table, $item, $value){

		if ($item == "store_id"){ 

			$stmt = connection::connect()->prepare("SELECT * FROM $table WHERE $item = :$item");

			$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetchAll();
			
		} elseif($item != null){

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

	static public function mdlEditTax($table, $data){

		$stmt = connection::connect()->prepare("UPDATE $table SET VAT = :VAT, VATName = :VATName  WHERE taxId = :taxId");

		$stmt->bindparam("taxId", $data["taxId"], PDO::PARAM_STR);
		$stmt->bindParam(":VAT", $data["VAT"],PDO::PARAM_STR);
		$stmt->bindParam(":VATName", $data["VATName"],PDO::PARAM_STR);

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

	static public function mdlDeleteTax($table, $data){

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