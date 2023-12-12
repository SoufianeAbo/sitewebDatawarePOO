<?php
if (isset($_SESSION['email'])) {
    $oldEmail = $_SESSION['email'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $oldEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $row = $result->fetch_assoc();
    $_SESSION['id'] = $row['id'];
    $_SESSION['image'] = $row['image'];
    $_SESSION['firstName'] = $row['firstName'];
    $_SESSION['lastName'] = $row['lastName'];
    $_SESSION['email'] = $row['email'];
    $_SESSION['phoneNum'] = $row['phoneNum'];
    $_SESSION['role'] = $row['role'];
    $_SESSION['equipeID'] = $row['equipeID'];

    if ($_SESSION['role'] !== 'scrumMaster') {
        header("Location: login.php");
        exit();
    }
    // Your code here for the case when 'email' session variable exists
} else {
    // Your code here for the case when 'email' session variable does not exist
    header("Location: login.php");
    exit();
}
?>