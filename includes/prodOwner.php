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

    public function createProject($formName, $formDescription, $formDate, $teamImage) {
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
                $currentMemberID = $_SESSION['id'];

                $statut = 'Active';
                $insertSql = "INSERT INTO projects (image, name, description, scrumMasterID, productOwnerID, date_start, date_end, statut) VALUES (?, ?, ?, 0, ?, CURDATE(), ?, ?)";
                $stmt = $this->conn->prepare($insertSql);
                $stmt->bind_param("sssiss", $uploadPath, $formName, $formDescription, $currentMemberID, $formDate, $statut);
                $stmt->execute();
                $stmt->close();

                header('Location: dashboardProd.php');
                exit;
            } else {
                echo 'Invalid image dimensions. Please upload an image with a minimum size of 1152x768 pixels.';
            }
        } else {
            echo 'Invalid file. Please upload a valid image file (JPEG or PNG) with a size less than 1MB.';
        }
    }

    public function updateProjectDetails($formName, $formDescription, $formDate, $selectedModify, $teamImage) {
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
                $currentMemberID = $_SESSION['id'];

                $updateSql = "UPDATE projects SET image=?, name=?, description=?, date_end=? WHERE id=?";
                $stmt = $this->conn->prepare($updateSql);
                $stmt->bind_param("ssssi", $uploadPath, $formName, $formDescription, $formDate, $selectedModify);
                $stmt->execute();
                $stmt->close();

                header('Location: dashboardProd.php');
                exit;
            } else {
                echo 'Invalid image dimensions. Please upload an image with a minimum size of 1152x768 pixels.';
            }
        } else {
            echo 'Invalid file. Please upload a valid image file (JPEG or PNG) with a size less than 1MB.';
        }
    }
}