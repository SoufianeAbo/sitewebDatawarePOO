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
            return $row;
        } else {
            return null;
        }
    }

    public function getProdOwnerDetails($prodOwnerID) {
        $prodOwnerQuery = "SELECT * FROM users WHERE id = $prodOwnerID";
        $prodOwnerResult = $this->conn->query($prodOwnerQuery);

        if ($prodOwnerResult->num_rows > 0) {
            return $prodOwnerResult->fetch_assoc();
        } else {
            return array(
                'firstName' => 'N/A',
                'lastName' => 'N/A'
            );
        }
    }

    public function __destruct()
    {
        $this->conn->close();
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

    public function displayProjectDetails($teamId)
    {
        $sql = "SELECT projectID FROM teams WHERE id = $teamId";
        $result = $this->conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            $projectID = $row['projectID'];
            $projectDetails = $this->getProjectDetailsById($projectID);

            if ($projectDetails) {
                $scrumMasterDetails = $this->getScrumMasterDetails($projectDetails['scrumMasterID']);
                $prodMasterDetails = $this->getProdOwnerDetails($projectDetails['productOwnerID']);

                $this->displayProjectCard($projectDetails, $scrumMasterDetails, $prodMasterDetails);
            } else {

            }
        }
    }

    public function displayUserProjects($equipeID, $currentMemberID, $function = null)
    {
        $sql = "SELECT projectID FROM teams WHERE id = $equipeID";
        $result = $this->conn->query($sql);
    
        while ($row = $result->fetch_assoc()) {
            $projectsQuery = "SELECT * FROM projects WHERE productOwnerID = $currentMemberID";
            $projectsResult = $this->conn->query($projectsQuery);
    
            if ($projectsResult->num_rows > 0) {
                while ($projectsData = $projectsResult->fetch_assoc()) {
                    $scrumMasterDetails = $this->getScrumMasterDetails($projectsData['scrumMasterID']);
                    $prodMasterDetails = $this->getProdOwnerDetails($projectsData['productOwnerID']);
    
                    $this->displayProjectCard($projectsData, $scrumMasterDetails, $prodMasterDetails, $function);
                }
            } else {

            }
        }
    }    


    private function displayProjectCard($projectDetails, $scrumMasterDetails, $prodMasterDetails, $function = null)
    {
        if ($function == 1) {
            echo '<div class="max-w-sm bg-white border border-gray-200 rounded-lg shadow teamSelect transition-all" data-id="'. $projectDetails['id'] .'">';
        } else {
            echo '<div class="max-w-sm bg-white border border-gray-200 rounded-lg shadow">';
        }
        echo '<a href="#">';
        echo "<img class='rounded-t-lg' src='{$projectDetails['image']}' alt='' />";
        echo '</a>';
        echo '<div class="p-5">';
        echo '<div class="flex justify-between">';
        echo '<a href="#" class="flex flex-col">';
        echo "<h5 class='text-2xl font-bold tracking-tight text-gray-900'>{$projectDetails['name']}</h5>";
        echo "<p class='text-red-900'><i class='fa-solid fa-user-gear pr-2'></i>{$prodMasterDetails['firstName']} {$prodMasterDetails['lastName']}</p>";
        echo "<p class='mb-4 text-green-900'><i class='fa-solid fa-user-pen pr-2'></i>{$scrumMasterDetails['firstName']} {$scrumMasterDetails['lastName']}</p>";
        echo '</a>';
        echo '';
        echo "<img src='{$prodMasterDetails['image']}' alt='' class='w-[14%] h-[14%] rounded-full border-2 border-red-700 relative'>";
        echo '</div>';
        echo "<p class='mb-3 font-normal text-gray-700'>{$projectDetails['description']}</p>";
        echo '<div class="flex flex-row items-center justify-between">';
        if ($function == 0) {
            if ($_SESSION['role'] == 'prodOwner') {
                echo '<div class = "flex flex-col gap-2">';
                echo '<div class = "flex flex-row gap-2">';
                echo '<div>';
                echo '<a href="#" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 addBtn">';
                echo '<i class="fa-solid fa-user-pen mr-2"></i>Assign';
                echo '</a>';
                echo '</div>';
                echo '<div>';
                echo '<a href="#" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-orange-700 rounded-lg hover:bg-orange-800 focus:ring-4 focus:outline-none focus:ring-orange-300 modifyBtn" data-id="'. $projectDetails['id'] .'">';
                echo '<i class="fa-solid fa-gear mr-2"></i>Modify';
                echo '</a>';
                echo '</div>';
                echo '</div>';
                echo '<div>';
                echo '<a href="deleteProject.php?id='. $projectDetails['id'] .'" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 addBtn">';
                echo '<i class="fa-solid fa-trash mr-2"></i>Delete';
                echo '</a>';
                echo '</div>';
                echo '</div>';
            } else {
                echo '<div>';
                echo '<a href="#" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">';
                echo '<i class="fa-solid fa-user-pen mr-2"></i>More details';
                echo '</a>';
                echo '</div>';
            }
            echo '<div class="flex flex-col items-center">';
            echo "<p class='text-gray-500'>{$projectDetails['date_start']}</p>";
            echo "<p class='text-gray-500'>{$projectDetails['date_end']}</p>";
            if ($projectDetails['statut'] == 'Active') {
                echo '<p class="text-green-500">Active</p>';
            }
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        } else {
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    }
}
?>