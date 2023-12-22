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

    public function getRoleColor($role) {
        if ($role == 'user') {
            return 'gray';
        } elseif ($role == 'scrumMaster') {
            return 'green';
        } elseif ($role == 'prodOwner') {
            return 'red';
        } else {
            return 'black';
        }
    }

    public function getRoleIcon($role) {
        if ($role == 'user') {
            return 'fa-solid fa-user mr-2';
        } elseif ($role == 'scrumMaster') {
            return 'fa-solid fa-user-pen pr-2';
        } elseif ($role == 'prodOwner') {
            return 'fa-solid fa-user-gear pr-2';
        } else {
            return 'fa-solid fa-question';
        }
    }

    public function getRoleName($role) {
        if ($role == 'user') {
            return 'User';
        } elseif ($role == 'scrumMaster') {
            return 'Scrum Master';
        } elseif ($role == 'prodOwner') {
            return 'Product Owner';
        } else {
            return 'Unknown';
        }
    }

    public function getAllUsersFromTeam($equipeID) {
        $sql = "SELECT * FROM users WHERE equipeID = $equipeID";
        $result = $this->conn->query($sql);

        $users = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
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
        $currentPage = basename($_SERVER['PHP_SELF']);
        $dashboardPages = ['dashboardUser.php', 'dashboardScrum.php', 'dashboardProd.php'];

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

            if (isset($_SESSION['id']) && in_array($currentPage, $dashboardPages)) {
                return;
            }

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

    public function registerUser($firstName, $lastName, $email, $phoneNumber, $password, $teamImage) {
        $uploadDir = './img/';
        $uploadPath = $uploadDir . basename($teamImage['name']);
        $role = "user";

        move_uploaded_file($teamImage['tmp_name'], $uploadPath);

        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            header("Location: login.php?exist");
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $this->conn->prepare("INSERT INTO users (image, firstName, lastName, email, phoneNum, pass, role, equipeID) VALUES (?, ?, ?, ?, ?, ?, ?, 0)");
            $stmt->bind_param("sssssss", $uploadPath, $firstName, $lastName, $email, $phoneNumber, $hashedPassword, $role);
            $stmt->execute();

            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                if (password_verify($password, $row['pass'])) {
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['image'] = $row['image'];
                    $_SESSION['firstName'] = $row['firstName'];
                    $_SESSION['lastName'] = $row['lastName'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['phoneNum'] = $row['phoneNum'];
                    $_SESSION['role'] = $row['role'];
                    $_SESSION['equipeID'] = $row['equipeID'];

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
                    echo "<p class='text-red-300'>Invalid username or password.</p>";
                }
            } else {
                echo "<p class='text-red-300'>Registration failed.</p>";
            }
        }
    }
}

$userObj = new User();

$userId = 1;
$singleUser = $userObj->getUser($userId);
?>