<?php
include 'connection.php';

if (isset($_GET['id'])) {
    $teamId = $_GET['id'];
    $currentMemberID = $_SESSION['id'];

    echo "Team ID: " . $teamId;

    $sqlCheckUsers = "SELECT COUNT(*) AS userCount FROM users WHERE equipeID = ?";
    $stmtCheckUsers = $conn->prepare($sqlCheckUsers);
    
    if ($stmtCheckUsers) {
        $stmtCheckUsers->bind_param("i", $teamId);
        $stmtCheckUsers->execute();
        $resultCheckUsers = $stmtCheckUsers->get_result();
        $rowCheckUsers = $resultCheckUsers->fetch_assoc();
        $userCount = $rowCheckUsers['userCount'];

        if ($userCount > 0) {
            echo "Cannot delete the team. There are users in the team.";
        } else {
            $sqlDeleteTeam = "DELETE FROM teams WHERE id = ? AND scrumMasterID = ?";
            $stmtDeleteTeam = $conn->prepare($sqlDeleteTeam);

            if ($stmtDeleteTeam) {
                $stmtDeleteTeam->bind_param("ii", $teamId, $currentMemberID);
                $stmtDeleteTeam->execute();

                if ($stmtDeleteTeam->affected_rows > 0) {
                    echo "Team deleted successfully.";
                } else {
                    echo "Failed to delete the team.";
                }

                $stmtDeleteTeam->close();
            } else {
                echo "Failed to prepare delete statement.";
            }
        }

        $stmtCheckUsers->close();
    } else {
        echo "Failed to prepare check users statement.";
    }
} else {
    echo "Team ID not provided in the URL.";
}
?>
