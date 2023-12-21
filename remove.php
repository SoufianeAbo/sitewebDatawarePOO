<?php
include 'connection.php';
include './includes/scrumMaster.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['userID'])) {
        $scrumMaster = new ScrumMaster();
        $scrumMaster->removeUser($_POST['userID']);
    } else {
        echo "User ID not provided.";
    }
} else {
    echo "Invalid request method.";
}
?>
