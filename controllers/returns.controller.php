<?php
class ReturnProductController {
    /*=============================================
    ADDING RETURN PRODUCTS
    =============================================*/
    static public function ctrAddReturnProduct() {
        if (isset($_POST['btnschedule'])) {
            $barcode = $_POST['selectProduct'];
            $supplier = $_POST['selectSupplier'];
            $quantity = $_POST['quantity'];
            $returnDate = $_POST['dateField'];
            $reason = $_POST['reason'];
            $returnType = $_POST['return_type'];

            $tableProducts = "products";

            // Get the current stock of the product
            $productData = productModel::mdlShowProducts($tableProducts, "barcode", $barcode, "id");

            if ($productData) {
                $currentStock = $productData["stock"];

                // Check if return quantity is valid
                if ($quantity > 0 && $quantity <= $currentStock) {
                    // Calculate the new stock after return
                    $newStock = $currentStock - $quantity;

                    // Update the stock in the product table
                    $updateResult = productModel::mdlUpdateProduct($tableProducts, "stock", $newStock, $productData["id"]);

                    if ($updateResult == "ok") {
                        // Prepare the data for adding return product
                        $data = array(
                            "product" => $barcode,
                            "quantity" => $quantity,
                            "supplier" => $supplier,
                            "return_date" => $returnDate,
                            "reason" => $reason,
                            "return_type" => $returnType
                        );

                        $table = "returnproducts";
                        $response = ReturnProductModel::mdlAddReturnProduct($table, $data);

                        if ($response == "ok") {

                            // Create an array with the data for the activity log entry
                            $logdata = array(
                                'UserID' => $_SESSION['userId'],
                                'ActivityType' => 'Category',
                                'ActivityDescription' => 'User ' . $_SESSION['username'] . ' created a return proccess of ' .$data['quantity']. ' product(s) of product with barcode ' .$data['product']. '.'
                            );
                            // Call the ctrCreateActivityLog() function
                            activitylogController::ctrCreateActivityLog($logdata);
                            
                            $message = "Return product added successfully.";
                            // Display Swal.fire success message
                            echo "<script>
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: '$message',
                                        timer: 3000,
                                        showConfirmButton: false
                                    });
                                  </script>";
                        } else {
                            $message = "Error adding return product.";
                            // Display Swal.fire error message
                            echo "<script>
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: '$message',
                                        timer: 3000,
                                        showConfirmButton: false
                                    });
                                  </script>";
                        }
                    } else {
                        $message = "Error updating product stock.";
                        // Display Swal.fire error message
                        echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: '$message',
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                              </script>";
                    }
                } else {
                    $message = "Invalid return quantity.";
                    // Display Swal.fire error message
                    echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: '$message',
                                timer: 3000,
                                showConfirmButton: false
                            });
                          </script>";
                }

            }
            
        }

    }
    
    /*=============================================
    SHOW RETURNS
    =============================================*/
    static public function ctrShowReturnProducts($item, $value) {
        
        $table = "returnproducts";

        $response = ReturnProductModel::mdlShowReturnProducts($table, $item, $value);
        
        return $response;
    }

}
