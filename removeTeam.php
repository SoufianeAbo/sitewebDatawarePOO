<?php
session_start();
include 'connection.php';
include './includes/scrumMaster.php';

if (isset($_GET['id'])) {
    $teamId = $_GET['id'];
    $scrumMaster = new ScrumMaster();
    $scrumMaster->deleteTeam($teamId);
} else {
    echo "Team ID not provided in the URL.";
}
?>
