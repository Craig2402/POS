<?php


require_once "connection.php";

class OrdersModel{

    /*=============================================
	CREATE ORDER
	=============================================*/

	static public function mdlAddOrder($table, $data){

		$stmt = connection::connect()->prepare("INSERT INTO $table(supplier,products) VALUES(:supplier, :products)");

		$stmt->bindParam(":supplier", $data["supplier"],PDO::PARAM_STR);
		$stmt->bindParam(":products", $data["products"],PDO::PARAM_STR);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt -> close();

		$stmt = null;
		
	}

}