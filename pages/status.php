<?php
    include_once '../include/config.php';
    session_start();

    $ecode = $_SESSION['empcode'];

    if(isset($_POST['submitBtn'])){
		$ctype = $_POST["ctype"];
		$sub = $_POST["subject"];
        $descr = $_POST["descr"];
		$fwd = $_POST["forward"];

        $target_dir = "../uploads/";
        $target_file = $target_dir.basename($_FILES["uploadedFile"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Allow certain file formats
        if (!empty($_FILES["uploadedFile"]["name"])) {
            if ($imageFileType != "pdf" && $imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png") {
                echo "<script>alert('Sorry, only PDF, JPG, JPEG & PNG files are allowed.');</script>";
                $uploadOk = 0;
            }
            else {
                if (move_uploaded_file($_FILES["uploadedFile"]["tmp_name"], $target_file)){
                    $db_user = $dbo->query("INSERT INTO complaints ( ctype, sub, descr, empcode, compdate, uploadedFile, forwardto) VALUES ('$ctype', '$sub', '$descr', '$ecode', CURDATE(), '$target_file', '$fwd')");
                    echo "<script>alert('Complaint has been registered.');</script>";
                }
            }
        }
        else {
            $db_user = $dbo->query("INSERT INTO complaints (ctype, sub, descr, empcode, compdate, forwardto) VALUES ('$ctype', '$sub', '$descr', '$ecode', CURDATE(), '$fwd')");
            echo "<script>alert('Complaint has been registered.');</script>";
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <link rel="stylesheet" href="../css/styles.css" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
        <title>Welcome</title>
    </head>
    <body>           
        <!-- navbar+sidebar(from menu.php)+ main content  --> 
        <div class="wrapper">
            <?php include 'menu.php';?>
                <h2>Employee Complaint Form</h2>
                
        </div>

    <!-- Overlay for small screen dark background -->
    <div class="overlay" id="overlay"></div>

        <script src="../js/script.js"></script>
    </body>
</html>