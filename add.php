<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedTeam = $_POST['selectedTeam'];
    $selectedMember = $_POST['selectedMember'];

    $updateSql = "UPDATE users SET equipeID = ? WHERE id = ?";

    $stmt = $conn->prepare($updateSql);
    
    if ($stmt) {
        $stmt->bind_param("ii", $selectedTeam, $selectedMember);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            header('Location: dashboardScrum.php');
            exit;
        } else {
            echo "Error updating user's team ID.";
        }

        $stmt->close();
    } else {
        echo "Error preparing SQL statement.";
    }
}
?>