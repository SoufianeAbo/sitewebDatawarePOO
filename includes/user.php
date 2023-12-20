<?php
include 'connection.php';

class User {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli('localhost', 'root', '', 'datawareSite');

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getAllUsers() {
        $sql = "SELECT * FROM users";
        $result = $this->conn->query($sql);

        $users = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $user = new User(
                    $row['id'],
                    $row['image'],
                    $row['firstName'],
                    $row['lastName'],
                    $row['email'],
                    $row['pass'],
                    $row['phoneNum'],
                    $row['role'],
                    $row['equipeID']
                );
                $users[] = $user;
            }
        }

        return $users;
    }

    public function getUser($userId) {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user = new User(
                $row['id'],
                $row['image'],
                $row['firstName'],
                $row['lastName'],
                $row['email'],
                $row['pass'],
                $row['phoneNum'],
                $row['role'],
                $row['equipeID']
            );
            return $user;
        } else {
            return null;
        }
    }

    public function __destruct() {
        $this->conn->close();
    }

    public function isValidCredentials($email, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            return password_verify($password, $row['pass']);
        }

        return false;
    }

    public function getUserByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        } else {
            return null;
        }
    }

    public function initSession($email) {
        $user = $this->getUserByEmail($email);

        if ($user) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['image'] = $user['image'];
            $_SESSION['firstName'] = $user['firstName'];
            $_SESSION['lastName'] = $user['lastName'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['phoneNum'] = $user['phoneNum'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['equipeID'] = $user['equipeID'];

            if ($_SESSION['role'] == 'user') {
                header("Location: dashboardUser.php");
                exit();
            } else if ($_SESSION['role'] == 'scrumMaster') {
                header("Location: dashboardScrum.php");
                exit();
            } else if ($_SESSION['role'] == 'prodOwner') {
                header("Location: dashboardProd.php");
                exit();
            }
        } else {
            header("Location: login.php");
            exit();
        }
    }

    public function checkAuthentication() {
        if (isset($_SESSION['id'])) {
            if ($_SESSION['role'] == 'user') {
                header("Location: dashboardUser.php");
                exit();
            } else if ($_SESSION['role'] == 'scrumMaster') {
                header("Location: dashboardScrum.php");
                exit();
            } else if ($_SESSION['role'] == 'prodOwner') {
                header("Location: dashboardProd.php");
                exit();
            }
        } else {
            return;
        }
    }
}

$userObj = new User();

$userId = 1;
$singleUser = $userObj->getUser($userId);
?>