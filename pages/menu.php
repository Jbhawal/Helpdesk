<?php
    session_start();
    include_once '../include/config.php';
    $ecode = $_SESSION['empcode'];
    $listemp = $dbo->query("SELECT EMPNAME, DESG, SEC, DEPT FROM user WHERE empcode ='$ecode'");
    while ($rowemp = $listemp->fetch(PDO::FETCH_ASSOC))
    {	
        $empname	=	$rowemp["EMPNAME"];
        $desg	=	$rowemp["DESG"];
        $sec	=	$rowemp['SEC'];
        $dept	=	$rowemp['DEPT'];
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
        <title>Welcome</title>
    </head>
    <body>
        <nav class="navbar">
            <button id="toggle-btn">☰</button>
            <div class="logo">
                <img src="../images/Indian-Railways.jpg" alt="logo">
            </div>
            <div class="welcome-text">
                <span><p>Welcome!</span>
                <span><?php echo $empname; ?>, <?php echo $desg; ?>, <?php echo $dept; ?>, <?php echo $sec; ?></span>
            </div>
        </nav>
            <aside class="sidebar" id="sidebar">
                <ul>
                    <li><a href="#">Dashboard</a></li>
                    <li><a href="employee-page.php">New Complaint</a></li>
                    <li><a href="#">Show Status</a></li>
                    <li><a href="#">Report</a></li>
                    <li><a href="#">Logout</a></li>
                </ul>
            </aside>
        

    <!-- Overlay for small screen dark background -->
    <div class="overlay" id="overlay"></div>

        <script src="../js/script.js"></script>
    </body>
</html>