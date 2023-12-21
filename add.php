<?php
include 'connection.php';
include './includes/scrumMaster.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedTeam = $_POST['selectedTeam'];
    $selectedMember = $_POST['selectedMember'];

    $scrumMaster = new ScrumMaster();
    $scrumMaster->addMemberToTeam($selectedTeam, $selectedMember);
}
?>