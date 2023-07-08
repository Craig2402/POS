<?php
require_once 'connection.php';

class notificationModel{
	/*=============================================
	ADD NOTIFICATIONS
	=============================================*/
    public static function mdlAddNotification($table, $data){

        try {

            $stmt = connection::connect()->prepare("INSERT INTO $table(message, create_date, notification_type, name) VALUES(:message, :datetime, :type, :name)");
    
            $stmt->bindParam(":message", $data["message"], PDO::PARAM_STR);
            $stmt->bindParam(":datetime", $data["datetime"], PDO::PARAM_STR);
            $stmt->bindParam(":type", $data["type"], PDO::PARAM_STR);
            $stmt->bindParam(":name", $data["name"], PDO::PARAM_STR);
    
            if ($stmt->execute()) {

                return "ok";

            } else {

                return "error";

            }

        } catch (PDOException $e) {

            if ($e->getCode() === "23000") { // Error code for duplicate entry

                return "duplicate";
                
            } else {

                error_log("Database error: " . $e->getMessage()); // Log the error for debugging purposes

                return "error";

            }
        } finally {

            $stmt->closeCursor();

        }

    }
    
	/*=============================================
	MARK A NOTIFICATIONS READ
	=============================================*/
    static public function mdlMarkNotificationsRead($table, $data){

        $stmt = connection::connect()->prepare("UPDATE $table SET viewed_by = CONCAT_WS(',', viewed_by, :viewed_by) WHERE notification_type = :notification_type_value");

        $stmt->bindParam(":viewed_by", $data["id"], PDO::PARAM_INT); // Bind as integer
        $stmt->bindParam(":notification_type_value", $data["type"], PDO::PARAM_STR);

        if ($stmt->execute()) {

            return "ok";

        } else {

            return "error";
            
        }

    }
	/*=============================================
	SHOW NOTIFICATIONS
	=============================================*/
	static public function mdlShowNotifications($table, $item, $value){

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

}