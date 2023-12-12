<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['dropdownRole'], $_POST['userId'])) {
        $selectedRole = $_POST['dropdownRole'];
        $userId = $_POST['userId'];

        include 'connection.php';
        $sql = "UPDATE users SET role = '$selectedRole' WHERE id = $userId";

        echo $selectedRole;
        echo $userId;
        if ($conn->query($sql) === TRUE) {
            echo "User role updated successfully";
            header('Location: dashboardProd.php');
        } else {
            echo "Error updating user role: " . $conn->error;
        }

        $conn->close();
    } else {
        echo "Error: Selected role or user ID not found in the form data.";
    }
} else {
    echo "Error: This script should be accessed via a POST request.";
}
?>