<?php
    include_once '../include/config.php';
    session_start();

    if (!isset($_SESSION['empcode'])) {
        header("Location: ../index.php");
        exit();
    }

    $ecode = $_SESSION['empcode'];
    $initialFilter = isset($_GET['filter']) ? htmlspecialchars($_GET['filter']) : 'All';

    $ccode = '';
    $compid = '';
    $ctype = '';
    $status = '';
    $sub = '';
    $descr = '';
    $uploadedFile = '';
    $currstatus = '';
    $offrem  = '';
    $admrem  = '';

    if (isset($_POST['submitBtn'])) {
        $admrem = trim($_POST["admremarks"]);
        $ccode  = trim($_POST["cccode"]);
        $astatus = trim($_POST["astatus"]);
        $originalFilter = trim($_POST["originalFilter"]);
        if ($astatus == 'RU') $ast = 'Return to User';
        if ($astatus == 'CL') $ast = 'Closed';

        $db_user = $dbo->query("UPDATE complaints SET status='$ast', ADMREMARKS='$admrem' WHERE compid='$ccode'");
        $db_user = $dbo->query("INSERT INTO history (compid, arem, adate) VALUES ('$compid', '$admrem', currdate)");

        if ($astatus == 'RU') {
            $originalUser = $dbo->query("SELECT EMPCODE FROM complaints WHERE compid='$ccode'")->fetchColumn();
            $db_user = $dbo->query("UPDATE complaints SET CUREMPCODE=$originalUser WHERE compid='$ccode'");
        } else {
            $db_user = $dbo->query("UPDATE complaints SET CUREMPCODE=NULL WHERE compid='$ccode'");
        }

        echo "<script>
                alert('Update successful.');
                window.location.href = 'adminPg.php?filter={$originalFilter}';
              </script>";
    }

    if (isset($_GET['e'])) {
        $ccode = $_GET['e'];
        $list = $dbo->query("SELECT COMPID, CTYPE, SUB, DESCR, UPLOADEDFILE, STATUS FROM complaints WHERE compid = '$ccode'");
        $row = $list->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $compid = $row['COMPID'];
            $ctype = $row['CTYPE'];
            $sub = $row['SUB'];
            $descr = $row['DESCR'];
            $uploadedFile = $row['UPLOADEDFILE'];
            $status = $row['STATUS'];
            $currstatus = ($status == 'Pending') ? 'P' : 'N';
        } else {
            echo "<script>alert('Complaint not found.');
            window.location.href = 'adminPg.php';</script>";
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
        <title>Admin Page</title>
    </head>
    <body data-show-details="<?php echo isset($_GET['e']) ? 'true' : 'false'; ?>">
        <?php include 'menu.php'; ?>
        <main class="officer-main">
            <div class="cheading">
                <h2>List of complaints</h2>
                <div class="cstatus-select">
                    <button class="dropbtn" id="statusFilterButton">All ▾</button>
                    <div class="cstatus-select-content">
                        <a href="#" data-status="All">All</a>
                        <a href="#" data-status="Pending">Pending</a>
                        <a href="#" data-status="Forwarded to Admin">Forwarded to Admin</a>
                        <a href="#" data-status="Return to User">Return to User</a>
                        <a href="#" data-status="Closed">Closed</a>
                    </div>
                </div>
            </div>

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
                            $whereClause = " WHERE ADMEMPCODE='$ecode'";
                            if ($initialFilter !== 'All') {
                                $whereClause .= " AND status = '$initialFilter'";
                            }

                            $results = $dbo->query("SELECT compid, ctype, sub, status, empcode, curempcode FROM complaints{$whereClause}");
                            if ($results) {
                                while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
                                    $curecode = $row['curempcode'];
                                    $cbycode = $row['empcode'];

                                    $cname = ($curecode == $ecode) ? 'Me' : $dbo->query("SELECT EMPNAME FROM user WHERE empcode = '$curecode'")->fetchColumn();
                                    $cbyname = ($cbycode == $ecode) ? 'Me' : $dbo->query("SELECT EMPNAME FROM user WHERE empcode = '$cbycode'")->fetchColumn();

                                    echo "<tr>";
                                    echo "<td>".htmlspecialchars($row['compid'])."</td>";
                                    echo "<td>".htmlspecialchars($row['ctype'])."</td>";
                                    echo "<td>".htmlspecialchars($cbyname)."</td>";
                                    echo "<td>".htmlspecialchars($row['sub'])."</td>";
                                    echo "<td class='complaint-status'>".htmlspecialchars($row['status'])."</td>";
                                    echo "<td>".htmlspecialchars($cname)."</td>";
                                    echo "<td><a href='admin-page.php?e=".htmlspecialchars($row['compid'])."'>View Details</a></td>";
                                    echo "</tr>";
                                }
                            }

                            $displayNoComplaints = ($results->rowCount() == 0) ? '' : 'display:none;';
                            echo "<tr class='no-complaints' style='{$displayNoComplaints}'><td colspan='7'>No complaints found.</td></tr>";
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="cdetails" id="complaintDetails" style="display: <?php echo (isset($_GET['e']) ? 'block' : 'none'); ?>;">
                    <h2>Complaint Details</h2>
                    <input type="hidden" id="cccode" name="cccode" value="<?php echo htmlspecialchars($compid); ?>" />
                    <p><strong>Complaint ID:</strong> <?php echo htmlspecialchars($compid); ?></p>
                    <p><strong>Type:</strong> <?php echo htmlspecialchars($ctype); ?></p>
                    <p><strong>Subject:</strong> <?php echo htmlspecialchars($sub); ?></p>
                    <p><strong>Description:</strong> <?php echo htmlspecialchars($descr); ?></p>
                    <p><strong>File:</strong>
                        <?php if (!empty($uploadedFile)): ?>
                            <a href='<?php echo htmlspecialchars($uploadedFile); ?>' target="_blank" rel="noopener noreferrer">View Attached File</a>
                        <?php else: ?>
                            No file uploaded.
                        <?php endif; ?>
                    </p>
                    <p><strong>Status:</strong> <?php echo htmlspecialchars($status); ?></p>
                    <p><strong>Officer Remarks: </strong>
                    <?php if ($offrem): ?>
                            <p><?php echo htmlspecialchars($offrem); ?>"/>
                        <?php else: ?>
                            <p><?php echo ($admrem == '') ? 'No remarks' : htmlspecialchars($admrem); ?></p>
                        <?php endif; ?>

                    <div class="input-group">
                        <label for="admremarks">Admin Remarks: </label>
                        <?php if ($currstatus == 'P'): ?>
                            <input type="text" id="admremarks" name="admremarks" placeholder="Enter remarks" value="<?php echo htmlspecialchars($admrem); ?>"/>
                        <?php else: ?>
                            <p><?php echo ($admrem == '') ? 'No remarks' : htmlspecialchars($admrem); ?></p>
                        <?php endif; ?>
                    </div>

                    <?php if ($currstatus == 'P'): ?>
                        <div class="input-group">
                            <label for="astatus">Update Status</label>
                            <select id="astatus" name="astatus" required>
                                <option value="" disabled selected>Select status</option>
                                <option value="RU">Return to User</option>
                                <option value="CL">Closed</option>
                            </select>
                        </div>
                        <div class="submit-btn">
                            <button type="submit" name="submitBtn">Submit</button>
                        </div>
                    <?php endif; ?>
                </div>
            </form>
        </main>

        <div id="overlay" class="overlay"></div>
    <script src="../js/script.js"></script>   
    </body>
</html>
