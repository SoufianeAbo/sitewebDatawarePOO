<?php
include 'connection.php';

class Team {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli('localhost', 'root', '', 'datawareSite');

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getTeamDetailsBySession() {
        $equipeID = $_SESSION['equipeID'];
        $sql = "SELECT * FROM teams WHERE id = $equipeID";
        $result = $this->conn->query($sql);

        $teams = array();

        while ($row = $result->fetch_assoc()) {
            $teams[] = $row;
        }

        return $teams;
    }

    public function getTeamById($equipeID) {
        $sql = "SELECT * FROM teams WHERE id = $equipeID";
        $result = $this->conn->query($sql);

        $teams = array();

        while ($row = $result->fetch_assoc()) {
            $teams[] = $row;
        }

        return $teams;
    }

    public function getTeamByIdScrum($equipeID, $currentMemberID) {
        $sql = "SELECT * FROM teams WHERE scrumMasterID = $currentMemberID OR id = $equipeID";
        $result = $this->conn->query($sql);

        $teams = array();

        while ($row = $result->fetch_assoc()) {
            $teams[] = $row;
        }

        return $teams;
    }

    public function getScrumMasterDetails($scrumMasterID) {
        $scrumMasterQuery = "SELECT * FROM users WHERE id = $scrumMasterID";
        $scrumMasterResult = $this->conn->query($scrumMasterQuery);

        if ($scrumMasterResult->num_rows > 0) {
            return $scrumMasterResult->fetch_assoc();
        } else {
            return array(
                'firstName' => 'N/A',
                'lastName' => 'N/A'
            );
        }
    }

    public function getTeams($condition) {
        $sql = "SELECT * FROM teams";

        if (!empty($condition)) {
            $sql .= " WHERE " . $condition;
        }

        $result = $this->conn->query($sql);

        $teams = array();

        while ($row = $result->fetch_assoc()) {
            $teams[] = $row;
        }

        return $teams;
    }

    public function __destruct() {
        $this->conn->close();
    }
}