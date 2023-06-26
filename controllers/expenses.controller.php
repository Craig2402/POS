<?php
class expenseController{
 /*=============================================
    ADD EXPENSE
    =============================================*/

    static public function ctrAddExpense() {

        if (isset($_POST['addExpense'])) {

            $table = "expenses";

            $expenseName = $_POST["expense"];
            $date = $_POST["date"];
            $fileName = $expenseName . '_' . $date . '_' . time(); // Adding a timestamp to ensure uniqueness

            $data = array(
                "expense" => $expenseName,
                "expense_type" => $_POST["expenseType"],
                "date" => $date,
                "amount" => $_POST["amount"],
                "receipt" => $fileName
            );

            $answer = ExpenseModel::mdlAddExpense($table, $data);

            if ($answer == "ok") {

                // Upload file
                $targetDirectory = "views/expenses/reciepts/";
                $targetFilePath = $targetDirectory . $fileName;
                move_uploaded_file($_FILES["receipt"]["tmp_name"], $targetFilePath);

                echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "Expense added successfully!",
                        showConfirmButton: true,
                        confirmButtonText: "Close"
                    }).then(function(result) {
                        if (result.value) {
                            window.location = "expenses";
                        }
                    });
                </script>';

            } else {
                // Error handler if data is not saved in database
                echo '<div class="alert alert-danger">Error saving the expense. Please try again.</div>';
            }
        }
    }
    static public function ctrEditExpense() {

        if (isset($_POST['editExpense'])) {

            $table = "expenses";
            $expenseId = $_POST["expenseId"];
            $expenseName = $_POST["expense"];
            $expenseType = $_POST["expenseType"];
            $date = $_POST["date"];
            $amount = $_POST["amount"];
            $receipt = $_FILES["receipt"]["name"];

            $data = array(
                "expense" => $expenseName,
                "expense_type" => $expenseType,
                "date" => $date,
                "amount" => $amount,
                "receipt" => $receipt
            );

            $answer = ExpenseModel::mdlEditExpense($table, $data, $expenseId);

            if ($answer == "ok") {
                // Upload file
                $targetDirectory = "views/expenses/reciepts/";
                $targetFilePath = $targetDirectory . basename($_FILES["receipt"]["name"]);
                move_uploaded_file($_FILES["receipt"]["tmp_name"], $targetFilePath);

                echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "Expense updated successfully!",
                        showConfirmButton: true,
                        confirmButtonText: "Close"
                    }).then(function(result) {
                        if (result.value) {
                            window.location = "expenses";
                        }
                    });
                </script>';

            } else {
                // Error handler if data is not updated in database
                echo '<div class="alert alert-danger">Error updating the expense. Please try again.</div>';
            }
        }
    }
    static public function ctrDeleteExpense() {

        if (isset($_POST['deleteExpense'])) {
            $table = "expenses";
            $expenseId = $_POST["expenseId"];

            // Get the filename of the expense
            $filename = ExpenseModel::mdlGetExpenseFilename($table, $expenseId);

            $answer = ExpenseModel::mdlDeleteExpense($table, $expenseId);

            if ($answer == "ok") {
                // Delete the file
                $filePath = "uploads/" . $filename;
                if (file_exists($filePath)) {
                    unlink($filePath);
                }

                echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "Expense deleted successfully!",
                        showConfirmButton: true,
                        confirmButtonText: "Close"
                    }).then(function(result) {
                        if (result.value) {
                            window.location = "expenses";
                        }
                    });
                </script>';
            } else {
                // Error handler if data is not deleted from database
                echo '<div class="alert alert-danger">Error deleting the expense. Please try again.</div>';
            }
        }
    }
    static public function ctrShowExpenses($item, $value){

		$table = "expenses";

		$expenses = ExpenseModel::mdlShowExpenses($table, $item, $value);


		return $expenses;
	}

}


