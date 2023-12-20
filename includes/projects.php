<?php
include 'connection.php';

class Project
{
    private $conn;

    public function __construct()
    {
        $this->conn = new mysqli('localhost', 'root', '', 'datawareSite');

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getProjectDetailsByTeamId($teamId)
    {
        $sql = "SELECT * FROM teams WHERE id = $teamId";
        $result = $this->conn->query($sql);

        $projects = array();

        while ($row = $result->fetch_assoc()) {
            $projectId = $row['projectID'];
            $project = $this->getProjectDetailsById($projectId);
            $projects[] = $project;
        }

        return $projects;
    }

    public function getProjectDetailsById($projectId)
    {
        $sql = "SELECT * FROM projects WHERE id = $projectId";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $project = new Project(
                $row['id'],
                $row['image'],
                $row['name'],
                $row['description'],
                $row['scrumMasterID'],
                $row['productOwnerID'],
                $row['date_start'],
                $row['date_end'],
                $row['statut']
            );

            return $project;
        } else {
            return null;
        }
    }

    public function __destruct()
    {
        $this->conn->close();
    }
}
?>