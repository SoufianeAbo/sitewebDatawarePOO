<?php
require_once './includes/prodOwner.php';

$prodOwner = new ProdOwner();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['dropdownRole'], $_POST['userId'])) {
        $dropdownRole = $_POST['dropdownRole'];
        $userId = $_POST['userId'];
        $prodOwner->updateUserRole($dropdownRole, $userId);
    } else {
        echo "Error: Selected role or user ID not found in the form data.";
    }
} else {
    echo "Error: This script should be accessed via a POST request.";
}
?>
