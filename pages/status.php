<?php
    include_once '../include/config.php'; 
    session_start();

    if(!isset($_SESSION['empcode'])){
        header("Location: ../index.php"); 
        exit();
    }

    $ecode = $_SESSION['empcode']; 

    // Initialize variables for complaint details to avoid undefined variable errors
    $compid = '';
    $ctype = '';
    $sub = '';
    $descr = '';
    $uploadedFile = '';
    $status = '';
    $offrem = '';
    $admrem = '';
    $currstatus = 'N'; // Default to 'N'(no special status action for employee)
    $initialFilter = isset($_GET['filter']) ? htmlspecialchars($_GET['filter']) : 'All';

    if(isset($_GET['e'])){
        $ccode = $_GET['e'];
        $list = $dbo->query("SELECT COMPID, CTYPE, SUB, DESCR, UPLOADEDFILE, STATUS, OFFREM, ADMREM FROM complaints WHERE compid = '$ccode' AND empcode = '$ecode'");
        $row = $list->fetch(PDO::FETCH_ASSOC);

        if($row){
            $compid = $row['COMPID'];
            $ctype = $row['CTYPE'];
            $sub = $row['SUB'];
            $descr = $row['DESCR'];
            $uploadedFile = $row['UPLOADEDFILE'];
            $status = $row['STATUS'];
            $offrem = $row['OFFREM'];
            $admrem = $row['ADMREM'];

            if($status == 'Pending'){
                $currstatus = 'P'; 
            } else{
                $currstatus = 'N'; // Complaint is not pending(Closed, rejected, etc.)
            }
        } else{
            // If no complaint found, redirect to the status page with the initial filter
            header("Location: view-status.php?filter={$initialFilter}");
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
        <title>My Complaint Status</title>
    </head>
    <body data-is-detail-view-active="<?php echo isset($_GET['e']) ? 'true' : 'false'; ?>">
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
                                <a href="#" data-status="Closed">Closed</a>
                            </div>
                        </div>
                    </div>
                    <form action="" method="post" class="complaint-form">
                        <input type="hidden" id="currentFilter" name="currentFilter" value="<?php echo $initialFilter; ?>">

                        <div class="clist-container">
                            <table class="clist-table">
                                <thead>
                                    <tr>
                                        <th>Complaint ID</th>
                                        <th>Type</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $whereClause = "WHERE empcode = '$ecode'"; 
                                    $noComplaintsMessage = "No complaints found.";

                                    if($initialFilter !== 'All'){
                                        $whereClause .= " AND status = '$initialFilter'";
                                        $noComplaintsMessage = "No complaints found with status '{$initialFilter}'.";
                                    }
                                    $results = $dbo->query("SELECT compid, ctype, sub, status FROM complaints{$whereClause}");

                                    if($results->rowCount() > 0){
                                        while($row = $results->fetch(PDO::FETCH_ASSOC)){
                                            echo "<tr>";
                                            echo "<td>".htmlspecialchars($row['compid'])."</td>";
                                            echo "<td>".htmlspecialchars($row['ctype'])."</td>";
                                            echo "<td>".htmlspecialchars($row['sub'])."</td>";
                                            echo "<td class='complaint-status'>".htmlspecialchars($row['status'])."</td>";                                            
                                            echo "<td><a href='view-status.php?e=".htmlspecialchars($row['compid'])."&filter={$initialFilter}'>View Details</a></td>";
                                            echo "</tr>";
                                        }
                                    }
                                    $displayNoComplaints =($results->rowCount() == 0) ? '' : 'display:none;';
                                    echo "<tr class='no-complaints' style='{$displayNoComplaints}'><td colspan='5'>{$noComplaintsMessage}</td></tr>";
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
                                    <a href='<?php echo htmlspecialchars($uploadedFile); ?>' target="_blank" rel="noopener noreferrer">View Attached File</a>
                                <?php else: ?>
                                    No file uploaded.
                                <?php endif; ?>
                            </p>

                            <p><strong>Current Status:</strong> <?php echo htmlspecialchars($status); ?></p>

                            <p><strong>Officer Remarks:</strong>

                                <?php if(empty($offrem)){ ?>
                                    No remarks.
                                <?php } else{ ?>
                                    <?php echo htmlspecialchars($offrem); ?>
                                <?php }?>
                            </p>
                            
                            <p><strong>Admin Remarks:</strong>
                                <?php if(empty($admrem)){ ?>
                                    No remarks.
                                <?php } else{ ?>
                                    <?php echo htmlspecialchars($admrem); ?>
                                <?php }?>
                            </p>
                        </div>
                    </form>
                </main>
            </div> <!--closes wrapper(from menu.php) -->
        </div> <!--closes page-container (from menu.php) -->
        <div id="overlay" class="overlay"></div>
        <script src="../js/script.js"></script>
    </body>
</html>