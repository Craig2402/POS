<?php

require_once "connection.php";

class PaymentModel{
    private $db;

    public function __construct(){
        // Establish a database connection
        $this->db = Connection::connect();
    }

 	/*=============================================
	CREATE PAYMENT
	=============================================*/
    public function insertPayment($paymentId, $amount, $paymentMethod, $invoiceId){
        
        $query = "INSERT INTO payments (paymentid, amount, paymentmethod, invoiceId) 
                  VALUES (:paymentid, :amount, :paymentmethod, :invoiceId)";

        $date = date('Y-m-d');

        // Prepare the query
        $stmt = $this->db->prepare($query);

        // Bind the parameters
        $stmt->bindParam(':paymentid', $paymentId);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':paymentmethod', $paymentMethod);
        $stmt->bindParam(':invoiceId', $invoiceId);

        // Execute the query
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

 	/*=============================================
	GET NEXT ABAILABLE NUMBER IN THE PAYMENT ID
	=============================================*/
    public function getNextPaymentNumericPart(){

        $query = "SELECT MAX(CAST(SUBSTRING_INDEX(paymentid, '-', -1) AS UNSIGNED)) AS max_numeric_part
                  FROM payments";

        // Prepare the query
        $stmt = $this->db->prepare($query);

        // Execute the query
        $stmt->execute();

        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $maxNumericPart = $result['max_numeric_part'];

        if ($maxNumericPart) {
            return $maxNumericPart + 1;
        } else {
            return 1;
        }

    }
    
	/*=============================================
	DELETING TRANSACTION
	=============================================*/

	static public function mdlDeleteTransaction($table, $data){

		$stmt = connection::connect()->prepare("DELETE FROM $table WHERE paymentid = :id");

		$stmt -> bindParam(":id", $data, PDO::PARAM_STRING);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;

	}
    
}
class InvoiceModel{

    private $db;

    public function __construct(){
        // Establish a database connection
        $this->db = Connection::connect();
    }


 	/*=============================================
	CREATE INVOICE
	=============================================*/
    public function insertInvoice($invoiceId, $productsList, $invoiceStartDate, $invoiceDueDate, $invoiceCustomerName, $invoicePhone, $invoiceIdNumber, $invoiceTotalTax, $invoiceSubtotal, $invoiceTotal, $invoiceDiscount, $invoiceDueAmount, $invoiceUserId){
        $query = "INSERT INTO invoices (invoiceId, products, startdate, duedate, customername, phone, idnumber, totaltax, subtotal, total, discount, dueamount, userId) 
                  VALUES (:invoiceId, :products, :startdate, :duedate, :customername, :phone, :idnumber, :totaltax, :subtotal, :total, :discount, :dueamount, :userId)";

        // Prepare the query
        $stmt = $this->db->prepare($query);


        // Bind the parameters
        $stmt->bindParam(':invoiceId', $invoiceId);
        $stmt->bindParam(':products', $productsList);
        $stmt->bindParam(':startdate', $invoiceStartDate);
        $stmt->bindParam(':duedate', $invoiceDueDate);
        $stmt->bindParam(':customername', $invoiceCustomerName);
        $stmt->bindParam(':phone', $invoicePhone);
        $stmt->bindParam(':idnumber', $invoiceIdNumber);
        $stmt->bindParam(':totaltax', $invoiceTotalTax);
        $stmt->bindParam(':subtotal', $invoiceSubtotal);
        $stmt->bindParam(':total', $invoiceTotal);
        $stmt->bindParam(':discount', $invoiceDiscount);
        $stmt->bindParam(':dueamount', $invoiceDueAmount);
        $stmt->bindParam(':userId', $invoiceUserId);

        // Execute the query
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

 	/*=============================================
	GET NEXT ABAILABLE NUMBER IN THE INVOICE ID
	=============================================*/
    public function getNextInvoiceNumericPart() {
        $query = "SELECT MAX(CAST(SUBSTRING_INDEX(invoiceId, '-', -1) AS UNSIGNED)) AS max_numeric_part
                  FROM invoices";
    
        // Prepare the query
        $stmt = $this->db->prepare($query);
    
        // Execute the query
        $stmt->execute();
    
        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        $maxNumericPart = $result['max_numeric_part'];
    
        if ($maxNumericPart !== null) {
            return $maxNumericPart + 1;
        } else {
            return 1;
        }
    }

 	/*=============================================
	SHOW INVOICES
	=============================================*/
    public static function mdlShowInvoices($table, $item, $value){

		if($item != null){

            $stmt = connection::connect()->prepare("SELECT * FROM $table WHERE $item = :$item");

            $stmt -> bindParam(":".$item, $value);

            $stmt -> execute();

            return $stmt -> fetch(PDO::FETCH_ASSOC);

		}
		else{

			$stmt = connection::connect()->prepare("SELECT * FROM $table");

			$stmt -> execute();

			return $stmt -> fetchAll(PDO::FETCH_ASSOC);

		}

	}
    
 	/*=============================================
	EDIT INVOICES
	=============================================*/
    public function mdlEditInvoice($table, $data){

		$stmt = connection::connect()->prepare("UPDATE $table SET dueamount = :due WHERE invoiceId = :invoiceId");

		$stmt->bindParam(":due", $data['newdue'], PDO::PARAM_INT);
		$stmt->bindParam(":invoiceId", $data['invoiceid'], PDO::PARAM_STR);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

	}


    /*=============================================
	DATES RANGE
	=============================================*/	

	 public static function mdlSalesDatesRange($table, $initialDate, $finalDate){

		if($initialDate == null){

			$stmt = connection::connect()->prepare("SELECT * FROM $table ORDER BY invoiceId ASC");

			$stmt -> execute();

			return $stmt -> fetchAll();	


		}else if($initialDate == $finalDate){

			$stmt = connection::connect()->prepare("SELECT * FROM $table WHERE startdate like '%$finalDate%'");

			$stmt -> bindParam(":startdate", $finalDate, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetchAll();

		}else{

			$actualDate = new DateTime();
			$actualDate ->add(new DateInterval("P1D"));
			$actualDatePlusOne = $actualDate->format("Y-m-d");

			$finalDate2 = new DateTime($finalDate);
			$finalDate2 ->add(new DateInterval("P1D"));
			$finalDatePlusOne = $finalDate2->format("Y-m-d");

			if($finalDatePlusOne == $actualDatePlusOne){

				$stmt = connection::connect()->prepare("SELECT * FROM $table WHERE startdate BETWEEN '$initialDate' AND '$finalDatePlusOne'");

			}else{


				$stmt = connection::connect()->prepare("SELECT * FROM $table WHERE startdate BETWEEN '$initialDate' AND '$finalDate'");

			}
		
			$stmt -> execute();

			return $stmt -> fetchAll();

		}

	}

    	/*=============================================
	Adding TOTAL sales
	=============================================*/

	static public function mdlAddingTotalSales($table, $month){	

		$stmt = connection::connect()->prepare("SELECT SUM(total) as total FROM $table where MONTH(startdate) =:month");

        $stmt->bindParam(':month',$month,PDO::PARAM_INT);

		$stmt -> execute();

		return $stmt -> fetch();

	}
}



