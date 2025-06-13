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
		$ccode	= $_GET['e'];
		$list=$dbo->query("SELECT COMPID, CTYPE, SUB, DESCR, UPLOADEDFILE, STATUS, OFFREM FROM complaints WHERE compid = '$ccode'"); 
		while ($row = $list->fetch(PDO::FETCH_ASSOC)){
			$compid  = $row['COMPID'];
			$ctype = $row['CTYPE'];
			$sub = $row['SUB'];
			$descr = $row['DESCR'];
			$uploadedFile = $row['UPLOADEDFILE'];
			$status	= $row['STATUS'];
			$offrem	= $row['OFFREM'];
            if($status == 'Pending'){
                $currstatus='P';
            }
            elseif($status == 'Rejected'){
                $currstatus='R';
            }
            elseif($status == 'Forwarded to Admin'){
                $currstatus='F';
            }
            else{
                $currstatus='A';
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
        <!-- navbar+sidebar(from menu.php)+ main content  --> 
        <?php include 'menu.php';?>
        <!-- <div class="wrapper"> -->
            
            <main class="officer-main">
                <!-- <h2>Officer Page</h2> -->
                 <div class="cheading">
                     <h2>List of all complaints</h2>
                     <div class="cstatus-select">
                        <button class="dropbtn" >Show &#9660;</button>
                        <div class="cstatus-select-content">
                            <a href="#">All</a>
                            <a href="#">Pending</a>
                            <a href="#">Rejected</a>
                            <a href="#">Forwarded to Admin</a>
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
                                <?php if($currstatus=='P'){ 
                                    $results = $dbo->query("SELECT compid, ctype, sub, status FROM complaints WHERE forwardto='$ecode' AND status='Pending'");
                                    while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<tr>";
                                        echo "<td>{$row['compid']}</td>";
                                        echo "<td>{$row['ctype']}</td>";
                                        echo "<td>{$row['sub']}</td>";
                                        echo "<td>{$row['status']}</td>";
                                        echo "<td><a href='officer-page.php?e={$row['compid']}'>View Details</a></td>";
                                        echo "</tr>";
                                    }
                                    if ($results->rowCount() == 0) {
                                        echo "<tr class='no-complaints'><td colspan='5'>No complaints found.</td></tr>";
                                    }
                                } elseif($currstatus=='R'){
                                    $results = $dbo->query("SELECT compid, ctype, sub, status FROM complaints WHERE forwardto='$ecode' AND status='Rejected'");
                                    while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<tr>";
                                        echo "<td>{$row['compid']}</td>";
                                        echo "<td>{$row['ctype']}</td>";
                                        echo "<td>{$row['sub']}</td>";
                                        echo "<td>{$row['status']}</td>";
                                        echo "<td><a href='officer-page.php?e={$row['compid']}'>View Details</a></td>";
                                        echo "</tr>";
                                    }
                                    if ($results->rowCount() == 0) {
                                        echo "<tr class='no-complaints'><td colspan='5'>No complaints found.</td></tr>";
                                    }
                                } elseif($currstatus=='F'){
                                    $results = $dbo->query("SELECT compid, ctype, sub, status FROM complaints WHERE forwardto='$ecode' AND status='Forwarded to Admin'");
                                    while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<tr>";
                                        echo "<td>{$row['compid']}</td>";
                                        echo "<td>{$row['ctype']}</td>";
                                        echo "<td>{$row['sub']}</td>";
                                        echo "<td>{$row['status']}</td>";
                                        echo "<td><a href='officer-page.php?e={$row['compid']}'>View Details</a></td>";
                                        echo "</tr>";
                                    }
                                    if ($results->rowCount() == 0) {
                                        echo "<tr class='no-complaints'><td colspan='5'>No complaints found.</td></tr>";
                                    }
                                } else{
                                    $results = $dbo->query("SELECT compid, ctype, sub, status FROM complaints WHERE forwardto='$ecode' AND status='Accepted'");
                                    while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<tr>";
                                        echo "<td>{$row['compid']}</td>";
                                        echo "<td>{$row['ctype']}</td>";
                                        echo "<td>{$row['sub']}</td>";
                                        echo "<td>{$row['status']}</td>";
                                        echo "<td><a href='officer-page.php?e={$row['compid']}'>View Details</a></td>";
                                        echo "</tr>";
                                    }
                                    if ($results->rowCount() == 0) {
                                        echo "<tr class='no-complaints'><td colspan='5'>No complaints found.</td></tr>";
                                    }
                                }?>
                            </div>



                                <?php
                                    $results = $dbo->query("SELECT compid, ctype, sub, status FROM complaints WHERE forwardto='$ecode'");
                                    while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<tr>";
                                        echo "<td>{$row['compid']}</td>";
                                        echo "<td>{$row['ctype']}</td>";
                                        echo "<td>{$row['sub']}</td>";
                                        echo "<td>{$row['status']}</td>";
                                        echo "<td><a href='officer-page.php?e={$row['compid']}'>View Details</a></td>";
                                        echo "</tr>";
                                    }
                                    if ($results->rowCount() == 0) {
                                        echo "<tr class='no-complaints'><td colspan='5'>No complaints found.</td></tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="cdetails" id="complaintDetails">
                        <h2>Complaint Details</h2>
                        <input type="hidden" id="cccode" name="cccode" value="<?php echo $ccode ?>" />
                        <p><strong>Complaint ID:</strong><?php echo $ccode ?></p>
                        <p><strong>Type:</strong> <?php echo $ctype ?></p>
                        <p><strong>Subject:</strong> <?php echo $sub ?></p>
                        <p><strong>Description:</strong> <?php echo $descr ?></p>
                        <p><strong>File:</strong> <a href='<?php echo $uploadedFile ?>' target="_blank" rel="noopener noreferrer"><?php echo $uploadedFile ?></a></p>
                        <p><strong>Status:</strong> <?php echo $status ?></p>
                        <div class="input-group">
                            <label for="oremarks">Remarks</label>
                            <?php if($currstatus=='P'){ ?>
                                <input type="text" id="oremarks" name="oremarks" placeholder="enter remarks" required/>   
                                <?php } 
                                else{ ?>
                                    <p><?php echo $offrem ?> </p>
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
           
       
        </div>
        </div>

        <?php if(isset($_GET['e'])): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                document.getElementById("complaintDetails").style.display = "block";
                document.getElementById("complaintDetails").scrollIntoView({ behavior: 'smooth' });
            });
        </script>
        <?php endif; ?>
        <div id="overlay" class="overlay"></div>
        <script src="../js/script.js"></script>
    </body>
</html>