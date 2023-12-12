<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedTeam = $_POST['selectedTeam'];
    $selectedMember = $_POST['selectedMember'];

    $updateProjectSql = "UPDATE projects SET scrumMasterID = ? WHERE id = ?";
    $stmtProject = $conn->prepare($updateProjectSql);

    if ($stmtProject) {
        $stmtProject->bind_param("ii", $selectedMember, $selectedTeam);
        $stmtProject->execute();
        if ($stmtProject->affected_rows > 0) {
            $updateTeamsSql = "UPDATE teams SET projectID = ? WHERE scrumMasterID = ?";
            $stmtTeams = $conn->prepare($updateTeamsSql);

            if ($stmtTeams) {
                $stmtTeams->bind_param("ii", $selectedTeam, $selectedMember);
                $stmtTeams->execute();
                if ($stmtTeams->affected_rows > 0) {
                    header('Location: dashboardProd.php');
                    exit;
                } else {
                    echo "Error updating projectID for teams.";
                }
                $stmtTeams->close();
            } else {
                echo "Error preparing SQL statement for updating teams.";
            }
        } else {
            echo "Error updating user's team ID.";
        }

        $stmtProject->close();
    } else {
        echo "Error preparing SQL statement for updating the project.";
    }
}
?>