<?php
    include_once '../include/config.php';
    session_start();

    if(!isset($_SESSION['empcode'])){
        header("Location: ../index.php"); 
        exit();
    }

    $ecode = $_SESSION['empcode'];
    $initialFilter = isset($_GET['filter']) ? htmlspecialchars($_GET['filter']) : 'All';
    $viewMode = isset($_GET['view']) ? $_GET['view'] : 'received'; // 'received' or 'my'

    $ccode = '';
    $compid = '';
    $ctype = '';
    $status = '';
    $sub = '';
    $descr = '';
    $uploadedFile = '';
    $currstatus = '';
    $offrem  = '';
    $currec = '';
    $ccode = '';
    $curr = '';

    if(isset($_POST['submitBtn'])){
        $oremarks = trim($_POST["oremarks"]);
        $ccode 	= trim($_POST["cccode"]);
        $ostatus = trim($_POST["ostatus"]);
        $originalFilter = trim($_POST["originalFilter"]);
		if($ostatus == 'RJ') $ost = 'Rejected by Officer';
		if($ostatus == 'RU') $ost = 'Return to User';
		if($ostatus == 'FA') $ost = 'Pending';
		
		$db_user = $dbo->query("UPDATE complaints SET status='$ost' WHERE compid='$ccode'");
        if($ostatus == 'RJ'){
            $oecode = $dbo->query("SELECT EMPCODE FROM complaints WHERE compid='$ccode'")->fetchColumn();
			$db_user = $dbo->query("UPDATE complaints SET CUREMPCODE= '$oecode' WHERE compid='$ccode'");

            $email = $dbo->query("SELECT EMAIL FROM user WHERE EMPCODE = '$oecode'")->fetchColumn();
            if($email){
                $subject = "Complaint Rejected";
                $message = "Dear User,\n\nYour complaint with ID: $ccode has been rejected.\nPlease login to view remarks.\n\nRegards,\nHelpdesk";
                $headers = "From: joyitabhawal@gmail.com";
                mail($email, $subject, $message, $headers);
            }
        }
		if($ostatus == 'RU'){
			$oecode = $dbo->query("SELECT EMPCODE FROM complaints WHERE compid='$ccode'")->fetchColumn();
			$db_user = $dbo->query("UPDATE complaints SET CUREMPCODE= '$oecode' WHERE compid='$ccode'");

            $email = $dbo->query("SELECT EMAIL FROM user WHERE EMPCODE = '$oecode'")->fetchColumn();
            if($email){
                $subject = "Complaint Returned to You";
                $message = "Dear User,\n\nYour complaint with ID: $ccode has been returned to you.\nPlease login to view and respond.\n\nRegards,\nHelpdesk";
                $headers = "From: joyitabhawal@gmail.com";
                mail($email, $subject, $message, $headers);
            }
		}
		else if($ostatus == 'FA'){	
			$oecode = $dbo->query("SELECT MAX(EMPCODE) EC FROM user WHERE CATEGORY='Admin'")->fetchColumn();
			$db_user = $dbo->query("UPDATE complaints SET ADMEMPCODE='$oecode', CUREMPCODE= '$oecode' WHERE compid='$ccode'");

            $email = $dbo->query("SELECT EMAIL FROM user WHERE EMPCODE = '$oecode'")->fetchColumn();
            if($email){
                $subject = "New Complaint Assigned to You";
                $message = "Dear Admin,\n\nA new complaint with ID: $ccode has been forwarded to you for further action.\nPlease log in to view and respond.\n\nRegards,\nHelpdesk";
                $headers = "From: joyitabhawal@gmail.com";

                mail($email, $subject, $message, $headers);
            }

		}

        if($oremarks <> ''){  //<> same as !=
            $db_ins1 = $dbo->query("INSERT INTO history (COMPID, FORSTATUS, CATEGORY, REMARKS, REMDATE) VALUES ('$ccode', '$ost', 'Officer', '$oremarks', CURDATE())");
        }

		echo "<script>
                    alert('Update successful.');
                    window.location.href = 'officer-page.php?filter={$originalFilter}';
                  </script>";
    }

    if(isset($_GET['e'])){
        $ccode  = $_GET['e'];  //complaint code/id
        $list = $dbo->query("SELECT COMPID, CTYPE, SUB, DESCR, UPLOADEDFILE, STATUS, OFFEMPCODE, CUREMPCODE FROM complaints WHERE compid = '$ccode'");

        $row = $list->fetch(PDO::FETCH_ASSOC);
        if($row){
            $compid  = $row['COMPID'];
            $ctype = $row['CTYPE'];
            $sub = $row['SUB'];
            $descr = $row['DESCR'];
            $uploadedFile = $row['UPLOADEDFILE'];
            $status = $row['STATUS'];
            $currec = $row['CUREMPCODE'];
            $currstatus = (!in_array($status, ['Return to User', 'Rejected', 'Closed'])) ? 'P' : 'N';
        } 
        else{
            echo "<script>alert('Complaint not found.');
            window.location.href = 'officer-page.php';</script>";
            exit();
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
        <title>Officer Page</title>
    </head>
    <body data-show-details="<?php echo isset($_GET['e']) ? 'true' : 'false'; ?>">          
            <?php include 'menu.php';?>
                <main class="o-main">
                    <div class="cheading">
                        <h2><?php echo $viewMode === 'my' ? 'My Complaints' : 'Received Complaints'; ?></h2>

<!-- dropdown -->
                        <div class="cstatus-select">
                            <button class="dropbtn" id="statusFilterButton">All ▾</button>
                            <div class="cstatus-select-content">
                                <a href="#" data-status="All">All</a>
                                <a href="#" data-status="Pending">Pending</a>
                                <a href="#" data-status="In Progress">In Progress</a>
                                <a href="#" data-status="Rejected">Rejected by Admin</a>
                                <a href="#" data-status="Rejected by Officer">Rejected by Me</a>
                                <a href="#" data-status="RejectedAll">All Rejected</a>
                                <a href="#" data-status="Return to User">Return to User</a>
                                <a href="#" data-status="Closed">Closed</a>
                            </div>
                        </div>
                    </div>
<!-- complaint list table -->
                    <form action="" method="post" class="complaint-form">
                        <input type="hidden" name="originalFilter" id="currentFilter" value="<?php echo htmlspecialchars($initialFilter); ?>"/>   
                        <div class="clist-container">
                            <table class="clist-table">
                                <thead>
                                    <tr>
                                        <th>Complaint ID</th>
                                        <th>Type</th>
                                        <th>Complaint By</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Currently with</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $whereClause = $viewMode === 'my' ? " WHERE empcode = '$ecode'" : " WHERE offempcode = '$ecode' AND empcode != '$ecode'";
                                        switch ($initialFilter) {
                                            case 'RejectedByAdmin':
                                                $whereClause .= " AND status = 'Rejected by Admin'";
                                                break;
                                            case 'RejectedByOfficer':
                                                $whereClause .= " AND status = 'Rejected by Officer'";
                                                break;
                                            case 'RejectedAll':
                                                $whereClause .= " AND (status = 'Rejected by Admin' OR status = 'Rejected by Officer')";
                                                break;
                                            case 'All':
                                                break;
                                            default:
                                                $whereClause .= " AND status = '$initialFilter'";
                                        }
                                        $results = $dbo->query("SELECT compid, ctype, sub, status, empcode, offempcode, admempcode, curempcode FROM complaints{$whereClause}");
                                    
                                        if($results){ 
                                            while ($row = $results->fetch(PDO::FETCH_ASSOC)){
                                                $curecode = $row['curempcode'];
                                                $cbycode = $row['empcode'];
                                                $cname = ''; 
                                                $cbyname = ''; 
                                                $cname = ($curecode == $ecode) ? 'Me' : $dbo->query("SELECT EMPNAME FROM user WHERE EMPCODE = '$curecode'")->fetchColumn();
                                                $cbyname = ($cbycode == $ecode) ? 'Me' : $dbo->query("SELECT EMPNAME FROM user WHERE EMPCODE = '$cbycode'")->fetchColumn();

                                                echo "<tr>";
                                                echo "<td>".htmlspecialchars($row['compid'])."</td>";
                                                echo "<td>".htmlspecialchars($row['ctype'])."</td>";
                                                echo "<td>".htmlspecialchars($cbyname)."</td>";
                                                echo "<td style='word-wrap: break-word; overflow-wrap: break-word; white-space: normal; max-width: 200px;'>" . htmlspecialchars($row['sub']) . "</td>";
                                                echo "<td class='complaint-status'>".htmlspecialchars($row['status'])."</td>";
                                                echo "<td>".htmlspecialchars($cname)."</td>";
                                                echo "<td><a href='officer-page.php?e=" . htmlspecialchars($row['compid']) . "&view=$viewMode'>View Details</a></td>";
                                                echo "</tr>";
                                            }
                                        } 
                                        $displayNoComplaints =($results->rowCount() == 0) ? '' : 'display:none;';
                                        echo "<tr class='no-complaints' style='{$displayNoComplaints}'><td colspan='7'>No complaints found.</td></tr>";
                                    ?>
                                </tbody>
                            </table>
                        </div>
<!-- complaint details on clicking -->
                        <div class="cdetails" id="complaintDetails" style="display: <?php echo(isset($_GET['e']) ? 'block' : 'none'); ?>;">
                            <h2>Complaint Details</h2>
                            <input type="hidden" id="cccode" name="cccode" value="<?php echo htmlspecialchars($compid); ?>" />
                            <p><strong>Complaint ID:</strong> <?php echo htmlspecialchars($compid); ?></p>
                            <p><strong>Type:</strong> <?php echo htmlspecialchars($ctype); ?></p>
                            <p><strong>Subject:</strong> <?php echo htmlspecialchars($sub); ?></p>
                            <p><strong>Description:</strong> <?php echo htmlspecialchars($descr); ?></p>
                            <p><strong>File:</strong>
                                <?php if(!empty($uploadedFile)): ?>
                                    <a href='<?php echo htmlspecialchars($uploadedFile); ?>' target="_blank" rel="noopener noreferrer">View Attached File</a>
                                <?php else: ?>
                                    No file uploaded.
                                <?php endif; ?>
                            </p>
                            <p><strong>Status:</strong> <?php echo htmlspecialchars($status); ?></p>

                            <?php if(($currec == $ecode && trim($currstatus) == 'P') || ($status === 'Return to User')){ ?>
                                <div class="input-group">
                                    <label for="oremarks">Officer Remarks: </label>
                                    <input type="text" id="oremarks" name="oremarks" placeholder="Enter remarks" value="<?php echo htmlspecialchars($offrem); ?>" />
                                </div>

                                <?php if(trim($status) === 'Return to User'){ ?>
                                    <div class="input-group">
                                        <label for="ostatus">Update Status</label>
                                        <select id="ostatus" name="ostatus" required>
                                            <option value="" disabled selected>Select status</option>
                                            <option value="FA">Forward to Admin</option>
                                        </select>
                                    </div>
                                <?php } 
                                else{ ?>
                                    <div class="input-group">
                                        <label for="ostatus">Update Status</label>
                                        <select id="ostatus" name="ostatus" required>
                                            <option value="" disabled selected>Select status</option>
                                            <option value="RJ">Rejected</option>
                                            <option value="RU">Return to User</option>
                                            <option value="FA">Forward to Admin</option>
                                        </select>
                                    </div>
                                <?php } ?>

                                <div class="submit-btn">
                                    <button type="submit" name="submitBtn">
                                        <span class="spinner" id="registerSpinner" style="display: none;"></span>
                                        <span id="registerText">Submit</span>
                                    </button>
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
                                        if($results){ 
                                            while ($row = $results->fetch(PDO::FETCH_ASSOC)){
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
            </div>
        </div>
        <div id="overlay" class="overlay"></div>
        <script src="../js/script.js"></script>
        <div id="universalLoadingOverlay" class="loading-overlay">
            <div class="spinner"></div>
            <p>Loading...</p>
        </div>
    </body>
</html>