<?php
include 'connection.php';
require_once './includes/user.php';

class ScrumMaster extends User {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli('localhost', 'root', '', 'datawareSite');

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getAllUsersNT() {
        $sql = "SELECT * FROM users WHERE equipeID = 0";
        $result = $this->conn->query($sql);

        $users = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }

        return $users;
    }

    public function addMemberToTeam($selectedTeam, $selectedMember) {
        $updateSql = "UPDATE users SET equipeID = ? WHERE id = ?";

        $stmt = $this->conn->prepare($updateSql);

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

    public function createTeam($formName, $formDescription, $teamImage) {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $uploadDir = './img/';
            $uploadPath = $uploadDir . basename($teamImage['name']);

            if ($teamImage['size'] > 0 && $teamImage['size'] <= 10 * 1024 * 1024 && ($teamImage['type'] === 'image/jpeg' || $teamImage['type'] === 'image/png')) {
                list($width, $height) = getimagesize($teamImage['tmp_name']);
                if ($width >= 1152 && $height >= 768) {
                    move_uploaded_file($teamImage['tmp_name'], $uploadPath);

                    $scrumMasterID = $_SESSION['id'];
                    $projectIDQuery = "SELECT id FROM projects WHERE scrumMasterID = ?";
                    $stmtProjectID = $this->conn->prepare($projectIDQuery);

                    $stmtProjectID->bind_param("i", $scrumMasterID);
                    $stmtProjectID->execute(); 

                    $stmtProjectID->bind_result($projectID);
                    $stmtProjectID->fetch();
                    $stmtProjectID->close();

                    $insertSql = "INSERT INTO teams (image, description, teamName, projectID, scrumMasterID) VALUES (?, ?, ?, ?, ?)";
                    $stmt = $this->conn->prepare($insertSql);
                    $stmt->bind_param("sssss", $uploadPath, $formDescription, $formName, $projectID, $scrumMasterID);
                    $stmt->execute();
                    $stmt->close();

                    header('Location: dashboardScrum.php');
                    exit;
                } else {
                    echo 'Invalid image dimensions. Please upload an image with a minimum size of 1152x768 pixels.';
                }
            } else {
                echo 'Invalid file. Please upload a valid image file (JPEG or PNG) with a size less than 1MB.';
            }
        }
    }

    public function deleteTeam($teamId) {
        $currentMemberID = $_SESSION['id'];

        echo "Team ID: " . $teamId;

        $sqlCheckUsers = "SELECT COUNT(*) AS userCount FROM users WHERE equipeID = ?";
        $stmtCheckUsers = $this->conn->prepare($sqlCheckUsers);

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
                $stmtDeleteTeam = $this->conn->prepare($sqlDeleteTeam);

                if ($stmtDeleteTeam) {
                    $stmtDeleteTeam->bind_param("ii", $teamId, $currentMemberID);
                    $stmtDeleteTeam->execute();

                    if ($stmtDeleteTeam->affected_rows > 0) {
                        echo "Team deleted successfully.";
                    } else {
                        echo "Failed to delete the team.";
                    }

                } else {
                    echo "Failed to prepare delete statement.";
                }
            }

        } else {
            echo "Failed to prepare check users statement.";
        }
    }

    public function removeUser($userId) {
        $userRole = $_SESSION['role'];
        $checkRoleQuery = "SELECT role FROM users WHERE id = ?";
        $stmtRole = $this->conn->prepare($checkRoleQuery);

        if ($stmtRole) {
            $stmtRole->bind_param("i", $userId);
            $stmtRole->execute();
            $stmtRole->bind_result($userRole);
            $stmtRole->fetch();
            $stmtRole->close();

            if ($userRole !== 'scrumMaster' && $userRole !== 'prodOwner') {
                $updateQuery = "UPDATE users SET equipeID = 0 WHERE id = ?";
                $stmt = $this->conn->prepare($updateQuery);

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
    }
}

?>