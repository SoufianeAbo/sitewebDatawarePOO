<?php
include 'connection.php';
include './includes/user.php';

class ScrumMaster extends User {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli('localhost', 'root', '', 'datawareSite');

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
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