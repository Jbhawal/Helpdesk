<?php
include_once '../include/config.php';
session_start();

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['empcode'])) {
    header("Location: ../index.php"); // Redirect to login or a restricted access page
    exit();
}

$complaintId = '';
$complaintType = '';
$complaintSubject = '';
$complaintDescription = '';
$uploadedFilePath = '';
$complaintStatus = '';
$adminRemarks = '';
$currentComplaintStatusForAction = 'N'; // Default to 'N' (Not ready for admin action)

// Get the current filter from the URL if set
$initialFilter = isset($_GET['filter']) ? htmlspecialchars($_GET['filter']) : 'All';

if (isset($_POST['submitBtn'])) {
    $adminRemarks = $_POST["adminRemarks"];
    $complaintIdToUpdate = $_POST["complaintIdToUpdate"];
    $newStatus = $_POST["newStatus"];
    $currentFilterOnSubmit = $_POST["currentFilter"] ?? 'All'; // Get current filter from hidden input

    // Direct query for update (as requested, no prepared statements)
    $dbo->query("UPDATE complaints SET offrem='$adminRemarks', status='$newStatus' WHERE compid='$complaintIdToUpdate'");

    echo "<script>
            alert('Complaint updated successfully.');
            // Redirect back to admin-page.php, preserving the filter
            window.location.href = 'admin-page.php?filter={$currentFilterOnSubmit}';
          </script>";
}

