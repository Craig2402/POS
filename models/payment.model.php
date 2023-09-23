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
    public function insertPayment($paymentId, $amount, $paymentMethod, $invoiceId, $storeid, $loyaltyPoint){
        
        $query = "INSERT INTO payments (paymentid, amount, paymentmethod, invoiceId, store_id, loyaltyid) VALUES(:paymentid, :amount, :paymentmethod, :invoiceId, :store_id, :loyaltyid)";

        // Prepare the query
        $stmt = $this->db->prepare($query);

        // Bind the parameters
        $stmt->bindParam(':paymentid', $paymentId);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':paymentmethod', $paymentMethod);
        $stmt->bindParam(':invoiceId', $invoiceId);
		$stmt -> bindParam(":store_id", $storeid);
		$stmt -> bindParam(":loyaltyid", $loyaltyPoint);

		if ($stmt->execute()) {

			// Close the statement and set it to null
			$stmt->closeCursor();

			$stmt = null;

			return true;
			
		} else {

			// Close the statement and set it to null
			$stmt->closeCursor();

			$stmt = null;

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

		$stmt -> closeCursor();

		$stmt = null;

    }
    
	/*=============================================
	DELETING TRANSACTION
	=============================================*/

	static public function mdlDeleteTransaction($table, $data){

		$stmt = connection::connect()->prepare("DELETE FROM $table WHERE paymentid = :id");

		$stmt -> bindParam(":id", $data, PDO::PARAM_STR);

		if ($stmt->execute()) {

			// Close the statement and set it to null
			$stmt->closeCursor();

			$stmt = null;

			return 'ok';
			
		} else {

			// Close the statement and set it to null
			$stmt->closeCursor();

			$stmt = null;

			return 'error';

		}

	}
	/*=============================================
	SHOW Payments
	=============================================*/
    public static function mdlShowPayments($table, $item, $value){

		if ($item == "store_id"){ 

			$stmt = connection::connect()->prepare("SELECT * FROM $table WHERE $item = :$item");

			$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetchAll();

			$stmt->closeCursor();

			$stmt = null;
			
		} elseif($item != null){

			$stmt = connection::connect()->prepare("SELECT * FROM $table WHERE $item = :$item");

			$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();
			
			$stmt->closeCursor();

			$stmt = null;

		}
		else{
			$stmt = connection::connect()->prepare("SELECT * FROM $table");

			$stmt -> execute();

			return $stmt -> fetchAll();
			
			$stmt->closeCursor();

			$stmt = null;

		}

	}
	/*=============================================
	FETCH GROUPED PAYMENTS
	=============================================*/

	static public function mdlfetchGroupedPayments($table, $item, $value){

		$stmt = connection::connect()->prepare("SELECT * FROM $table WHERE $item = :$item");

		$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);

		$stmt -> execute();

		return $stmt -> fetchAll();
		
		$stmt->closeCursor();

		$stmt = null;

	}
	/*=============================================
	Adding TOTAL sales
	=============================================*/

	static public function mdlAddingTotalPayments($table, $month, $storeid){	

		if ($storeid != null) {

			$stmt = connection::connect()->prepare("SELECT SUM(amount) as total FROM $table where MONTH(date) =:month AND store_id = :storeid");

			$stmt->bindParam(':month',$month,PDO::PARAM_INT);
			$stmt->bindParam(':storeid',$storeid,PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

			$stmt -> closeCursor();

			$stmt = null;

		}else {

			$stmt = connection::connect()->prepare("SELECT SUM(amount) as total FROM $table where MONTH(date) =:month");

			$stmt->bindParam(':month',$month,PDO::PARAM_INT);

			$stmt -> execute();

			return $stmt -> fetch();

			$stmt -> closeCursor();

			$stmt = null;
			
		}

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
    public function insertInvoice($invoiceId, $productsList, $invoiceStartDate, $invoiceDueDate, $invoiceCustomerid, $invoiceTotalTax, $invoiceSubtotal, $invoiceTotal, $invoiceBalace, $invoiceDiscount, $invoiceDueAmount, $invoiceUserId, $storeid, $datecreated){
        $query = "INSERT INTO invoices (invoiceId, products, startdate, duedate, customer_id, totaltax, subtotal, total, balance, discount, dueamount, userId, store_id, datecreated) VALUES (:invoiceId, :products, :startdate, :duedate, :customer_id, :totaltax, :subtotal, :total, :balance, :discount, :dueamount, :userId, :store_id, :datecreated)";

        // Prepare the query
        $stmt = $this->db->prepare($query);


        // Bind the parameters
        $stmt->bindParam(':invoiceId', $invoiceId);
        $stmt->bindParam(':products', $productsList);
        $stmt->bindParam(':startdate', $invoiceStartDate);
        $stmt->bindParam(':duedate', $invoiceDueDate);
        $stmt->bindParam(':customer_id', $invoiceCustomerid);
        $stmt->bindParam(':totaltax', $invoiceTotalTax);
        $stmt->bindParam(':subtotal', $invoiceSubtotal);
        $stmt->bindParam(':total', $invoiceTotal);
        $stmt->bindParam(':balance', $invoiceBalace);
        $stmt->bindParam(':discount', $invoiceDiscount);
        $stmt->bindParam(':dueamount', $invoiceDueAmount);
        $stmt->bindParam(':userId', $invoiceUserId);
		$stmt -> bindParam(":store_id", $storeid);
		$stmt -> bindParam(":datecreated", $datecreated);

		if ($stmt->execute()) {

			// Close the statement and set it to null
			$stmt->closeCursor();

			$stmt = null;

			return true;
			
		} else {

			// Close the statement and set it to null
			$stmt->closeCursor();

			$stmt = null;

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

		$stmt -> closeCursor();

		$stmt = null;
    }

 	/*=============================================
	SHOW INVOICES
	=============================================*/
    public static function mdlShowInvoices($table, $item, $value){

		if ($item == "store_id"){ 

			$stmt = connection::connect()->prepare("SELECT * FROM $table WHERE $item = :$item");

			$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetchAll();

			$stmt -> closeCursor();

			$stmt = null;
			
		} elseif($item != null){

			$stmt = connection::connect()->prepare("SELECT * FROM $table WHERE $item = :$item");

			$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

			$stmt -> closeCursor();

			$stmt = null;

		}
		else{
			$stmt = connection::connect()->prepare("SELECT * FROM $table");

			$stmt -> execute();

			return $stmt -> fetchAll();

			$stmt -> closeCursor();

			$stmt = null;

		}

	}
    
 	/*=============================================
	EDIT INVOICES
	=============================================*/
    public static function mdlEditInvoice($table, $data){

		$stmt = connection::connect()->prepare("UPDATE $table SET dueamount = :due WHERE invoiceId = :invoiceId");

		$stmt->bindParam(":due", $data['newdue'], PDO::PARAM_INT);
		$stmt->bindParam(":invoiceId", $data['invoiceid'], PDO::PARAM_STR);

		if ($stmt->execute()) {

			// Close the statement and set it to null
			$stmt->closeCursor();

			$stmt = null;

			return 'ok';
			
		} else {

			// Close the statement and set it to null
			$stmt->closeCursor();

			$stmt = null;

			return 'error';

		}

	}


    /*=============================================
	DATES RANGE
	=============================================*/	

	 public static function mdlSalesDatesRange($table, $initialDate, $finalDate, $storeid){

		if($initialDate == null){

			$stmt = connection::connect()->prepare("SELECT * FROM $table WHERE store_id = :storeid ORDER BY invoiceId ASC");

			$stmt->bindParam(':storeid', $storeid, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetchAll();	
			
			$stmt->closeCursor();

			$stmt = null;


		}else if($initialDate == $finalDate){

			$stmt = connection::connect()->prepare("SELECT * FROM $table WHERE startdate like '%$finalDate%' AND store_id = :storeid");
			
			$stmt -> bindParam(':storeid', $storeid, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetchAll();
			
			$stmt->closeCursor();

			$stmt = null;

		}else{

			$actualDate = new DateTime();
			$actualDate ->add(new DateInterval("P1D"));
			$actualDatePlusOne = $actualDate->format("Y-m-d");

			$finalDate2 = new DateTime($finalDate);
			$finalDate2 ->add(new DateInterval("P1D"));
			$finalDatePlusOne = $finalDate2->format("Y-m-d");

			if($finalDatePlusOne == $actualDatePlusOne){

				$stmt = connection::connect()->prepare("SELECT * FROM $table WHERE startdate BETWEEN '$initialDate' AND '$finalDatePlusOne' AND store_id = :storeid");
				$stmt->bindParam(':storeid', $storeid);

			}else{

				$stmt = connection::connect()->prepare("SELECT * FROM $table WHERE startdate BETWEEN '$initialDate' AND '$finalDate' AND store_id = :storeid");
				$stmt->bindParam(':storeid', $storeid);

			}
		
			$stmt -> execute();

			return $stmt -> fetchAll();
			
			$stmt->closeCursor();

			$stmt = null;

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

		$stmt->closeCursor();

		$stmt = null;

	}
}



