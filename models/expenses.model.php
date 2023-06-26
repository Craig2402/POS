<?php
class ExpenseModel {
    /*=============================================
    ADDING EXPENSES
    =============================================*/
    static public function mdlAddExpense($table, $data) {
        $stmt = Connection::connect()->prepare("INSERT INTO $table (expense, expense_type, date, amount, receipt) VALUES (:expense, :expense_type, :date, :amount, :receipt)");

        $stmt->bindParam(":expense", $data["expense"], PDO::PARAM_STR);
        $stmt->bindParam(":expense_type", $data["expense_type"], PDO::PARAM_STR);
        $stmt->bindParam(":date", $data["date"], PDO::PARAM_STR);
        $stmt->bindParam(":amount", $data["amount"], PDO::PARAM_STR);
        $stmt->bindParam(":receipt", $data["receipt"], PDO::PARAM_STR);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
    }
     /*=============================================
    EDITING EXPENSES
    =============================================*/
    static public function mdlEditExpense($table, $data, $expenseId) {
        $stmt = Connection::connect()->prepare("UPDATE $table SET expense = :expense, expense_type = :expense_type, date = :date, amount = :amount, receipt = :receipt WHERE id = :expenseId");

        $stmt->bindParam(":expense", $data["expense"], PDO::PARAM_STR);
        $stmt->bindParam(":expense_type", $data["expense_type"], PDO::PARAM_STR);
        $stmt->bindParam(":date", $data["date"], PDO::PARAM_STR);
        $stmt->bindParam(":amount", $data["amount"], PDO::PARAM_STR);
        $stmt->bindParam(":receipt", $data["receipt"], PDO::PARAM_STR);
        $stmt->bindParam(":expenseId", $expenseId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
    }
    /*=============================================
    DELETING EXPENSES
    =============================================*/
    static public function mdlDeleteExpense($table, $expenseId) {
        $stmt = Connection::connect()->prepare("DELETE FROM $table WHERE id = :expenseId");

        $stmt->bindParam(":expenseId", $expenseId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
    }

    /*=============================================
    GETTING EXPENSE FILENAME
    =============================================*/
    static public function mdlGetExpenseFilename($table, $expenseId) {
        $stmt = Connection::connect()->prepare("SELECT receipt FROM $table WHERE id = :expenseId");

        $stmt->bindParam(":expenseId", $expenseId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['receipt'];
        } else {
            return false;
        }
    }
 
    static public function mdlShowExpenses($table,$item, $value) {
        if($item != null){

			$stmt = connection::connect()->prepare("SELECT * FROM $table WHERE $item = :$item");

			$stmt -> bindParam(":".$item, $value, PDO::PARAM_INT);

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
