<?php
include 'connection.php';

class User {
    private $conn;
    private $id;
    private $image;
    private $firstName;
    private $lastName;
    private $email;
    private $pass;
    private $phoneNum;
    private $role;
    private $equipeID;

    public function __construct($image, $firstName, $lastName, $email, $pass, $phoneNum, $role, $equipeID) {
        $this->conn = new mysqli('localhost', 'root', '', 'datawareSite');

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // getters
    public function getId() {
        return $this->id;
    }

    public function getImage() {
        return $this->image;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPass() {
        return $this->pass;
    }

    public function getPhoneNum() {
        return $this->phoneNum;
    }

    public function getRole() {
        return $this->role;
    }

    public function getEquipeID() {
        return $this->equipeID;
    }

    // setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setImage($image) {
        $this->image = $image;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPass($pass) {
        $this->pass = $pass;
    }

    public function setPhoneNum($phoneNum) {
        $this->phoneNum = $phoneNum;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function setEquipeID($equipeID) {
        $this->equipeID = $equipeID;
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

    public static function isValidCredentials($conn, $email, $password) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            return password_verify($password, $row['pass']);
        }

        return false;
    }

    public static function getUserByEmail($conn, $email) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
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

    public static function initSession($conn, $email) {
        $currentPage = basename($_SERVER['PHP_SELF']);
        $dashboardPages = ['dashboardUser.php', 'dashboardScrum.php', 'dashboardProd.php'];

        $user = User::getUserByEmail($conn, $email);

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

    public static function checkAuthentication() {
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

    public function registerUser() {
        $firstName = $this->getFirstName();
        $lastName = $this->getLastName();
        $email = $this->getEmail();
        $phoneNumber = $this->getPhoneNum();
        $password = $this->getPass();
        $teamImage = $this->getImage();

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
?>