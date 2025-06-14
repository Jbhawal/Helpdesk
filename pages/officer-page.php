<?php
    include_once '../include/config.php';
    session_start();

    $ecode = $_SESSION['empcode'];

    $ccode = '';
    $compid  = '';
    $ctype = '';
    $sub = '';
    $descr = '';
    $uploadedFile = '';
    $status = '';
    $offrem = '';
    
    if(!isset($_SESSION['empcode'])){
        header("Location: ../index.php");
        exit();
    }

    if(isset($_POST['submitBtn'])){
        $oremarks = $_POST["oremarks"];
        $ccode = $_POST["cccode"];
        $ostatus = $_POST["ostatus"];
        $db_user = $dbo->query("UPDATE complaints SET offrem='$oremarks', status='$ostatus' WHERE compid='$ccode'");
        echo "<script>
                    alert('Update successful.');
                    window.location.href = 'officer-page.php';
                </script>";
    }

    if(isset($_GET['e'])){    
        $ccode  = $_GET['e'];
        $list=$dbo->query("SELECT COMPID, CTYPE, SUB, DESCR, UPLOADEDFILE, STATUS, OFFREM FROM complaints WHERE compid = '$ccode'"); 
        while($row = $list->fetch(PDO::FETCH_ASSOC)){
            $compid  = $row['COMPID'];
            $ctype = $row['CTYPE'];
            $sub = $row['SUB'];
            $descr = $row['DESCR'];
            $uploadedFile = $row['UPLOADEDFILE'];
            $status = $row['STATUS'];
            $offrem = $row['OFFREM'];
            if($status == 'Pending'){
                $currstatus='P';
            }
            else{
                $currstatus='N';
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
        <title>Officer Page</title>
    </head>
    <body>          
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
                                <a href="#" data-status="Closed">Closed</a>
                            </div>
                        </div>
                    </div>
                    <form action="" method="post" class="complaint-form">
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
                                        $results = $dbo->query("SELECT compid, ctype, sub, status FROM complaints WHERE forwardto='$ecode'");
                                        if($results->rowCount() > 0){
                                            while($row = $results->fetch(PDO::FETCH_ASSOC)){
                                                echo "<tr>";
                                                echo "<td>".htmlspecialchars($row['compid'])."</td>";
                                                echo "<td>".htmlspecialchars($row['ctype'])."</td>";
                                                echo "<td>".htmlspecialchars($row['sub'])."</td>";
                                                echo "<td class='complaint-status'>".htmlspecialchars($row['status'])."</td>";
                                                echo "<td><a href='officer-page.php?e=".htmlspecialchars($row['compid'])."'>View Details</a></td>";
                                                echo "</tr>";
                                            }
                                        } 
                                        $displayNoComplaints =($results->rowCount() == 0) ? '' : 'display:none;';
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
                                    <input type="text" id="oremarks" name="oremarks" placeholder="enter remarks" value="<?php echo htmlspecialchars($offrem); ?>"/>    
                                <?php } else if($offrem==''){ ?>
                                    <p><?php echo 'No remarks'; ?> </p>
                                <?php } else{ ?>
                                    <p><?php echo htmlspecialchars($offrem); ?> </p>
                                <?php }?>
                            </div>

                            <?php if($currstatus=='P'){ ?>
                                <div class="input-group">
                                    <label for="ostatus">Current Status</label>
                                    <select id="ostatus" name="ostatus" required>
                                        <option value="" disabled selected>select status</option>
                                        <option value="Rejected">Return to User</option>
                                        <option value="Forwarded to Admin">Forward to Admin</option>
                                    </select>
                                </div>

                                <div class="submit-btn">
                                    <button type="submit" name="submitBtn">Submit</button>
                                </div>
                            <?php }  ?>
                        </div>
                    </form>
                </main>
            </div> <!--closes wrapper(from menu.php) -->
        </div> <!--closes page-container (from menu.php) -->
        <div id="overlay" class="overlay"></div>
        <script src="../js/script.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function(){
                const statusFilterButton = document.getElementById('statusFilterButton');
                const dropdownContent = document.querySelector('.cstatus-select-content');
                const statusLinks = dropdownContent.querySelectorAll('a');
                const complaintTableBody = document.querySelector('.clist-table tbody');
                const allTableRows = complaintTableBody ? complaintTableBody.querySelectorAll('tr') : []; 
                const arrowSymbol = ' ▾';
                const complaintDetailsDiv = document.getElementById("complaintDetails");

                function filterComplaintsTable(selectedStatus){
                    let visibleComplaintsCount = 0; 

                    allTableRows.forEach(row =>{
                        if(row.classList.contains('no-complaints')){
                            row.style.display = 'none';
                            return; 
                        }

                        const statusCell = row.querySelector('.complaint-status');
                        if(statusCell){
                            const rowStatus = statusCell.textContent.trim();
                            if(selectedStatus === 'All'){
                                row.style.display = '';
                                visibleComplaintsCount++;
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
                            noComplaintsRow.style.display = ''; // Show if no complaints are visible
                        } 
                        else{
                            noComplaintsRow.style.display = 'none'; // Hide if complaints are visible
                        }
                    }
                }

                statusLinks.forEach(link =>{
                    link.addEventListener('click', function(event){
                        event.preventDefault();
                        const selectedDisplayText = this.textContent;
                        const selectedStatusValue = this.dataset.status;

                        statusFilterButton.textContent = selectedDisplayText + arrowSymbol;
                        dropdownContent.style.display = 'none';

                        if(complaintDetailsDiv){
                            complaintDetailsDiv.style.display = 'none';
                        }

                        filterComplaintsTable(selectedStatusValue);
                    });
                });

                statusFilterButton.addEventListener('click', function(event){
                    event.stopPropagation();
                    if(dropdownContent.style.display === 'block'){
                        dropdownContent.style.display = 'none';
                    } 
                    else{
                        const buttonRect = statusFilterButton.getBoundingClientRect();
                        dropdownContent.style.top =(buttonRect.height) + 'px';
                        dropdownContent.style.left = '0px';
                        dropdownContent.style.display = 'block';
                    }
                });

                document.addEventListener('click', function(event){
                    if(!statusFilterButton.contains(event.target) && !dropdownContent.contains(event.target)){
                        dropdownContent.style.display = 'none';
                    }
                });

                filterComplaintsTable('All');
                statusFilterButton.textContent = 'All' + arrowSymbol;

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