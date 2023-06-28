<?php
class expenseController{
    /*=============================================
    ADD EXPENSE
    =============================================*/

    static public function ctrAddExpense()
    {
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
                    } else {
                        // Error handling if saving the expense fails
                        echo '<div class="alert alert-danger">Error saving the expense. Please try again.</div>';
                    }
                } else {
                    // Error handling if file upload fails
                    echo '<div class="alert alert-danger">Error uploading the receipt file. Please try again.</div>';
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
                } else {
                    // Error handling if saving the expense fails
                    echo '<div class="alert alert-danger">Error saving the expense. Please try again.</div>';
                }

            }

        }

    }
    
    


    /*=============================================
        EDIT EXPENSE
    =============================================*/
    static public function ctrEditExpense(){

        if (isset($_POST['editExpense'])) {
            $table = "expenses";
            $expenseId = $_POST["editExpenseId"];
            $expenseName = $_POST["editExpense"];
            $expenseType = $_POST["editExpenseType"];
            $date = $_POST["editDate"];
            $amount = $_POST["editAmount"];
            $receipt = $_FILES["reciept"]["name"];

            $targetDirectory = "views/expenses/reciepts/";
            $existingFilePath = $_POST["existingFilePath"]; // The existing file path from the database

            // Check if a new file is uploaded
            if (!empty($_FILES["reciept"]["name"])) {
                // Generate new file name
                $fileName = $targetDirectory . $expenseName . '' . $date . '' . time() . '.' . pathinfo($receipt, PATHINFO_EXTENSION);

                // Move the uploaded file to the destination path
                if (move_uploaded_file($_FILES["reciept"]["tmp_name"], $fileName)) {
                    // File upload successful
                    // Update the file path in the database
                    $data = array(
                        "expense" => $expenseName,
                        "expense_type" => $expenseType,
                        "date" => $date,
                        "amount" => $amount,
                        "receipt" => $fileName
                    );
                } else {
                    // Error handling if file upload fails
                    echo '<div class="alert alert-danger">Error uploading the receipt file. Please try again.</div>';
                    return;
                }
            } else {
                // No new file uploaded, use the existing file path
                $fileName = $existingFilePath;
                $data = array(
                    "expense" => $expenseName,
                    "expense_type" => $expenseType,
                    "date" => $date,
                    "amount" => $amount,
                    "receipt" => $fileName // Include the existing file path
                );
            }

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
            } else {
                echo '<div class="alert alert-danger">Error updating the expense. Please try again.</div>';
            }
        }
    }


    /*=============================================
    DELETE EXPENSE
    =============================================*/
    
    static public function ctrDeleteExpense()
{
    if (isset($_GET["deleteExpenseId"])) {
        $table = "expenses";
        $expenseId = $_GET["deleteExpenseId"];

        // Get the filename of the expense
        $filename = ExpenseModel::mdlGetExpenseFilename($table, $expenseId);

        $answer = ExpenseModel::mdlDeleteExpense($table, $expenseId);

        if ($answer == "ok") {
            // Delete the file
            $targetDirectory = "views/expenses/reciepts/";
            $filePath = $targetDirectory . $filename;
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            echo json_encode(array("status" => "success"));
            echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "The expense has been successfully deleted",
                    showConfirmButton: true,
                    confirmButtonText: "Close"
                }).then(function(result) {
                    if (result.value) {
                        window.location = "expenses";
                    }
                });
            </script>';
        } else {
            // Error handler if data is not deleted from the database
            echo json_encode(array("status" => "error", "message" => "Error deleting the expense. Please try again."));
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

}


