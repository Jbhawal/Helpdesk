<?php
    include_once '../include/config.php';
    session_start();

    $dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $ecode = $_SESSION['empcode'];

    $ccode = '';
    $compid = '';
    $ctype = '';
    $sub = '';
    $descr = '';
    $uploadedFile = '';
    $status = '';
    $offrem = '';
    $currstatus = '';

    if(!isset($_SESSION['empcode'])){
        header("Location: ../index.php");
        exit();
    }

    if(isset($_POST['submitBtn'])){
        $oremarks = trim($_POST["oremarks"] ?? '');
        $ccode = trim($_POST["cccode"] ?? '');
        $ostatus = trim($_POST["ostatus"] ?? '');
        $originalFilter = trim($_POST["originalFilter"] ?? 'All');

        $offrem_for_db = ($oremarks === '') ? NULL : $oremarks;

        if ($ostatus === 'Rejected') {
            $ostatus_for_db = 'Rejected by Officer';
        } else {
            $ostatus_for_db = $ostatus;
        }

        try {
            $stmt = $dbo->prepare("UPDATE complaints SET offrem = :offrem, status = :status WHERE compid = :compid");

            $stmt->bindValue(':offrem', $offrem_for_db, ($offrem_for_db === NULL ? PDO::PARAM_NULL : PDO::PARAM_STR));
            $stmt->bindValue(':status', $ostatus_for_db, PDO::PARAM_STR);
            $stmt->bindValue(':compid', $ccode, PDO::PARAM_STR);

            $stmt->execute();

            echo "<script>
                    alert('Update successful.');
                    window.location.href = 'officer-page.php?filter={$originalFilter}';
                  </script>";
            exit();
        } catch (PDOException $e) {
            error_log("Officer page DB error: " . $e->getMessage());
            echo "<script>alert('An error occurred during the update. Please try again.');</script>";
        }
    }

    if(isset($_GET['e'])){
        $ccode = htmlspecialchars($_GET['e']);
        try {
            $stmt_list = $dbo->prepare("SELECT COMPID, CTYPE, SUB, DESCR, UPLOADEDFILE, STATUS, OFFREM FROM complaints WHERE compid = :ccode");
            $stmt_list->bindValue(':ccode', $ccode, PDO::PARAM_STR);
            $stmt_list->execute();
            $row = $stmt_list->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $compid = $row['COMPID'];
                $ctype = $row['CTYPE'];
                $sub = $row['SUB'];
                $descr = $row['DESCR'];
                $uploadedFile = $row['UPLOADEDFILE'];
                $status = $row['STATUS'];
                $offrem = $row['OFFREM'] ?? '';

                if($status == 'Pending'){
                    $currstatus='P';
                } else {
                    $currstatus='N';
                }
            } else {
                echo "<script>alert('Complaint not found.'); window.location.href = 'officer-page.php';</script>";
                exit();
            }
        } catch (PDOException $e) {
            error_log("Officer page DB fetch error: " . $e->getMessage());
            echo "<script>alert('Error fetching complaint details.'); window.location.href = 'officer-page.php';</script>";
            exit();
        }
    }

    $currentFilter = isset($_GET['filter']) ? htmlspecialchars($_GET['filter']) : 'All';
    $whereConditions = ["forwardto = :ecode"];

    if ($currentFilter !== 'All') {
        if ($currentFilter === 'Rejected') {
            $whereConditions[] = "status = 'Rejected by Officer'";
        } else {
            $whereConditions[] = "status = :statusFilter";
        }
    }

    $sql_list_complaints = "SELECT compid, ctype, sub, status FROM complaints WHERE " . implode(" AND ", $whereConditions);

    try {
        $stmt_all_complaints = $dbo->prepare($sql_list_complaints);
        $stmt_all_complaints->bindValue(':ecode', $ecode, PDO::PARAM_STR);
        if ($currentFilter !== 'All' && $currentFilter !== 'Rejected') {
            $stmt_all_complaints->bindValue(':statusFilter', $currentFilter, PDO::PARAM_STR);
        }
        $stmt_all_complaints->execute();
        $results_table = $stmt_all_complaints->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Officer page DB list error: " . $e->getMessage());
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
        <title>Officer Page</title>
    </head>
    <body data-is-detail-view-active="<?php echo (isset($_GET['e']) ? 'true' : 'false'); ?>" data-is-page="officer">
        <?php include 'menu.php';?>
        <main class="officer-main">
            <div class="cheading">
                <h2>List of all complaints</h2>
                <div class="cstatus-select">
                    <button class="dropbtn" id="statusFilterButton">All ▾</button>
                    <div class="cstatus-select-content">
                        <a href="#" data-status="All">All</a>
                        <a href="#" data-status="Pending">Pending</a>
                        <a href="#" data-status="Rejected">Rejected</a>
                        <a href="#" data-status="Forwarded to Admin">Forwarded to Admin</a>
                    </div>
                </div>
            </div>
            <form action="" method="post" class="complaint-form">
                <input type="hidden" id="currentFilter" value="<?php echo htmlspecialchars($currentFilter); ?>">
                <input type="hidden" id="originalFilter" name="originalFilter" value="<?php echo htmlspecialchars($currentFilter); ?>">
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
                                        echo "<tr>";
                                        echo "<td>".htmlspecialchars($row['compid'])."</td>";
                                        echo "<td>".htmlspecialchars($row['ctype'])."</td>";
                                        echo "<td>".htmlspecialchars($row['sub'])."</td>";
                                        echo "<td class='complaint-status'>".htmlspecialchars($row['status'])."</td>";
                                        echo "<td><a href='officer-page.php?e=".htmlspecialchars($row['compid'])."&filter=".htmlspecialchars($currentFilter)."'>View Details</a></td>";
                                        echo "</tr>";
                                    }
                                }
                                $displayNoComplaints = empty($results_table) ? '' : 'display:none;';
                                echo "<tr class='no-complaints' style='{$displayNoComplaints}'><td colspan='5'>No complaints found.</td></tr>";
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
                    <p><strong>Status:</strong> <?php echo htmlspecialchars($status); ?></p>
                    <div class="input-group">
                        <label for="oremarks">Officer Remarks: </label>
                        <?php if($currstatus=='P'){ ?>
                            <input type="text" id="oremarks" name="oremarks" placeholder="enter remarks" value="<?php echo htmlspecialchars($offrem ?? ''); ?>"/>
                        <?php } else if($offrem==''){ ?>
                            <p><?php echo 'No remarks'; ?> </p>
                        <?php } else { ?>
                            <p><?php echo htmlspecialchars($offrem); ?> </p>
                        <?php }?>
                    </div>

                    <?php if($currstatus=='P'){ ?>
                        <div class="input-group">
                            <label for="ostatus">Update Status</label>
                            <select id="ostatus" name="ostatus" required>
                                <option value="" disabled selected>select status</option>
                                <option value="Rejected">Return to User</option>
                                <option value="Forwarded to Admin">Forward to Admin</option>
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
                const arrowSymbol = ' ▾';
                const complaintDetailsDiv = document.getElementById("complaintDetails");
                const currentFilterInput = document.getElementById('currentFilter');

                let closeDropdownListener = null;

                function getQueryParam(name){
                    const urlParams = new URLSearchParams(window.location.search);
                    return urlParams.get(name);
                }

                function filterComplaintsTable(selectedStatus){
                    let visibleComplaintsCount = 0;

                    const allTableRows = complaintTableBody ? complaintTableBody.querySelectorAll('tr') : [];

                    allTableRows.forEach(row => {
                        if(row.classList.contains('no-complaints')){
                            row.style.display = 'none';
                            return;
                        }
                        const statusCell = row.querySelector('.complaint-status');
                        if(statusCell){
                            const rowStatus = statusCell.textContent.trim();
                            let shouldDisplay = false;

                            if(document.body.dataset.isPage === 'officer'){
                                 if(selectedStatus === 'All'){
                                    shouldDisplay = true;
                                } else if (selectedStatus === 'Rejected') {
                                    shouldDisplay = (rowStatus === 'Rejected by Officer');
                                } else {
                                    shouldDisplay = (rowStatus === selectedStatus);
                                }
                            } else {
                                if(selectedStatus === 'All'){
                                    shouldDisplay = ['Forwarded to Admin', 'Closed', 'Rejected', 'Rejected by Officer'].includes(rowStatus);
                                } else if (selectedStatus === 'Rejected') {
                                    shouldDisplay = (rowStatus === 'Rejected' || rowStatus === 'Rejected by Officer');
                                } else {
                                    shouldDisplay = (rowStatus === selectedStatus);
                                }
                            }

                            if(shouldDisplay){
                                row.style.display = '';
                                visibleComplaintsCount++;
                            } else {
                                row.style.display = 'none';
                            }
                        }
                    });

                    const noComplaintsRow = complaintTableBody ? complaintTableBody.querySelector('.no-complaints') : null;
                    if(noComplaintsRow){
                        if(visibleComplaintsCount === 0){
                            noComplaintsRow.style.display = '';
                        } else {
                            noComplaintsRow.style.display = 'none';
                        }
                    }
                }

                statusLinks.forEach(link => {
                    link.addEventListener('click', function(event){
                        event.preventDefault();
                        const selectedStatusValue = this.dataset.status;

                        dropdownContent.style.display = 'none';

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
                        if (closeDropdownListener) {
                            document.removeEventListener('click', closeDropdownListener);
                            closeDropdownListener = null;
                        }
                    } 
                    else {
                        const buttonRect = statusFilterButton.getBoundingClientRect();
                        dropdownContent.style.top = (buttonRect.height) + 'px';
                        dropdownContent.style.left = '0px';
                        dropdownContent.classList.add('show');


                        setTimeout(() => {
                            if (!closeDropdownListener) {
                                closeDropdownListener = function(e) {
                                    if (!statusFilterButton.contains(e.target) && !dropdownContent.contains(e.target)) {
                                        dropdownContent.classList.remove('show');

                                        document.removeEventListener('click', closeDropdownListener);
                                        closeDropdownListener = null;
                                    }
                                };
                                document.addEventListener('click', closeDropdownListener);
                            }
                        }, 50);
                    }
                });

                const initialFilterFromURL = getQueryParam('filter');
                let filterToApply = initialFilterFromURL || 'All';

                let displayFilterText = filterToApply;

                if (document.body.dataset.isPage === 'officer' && filterToApply === 'Rejected by Officer') {
                    displayFilterText = 'Rejected';
                }

                statusLinks.forEach(link => {
                    if (link.dataset.status === filterToApply) {
                        displayFilterText = link.textContent;
                    } else if (document.body.dataset.isPage === 'officer' && filterToApply === 'Rejected by Officer' && link.dataset.status === 'Rejected') {
                        displayFilterText = link.textContent;
                    }
                });

                statusFilterButton.textContent = displayFilterText + arrowSymbol;
                currentFilterInput.value = filterToApply;

                filterComplaintsTable(filterToApply);

                const initialDetailsDisplay = <?php echo isset($_GET['e']) ? 'true' : 'false'; ?>;
                if(initialDetailsDisplay){
                    if(complaintDetailsDiv){
                        complaintDetailsDiv.style.display = "block";
                        complaintDetailsDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                } else {
                    if(complaintDetailsDiv){
                        complaintDetailsDiv.style.display = 'none';
                    }
                }
            });
        </script>
    </body>
</html>