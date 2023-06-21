<?php
require_once('../../models/connection.php');

// Get the current date
$currentDate = date("Y-m-d");

echo $currentDate;

try {
    // Establish the database connection
    $pdo = connection::connect();

    // Update expired discounts
    $sql = "UPDATE `discount` SET `status` = CASE
        WHEN `enddate` <= :currentDate THEN 0
        WHEN `startdate` >= :currentDate THEN 1
        ELSE `status`
        END";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':currentDate', $currentDate);
    $stmt->execute();

    echo "Discount status updated successfully.";
} catch (PDOException $e) {
    echo "Error updating discount status: " . $e->getMessage();
}
?>
