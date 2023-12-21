<?php
session_start();
include 'connection.php';
include './includes/scrumMaster.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $scrumMaster = new ScrumMaster();
    $scrumMaster->createTeam($_POST['formName'], $_POST['formDescription'], $_FILES['teamImage']);
}
?>