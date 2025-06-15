<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $current = basename($_SERVER['PHP_SELF']); 
    // session_start();
    include_once '../include/config.php';
    $ecode = $_SESSION['empcode'];
    $listemp = $dbo->query("SELECT EMPNAME, DESG, SEC, DEPT, CATEGORY FROM user WHERE empcode ='$ecode'");
    while ($rowemp = $listemp->fetch(PDO::FETCH_ASSOC)){	
        $empname = $rowemp["EMPNAME"];
        $desg = $rowemp["DESG"];
        $sec = $rowemp['SEC'];
        $dept = $rowemp['DEPT'];
        $catg = $rowemp['CATEGORY'];
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
        <title>Employee Menu</title>
    </head>
    <body>           
        <!-- navbar+sidebar(from menu.php)+ main content  --> 
                <?php include 'menu.php';?>
                        <main class="officer-main"> 
                            <img style="height: 300px; width:auto; display: block; margin: 145px auto;" src="../images/helpdesk-image.jpg" alt="Employee Image">
                            
                        </main>
                    </div> <!--closes wrapper(from menu.php) -->
            </div> <!--closes page-container (from menu.php) -->

        <!-- Overlay for small screen dark background -->
        <div class="overlay" id="overlay"></div>
        <script src="../js/script.js"></script>
    </body>
</html>