if (isset($_GET['e'])) {
    $complaintId = $_GET['e'];
    // Direct query for fetching details (as requested, no prepared statements)
    $list = $dbo->query("SELECT COMPID, CTYPE, SUB, DESCR, UPLOADEDFILE, STATUS, OFFREM FROM complaints WHERE compid = '$complaintId'");
    $row = $list->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $complaintId = $row['COMPID'];
        $complaintType = $row['CTYPE'];
        $complaintSubject = $row['SUB'];
        $complaintDescription = $row['DESCR'];
        $uploadedFilePath = $row['UPLOADEDFILE'];
        $complaintStatus = $row['STATUS'];
        $adminRemarks = $row['OFFREM'];

        // Admin can only take action on complaints "Forwarded to Admin"
        if ($complaintStatus == 'Forwarded to Admin') {
            $currentComplaintStatusForAction = 'P'; // 'P' for "Pending Action by Admin"
        } else {
            $currentComplaintStatusForAction = 'N'; // 'N' for "No Action needed by Admin on this complaint"
        }
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
    <body>
        <?php include 'menu.php'; ?>
        <main class="officer-main">
            <div class="cheading">
                <h2>List of Complaints</h2>
                <div class="cstatus-select">
                    <button class="dropbtn" id="statusFilterButton">All ▾</button>
                    <div class="cstatus-select-content">
                        <a href="#" data-status="All">All</a>
                        <a href="#" data-status="Forwarded to Admin">Forwarded to Admin</a>
                        <a href="#" data-status="Resolved">Resolved</a>
                        <a href="#" data-status="Rejected">Rejected</a>
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
                            // Construct the WHERE clause based on the initialFilter
                            $whereClause = "WHERE status IN ('Forwarded to Admin', 'Resolved', 'Rejected')"; // Default for 'All'
                            $noComplaintsMessage = "No complaints found 'Forwarded to Admin', 'Resolved', or 'Rejected'.";

                            if ($initialFilter !== 'All') {
                                $whereClause = "WHERE status = '$initialFilter'";
                                $noComplaintsMessage = "No complaints found.";
                            }

                            // PHP now fetches complaints based on the active filter
                            $results = $dbo->query("SELECT compid, ctype, sub, status FROM complaints {$whereClause}");

                            if ($results->rowCount() > 0) {
                                while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['compid']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['ctype']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['sub']) . "</td>";
                                    echo "<td class='complaint-status'>" . htmlspecialchars($row['status']) . "</td>";
                                    echo "<td><a href='admin-page.php?e=" . htmlspecialchars($row['compid']) . "&filter={$initialFilter}'>View Details</a></td>";
                                    echo "</tr>";
                                }
                            }
                            $displayNoComplaints = ($results->rowCount() == 0) ? '' : 'display:none;';
                            echo "<tr class='no-complaints' style='{$displayNoComplaints}'><td colspan='5'>{$noComplaintsMessage}</td></tr>";
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="cdetails" id="complaintDetails" style="display: <?php echo (isset($_GET['e']) ? 'block' : 'none'); ?>;">
                    <h2>Complaint Details</h2>
                    <input type="hidden" id="complaintIdToUpdate" name="complaintIdToUpdate" value="<?php echo htmlspecialchars($complaintId); ?>" />
                    <p><strong>Complaint ID:</strong> <?php echo htmlspecialchars($complaintId); ?></p>
                    <p><strong>Type:</strong> <?php echo htmlspecialchars($complaintType); ?></p>
                    <p><strong>Subject:</strong> <?php echo htmlspecialchars($complaintSubject); ?></p>
                    <p><strong>Description:</strong> <?php echo htmlspecialchars($complaintDescription); ?></p>
                    <p><strong>File:</strong>
                        <?php if (!empty($uploadedFilePath)): ?>
                            <a href='<?php echo htmlspecialchars($uploadedFilePath); ?>' target="_blank" rel="noopener noreferrer">View Attached File</a>
                        <?php else: ?>
                            No file uploaded.
                        <?php endif; ?>
                    </p>
                    <p><strong>Status:</strong> <?php echo htmlspecialchars($complaintStatus); ?></p>
                    <div class="input-group">
                        <label for="adminRemarks">Admin Remarks</label>
                        <?php if ($currentComplaintStatusForAction == 'P') { ?>
                            <input type="text" id="adminRemarks" name="adminRemarks" placeholder="Enter remarks" value="<?php echo htmlspecialchars($adminRemarks); ?>"/>
                        <?php } else if($adminRemarks=='') { ?>
                            <p><?php echo 'No remarks'; ?> </p>
                        <?php } else { ?>
                            <p><?php echo htmlspecialchars($adminRemarks); ?> </p>
                        <?php } ?>
                    </div>
                    <?php if ($currentComplaintStatusForAction == 'P') { ?>
                        <div class="input-group">
                            <label for="newStatus">Change Status To</label>
                            <select id="newStatus" name="newStatus" required>
                                <option value="" disabled selected>Select status</option>
                                <option value="Resolved">Resolved</option>
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
        <div id="overlay" class="overlay"></div>
        <script src="../js/script.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const statusFilterButton = document.getElementById('statusFilterButton');
                const dropdownContent = document.querySelector('.cstatus-select-content');
                const statusLinks = dropdownContent.querySelectorAll('a');
                const complaintTableBody = document.querySelector('.clist-table tbody');
                let allTableRows = complaintTableBody ? complaintTableBody.querySelectorAll('tr') : [];
                const arrowSymbol = ' ▾';
                const complaintDetailsDiv = document.getElementById("complaintDetails");
                const currentFilterInput = document.getElementById("currentFilter"); 

                function getQueryParam(name) {
                    const urlParams = new URLSearchParams(window.location.search);
                    return urlParams.get(name);
                }

                function filterComplaintsTable(selectedStatus) {
                    let visibleComplaintsCount = 0;
                    allTableRows = complaintTableBody ? complaintTableBody.querySelectorAll('tr') : [];
                    allTableRows.forEach(row => {
                        if (row.classList.contains('no-complaints')) {
                            row.style.display = 'none'; 
                            return;
                        }
                        const statusCell = row.querySelector('.complaint-status');
                        if (statusCell) {
                            const rowStatus = statusCell.textContent.trim();
                            if (selectedStatus === 'All') {
                                row.style.display = '';
                                visibleComplaintsCount++;
                            } else if (rowStatus === selectedStatus) {
                                row.style.display = '';
                                visibleComplaintsCount++;
                            } else {
                                row.style.display = 'none';
                            }
                        }
                    });

                    const noComplaintsRow = complaintTableBody ? complaintTableBody.querySelector('.no-complaints') : null;
                    if (noComplaintsRow) {
                        if (visibleComplaintsCount === 0) {
                            noComplaintsRow.style.display = '';
                        } else {
                            noComplaintsRow.style.display = 'none';
                        }
                    }
                }

                statusLinks.forEach(link => {
                    link.addEventListener('click', function(event) {
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

                statusFilterButton.addEventListener('click', function(event) {
                    event.stopPropagation();
                    if (dropdownContent.style.display === 'block') {
                        dropdownContent.style.display = 'none';
                    } else {
                        const buttonRect = statusFilterButton.getBoundingClientRect();
                        dropdownContent.style.top = (buttonRect.height) + 'px';
                        dropdownContent.style.left = '0px';
                        dropdownContent.style.display = 'block';
                    }
                });

                document.addEventListener('click', function(event) {
                    if (!statusFilterButton.contains(event.target) && !dropdownContent.contains(event.target)) {
                        dropdownContent.style.display = 'none';
                    }
                });
                let initialFilterValueFromURL = getQueryParam('filter');
                if (!initialFilterValueFromURL) {
                    initialFilterValueFromURL = 'All';
                }

                let initialFilterDisplayText = 'All'; 
                statusLinks.forEach(link => {
                    if (link.dataset.status === initialFilterValueFromURL) {
                        initialFilterDisplayText = link.textContent;
                    }
                });
                statusFilterButton.textContent = initialFilterDisplayText + arrowSymbol;
                currentFilterInput.value = initialFilterValueFromURL;
                const initialDetailsDisplay = <?php echo isset($_GET['e']) ? 'true' : 'false'; ?>;
                if (initialDetailsDisplay) {
                    if (complaintDetailsDiv) {
                        complaintDetailsDiv.style.display = "block";
                        complaintDetailsDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                } else {
                    if (complaintDetailsDiv) {
                        complaintDetailsDiv.style.display = 'none';
                    }
                }
            });
        </script>
    </body>
</html>