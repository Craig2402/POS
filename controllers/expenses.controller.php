<?php
class expenseController{
/*=============================================
    ADD EXPENSE
    =============================================*/

    static public function ctrAddExpense(){

        if (isset($_POST['addExpense'])) {
            $table = "expenses";
            $targetDirectory = "views/expenses/reciepts/";
    
            $expenseName = $_POST["expense"];
            $date = $_POST["date"];
            $fileExtension = pathinfo($_FILES['reciept']['name'], PATHINFO_EXTENSION);
            $fileName = $targetDirectory . $expenseName . '' . $date . '' . time() . '.' . $fileExtension;
    
            $data = array(
                "expense" => $expenseName,
                "expense_type" => $_POST["expenseType"],
                "date" => $date,
                "amount" => $_POST["amount"],
                "receipt" => $fileName
            );
    
            // Check if a file was uploaded
            if (!empty($_FILES['reciept']['tmp_name'])) {
                // Move the uploaded file to the destination path
                if (move_uploaded_file($_FILES['reciept']['tmp_name'], $fileName)) {
                    // File upload successful
                    $answer = ExpenseModel::mdlAddExpense($table, $data);
    
                    if ($answer == "ok") {
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
                    } 

                }

            } else {
                // No file uploaded, use the default image
                $defaultImage = "views/expenses/defaults/reciept.png";
                $data["receipt"] = $defaultImage;
                $answer = ExpenseModel::mdlAddExpense($table, $data);
    
                if ($answer == "ok") {
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
                } 

            }

        }

    }
    
    /*=============================================
    EDIT EXPENSE
    =============================================*/
    static public function ctrEditExpense(){

        if (isset($_POST['updateExpense'])) {

            $targetDirectory = "views/expenses/reciepts/";
            $table = "expenses";
            $expenseId = $_POST["editExpenseId"];
            $expenseName = $_POST["editExpense"];
            $expenseType = $_POST["editExpenseType"];
            $date = $_POST["editDate"];
            $amount = $_POST["editAmount"];
            $fileExtension = pathinfo($_FILES['editReceipt']['name'], PATHINFO_EXTENSION);
            $filename= $targetDirectory.$expenseName . '_' . $date . '_' . time() . '.' . $fileExtension;

            $existingFilePath = ExpenseModel::mdlGetExpenseFilename($table, $expenseId); // The existing file path from the database
        
            // Check if a file was uploaded
            if (!empty($_FILES['editReceipt']['tmp_name'])) {
                // Move the uploaded file to the destination path
                if (move_uploaded_file($_FILES['editReceipt']['tmp_name'], $filename)) {

                    // Update the data in the database
                    $data = array(
                        "expense" => $expenseName,
                        "expense_type" => $expenseType,
                        "date" => $date,
                        "amount" => $amount,
                        "receipt" => $filename
                    );

                    // File upload successful
                    $answer = ExpenseModel::mdlEditExpense($table, $data, $expenseId);

                    if ($answer == "ok") {
                        
                        // Delete the file
                        if (file_exists($existingFilePath)) {
                            unlink($existingFilePath);
                        }

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
                    }

                }

            }else {

                // Update the data in the database
                $data = array(
                    "expense" => $expenseName,
                    "expense_type" => $expenseType,
                    "date" => $date,
                    "amount" => $amount,
                    "receipt" => $existingFilePath
                );

                // File upload successful
                $answer = ExpenseModel::mdlEditExpense($table, $data, $expenseId);

                if ($answer == "ok") {

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
                }
            }

        }

    }

    /*=============================================
    DELETE EXPENSE
    =============================================*/
    
    static public function ctrDeleteExpense(){

        if (isset($_GET["deleteExpenseId"])) {
            $table = "expenses";
            $expenseId = $_GET["deleteExpenseId"];

            // Get the filename of the expense
            $filename = ExpenseModel::mdlGetExpenseFilename($table, $expenseId);

            $answer = ExpenseModel::mdlDeleteExpense($table, $expenseId);

            if ($answer == "ok") {
                // Delete the file
                if (file_exists($filename)) {
                    unlink($filename);
                }

                echo json_encode(array("status" => "success"));
                echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "'.$filename.'The expense has been successfully deleted",
                        showConfirmButton: true,
                        confirmButtonText: "Close"
                    }).then(function(result) {
                        if (result.value) {
                            window.location = "expenses";
                        }
                    });
                </script>';
            }

        }

    }

    /*=============================================
    SHOW EXPENSE
    =============================================*/

    static public function ctrShowExpenses($item, $value){

		$table = "expenses";

		$expenses = ExpenseModel::mdlShowExpenses($table, $item, $value);

		return $expenses;
	}
    
        public function ctrAddExpenseType() {
            if (isset($_POST['addExpenseType'])) {
                $table = "expensecat";
                $expenseType = $_POST["expenseType"];
    
                // Validate and sanitize the input as needed
    
                $data = array(
                    "expensetype" => $expenseType
                );
    
                $answer = ExpenseModel::mdlAddExpenseType($table, $data);
    
                if ($answer == "ok") {
                    echo '<script>
                        Swal.fire({
                            icon: "success",
                            title: "Expense type added successfully!",
                            showConfirmButton: true,
                            confirmButtonText: "Close"
                        }).then(function(result) {
                            if (result.value) {
                                window.location = "expenses";
                            }
                        });
                    </script>';
                } else {
                    echo '<div class="alert alert-danger">Error adding the expense type. Please try again.</div>';
                }
            }
        }
    

}


