<?php

// include_once "ajax/pos2.ajax.php";

class PaymentController {
    
 	/*=============================================
	ADD PAYMENT AND INVOICE
	=============================================*/
    public function addPayment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['saveorder'])) {

                // Use the payment ID to insert into the invoices table
                $invoiceModel = new InvoiceModel();

                $productsList = json_decode($_POST["productsList"], true);
                // echo json_encode($productsList);

                $totalPurchasedProducts = array();
    
                foreach ($productsList as $key => $value) {
    
                   array_push($totalPurchasedProducts, $value["Quantity"]);
                    
                   $tableProducts = "products";
    
                    $item = "id";
                    $valueProductId = $value["id"];
                    $order = "id";
    
                    $getProduct = productModel::mdlShowProducts($tableProducts, $item, $valueProductId, $order);
    
                    $item1a = "sales";
                    $value1a = $value["Quantity"] + $getProduct["sales"];
    
                    $newSales = productModel::mdlUpdateProduct($tableProducts, $item1a, $value1a, $valueProductId);
    
                    $item1b = "stock";
                    $value1b = $getProduct["stock"] - $value["Quantity"];
    
                    $newStock = productModel::mdlUpdateProduct($tableProducts, $item1b, $value1b, $valueProductId);
                    if ($newStock == "ok") {
                        
                        $order = null;
                        $table = "products";
                        $stock = productModel::mdlShowProducts($table, $item, $valueProductId, $order);
                        if ($stock && $stock["stock"] <= 15) {
                            date_default_timezone_set('africa/nairobi');
                            $currentDateTime = date('Y-m-d H:i:s');
                            $data = array(
                                "message" => $stock["product"] . " is running low on stock",
                                "datetime" => $currentDateTime,
                                "name" => "System",
                                "type" => "Stock notification,".$getProduct["barcode"]
                            );
                            $notification = notificationController::ctrCreateNotification($data);
                        }
                    }
                }
                
                $currentDate = date('Y-m-d');
                $future_date = date("Y-m-d", strtotime($currentDate . " +15 days"));

                $productsList = $_POST['productsList'];
                $invoiceStartDate = $currentDate;
                $invoiceDueDate = $future_date;
                $invoiceCustomerName = $_POST['cname'];
                $invoicePhone = $_POST['phone'];
                $invoiceIdNumber = $_POST['cid'];
                $invoiceTotalTax = $_POST['totaltax'];
                $invoiceSubtotal = $_POST['subtotal'];
                $invoiceTotal = $_POST['total'];
                $invoiceDiscount = $_POST['totaldiscount'];
                $invoiceDueAmount = $_POST['dueamount'];
                $invoiceUserId = $_SESSION['userId'];

                
                // Generate the invoice ID
                $nextNumericPart = $invoiceModel->getNextInvoiceNumericPart(); // Get the next available numeric part
                $invoiceId = "INVC-" . str_pad($nextNumericPart, 8, '0', STR_PAD_LEFT);
                
                // Insert invoice data into the invoices table, linking it with the payment
                $invoiceModel->insertInvoice($invoiceId, $productsList, $invoiceStartDate, $invoiceDueDate, $invoiceCustomerName, $invoicePhone, $invoiceIdNumber, $invoiceTotalTax, $invoiceSubtotal,  $invoiceTotal, $invoiceDiscount, $invoiceDueAmount, $invoiceUserId);

                // Retrieve payment data from the form or request parameters
                $amount = $_POST['txtpaid'];
                $paymentMethod = $_POST['r3'];
                
                // Create an instance of the PaymentModel
                $paymentModel = new PaymentModel();
                
                // Generate the payment ID manually
                $nextNumericPart = $paymentModel->getNextPaymentNumericPart(); // Implement this method in PaymentModel to get the next available numeric part
                $paymentId = "CASH-" . str_pad($nextNumericPart, 8, '0', STR_PAD_LEFT);
                
                // Insert payment data into the payments table
                $paymentModel->insertPayment($paymentId, $amount, $paymentMethod, $invoiceId);

                // create an activitylog of the payment
                if($paymentModel == true){

                    // Create an array with the data for the activity log entry
                    $data = array(
                        'UserID' => $_SESSION['userId'],
                        'ActivityType' => 'Sale',
                        'ActivityDescription' => 'User ' . $_SESSION['username'] . ' Processed transaction '.$invoiceId.'.'
                    );
                    // Call the ctrCreateActivityLog() function
                    activitylogController::ctrCreateActivityLog($data);
                }
                
                // Redirect or display success message
                // echo "Payment and invoice added successfully!";
            //     echo "<script>
            //     var invoiceId = " . json_encode($invoiceId) . ";
			// 	window.open('views/pdfs/receipt.php?invoiceId=' + invoiceId, '_blank');
			// </script>";
            echo "
            <script>
            var invoiceId = " . json_encode($invoiceId) . ";

            Swal.fire({
                title: 'Generating Receipt',
                text: 'Please wait while the receipt is being generated...',
                allowOutsideClick: false,
                showConfirmButton:false,
                onBeforeOpen: function() {
                Swal.showLoading();
                }
            });

            fetch('views/pdfs/receipt.php?invoiceId=' + invoiceId)
                .then(response => response.blob())
                .then(blob => {
                var fileURL = URL.createObjectURL(blob);
                var iframe = document.createElement('iframe');
                iframe.style.display = 'none';
                document.body.appendChild(iframe);
                iframe.src = fileURL;
                iframe.onload = function() {
                    try {
                    // Try printing the content
                    iframe.contentWindow.print();
                    document.body.removeChild(iframe);

                    Swal.close();

                    Swal.fire({
                        title: 'Print Complete',
                        icon: 'success',
                        text: 'The receipt has been printed successfully!',
                        timer:2000,
                        showConfirmButton:false,
                    });
                    } catch (error) {
                    document.body.removeChild(iframe);

                    Swal.close();

                    Swal.fire({
                        title: 'Print Failed',
                        icon: 'error',
                        text: 'Failed to print the receipt. Please try again.',
                        timer:2000,
                         showConfirmButton:false,
                    });
                    }
                };
                })
                .catch(error => {
                Swal.close();
                Swal.fire({
                    title: 'Print Failed',
                    icon: 'error',
                    text: 'Failed to generate the receipt. Please try again.',
                    timer:2000,
                    showConfirmButton:false,
                });
                });
            </script>
            ";

            
            
            }
        }
    }

        
 	/*=============================================
	MAKE PAYMENT
	=============================================*/
    public function makePayment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['makePayment']) && isset($_POST['r3'])) {

                // Retrieve payment data from the form or request parameters
                $amount = $_POST['payment'];
                $paymentMethod = $_POST['r3'];
                $invoiceId = $_POST['invoiceId'];
                
                // Create an instance of the PaymentModel
                $paymentModel = new PaymentModel();
                
                // Generate the payment ID manually
                $nextNumericPart = $paymentModel->getNextPaymentNumericPart(); // Implement this method in PaymentModel to get the next available numeric part
                $paymentId = "CASH-" . str_pad($nextNumericPart, 8, '0', STR_PAD_LEFT);
                
                // Insert payment data into the payments table
                $paymentModel->insertPayment($paymentId, $amount, $paymentMethod, $invoiceId);

                if($paymentModel == true){
                    
                    /*=============================================
                    EDIT INVOICE
                    =============================================*/

                    $table = "invoices";
                    $newDue = $_POST['due'] + $amount;
                        
                    $data = array("newdue" => $newDue,
                    "invoiceid" => $_POST['invoiceId']);

                    $answer = InvoiceModel::mdlEditInvoice($table, $data);

                    if($answer == "ok"){
                        
                        // Create an array with the data for the activity log entry
                        $data = array(
                            'UserID' => $_SESSION['userId'],
                            'ActivityType' => 'Sale',
                            'ActivityDescription' => 'User ' . $_SESSION['username'] . ' Processed transaction '.$data['invoiceid'].'.'
                        );
                        // Call the ctrCreateActivityLog() function
                        activitylogController::ctrCreateActivityLog($data);

                        echo'<script>

                                Swal.fire({
                                        icon: "success",
                                        title: "Transaction succesfully!",
                                        showConfirmButton: true,
                                        confirmButtonText: "Close"
                                        }).then(function(result){
                                                    if (result.value) {

                                                    window.location = "invoices";

                                                    }
                                                })

                            </script>';
                    }

				}
                
            }else{

                echo'<script>

                        Swal.fire({
                                icon: "warning",
                                title: "Select a payent method to proceed",
                                showConfirmButton: true,
                                confirmButtonText: "Close"
                                })

                    </script>';
            }

        }

    }

 	/*=============================================
	SHOW INVOICES
	=============================================*/

	public static function ctrShowInvoices($item, $value){

		$table = "invoices";

		$answer = InvoiceModel::mdlShowInvoices($table, $item, $value);

		return $answer;
	}   


    /*=============================================
	SHOW INVOICES
	=============================================*/

	public static function ctrShowPayments($item, $value){

		$table = "payments";

		$answer = PaymentModel::mdlShowPayments($table, $item, $value);

		return $answer;
	}

    /*=============================================
	DATES RANGE
	=============================================*/	

	public static function ctrSalesDatesRange($initialDate, $finalDate){

		$table = "invoices";

		$answer = InvoiceModel:: mdlSalesDatesRange($table, $initialDate, $finalDate);

		return $answer;
		
	}
