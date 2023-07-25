<?php
require_once 'connection.php';

class supplierModel{
    /*=============================================
	ADDING SUPPLIERS
	=============================================*/
	static public function mdlAddsSupplier($table, $data){

		$stmt = connection::connect()->prepare("INSERT INTO $table(name,address, email, contact, store_id) VALUES(:name, :address, :email, :contact, :store_id)");

		$stmt->bindParam(":name", $data["name"],PDO::PARAM_STR);
		$stmt->bindParam(":address", $data["address"],PDO::PARAM_STR);
        $stmt->bindParam(":email", $data["email"],PDO::PARAM_STR);
        $stmt->bindParam(":contact", $data["contact"],PDO::PARAM_STR);
		$stmt -> bindParam(":store_id", $data['storeid'], PDO::PARAM_STR);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt -> close();

		$stmt = null;

	}
    	
	/*=============================================
	SHOW SUPPLIERS
	=============================================*/
	static public function mdlShowSuppliers($table, $item, $value){

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
	EDITING SUPPLIERS
	=============================================*/
	static public function mdlEditSupplier($table, $data){

		$stmt = connection::connect()->prepare("UPDATE $table SET name = :name, address= :address, email = :email, contact = :contact WHERE supplierid = :id");

		$stmt->bindParam(":name", $data["name"], PDO::PARAM_STR);
		$stmt->bindParam(":address", $data["address"], PDO::PARAM_STR);
		$stmt->bindParam(":email", $data["email"],PDO::PARAM_STR);
        $stmt->bindParam(":contact", $data["contact"],PDO::PARAM_STR);
        $stmt->bindParam(":id", $data["supplierid"],PDO::PARAM_INT);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	DELETING SUPPLIERS
	=============================================*/

	static public function mdlDeleteSupplier($table, $data){

		$stmt = connection::connect()->prepare("DELETE FROM $table WHERE supplierid = :id");

		$stmt -> bindParam(":id", $data, PDO::PARAM_INT);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;

	}

}