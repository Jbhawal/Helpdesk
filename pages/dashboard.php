<?php 
    include_once '../include/config.php';
    session_start();

    if(!isset($_SESSION['empcode'])){
        header("Location: ../index.php");
        exit();
    }

    $ecode = $_SESSION['empcode'];

    //user own complaint details
    $totalLodge = $dbo->query("SELECT COUNT(*) FROM complaints WHERE EMPCODE = '$ecode'")->fetchColumn();
    $pending = $dbo->query("SELECT COUNT(*) FROM complaints WHERE EMPCODE = '$ecode' AND STATUS = 'Pending'")->fetchColumn();
    $inProgress = $dbo->query("SELECT COUNT(*) FROM complaints WHERE EMPCODE = '$ecode' AND STATUS = 'In Progress'")->fetchColumn();
    $returned = $dbo->query("SELECT COUNT(*) FROM complaints WHERE EMPCODE = '$ecode' AND STATUS = 'Return to User'")->fetchColumn();
    $closed = $dbo->query("SELECT COUNT(*) FROM complaints WHERE EMPCODE = '$ecode' AND STATUS = 'Closed'")->fetchColumn();
    $rejected = $dbo->query("SELECT COUNT(*) FROM complaints WHERE EMPCODE = '$ecode' AND STATUS = 'Rejected'")->fetchColumn();
    $rejectedE = $dbo->query("SELECT COUNT(*) FROM complaints WHERE EMPCODE = '$ecode' AND (STATUS = 'Rejected' OR STATUS = 'Rejected by Officer')")->fetchColumn();
    
    //user received complaint details
    $totalORcv = $dbo->query("SELECT COUNT(*) FROM complaints WHERE OFFEMPCODE = '$ecode'")->fetchColumn();
    $totalARcv = $dbo->query("SELECT COUNT(*) FROM complaints WHERE ADMEMPCODE = '$ecode'")->fetchColumn(); 
    $pendingbyO = $dbo->query("SELECT COUNT(*) FROM complaints WHERE CUREMPCODE = '$ecode' AND STATUS = 'Pending'")->fetchColumn();
    $pendingbyA = $dbo->query("SELECT COUNT(*) FROM complaints WHERE CUREMPCODE = '$ecode' AND STATUS = 'Pending'")->fetchColumn();
    $inProgressbyA = $dbo->query("SELECT COUNT(*) FROM complaints WHERE ADMEMPCODE = '$ecode' AND STATUS = 'In Progress'")->fetchColumn();
    $returnedbyO = $dbo->query("SELECT COUNT(*) FROM complaints WHERE OFFEMPCODE = '$ecode' AND EMPCODE != '$ecode' AND STATUS = 'Return to User'")->fetchColumn();
    $returnedbyA = $dbo->query("SELECT COUNT(*) FROM complaints WHERE ADMEMPCODE = '$ecode' AND STATUS = 'Return to User'")->fetchColumn();
    $closedbyA = $dbo->query("SELECT COUNT(*) FROM complaints WHERE ADMEMPCODE = '$ecode' AND STATUS = 'Closed'")->fetchColumn();
    $rejectedbyO = $dbo->query("SELECT COUNT(*) FROM complaints WHERE OFFEMPCODE = '$ecode' AND STATUS = 'Rejected by Officer'")->fetchColumn();
    $rejectedbyA = $dbo->query("SELECT COUNT(*) FROM complaints WHERE ADMEMPCODE = '$ecode' AND STATUS = 'Rejected'")->fetchColumn();
    $usercat=$dbo->query("SELECT CATEGORY FROM user WHERE EMPCODE='$ecode'")->fetchColumn();
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <title>My Dashboard</title>
</head>
<body>
    <!-- navbar+sidebar(from menu.php)+ main content  --> 
    <?php include 'menu.php';?>
            <main class="o-main"> 
                <div class="dashboard">
                    <h1>My Dashboard</h1>
                </div>
                <div class="summary-cards">
                    <?php if($usercat == 'Employee'){ ?>
                        <div class="complaint-box">
                            <div class="card" onclick="window.location.href='view-status.php?&filter=All'">  
                                <h2>Total Lodged</h2>
                                <p><?php echo $totalLodge ?></p>
                            </div>
                            <div class="card" onclick="window.location.href='view-status.php?&filter=Pending'">
                                <h2>Current Pending</h2>
                                <p><?php echo $pending ?></p>
                            </div>
                            <div class="card" onclick="window.location.href='view-status.php?&filter=In+Progres'">
                                <h2>In Progress</h2>
                                <p><?php echo $inProgress ?></p>
                            </div>
                            <div class="card" onclick="window.location.href='view-status.php?&filter=Return+to+User'">
                                <h2>Returned Back</h2>
                                <p><?php echo $returned ?></p>
                            </div>
                            <div class="card" onclick="window.location.href='view-status.php?&filter=Closed'">
                                <h2>Closed</h2>
                                <p><?php echo $closed ?></p>
                            </div>
                            <div class="card" onclick="window.location.href='view-status.php?&filter=RejectedAll'">
                                <h2>Rejected</h2>
                                <p><?php echo $rejectedE ?></p>
                            </div>
                        </div>
                    <?php }
                    else if($usercat == 'Officer'){ ?>
                        <div class="cbox-heading">
                            <h3>Complaints By Me</h3>
                        </div>
                        <div class="complaint-box">
                            <div class="card" onclick="window.location.href='officer-page.php?view=my'">  
                                <h2>Total Lodged</h2>
                                <p><?php echo $totalLodge ?></p>
                            </div>
                            <div class="card" onclick="window.location.href='officer-page.php?view=my&filter=Pending'">
                                <h2>Current Pending</h2>
                                <p><?php echo $pending ?></p>
                            </div>
                            <div class="card" onclick="window.location.href='officer-page.php?view=my&filter=In+Progress'">
                                <h2>In Progress</h2>
                                <p><?php echo $inProgress ?></p>
                            </div>
                            <div class="card" onclick="window.location.href='officer-page.php?view=my&filter=Return+to+User'">
                                <h2>Returned Back</h2>
                                <p><?php echo $returned ?></p>
                            </div>
                            <div class="card" onclick="window.location.href='officer-page.php?view=my&filter=Closed'">
                                <h2>Closed</h2>
                                <p><?php echo $closed ?></p>
                            </div>
                            <div class="card" onclick="window.location.href='officer-page.php?view=my&filter=RejectedAll'">
                                <h2>Rejected</h2>
                                <p><?php echo $rejected ?></p>
                            </div>
                        </div>
                        <div class="complaint-box">
                            <div class="cbox-heading">
                                <h3>Complaints By Users</h3>
                            </div>
                            <div class="card" onclick="window.location.href='officer-page.php?view=received'">  
                                <h2>Total Received</h2>
                                <p><?php echo $totalORcv ?></p>
                            </div>
                            <div class="card" onclick="window.location.href='officer-page.php?view=received&filter=Pending'">
                                <h2>Current Pending</h2>
                                <p><?php echo $pendingbyO ?></p>
                            </div>
                            <div class="card" onclick="window.location.href='officer-page.php?view=received&filter=Return+to+User'">
                                <h2>Returned Back</h2>
                                <p><?php echo $returnedbyO ?></p>
                            </div>
                            <div class="card" onclick="window.location.href='officer-page.php?view=received&filter=RejectedAll'">
                                <h2>Rejected</h2>
                                <p><?php echo $rejectedbyO ?></p>
                            </div>
                        </div>
                        

                    <?php }
                    else if($usercat == 'Admin'){ ?> 
                        <div class="card" onclick="window.location.href='admin-page.php?&filter=All' ";>  
                            <h2>Total Received</h2>
                            <p><?php echo $totalARcv ?></p>
                        </div>
                        <div class="card" onclick="window.location.href='admin-page.php?&filter=Pending' ";>
                            <h2>Current Pending</h2>
                            <p><?php echo $pendingbyA ?></p>
                        </div>
                        <div class="card" onclick="window.location.href='admin-page.php?&filter=In+Progress' ";>
                            <h2>In Progress</h2>
                            <p><?php echo $inProgressbyA ?></p>
                        </div>
                        <div class="card" onclick="window.location.href='admin-page.php?&filter=Return+to+User' ";>
                            <h2>Returned</h2>
                            <p><?php echo $returnedbyA ?></p>
                        </div>
                        <div class="card" onclick="window.location.href='admin-page.php?&filter=Closed' ";>
                            <h2>Closed</h2>
                            <p><?php echo $closedbyA ?></p>
                        </div>
                        <div class="card" onclick="window.location.href='admin-page.php?&filter=Rejected' ";>
                            <h2>Rejected</h2>
                            <p><?php echo $rejectedbyA ?></p>
                        </div>
                    <?php } ?>        
                </div>
            </main>
        </div> <!--closes wrapper(from menu.php) -->
    </div> <!--closes page-container (from menu.php) -->

    <!-- Overlay for small screen dark background -->
    <div class="overlay" id="overlay"></div>
    <script src="../js/script.js"></script>
    <div id="loadingOverlay" class="loading-overlay">
        <div class="spinner"></div>
        <p>Working on it...</p>
    </div>
</body>
</html>