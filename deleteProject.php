<?php
session_start();
include 'connection.php';
require_once './includes/prodOwner.php';

$prodOwner = new ProdOwner();

if (isset($_GET['id'])) {
    $projectId = $_GET['id'];

    $prodOwner->deleteProject($projectId);
} else {
    echo "Project ID not provided.";
}
?>