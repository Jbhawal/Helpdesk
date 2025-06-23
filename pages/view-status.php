<?php
    include_once '../include/config.php'; 
    session_start();

    if(!isset($_SESSION['empcode'])){
        header("Location: ../index.php"); 
        exit();
    }

    $ecode = $_SESSION['empcode']; 
    

    $compid = '';
    $ctype = '';
    $sub = '';
    $descr = '';
    $uploadedFile = '';
    $status = '';
    $offrem = '';
    $admrem = '';
    $ccode = '';
    $oename = '';
    $currstatus = 'N'; // Default to 'N'(no special status action for employee)
    $initialFilter = isset($_GET['filter']) ? htmlspecialchars($_GET['filter']) : 'All';

    if(isset($_GET['e'])){
        $ccode = $_GET['e'];
        $list = $dbo->query("SELECT COMPID, CTYPE, SUB, DESCR, UPLOADEDFILE, STATUS, OFFEMPCODE FROM complaints WHERE compid = '$ccode' AND empcode = '$ecode'");
        $row = $list->fetch(PDO::FETCH_ASSOC);

        if($row){
            $compid = $row['COMPID'];
            $ctype = $row['CTYPE'];
            $sub = $row['SUB'];
            $descr = $row['DESCR'];
            $uploadedFile = $row['UPLOADEDFILE'];
            $status = $row['STATUS'];
            $oecode = $row['OFFEMPCODE'];
            $oename = $dbo->query("SELECT EMPNAME FROM user WHERE EMPCODE='$oecode'")->fetchColumn();
            if($status == 'Pending'){
                $currstatus = 'P'; 
            } else{
                $currstatus = 'N'; // Complaint is not pending(Closed, rejected, etc.)
            }
        } 
        else{
            header("Location: view-status.php?filter={$initialFilter}");
            exit();
        }
    }

    if (isset($_POST['submitBtn'])) {
        $eremarks = trim($_POST["eremarks"]);
        $ccode = $_POST["cccode"];
        $oecode = $dbo->query("SELECT offempcode FROM complaints WHERE compid='$ccode'")->fetchColumn();

        $dbo->query("INSERT INTO history (COMPID, FORSTATUS, CATEGORY, REMARKS, REMDATE) 
                    VALUES ('$ccode', 'Pending', 'Employee', '$eremarks', CURDATE())");
        $dbo->query("UPDATE complaints SET STATUS='Pending', CUREMPCODE='$oecode' WHERE compid='$ccode'");

        if (!empty($_FILES["uploadedFile"]["name"])) {
            $target_dir = "../uploads/";
            $target_file = $target_dir . basename($_FILES["uploadedFile"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            if (!in_array($imageFileType, ['pdf', 'jpg', 'jpeg', 'png'])) {
                echo "<script>alert('Only PDF, JPG, JPEG & PNG files are allowed.');</script>";
            }
            else{
                if (move_uploaded_file($_FILES["uploadedFile"]["tmp_name"], $target_file)) {
                    $dbo->query("UPDATE complaints SET uploadedFile='$target_file' WHERE compid='$ccode'");
                }
            }
        }
        echo "<script>alert('Complaint forwarded.'); window.location.href = 'view-status.php';</script>";
        exit();
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
        <title>My Complaint Status</title>
    </head>
    <body data-show-details="<?php echo isset($_GET['e']) ? 'true' : 'false'; ?>">
        <?php include 'menu.php'; ?>
                <main class="officer-main">
                    <div class="cheading">
                        <h2>My Complaints</h2>
                        <div class="cstatus-select">
                            <button class="dropbtn" id="statusFilterButton">All ▾</button>
                            <div class="cstatus-select-content">
                                <a href="#" data-status="All">All</a>
                                <a href="#" data-status="Pending">Pending</a>
                                <a href="#" data-status="Rejected">Rejected</a>
                                <a href="#" data-status="Forwarded to Admin">Forwarded to Admin</a>
                                <a href="#" data-status="In Progress">In Progress</a>
                                <a href="#" data-status="Return to User">Return to User</a>
                                <a href="#" data-status="Closed">Closed</a>
                            </div>
                        </div>
                    </div>
                    <form action="" method="post" enctype="multipart/form-data" class="complaint-form">
                        <input type="hidden" id="currentFilter" name="currentFilter" value="<?php echo $initialFilter; ?>">

                        <div class="clist-container">
                            <table class="clist-table">
                                <thead>
                                    <tr>
                                        <th>Complaint ID</th>
                                        <th>Type</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Currently with</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    
                                    $whereClause = " WHERE empcode = '$ecode'";
                                    if($initialFilter !== 'All'){
                                        $whereClause .= " AND status = '$initialFilter'";
                                    }
                                    $results = $dbo->query("SELECT compid, ctype, sub, status, empcode, offempcode, admempcode, curempcode FROM complaints{$whereClause}");
                                        if ($results) { 
                                            while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
                                                $curecode = $row['curempcode'];
                                                $offempcode = $row['offempcode'];
                                                $admempcode = $row['admempcode'];
                                                $cname = '';
                                                if ($curecode == $offempcode){
                                                    $cname = $dbo->query("SELECT CATEGORY FROM user WHERE empcode = '$offempcode'")->fetchColumn();
                                                } 
                                                else if($curecode == $admempcode){
                                                    $cname = $dbo->query("SELECT EMPNAME FROM user WHERE empcode = '$admempcode'")->fetchColumn();
                                                }
                                                else{
                                                    $cname='Me';
                                                }

                                                echo "<tr>";
                                                echo "<td>".htmlspecialchars($row['compid'])."</td>";
                                                echo "<td>".htmlspecialchars($row['ctype'])."</td>";
                                                echo "<td>".htmlspecialchars($row['sub'])."</td>";
                                                echo "<td class='complaint-status'>".htmlspecialchars($row['status'])."</td>";
                                                echo "<td>".htmlspecialchars($cname)."</td>";
                                                echo "<td><a href='view-status.php?e=".htmlspecialchars($row['compid'])."&filter={$initialFilter}'>View Details</a></td>";
                                                // echo "<td><a href='view-status.php?e=".htmlspecialchars($row['compid'])."'>View Details</a></td>";
                                                echo "</tr>";
                                            }
                                        }
                                    $displayNoComplaints =($results->rowCount() == 0) ? '' : 'display:none;';
                                    echo "<tr class='no-complaints' style='{$displayNoComplaints}'><td colspan='7'>No complaints found.</td></tr>";
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="cdetails" id="complaintDetails" style="display: <?php echo(isset($_GET['e']) ? 'block' : 'none'); ?>;">
                            <h2>Complaint Details</h2>
                            <input type="hidden" id="cccode" name="cccode" value="<?php echo htmlspecialchars($compid); ?>" />
                            <p><strong>Complaint ID:</strong> <?php echo htmlspecialchars($compid); ?></p>

                            <p><strong>Type:</strong> <?php echo htmlspecialchars($ctype); ?></p>

                            <p><strong>Subject:</strong> <?php echo htmlspecialchars($sub); ?></p>

                            <p><strong>Description:</strong> <?php echo htmlspecialchars($descr); ?></p>

                            <p><strong>File:</strong>
                                <?php if(!empty($uploadedFile)): ?>
                                    <a href="/uploads/<?php echo basename($uploadedFile); ?>" target="_blank">View Attached File</a>
                                <?php else: ?>
                                    No file uploaded.
                                <?php endif; ?>

                            </p>
                                        
                            <p><strong>Current Status:</strong> <?php echo htmlspecialchars($status); ?></p>
                            
                            
                            <?php if($status === 'Return to User'){ ?>
                                <div class="input-group">
                                    <label for="eremarks">My Remarks: </label>                        
                                    <input type="text" id="eremarks" name="eremarks" placeholder="enter your remarks" value="<?php echo htmlspecialchars($offrem); ?>" required/>
                                </div>

                                <div class="input-group">
                                <label for="file">Upload File</label>
                                <input type="file" id="uploadedFile" name="uploadedFile" class="upload-file" accept=".jpg, .jpeg, .png, .pdf" />
                            </div>
                            
                            

                            <p><strong>Forward To:</strong> <?php echo htmlspecialchars($oename); ?></p>

                                <!-- <div class="input-group">
                                    <label for="forward">Forward To</label>
                                    <select id="forward" name="forward" required>
                                        <option value="" disabled selected>Select Officer</option>
                                        
                                            <option value="<?php echo $rowoff['EMPCODE']?>"><?php echo $rowoff['EMPNAME']?></option>
                                            
                                        
                                    </select>
                                </div> -->

                                <div class="submit-btn">
                                    <button type="submit" name="submitBtn" id="submitBtn">Submit</button>
                                </div>
                            <?php } 
                            else{ ?>
                                <p>Remarks can be found below.</p>
                            <?php } ?>

                        </div>
                        <!-- remarks history table -->
                        <div class="clist-container">
                            <table class="clist-table">
                                <thead>
                                    <tr>
                                        <th>For Status</th>
                                        <th>Remarks By</th>
                                        <th>Remarks</th>
                                        <th>Remarks Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    
                                        $results = $dbo->query("SELECT FORSTATUS, CATEGORY, REMARKS, REMDATE FROM history WHERE COMPID='$ccode' ORDER BY ROWID");
                                        if ($results) { 
                                            while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
                                                $hforstatus = $row['FORSTATUS'];
                                                $hcatg = $row['CATEGORY'];
                                                $hrem = $row['REMARKS'];
                                                $hremdate = $row['REMDATE'];
                                                echo "<tr>";
                                                echo "<td>".htmlspecialchars($hforstatus)."</td>";
                                                echo "<td>".htmlspecialchars($hcatg)."</td>";
                                                echo "<td>".htmlspecialchars($hrem)."</td>";
                                                echo "<td>".htmlspecialchars($hremdate)."</td>";
                                                echo "</tr>";
                                            }
                                        } 
                                        $displayNoComplaints =($results->rowCount() == 0) ? '' : 'display:none;';
                                        echo "<tr class='no-complaints' style='{$displayNoComplaints}'><td colspan='4'>No Remarks found.</td></tr>";
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </main>
            </div> <!--closes wrapper(from menu.php) -->
        </div> <!--closes page-container (from menu.php) -->
        <div id="overlay" class="overlay"></div>
        <script src="../js/script.js"></script>
    </body>
</html>