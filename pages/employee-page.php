<?php
    include_once '../include/config.php';
    session_start();

    if(!isset($_SESSION['empcode'])){
        header("Location: ../index.php");
        exit();
    }

    $ecode = $_SESSION['empcode'];
    $cat = $dbo->query("SELECT CATEGORY FROM user WHERE empcode = '$ecode'")->fetchColumn();
    $oquery = "SELECT EMPCODE, EMPNAME FROM user WHERE CATEGORY='Officer' ORDER BY EMPNAME";

    if(isset($_POST['submitBtn'])){
        $ctype = $_POST["ctype"];
        $sub = $_POST["subject"];
        $descr = $_POST["descr"];
        $ofwd = '';
        $afwd = '';
        $curempcode = '';

        if($cat == 'Employee'){
            $fwd = $_POST["forward"];
            $ofwd = $fwd;
            $curempcode = $fwd;
        } elseif($cat == 'Officer'){
            $ofwd = $ecode;
            $afwd = $dbo->query("SELECT EMPCODE FROM user WHERE CATEGORY='Admin' LIMIT 1")->fetchColumn();
            $curempcode = $afwd;
        }

        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["uploadedFile"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if(!empty($_FILES["uploadedFile"]["name"])){
            if(!in_array($imageFileType, ["pdf", "jpg", "jpeg", "png"])){
                echo "<script>alert('Sorry, only PDF, JPG, JPEG & PNG files are allowed.');</script>";
                $uploadOk = 0;
            } 
            else{
                if(move_uploaded_file($_FILES["uploadedFile"]["tmp_name"], $target_file)){
                    $dbo->query("INSERT INTO complaints (ctype, sub, descr, empcode, compdate, status, uploadedFile, offempcode, admempcode, curempcode) 
                        VALUES ('$ctype', '$sub', '$descr', '$ecode', CURDATE(), 'Pending', '$target_file', '$ofwd', '$afwd', '$curempcode')");
                    $email = $dbo->query("SELECT EMAIL FROM user WHERE EMPCODE = '$ofwd'")->fetchColumn();
                    if($email){
                        $subject = "Received a Complaint";
                        $message = "Dear Officer,\n\nYou have received a new complaint. Please login to your account for further action.\n\nRegards,\nHelpdesk";
                        $headers = "From: joyitabhawal@gmail.com";
                        mail($email, $subject, $message, $headers);
                    }
                    
                }
            }
        } 
        else{
            $dbo->query("INSERT INTO complaints (ctype, sub, descr, empcode, compdate, status, offempcode, admempcode, curempcode) 
                VALUES ('$ctype', '$sub', '$descr', '$ecode', CURDATE(), 'Pending', '$ofwd', '$afwd', '$curempcode')");
            $email = $dbo->query("SELECT EMAIL FROM user WHERE EMPCODE = '$ofwd'")->fetchColumn();
                if($email){
                    $subject = "Received a Complaint";
                    $message = "Dear Officer,\n\nYou have received a new complaint. Please login to your account for further action.\n\nRegards,\nHelpdesk";
                    $headers = "From: joyitabhawal@gmail.com";
                    mail($email, $subject, $message, $headers);
                }
        }
        echo "<script>alert('Complaint has been registered.');</script>";
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
        <title>Employee Page</title>
    </head>
    <body>           
        <!-- navbar+sidebar(from menu.php)+ main content  --> 
                <?php include 'menu.php';?>
                <main class="main-content" style="margin-top: 20px;"> 
                    <h2>Employee Complaint Form</h2>
                    <div id="complaint-container">
                        <form action="" method="post" enctype="multipart/form-data" class="complaint-form" id="complaint-form">
                            <div class="input-group">
                                <label for="ctype">Type</label>
                                <select id="ctype" name="ctype" required>
                                    <option value="" disabled selected>Select Type</option>
                                    <option value="Hardware">Hardware</option>
                                    <option value="Software">Software</option>
                                    <option value="Network">Network</option>
                                </select>
                            </div>

                            <div class="input-group">
                                <label for="subject">Subject </label>
                                <input type="text" id="subject" name="subject"  maxlength="100" placeholder="Enter Complaint Subject" required />
                            </div>

                            <div class="input-group">
                                <label for="descr">Description </label>
                                <textarea id="descr" name="descr" maxlength="250" rows="5" cols="40" placeholder="Describe your complaint here..."></textarea>
                            </div>

                            <div class="input-group">
                                <label for="file">Upload File (Max 5MB)</label>
                                <input type="file" id="uploadedFile" name="uploadedFile" class="upload-file" accept=".jpg, .jpeg, .png, .pdf" />
                            </div>

                            <div class="input-group">
                                <label for="forward">Forward To</label>
                                <?php if($cat=='Employee'){ ?>
                                    <select id="forward" name="forward" required>
                                        <option value="" disabled selected>Select Officer</option>
                                        <?php
                                            $listoff = $dbo->query($oquery);
                                            while ($rowoff = $listoff->fetch(PDO::FETCH_ASSOC)){	
                                                ?>
                                        <option value="<?php echo $rowoff['EMPCODE']?>"><?php echo $rowoff['EMPNAME']?></option>
                                        <?php
                                            }
                                            ?>
                                    </select>
                                    <?php }                                 
                                else if($cat=='Officer'){ ?>
                                    <select id="forward" name="forward" required>
                                        <option value="" disabled selected>Forward to</option>
                                        <option value="Forwarded to Admin">Forward to Admin</option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                            </div>

                            <div class="submit-btn">
                                <button type="submit" name="submitBtn" id="submitBtn">Submit</button>
                            </div>
                        </form>
                    </div>
                </main>
            </div> <!--closes wrapper(from menu.php) -->
        </div> <!--closes page-container (from menu.php) -->

        <!-- Overlay for small screen dark background -->
        <div class="overlay" id="overlay"></div>
        <script src="../js/script.js"></script>
        <div id="universalLoadingOverlay" class="loading-overlay">
            <div class="spinner"></div>
            <p>Loading...</p>
        </div>
    </body>
</html>