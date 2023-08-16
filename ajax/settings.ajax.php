<?php
require_once "../models/connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // Fetch loyalty settings
    $stmt = connection::connect()->prepare("SELECT * FROM loyaltysettings");

    $stmt->execute();
    
    $settings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($settings);

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Update loyalty setting
    if (isset($_POST['item']) && isset($_POST['value'])) {

        $item = $_POST['item'];
        $value = $_POST['value'];

        // Validate and sanitize user input (ensure $item is a valid column name)
        $validColumns = ["Lipamdogomdogo", "Loyaltypoints"]; // List of valid columns
        if (in_array($item, $validColumns)) {

            $stmt = connection::connect()->prepare("UPDATE loyaltysettings SET SettingValue = :value WHERE SettingName = :item");

            $stmt->bindParam(':value', $value, PDO::PARAM_INT);
            $stmt->bindParam(':item', $item, PDO::PARAM_STR);

            $stmt->execute();

        } else {
            echo json_encode("Invalid item");
        }

    }

}
