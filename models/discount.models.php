<?php


require_once "connection.php";

class DiscountModel{

	/*=============================================
	CREATE CATEGORY
	=============================================*/

	static public function mdlAddDiscount($table, $data){

		$stmt = connection::connect()->prepare("INSERT INTO $table(store_id, product, discount, amount, startdate, enddate) VALUES (:store_id, :product, :discount, :amount, :startdate, :enddate)");

		$stmt -> bindParam(":product", $data["product"], PDO::PARAM_STR);
		$stmt -> bindParam(":discount", $data["discount"], PDO::PARAM_STR);
		$stmt -> bindParam(":amount", $data["amount"], PDO::PARAM_STR);
		$stmt -> bindParam(":startdate", $data["startdate"], PDO::PARAM_STR);
		$stmt -> bindParam(":enddate", $data["enddate"], PDO::PARAM_STR);
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
	
	static public function mdlShowDiscount($table, $item, $value){

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
	EDIT DISCOUNT
	=============================================*/

	static public function mdlEditDiscount($table, $data){

		$stmt = connection::connect()->prepare("UPDATE $table SET discount = :discount, amount = :amount, startdate = :startdate, enddate = :enddate WHERE product = :barcode");

		$stmt -> bindParam(":barcode", $data["product"], PDO::PARAM_STR);
		$stmt -> bindParam(":discount", $data["discount"], PDO::PARAM_STR);
		$stmt -> bindParam(":amount", $data["amount"], PDO::PARAM_STR);
		$stmt -> bindParam(":startdate", $data["startdate"], PDO::PARAM_STR);
		$stmt -> bindParam(":enddate", $data["enddate"], PDO::PARAM_STR);

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

	static public function mdlDeleteDiscount($table, $data){

		$stmt = connection::connect()->prepare("DELETE FROM $table WHERE disId = :Id");

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