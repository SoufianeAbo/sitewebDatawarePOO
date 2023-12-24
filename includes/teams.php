<?php
include 'connection.php';

class Team {
    private $conn;
    private $id;
    private $name;
    private $scrumMasterID;

    public function __construct() {
        $this->conn = new mysqli('localhost', 'root', '', 'datawareSite');

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getScrumMasterID() {
        return $this->scrumMasterID;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setScrumMasterID($scrumMasterID) {
        $this->scrumMasterID = $scrumMasterID;
    }

    public function getTeamDetailsBySession() {
        $equipeID = $_SESSION['equipeID'];
        $sql = "SELECT * FROM teams WHERE id = $equipeID";
        $result = $this->conn->query($sql);

        $teams = array();

        while ($row = $result->fetch_assoc()) {
            $team = new Team();
            $team->setId($row['id']);
            $team->setName($row['name']);
            $team->setScrumMasterID($row['scrumMasterID']);
            $teams[] = $team;
        }

        return $teams;
    }

    public function getTeamById($equipeID) {
        $sql = "SELECT * FROM teams WHERE id = $equipeID";
        $result = $this->conn->query($sql);

        $teams = array();

        while ($row = $result->fetch_assoc()) {
            $team = new Team();
            $team->setId($row['id']);
            $team->setName($row['name']);
            $team->setScrumMasterID($row['scrumMasterID']);
            $teams[] = $team;
        }

        return $teams;
    }

    public function getTeamByIdScrum($equipeID, $currentMemberID) {
        $sql = "SELECT * FROM teams WHERE scrumMasterID = $currentMemberID OR id = $equipeID";
        $result = $this->conn->query($sql);

        $teams = array();

        while ($row = $result->fetch_assoc()) {
            $team = new Team();
            $team->setId($row['id']);
            $team->setName($row['name']);
            $team->setScrumMasterID($row['scrumMasterID']);
            $teams[] = $team;
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
            $team = new Team();
            $team->setId($row['id']);
            $team->setName($row['name']);
            $team->setScrumMasterID($row['scrumMasterID']);
            $teams[] = $team;
        }

        return $teams;
    }

    public function __destruct() {
        $this->conn->close();
    }
}
?>