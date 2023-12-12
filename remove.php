<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['userID'])) {
        $userId = $_POST['userID'];

        $checkRoleQuery = "SELECT role FROM users WHERE id = ?";
        $stmtRole = $conn->prepare($checkRoleQuery);

        if ($stmtRole) {
            $stmtRole->bind_param("i", $userId);
            $stmtRole->execute();
            $stmtRole->bind_result($userRole);
            $stmtRole->fetch();
            $stmtRole->close();

            if ($userRole !== 'scrumMaster' && $userRole !== 'prodOwner') {
                $updateQuery = "UPDATE users SET equipeID = 0 WHERE id = ?";
                $stmt = $conn->prepare($updateQuery);

                if ($stmt) {
                    $stmt->bind_param("i", $userId);
                    $stmt->execute();
                    $stmt->close();

                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                    exit;
                } else {
                    echo "Error in prepared statement for update.";
                }
            } else {
                echo "Scrum masters and Product owners cannot be removed.";
                header('Refresh: 2; URL=./dashboardScrum.php');
            }
        } else {
            echo "Error in prepared statement for role check.";
        }
    } else {
        echo "User ID not provided.";
    }
} else {
    echo "Invalid request method.";
}
?>