/*=============================================
   DOWNLOAD EXCEL
=============================================*/

public function ctrDownloadReport(){

    if(isset($_GET["report"])){

        $table = "invoices";

        if(isset($_GET["initialDate"]) && isset($_GET["finalDate"])){

            $sales = InvoiceModel::mdlSalesDatesRange($table, $_GET["initialDate"], $_GET["finalDate"]);

        }else{

            $item = null;
            $value = null;

            $sales = InvoiceModel::mdlShowInvoices($table, $item, $value);

        }

        /*=============================================
        WE CREATE EXCEL FILE
        =============================================*/

        $name = $_GET["report"].'.xls';

        header('Expires: 0');
        header('Cache-control: private');
        header("Content-type: application/vnd.ms-excel"); // Excel file
        header("Cache-Control: cache, must-revalidate");
        header('Content-Description: File Transfer');
        header('Last-Modified: '.date('D, d M Y H:i:s'));
        header("Pragma: public");
        header('Content-Disposition:; filename="'.$name.'"');
        header("Content-Transfer-Encoding: binary");

        echo utf8_decode("<table border='0'>
            <tr>
                <td style='font-weight:bold; border:1px solid #eee;'>Invoice</td>
                <td style='font-weight:bold; border:1px solid #eee;'>Seller</td>
                <td style='font-weight:bold; border:1px solid #eee;'>Quantity</td>
                <td style='font-weight:bold; border:1px solid #eee;'>Products</td>
                <td style='font-weight:bold; border:1px solid #eee;'>Tax</td>
                <td style='font-weight:bold; border:1px solid #eee;'>Subtotal</td>
                <td style='font-weight:bold; border:1px solid #eee;'>TOTAL</td>
                <td style='font-weight:bold; border:1px solid #eee;'>Date</td>
            </tr>");

        foreach ($sales as $row => $item){

            $customerName = isset($item["customername"]) ? $item["customername"] : "";
            $customer = PaymentController::ctrShowInvoices("invoiceId", $customerName);
            $customerName = isset($customer["customername"]) ? $customer["customername"] : "";

            $sellerId = isset($item["userId"]) ? $item["userId"] : "";
            $seller = userController::ctrShowUsers("userId", $sellerId);
            $sellerName = isset($seller["name"]) ? $seller["name"] : "";

            $products = json_decode($item["products"], true); // Decode the product field to retrieve the array of products

            $quantity = ""; // Variable to store the quantity
            $productDetails = ""; // Variable to store the product details

            if (is_array($products)) {
                foreach ($products as $product) {
                    $quantity .= $product["Quantity"] . "<br>"; // Extract the quantity and append to the quantity variable
                    $productDetails .= $product["productName"] . "<br>"; // Extract the product name and append to the productDetails variable
                }
            }

            $paymentMethod = isset($item["paymentmethod"]) ? $item["paymentmethod"] : "";

            echo utf8_decode("<tr>
                <td style='border:1px solid #eee;'>" . $item["invoiceId"] . "</td>
                <td style='border:1px solid #eee;'>" . $sellerName . "</td>
                <td style='border:1px solid #eee;'>" . $quantity . "</td>
                <td style='border:1px solid #eee;'>" . $productDetails . "</td>
                <td style='border:1px solid #eee;'>Ksh " . number_format($item["totaltax"], 2) . "</td>
                <td style='border:1px solid #eee;'>Ksh " . number_format($item["subtotal"], 2) . "</td>
                <td style='border:1px solid #eee;'>Ksh " . number_format($item["total"], 2) . "</td>
                <td style='border:1px solid #eee;'>" . substr($item["datecreated"], 0, 10) . "</td>
            </tr>");
        }

        echo "</table>";
    }
}

	/*=============================================
	Adding TOTAL sales
	=============================================*/

	public static function ctrAddingTotalPayments($month){

		$table = "payments";

		$answer = PaymentModel::mdlAddingTotalPayments($table, $month);

		return $answer;

	}


	/*=============================================
	Adding TOTAL sales
	=============================================*/

	public static function ctrAddingTotalSales($month){

		$table = "invoices";

		$answer = InvoiceModel::mdlAddingTotalSales($table, $month);

		return $answer;

	}


    // mpesa payment
    public static function stkpush(){
        if(isset($_POST['phone'])){
          date_default_timezone_set('Africa/Nairobi');
        
          # access token
          $consumerKey = '2FEEYG2mru3WUOsZOwSHA63GgLDqVhhI'; //Fill with your app Consumer Key
          $consumerSecret = 'G6f54z31OA7GOAPF'; // Fill with your app Secret
        
          # define the variales
          # provide the following details, this part is found on your test credentials on the developer account
          $BusinessShortCode = '174379';
          $Passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';  
          
          /*
            This are your info, for
            $PartyA should be the ACTUAL clients phone number or your phone number, format 2547********
            $AccountRefference, it maybe invoice number, account number etc on production systems, but for test just put anything
            TransactionDesc can be anything, probably a better description of or the transaction
            $Amount this is the total invoiced amount, Any amount here will be 
            actually deducted from a clients side/your test phone number once the PIN has been entered to authorize the transaction. 
            for developer/test accounts, this money will be reversed automatically by midnight.
          */
          
           $PartyA = $_POST['phone']; // This is your phone number, 
          $AccountReference = $_POST['phone'];
          $TransactionDesc = 'POS PAYMENT';
          $Amount = $_POST['amount'];
         
          # Get the timestamp, format YYYYmmddhms -> 20181004151020
          $Timestamp = date('YmdHis');    
          
          # Get the base64 encoded string -> $password. The passkey is the M-PESA Public Key
          $Password = base64_encode($BusinessShortCode.$Passkey.$Timestamp);
        
          # header for access token
          $headers = ['Content-Type:application/json; charset=utf8'];
        
            # M-PESA endpoint urls
          $access_token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
          $initiate_url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
        
          # callback url
          $CallBackURL = 'https://mungatest.000webhostapp.com/callbackurl.php';  
        
          $curl = curl_init($access_token_url);
          curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
          curl_setopt($curl, CURLOPT_HEADER, FALSE);
          curl_setopt($curl, CURLOPT_USERPWD, $consumerKey.':'.$consumerSecret);
          $result = curl_exec($curl);
          $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
          $result = json_decode($result);
          $access_token = $result->access_token;  
          curl_close($curl);
        
          # header for stk push
          $stkheader = ['Content-Type:application/json','Authorization:Bearer '.$access_token];
        
          # initiating the transaction
          $curl = curl_init();
          curl_setopt($curl, CURLOPT_URL, $initiate_url);
          curl_setopt($curl, CURLOPT_HTTPHEADER, $stkheader); //setting custom header
        
          $curl_post_data = array(
            //Fill in the request parameters with valid values
            'BusinessShortCode' => $BusinessShortCode,
            'Password' => $Password,
            'Timestamp' => $Timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $Amount,
            'PartyA' => $PartyA,
            'PartyB' => $BusinessShortCode,
            'PhoneNumber' => $PartyA,
            'CallBackURL' => $CallBackURL,
            'AccountReference' => $AccountReference,
            'TransactionDesc' => $TransactionDesc
          );
        
          $data_string = json_encode($curl_post_data);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_POST, true);
          curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
          $curl_response = curl_exec($curl);
          //print_r($curl_response);
        
          //echo $curl_response;
        
        }
          
    }

    /*=============================================
	DELETE PRODUCT
	=============================================*/
	static public function ctrDeleteTransaction(){

		if(isset($_GET["reciept"])){

			$table ="payments";
			$data = $_GET["reciept"];

            $answer = PaymentModel::mdlDeleteTransaction($table, $data);

			if($answer == "ok"){

				echo'<script>

				Swal.fire({
					  icon: "success",
					  title: "The transaction has been successfully deleted",
					  showConfirmButton: true,
					  confirmButtonText: "Close"
					  }).then(function(result){
								if (result.value) {

								    window.location = "transactions";

								}
							})

				</script>';

			}	

        }

    }

}