<?php
    require_once '../models/connection.php';

    $barcode = $_GET['barcode'];
    $table = 'products';

    $pdo = connection::connect();

    $select = $pdo->prepare("SELECT * FROM $table WHERE barcode = :barcode");
    $select->bindParam(':barcode', $barcode);
    $select->execute();

    $row = $select->fetch(PDO::FETCH_ASSOC);

    $response = $row;

    header('Content-Type: application/json');
    echo json_encode($response);
?>