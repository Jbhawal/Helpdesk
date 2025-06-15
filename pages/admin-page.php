<?php
    include_once '../include/config.php';
    session_start();

    $dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if(!isset($_SESSION['empcode'])){
        header("Location: ../index.php");
        exit();
    }

    $complaintId = '';
    $complaintType = '';
    $complaintSubject = '';
    $complaintDescription = '';
    $uploadedFilePath = '';
    $complaintStatus = '';
    $adminRemarks = '';
    $currentComplaintStatusForAction = 'N';
    $initialFilter = isset($_GET['filter']) ? htmlspecialchars($_GET['filter']) : 'All';

    if(isset($_POST['submitBtn'])){
        $adminRemarks = trim($_POST["adminRemarks"] ?? '');
        $complaintIdToUpdate = trim($_POST["complaintIdToUpdate"] ?? '');
        $newStatus = trim($_POST["newStatus"] ?? '');
        $currentFilterOnSubmit = trim($_POST["currentFilter"] ?? 'All');

        $adminRemarks_for_db = ($adminRemarks === '') ? NULL : $adminRemarks;

        try {
            $stmt = $dbo->prepare("UPDATE complaints SET admrem = :adminRemarks, status = :newStatus WHERE compid = :compid");
            $stmt->bindValue(':adminRemarks', $adminRemarks_for_db, ($adminRemarks_for_db === NULL ? PDO::PARAM_NULL : PDO::PARAM_STR));
            $stmt->bindValue(':newStatus', $newStatus, PDO::PARAM_STR);
            $stmt->bindValue(':compid', $complaintIdToUpdate, PDO::PARAM_STR);
            $stmt->execute();

            echo "<script>
                    alert('Complaint updated successfully.');
                    window.location.href = 'admin-page.php?filter={$currentFilterOnSubmit}';
                  </script>";
            exit();
        } catch (PDOException $e) {
            error_log("Admin page DB update error: " . $e->getMessage());
            echo "<script>alert('An error occurred during the update. Please try again.');</script>";
        }
    }

    if(isset($_GET['e'])){
        $complaintId = htmlspecialchars($_GET['e']);
        try {
            $stmt_detail = $dbo->prepare("SELECT COMPID, CTYPE, SUB, DESCR, UPLOADEDFILE, STATUS, ADMREM FROM complaints WHERE compid = :complaintId");
            $stmt_detail->bindValue(':complaintId', $complaintId, PDO::PARAM_STR);
            $stmt_detail->execute();
            $row = $stmt_detail->fetch(PDO::FETCH_ASSOC);

            if($row){
                $complaintId = $row['COMPID'];
                $complaintType = $row['CTYPE'];
                $complaintSubject = $row['SUB'];
                $complaintDescription = $row['DESCR'];
                $uploadedFilePath = $row['UPLOADEDFILE'];
                $complaintStatus = $row['STATUS'];
                $adminRemarks = $row['ADMREM'] ?? '';

                if($complaintStatus == 'Forwarded to Admin'){
                    $currentComplaintStatusForAction = 'P';
                }
                else{
                    $currentComplaintStatusForAction = 'N';
                }
            } else {
                echo "<script>alert('Complaint not found.'); window.location.href = 'admin-page.php';</script>";
                exit();
            }
        } catch (PDOException $e) {
            error_log("Admin page DB detail fetch error: " . $e->getMessage());
            echo "<script>alert('Error fetching complaint details.'); window.location.href = 'admin-page.php';</script>";
            exit();
        }
    }

    $whereConditions = [];
    $noComplaintsMessage = "No complaints found.";

    if($initialFilter === 'All'){
        $whereConditions[] = "status IN ('Forwarded to Admin', 'Closed', 'Rejected')";
        $noComplaintsMessage = "No complaints found.";
    } else if ($initialFilter === 'Rejected') {
        $whereConditions[] = "status IN ('Rejected')";
        $noComplaintsMessage = "No complaints found.";
    } else {
        $whereConditions[] = "status = :initialFilter";
        $noComplaintsMessage = "No " . htmlspecialchars($initialFilter) . " complaints found.";
    }

    $sql_list_complaints = "SELECT compid, ctype, sub, status FROM complaints WHERE " . implode(" AND ", $whereConditions);

    try {
        $stmt_all_complaints = $dbo->prepare($sql_list_complaints);
        if ($initialFilter !== 'All' && $initialFilter !== 'Rejected') {
            $stmt_all_complaints->bindValue(':initialFilter', $initialFilter, PDO::PARAM_STR);
        }
        $stmt_all_complaints->execute();
        $results_table = $stmt_all_complaints->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Admin page DB list error: " . $e->getMessage());
        $results_table = [];
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
    <body data-is-detail-view-active="<?php echo (isset($_GET['e']) ? 'true' : 'false'); ?>">

        <?php include 'menu.php'; ?>
        <main class="officer-main">
            <div class="cheading">
                <h2>List of Complaints</h2>
                <div class="cstatus-select">
                    <button class="dropbtn" id="statusFilterButton">All ▾</button>
                    <div class="cstatus-select-content">
                        <a href="#" data-status="All">All</a>
                        <a href="#" data-status="Forwarded to Admin">Forwarded to Admin</a>
                        <a href="#" data-status="Closed">Closed</a>
                        <a href="#" data-status="Rejected">Rejected</a>
                    </div>
                </div>
            </div>

            <form action="" method="post" class="complaint-form">
                <input type="hidden" id="currentFilter" name="currentFilter" value="<?php echo htmlspecialchars($initialFilter); ?>">

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
                                if(!empty($results_table)){
                                    foreach($results_table as $row){
                                        if($row['status'] === 'Rejected by Officer') {
                                            continue; // Skip this complaint
                                        }
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row['compid']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['ctype']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['sub']) . "</td>";
                                        echo "<td class='complaint-status'>" . htmlspecialchars($row['status']) . "</td>";
                                        echo "<td><a href='admin-page.php?e=" . htmlspecialchars($row['compid']) . "&filter=" . htmlspecialchars($initialFilter) . "'>View Details</a></td>";
                                        echo "</tr>";
                                    }
                                }
                                $displayNoComplaints = (empty($results_table)) ? '' : 'display:none;';
                                echo "<tr class='no-complaints' style='{$displayNoComplaints}'><td colspan='5'>{$noComplaintsMessage}</td></tr>";
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="cdetails" id="complaintDetails" style="display: <?php echo(isset($_GET['e']) ? 'block' : 'none'); ?>;">
                    <h2>Complaint Details</h2>
                    <input type="hidden" id="complaintIdToUpdate" name="complaintIdToUpdate" value="<?php echo htmlspecialchars($complaintId); ?>" />
                    <p><strong>Complaint ID: </strong> <?php echo htmlspecialchars($complaintId); ?></p>

                    <p><strong>Type: </strong> <?php echo htmlspecialchars($complaintType); ?></p>

                    <p><strong>Subject: </strong> <?php echo htmlspecialchars($complaintSubject); ?></p>

                    <p><strong>Description: </strong> <?php echo htmlspecialchars($complaintDescription); ?></p>

                    <p><strong>File: </strong>
                        <?php if(!empty($uploadedFilePath)): ?>
                            <a href='<?php echo htmlspecialchars($uploadedFilePath); ?>' target="_blank" rel="noopener noreferrer">View Attached File</a>
                        <?php else: ?>
                            No file uploaded.
                        <?php endif; ?>
                    </p>

                    <p><strong>Status: </strong> <span id="currentComplaintStatusDisplay"><?php echo htmlspecialchars($complaintStatus); ?></span></p>
                    <div class="input-group">
                        <label for="adminRemarks">Admin Remarks</label>
                        <?php
                            $currstatus = $complaintStatus;
                            $offrem = $adminRemarks;
                            if($currstatus == 'Forwarded to Admin' || $currstatus == 'Pending'){
                                $currstatus = 'P';
                            } else if($currstatus == 'Closed' || $currstatus == 'Rejected'){
                                $currstatus = 'N';
                            } else {
                                $currstatus = 'N';
                            }
                        ?>
                        <?php if($currstatus=='P'){ ?>
                            <input type="text" id="adminRemarks" name="adminRemarks" placeholder="enter remarks" value="<?php echo htmlspecialchars($offrem ?? ''); ?>"/>
                        <?php } else if($offrem==''){ ?>
                            <p><?php echo 'No remarks'; ?> </p>
                        <?php } else { ?>
                            <p><?php echo htmlspecialchars($offrem); ?> </p>
                        <?php } ?>
                    </div>

                    <?php if($currentComplaintStatusForAction == 'P'){ ?>
                        <div class="input-group">
                            <label for="newStatus">Change Status To</label>
                            <select id="newStatus" name="newStatus" required>
                                <option value="" disabled selected>Select status</option>
                                <option value="Closed">Closed</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                        </div>

                        <div class="submit-btn">
                            <button type="submit" name="submitBtn">Submit</button>
                        </div>
                    <?php } ?>
                </div>
            </form>
        </main>
        </div>
        </div>

        <div id="overlay" class="overlay"></div>
        <script src="../js/script.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function(){
                const statusFilterButton = document.getElementById('statusFilterButton');
                const dropdownContent = document.querySelector('.cstatus-select-content');
                const statusLinks = dropdownContent.querySelectorAll('a');
                const complaintTableBody = document.querySelector('.clist-table tbody');
                let allTableRows = complaintTableBody ? complaintTableBody.querySelectorAll('tr') : [];
                const arrowSymbol = ' ▾';
                const complaintDetailsDiv = document.getElementById("complaintDetails");
                const currentFilterInput = document.getElementById("currentFilter");

                function getQueryParam(name){
                    const urlParams = new URLSearchParams(window.location.search);
                    return urlParams.get(name);
                }

                function filterComplaintsTable(selectedStatus){
                    let visibleComplaintsCount = 0;
                    allTableRows = complaintTableBody ? complaintTableBody.querySelectorAll('tr') : [];
                    allTableRows.forEach(row =>{
                        if(row.classList.contains('no-complaints')){
                            row.style.display = 'none';
                            return;
                        }
                        const statusCell = row.querySelector('.complaint-status');
                        if(statusCell){
                            const rowStatus = statusCell.textContent.trim();
                            if(selectedStatus === 'All'){
                                if(['Forwarded to Admin', 'Closed', 'Rejected'].includes(rowStatus)){
                                    row.style.display = '';
                                    visibleComplaintsCount++;
                                } else {
                                    row.style.display = 'none';
                                }
                            }
                            else if(selectedStatus === 'Rejected'){
                                if(rowStatus === 'Rejected'){
                                    row.style.display = '';
                                    visibleComplaintsCount++;
                                } else {
                                    row.style.display = 'none';
                                }
                            }
                            else if(rowStatus === selectedStatus){
                                row.style.display = '';
                                visibleComplaintsCount++;
                            }
                            else{
                                row.style.display = 'none';
                            }
                        }
                    });

                    const noComplaintsRow = complaintTableBody ? complaintTableBody.querySelector('.no-complaints') : null;
                    if(noComplaintsRow){
                        if(visibleComplaintsCount === 0){
                            noComplaintsRow.style.display = '';
                        }
                        else{
                            noComplaintsRow.style.display = 'none';
                        }
                    }
                }

                statusLinks.forEach(link =>{
                    link.addEventListener('click', function(event){
                        event.preventDefault();
                        const selectedDisplayText = this.textContent;
                        const selectedStatusValue = this.dataset.status;
                        currentFilterInput.value = selectedStatusValue;
                        const url = new URL(window.location.origin + window.location.pathname);
                        url.searchParams.set('filter', selectedStatusValue);
                        url.searchParams.delete('e');
                        window.location.href = url.toString();
                    });
                });

                statusFilterButton.addEventListener('click', function(event){
                    event.stopPropagation();
                    if(dropdownContent.classList.contains('show')){
                        dropdownContent.classList.remove('show');
                    }
                    else{
                        const buttonRect = statusFilterButton.getBoundingClientRect();
                        dropdownContent.style.top =(buttonRect.height) + 'px';
                        dropdownContent.style.left = '0px';
                        dropdownContent.classList.add('show');
                    }
                });

                document.addEventListener('click', function(event){
                    if(!statusFilterButton.contains(event.target) && !dropdownContent.contains(event.target)){
                        dropdownContent.classList.remove('show');
                    }
                });

                let initialFilterValueFromURL = getQueryParam('filter');
                if(!initialFilterValueFromURL){
                    initialFilterValueFromURL = 'All';
                }

                let initialFilterDisplayText = 'All';
                statusLinks.forEach(link =>{
                    if(link.dataset.status === initialFilterValueFromURL){
                        initialFilterDisplayText = link.textContent;
                    }
                });
                statusFilterButton.textContent = initialFilterDisplayText + arrowSymbol;
                currentFilterInput.value = initialFilterValueFromURL;

                filterComplaintsTable(initialFilterValueFromURL);

                const initialDetailsDisplay = <?php echo isset($_GET['e']) ? 'true' : 'false'; ?>;
                if(initialDetailsDisplay){
                    if(complaintDetailsDiv){
                        complaintDetailsDiv.style.display = "block";
                        complaintDetailsDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
                else{
                    if(complaintDetailsDiv){
                        complaintDetailsDiv.style.display = 'none';
                    }
                }
            });
        </script>
    </body>
</html>