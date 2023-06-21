<?php
require_once 'connection.php';

class productModel{
    /*=============================================
	ADDING PRODUCT
	=============================================*/
	static public function mdlAddProduct($table, $data){

		$stmt = connection::connect()->prepare("INSERT INTO $table(barcode,idCategory, product, description, stock, purchaseprice, saleprice, image ,taxId) VALUES(:barcode, :idCategory, :product,  :description, :stock, :purchaseprice, :saleprice, :image , :taxId)");

		$stmt->bindParam(":barcode", $data["barcode"],PDO::PARAM_STR);
		$stmt->bindParam(":idCategory", $data["idCategory"],PDO::PARAM_STR);
        $stmt->bindParam(":product", $data["product"],PDO::PARAM_STR);
        $stmt->bindParam(":description", $data["description"],PDO::PARAM_STR);
        $stmt->bindParam(":stock", $data["stock"],PDO::PARAM_STR);
        $stmt->bindParam(":purchaseprice", $data["purchaseprice"],PDO::PARAM_STR);
        $stmt->bindParam(":saleprice", $data["saleprice"],PDO::PARAM_STR);
        $stmt->bindParam(":image", $data["image"],PDO::PARAM_STR);
        $stmt->bindParam(":taxId", $data["taxId"],PDO::PARAM_STR);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt->close();
		$stmt = null;

	}
	
	/*=============================================
	SHOW PRODUCT
	=============================================*/
	static public function mdlShowProducts($table, $item, $value, $order){

		if($item != null){

			$stmt = connection::connect()->prepare("SELECT * FROM $table WHERE $item = :$item");

			$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}
		else{
			$stmt = connection::connect()->prepare("SELECT * FROM $table order by $order desc");

			$stmt -> execute();

			return $stmt -> fetchAll();

			
		}

		$stmt -> close();

		$stmt = null;

	}
	/*=============================================
	EDITING PRODUCT
	=============================================*/
	static public function mdlEditProduct($table, $data){

		$stmt = connection::connect()->prepare("UPDATE $table SET idCategory = :idCategory, product= :product, description = :description, image = :image, stock = :stock, purchaseprice = :purchaseprice, saleprice = :saleprice WHERE barcode = :barcode");

		$stmt->bindParam(":idCategory", $data["idCategory"], PDO::PARAM_INT);
		$stmt->bindParam(":barcode", $data["barcode"], PDO::PARAM_STR);
		$stmt->bindParam(":product", $data["product"],PDO::PARAM_STR);
        $stmt->bindParam(":description", $data["description"],PDO::PARAM_STR);
        $stmt->bindParam(":stock", $data["stock"],PDO::PARAM_STR);
        $stmt->bindParam(":purchaseprice", $data["purchaseprice"],PDO::PARAM_STR);
        $stmt->bindParam(":saleprice", $data["saleprice"],PDO::PARAM_STR);
        $stmt->bindParam(":image", $data["image"],PDO::PARAM_STR);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt->close();
		$stmt = null;

	}

	/*=============================================
	DELETING PRODUCT
	=============================================*/

	static public function mdlDeleteProduct($table, $data){

		$stmt = connection::connect()->prepare("DELETE FROM $table WHERE barcode = :id");

		$stmt -> bindParam(":id", $data, PDO::PARAM_INT);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;

	}
		
	/*=============================================
	UPDATE PRODUCT
	=============================================*/

	static public function mdlUpdateProduct($table, $item1, $value1, $value){

		$stmt = Connection::connect()->prepare("UPDATE $table SET $item1 = :$item1 WHERE id = :id");

		$stmt -> bindParam(":".$item1, $value1, PDO::PARAM_STR);
		$stmt -> bindParam(":id", $value, PDO::PARAM_STR);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;

	}
	/*=============================================
	SHOW ADDING OF THE SALES
	=============================================*/	

	static public function mdlAddingTotalSales($table){

		$stmt = connection::connect()->prepare("SELECT SUM(sales) as total FROM $table");

		$stmt -> execute();

		return $stmt -> fetch();

		$stmt -> close();

		$stmt = null;
	}

	/*=============================================
	Adding to stock
	=============================================*/	

	static public function mdlAddingStock($table ,$quantity, $barcode){

		$stmt = connection::connect()->prepare("UPDATE $table SET stock = stock + :quantity WHERE barcode = :barcode");
		
		$stmt -> bindParam(":quantity", $quantity, PDO::PARAM_INT);
		$stmt -> bindParam(":barcode", $barcode, PDO::PARAM_INT);
		
		if($stmt -> execute()){

			return "ok";
			
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;
	}
}
?>