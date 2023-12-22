<?php
session_start();

include "connection.php";
require_once "./includes/prodOwner.php";
$prodOwner = new ProdOwner();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formName = $_POST['formName'];
    $formDescription = $_POST['formDescription'];
    $formDate = $_POST['formDate'];
    $teamImage = $_FILES['teamImage'];

    $prodOwner->createProject($formName, $formDescription, $formDate, $teamImage);
}
?>