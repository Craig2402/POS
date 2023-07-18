<?php
require_once "connection.php";

class userModel{

	/*=============================================
	SHOW USER 
	=============================================*/

	static public function mdlShowUser($tableUsers, $item, $value){

		if($item != null){

			$stmt = connection::connect()->prepare("SELECT * FROM $tableUsers WHERE $item = :$item and deleted = 0");

			$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}
		else{
			$stmt = connection::connect()->prepare("SELECT * FROM $tableUsers WHERE deleted = 0");

			$stmt -> execute();

			return $stmt -> fetchAll();

			
		}

		$stmt -> close();

		$stmt = null;

	}
	static public function mdlShowAllUser($tableUsers, $item, $value){

		$stmt = connection::connect()->prepare("SELECT * FROM $tableUsers WHERE $item = :$item");

		$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);

		$stmt -> execute();

		return $stmt -> fetch();

	}


	/*=============================================
	ADD USER 
	=============================================*/	

	static public function mdlCreateUser($table, $data){

		$stmt = connection::connect()->prepare("INSERT INTO $table(name, username, userpassword, role, userphoto) VALUES (:name, :username, :userpassword, :role, :userphoto)");

		$stmt -> bindParam(":name", $data["name"], PDO::PARAM_STR);
		$stmt -> bindParam(":username", $data["username"], PDO::PARAM_STR);
		$stmt -> bindParam(":userpassword", $data["userpassword"], PDO::PARAM_STR);
		$stmt -> bindParam(":role", $data["role"], PDO::PARAM_STR);
		$stmt -> bindParam(":userphoto", $data["userphoto"], PDO::PARAM_STR);

		if ($stmt->execute()) {
			
			return 'ok';
		
		} else {
			
			return 'error';
		}
		
		$stmt -> close();

		$stmt = null;
	}


	/*=============================================
	EDIT USER 
	=============================================*/

	static public function mdlEditUser($table, $data){

		$stmt = connection::connect()->prepare("UPDATE $table set name = :name, userpassword = :userpassword, role = :role, userphoto = :userphoto, deleted = :deleted WHERE username = :username");

		$stmt -> bindParam(":name", $data["name"], PDO::PARAM_STR);
		$stmt -> bindParam(":username", $data["username"], PDO::PARAM_STR);
		$stmt -> bindParam(":userpassword", $data["userpassword"], PDO::PARAM_STR);
		$stmt -> bindParam(":role", $data["role"], PDO::PARAM_STR);
		$stmt -> bindParam(":userphoto", $data["userphoto"], PDO::PARAM_STR);
		$stmt -> bindParam(":deleted", $data["deleted"], PDO::PARAM_STR);

		if ($stmt->execute()) {
			
			return 'ok';
		
		} else {
			
			return 'error';
		
		}
		
		$stmt -> close();

		$stmt = null;
	}


	/*=============================================
	UPDATE USER 
	=============================================*/

	static public function mdlUpdateUser($table, $item1, $value1, $item2, $value2){

		$stmt = connection::connect()->prepare("UPDATE $table set $item1 = :$item1 WHERE $item2 = :$item2");

		$stmt -> bindParam(":".$item1, $value1, PDO::PARAM_STR);
		$stmt -> bindParam(":".$item2, $value2, PDO::PARAM_STR);

		if ($stmt->execute()) {
			
			return 'ok';
		
		} else {

			return 'error';
		
		}
		
		$stmt -> close();

		$stmt = null;
	}

	/*=============================================
	DELETE USER 
	=============================================*/	

	static public function mdlDeleteUser($table, $data){

		// $stmt = connection::connect()->prepare("DELETE FROM $table WHERE userId = :userId");

		// $stmt -> bindParam(":userId", $data, PDO::PARAM_STR);
		
		$stmt = connection::connect()->prepare("UPDATE $table SET deleted = :status WHERE userId = :userId");

		$stmt -> bindParam(":status", $data['status'], PDO::PARAM_INT);
		$stmt -> bindParam(":userId", $data['userId'], PDO::PARAM_STR);
		
		if ($stmt->execute()) {
			
			return 'ok';
		
		} else {

			return 'error';
		
		}
		
		$stmt -> close();

		$stmt = null;
	}

}