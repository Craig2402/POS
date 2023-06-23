<?php
require_once 'connection.php';

class supplierModel{
    /*=============================================
	ADDING PRODUCT
	=============================================*/
	static public function mdlAddsSupplier($table, $data){

		$stmt = connection::connect()->prepare("INSERT INTO $table(name,address, email, contact) VALUES(:name, :address, :email, :contact)");

		$stmt->bindParam(":name", $data["name"],PDO::PARAM_STR);
		$stmt->bindParam(":address", $data["address"],PDO::PARAM_STR);
        $stmt->bindParam(":email", $data["email"],PDO::PARAM_STR);
        $stmt->bindParam(":contact", $data["contact"],PDO::PARAM_STR);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

	}
    	
	/*=============================================
	SHOW SUPPLIERS
	=============================================*/
	static public function mdlShowSuppliers($table, $item, $value){

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

	}

}