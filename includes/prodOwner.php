<?php
include 'connection.php';
require_once './includes/user.php';

class ProdOwner extends User {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli('localhost', 'root', '', 'datawareSite');

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function updateUserRole($selectedRole, $userId) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->validateForm($selectedRole, $userId);

            $sql = "UPDATE users SET role = '$selectedRole' WHERE id = $userId";

            if ($this->conn->query($sql) === TRUE) {
                echo "User role updated successfully";
                header('Location: dashboardProd.php');
            } else {
                echo "Error updating user role: " . $this->conn->error;
            }
        } else {
            echo "Error: This script should be accessed via a POST request.";
        }
    }

    private function validateForm($selectedRole, $userId) {
        if (!isset($selectedRole, $userId)) {
            echo "Error: Selected role or user ID not found in the form data.";
            exit();
        }
    }

    public function getScrumMasters() {
        $query = "SELECT * FROM users u
                    WHERE u.role = 'scrumMaster'
                    AND u.id NOT IN (
                        SELECT scrumMasterID FROM projects
                        WHERE scrumMasterID IS NOT NULL
                    )";

        $result = $this->conn->query($query);
        $members = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $members[] = $row;
            }
        }

        return $members;
    }

    public function assignScrumMasterToProject($selectedTeam, $selectedMember) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $updateProjectSql = "UPDATE projects SET scrumMasterID = ? WHERE id = ?";
            $stmtProject = $this->conn->prepare($updateProjectSql);

            if ($stmtProject) {
                $stmtProject->bind_param("ii", $selectedMember, $selectedTeam);
                $stmtProject->execute();

                if ($stmtProject->affected_rows > 0) {
                    $updateTeamsSql = "UPDATE teams SET projectID = ? WHERE scrumMasterID = ?";
                    $stmtTeams = $this->conn->prepare($updateTeamsSql);

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
        } else {
            echo "Error: This script should be accessed via a POST request.";
        }
    }
}