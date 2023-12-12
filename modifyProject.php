<?php
session_start();
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formName = $_POST['formName'];
    $formDescription = $_POST['formDescription'];
    $formDate = $_POST['formDate'];
    $selectedModify = $_POST['selectedModify'];

    $uploadDir = './img/';
    $teamImage = $_FILES['teamImage'];

    $uploadPath = $uploadDir . basename($teamImage['name']);

    if ($teamImage['size'] > 0 && $teamImage['size'] <= 10 * 1024 * 1024 && ($teamImage['type'] === 'image/jpeg' || $teamImage['type'] === 'image/png')) {
        list($width, $height) = getimagesize($teamImage['tmp_name']);
        if ($width >= 1152 && $height >= 768) {
            move_uploaded_file($teamImage['tmp_name'], $uploadPath);

            $scrumMasterID = $_SESSION['id'];
            $projectIDQuery = "SELECT id FROM projects WHERE scrumMasterID = ?";
            $stmtProjectID = $conn->prepare($projectIDQuery);

            $stmtProjectID->bind_param("i", $scrumMasterID);
            $stmtProjectID->execute(); 

            $stmtProjectID->bind_result($projectID);
            $stmtProjectID->fetch();
            $stmtProjectID->close();
            $currentMemberID = $_SESSION['id'];

            $insertSql = "UPDATE projects SET image=?, name=?, description=?, date_end=? WHERE id=?";
            $stmt = $conn->prepare($insertSql);
            $stmt->bind_param("ssssi", $uploadPath, $formName, $formDescription, $formDate, $selectedModify);
            $stmt->execute();
            $stmt->close();

            header('Location: dashboardProd.php');
            exit;
        } else {
            echo 'Invalid image dimensions. Please upload an image with a minimum size of 1152x768 pixels.';
        }
    } else {
        echo 'Invalid file. Please upload a valid image file (JPEG or PNG) with a size less than 1MB.';
    }
}
?>