<?php
include 'connection.php';

if (isset($_GET['id'])) {
    $projectId = $_GET['id'];

    $updateTeamsQuery = "UPDATE teams SET projectID = 0 WHERE projectID = $projectId";

    $updateTeamsResult = mysqli_query($conn, $updateTeamsQuery);

    if ($updateTeamsResult) {
        $deleteProjectQuery = "DELETE FROM projects WHERE id = $projectId";

        $deleteProjectResult = mysqli_query($conn, $deleteProjectQuery);

        if ($deleteProjectResult) {
            // echo "Project and associated teams deleted successfully.";
            header("Location: dashboardProd.php");
        } else {
            echo "Error deleting project: " . mysqli_error($conn);
        }
    } else {
        echo "Error updating team records: " . mysqli_error($conn);
    }
} else {
    echo "Project ID not provided.";
}
?>