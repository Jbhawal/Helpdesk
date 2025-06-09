<?php
    include_once '../include/config.php';
    include 'menu.php';
    // session_start();
    $ecode = $_SESSION['empcode'];
    $listemp = $dbo->query("SELECT EMPNAME, DESG, SEC, DEPT FROM user WHERE empcode ='$ecode'");
    while ($rowemp = $listemp->fetch(PDO::FETCH_ASSOC))
    {	
        $empname = $rowemp["EMPNAME"];
        $desg = $rowemp["DESG"];
        $sec = $rowemp['SEC'];
        $dept = $rowemp['DEPT'];
    }

    if(isset($_POST['submitBtn'])){
		$ctype = $_POST["ctype"];
		$sub = $_POST["subject"];
        $descr = $_POST["descr"];
		$fwd = $_POST["forward"];

        $target_dir = "../uploads/";
        $target_file = $target_dir.basename($_FILES["uploadedFile"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        // Allow certain file formats
        if (!empty($_FILES["uploadedFile"]["name"])) {
            if ($imageFileType != "pdf" && $imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png") {
                echo "<script>alert('Sorry, only PDF, JPG, JPEG & PNG files are allowed.');</script>";
                $uploadOk = 0;
            }
            else {
                if (move_uploaded_file($_FILES["uploadedFile"]["tmp_name"], $target_file)) 
                {
                    $db_user = $dbo->query("INSERT INTO complaints ( ctype, sub, descr, empcode, compdate, uploadedFile, forwardto) VALUES ('$ctype', '$sub', '$descr', '$ecode', CURDATE(), '$target_file', '$fwd')");
                    echo "<script>alert('Complaint has been registered.');</script>";
                }

            }
        }

        else {
            $db_user = $dbo->query("INSERT INTO complaints ( ctype, sub, descr, empcode, compdate, forwardto) VALUES ('$ctype', '$sub', '$descr', '$ecode', CURDATE(), '$fwd')");
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
        <!-- <div class="wrapper"> -->
            <main class="main-content"> 
                <h2>Employee Complaint Form</h2>
                <div id="complaint-container">
                    <form action="" method="post" enctype="multipart/form-data" class="complaint-form" id="complaint-form">
                        <div class="input-group">
                            <label for="ctype">Type</label>
                            <select id="ctype" name="ctype" required>
                                <option value="" disabled selected>select type</option>
                                <option value="HW">Hardware</option>
                                <option value="SW">Software</option>
                                <option value="NW">Network</option>
                            </select>
                        </div>
                        <div class="input-group">
                            <label for="subject">Subject </label>
                            <input type="text" id="subject" name="subject" placeholder="enter complaint subject" required />
                        </div>
                        <div class="input-group">
                            <label for="descr">Description </label>
                            <textarea id="descr" name="descr" rows="5" cols="40" placeholder="Describe your complaint here..."></textarea>
                        </div>
                        <div class="input-group">
                            <label for="file">Upload File</label>
                            <input type="file" id="uploadedFile" name="uploadedFile" class="upload-file" accept=".jpg, .jpeg, .png, .pdf" />
                        </div>
                        <div class="input-group">
                            <label for="forward">Forward To</label>
                            <select id="forward" name="forward" required>
                                <option value="" disabled selected>select officer</option>
                                <option value="o1">Officer1</option>
                                <option value="o2">Officer2</option>
                                <option value="o3">Officer3</option>
                            </select>
                        </div>
                        <div class="submit-btn">
                            <button type="submit" name="submitBtn" id="submitBtn">Submit</button>
                        </div>
                    </form>
                </div>
            </main>
        <!-- </div> -->

    <!-- Overlay for small screen dark background -->
    <div class="overlay" id="overlay"></div>

        <script src="../js/script.js"></script>
    </body>
</html>