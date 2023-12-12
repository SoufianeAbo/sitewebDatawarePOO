<?php
session_start();
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $email = $_POST["email"];
    $phoneNumber = $_POST["phoneNumber"];
    $password = $_POST["password"];

    $uploadDir = './img/';
    $teamImage = $_FILES['teamImage'];

    $uploadPath = $uploadDir . basename($teamImage['name']);
    $role = "user";

    move_uploaded_file($teamImage['tmp_name'], $uploadPath);
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        header("Location: login.php?exist");
    } else {
        $stmt = $conn->prepare("INSERT INTO users (image, firstName, lastName, email, phoneNum, pass, role, equipeID) VALUES (?, ?, ?, ?, ?, ?, ?, 0)");
        $stmt->bind_param("sssssss", $uploadPath, $firstName, $lastName, $email, $phoneNumber, $password, $role);
        $stmt->execute();
    
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
    
            if ($password === $row['pass']) {
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
?>