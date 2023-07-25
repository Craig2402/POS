<?php


require_once "connection.php";

class OrdersModel{

    /*=============================================
	CREATE ORDER
	=============================================*/

	static public function mdlAddOrder($table, $data){

		$stmt = connection::connect()->prepare("INSERT INTO $table(orderid, supplier, products, total, status, store_id) VALUES(:orderid, :supplier, :products, :total, :status, :store_id)");

		$stmt->bindParam(":orderid", $data["orderid"],PDO::PARAM_STR);
		$stmt->bindParam(":supplier", $data["supplier"],PDO::PARAM_STR);
		$stmt->bindParam(":products", $data["products"],PDO::PARAM_STR);
		$stmt->bindParam(":total", $data["total"],PDO::PARAM_STR);
		$stmt->bindParam(":status", $data["status"],PDO::PARAM_INT);
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
	SHOW ORDERS
	=============================================*/
	
    static public function mdlShowOrders($table, $item, $value){

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
    EDIT ORDERS
    =============================================*/

	static public function mdlEditOrder($table, $data){

		$stmt = connection::connect()->prepare("UPDATE $table SET status = :status WHERE orderid = :id");

		$stmt->bindParam(":status", $data["status"], PDO::PARAM_STR);
		$stmt->bindParam(":id", $data["id"],PDO::PARAM_STR);
		

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt -> close();

		$stmt = null;

	}

}