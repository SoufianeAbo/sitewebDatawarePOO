<?php
include 'connection.php';
require_once './includes/prodOwner.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedTeam = $_POST['selectedTeam'];
    $selectedMember = $_POST['selectedMember'];

    $prodOwner = new ProdOwner();

    $prodOwner->assignScrumMasterToProject($selectedTeam, $selectedMember);
}
?>