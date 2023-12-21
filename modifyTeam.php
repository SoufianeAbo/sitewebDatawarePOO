<?php
session_start();
include 'connection.php';
include './includes/scrumMaster.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $scrumMaster = new ScrumMaster();
    $scrumMaster->updateTeam($_POST['formName'], $_POST['formDescription'], $_FILES['teamImage'], $_POST['selectedModify']);
}
?>
