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
                        // Create an array with the data for the activity log entry
                        $logdata = array(
                        	'UserID' => $_SESSION['userId'],
                        	'ActivityType' => 'Expense',
                        	'ActivityDescription' => 'User ' . $_SESSION['username'] . ' added expense ' .$data['expense']. '.'
                        );
                        // Call the ctrCreateActivityLog() function
                        activitylogController::ctrCreateActivityLog($logdata);

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
        
                    
            $item = "id";
            $value = $expenseId;
            $oldItem = ExpenseModel::mdlShowExpenses($table, $item, $value);

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

                    $changedInfo = ''; // Initialize the changed information string

                    foreach ($data as $property => $value) {
                        if ($property !== $expenseId && $oldItem[$property] !== $value) {
                            $changedInfo .= "Property $property changed from {$oldItem[$property]} to $value. ";
                        }
                    }
                    
                    // If any properties were changed, use the changed information as the log message
                    if (!empty($changedInfo)) {
                        $logMessage = $changedInfo;
                    } else {
                        $logMessage = "Expense has been edited."; 
                    }

                    // File upload successful
                    $answer = ExpenseModel::mdlEditExpense($table, $data, $expenseId);

                    if ($answer == "ok") {
                        // Create an array with the data for the activity log entry
                        $logdata = array(
                        	'UserID' => $_SESSION['userId'],
                        	'ActivityType' => 'Expense',
                        	'ActivityDescription' => $logMessage
                        );
                        // Call the ctrCreateActivityLog() function
                        activitylogController::ctrCreateActivityLog($logdata);
                        
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


                $changedInfo = ''; // Initialize the changed information string

                foreach ($data as $property => $value) {
                    if ($property !== 'discountid' && $oldItem[$property] !== $value) {
                        $changedInfo .= "Property '$property' changed from '{$oldItem[$property]}' to '$value'. ";
                    }
                }
                
                // If any properties were changed, use the changed information as the log message
                if (!empty($changedInfo)) {
                    $logMessage =$changedInfo;
                } else {
                    $logMessage = "Expense has been edited."; 
                }

                // File upload successful
                $answer = ExpenseModel::mdlEditExpense($table, $data, $expenseId);

                if ($answer == "ok") {
                    // Create an array with the data for the activity log entry
                    $logdata = array(
                        'UserID' => $_SESSION['userId'],
                        'ActivityType' => 'Expense',
                        'ActivityDescription' => $logMessage,
                        'itemID' => $expenseId
                    );
                    // Call the ctrCreateActivityLog() function
                    activitylogController::ctrCreateActivityLog($logdata);

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
            
            $item = "id";
            $value = $expenseId;
		    $expenses = ExpenseModel::mdlShowExpenses($table, $item, $value);

            // Get the filename of the expense
            $filename = ExpenseModel::mdlGetExpenseFilename($table, $expenseId);

            $answer = ExpenseModel::mdlDeleteExpense($table, $expenseId);

            if ($answer == "ok") {
                // Create an array with the data for the activity log entry
                $logdata = array(
                    'UserID' => $_SESSION['userId'],
                    'ActivityType' => 'Expense',
                    'ActivityDescription' => 'User ' . $_SESSION['username'] . ' deleted expense ' .$expenses['expense']. ' of type ' . $expenses['expense_type'] . '.',
                    'itemID' => $value
                );
                // Call the ctrCreateActivityLog() function
                activitylogController::ctrCreateActivityLog($logdata);

                // Delete the file
                if (file_exists($filename)) {
                    unlink($filename);
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


