<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $current = basename($_SERVER['PHP_SELF']); 

    // session_start();
    include_once '../include/config.php';
    $ecode = $_SESSION['empcode'];
    $listemp = $dbo->query("SELECT EMPNAME, DESG, SEC, DEPT, CATEGORY FROM user WHERE empcode ='$ecode'");
    while ($rowemp = $listemp->fetch(PDO::FETCH_ASSOC))
    {	
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
        <title>Menu</title>
    </head>
    <body>
        <!-- Navigation bar  -->
        <nav class="navbar">
            <button id="toggle-btn">☰</button>
            <div class="logo">
                <img src="../images/Indian-Railways.jpg" alt="logo">
                <span style="display:flex;"><h2>Helpdesk</h2></span>
            </div>
            <div class="welcome-text">
                <span><p>Welcome!</span>
                <span><?php echo $empname; ?>, <?php echo $desg; ?>, <?php echo $dept; ?>, <?php echo $sec; ?></span>
            </div>
        </nav>
            <?php
            if ($catg === 'E') {
            ?>
                <aside class="sidebar" id="sidebar">
                    <ul class="sidebar-menu">
                        <li><a href="dashboard.php" class="<?= $current === 'dashboard.php' ? 'active' : '' ?>">Dashboard</a></li>
                        <li><a href="employee-page.php" class="<?= $current === 'employee-page.php' ? 'active' : '' ?>">New Complaint</a></li>
                        <li><a href="status.php" class="<?= $current === 'status.php' ? 'active' : '' ?>">Show Status</a></li>
                        <li><a href="report.php" class="<?= $current === 'report.php' ? 'active' : '' ?>">Report</a></li>
                        <li><a href="logout.php" class="<?= $current === 'logout.php' ? 'active' : '' ?>">Logout</a></li>
                    </ul>
                </aside>
            <?php
            } elseif ($catg === 'O') {
                ?>
                <aside class="sidebar" id="sidebar">
                    <ul class="sidebar-menu">
                        <li><a href="dashboard.php" class="<?= $current === 'dashboard.php' ? 'active' : '' ?>">Dashboard</a></li>
                        <li><a href="officer-page.php" class="<?= $current === 'officer-page.php' ? 'active' : '' ?>">All Complaints</a></li>
                        <li><a href="report.php" class="<?= $current === 'report.php' ? 'active' : '' ?>">Report</a></li>
                        <li><a href="logout.php" class="<?= $current === 'logout.php' ? 'active' : '' ?>">Logout</a></li>
                    </ul>
                </aside>
            <?php
            } elseif ($catg === 'A') {
                ?>
                <aside class="sidebar" id="sidebar">
                    <ul class="sidebar-menu">
                        <li><a href="dashboard.php" class="<?= $current === 'dashboard.php' ? 'active' : '' ?>">Dashboard</a></li>
                        <li><a href="employee-page.php" class="<?= $current === 'employee-page.php' ? 'active' : '' ?>">New Complaint</a></li>
                        <li><a href="status.php" class="<?= $current === 'status.php' ? 'active' : '' ?>">Show Status</a></li>
                        <li><a href="report.php" class="<?= $current === 'report.php' ? 'active' : '' ?>">Report</a></li>
                        <li><a href="logout.php" class="<?= $current === 'logout.php' ? 'active' : '' ?>">Logout</a></li>
                    </ul>
                </aside>
            <?php       
            }
            ?>
        <script>
            const links = document.querySelectorAll('.sidebar-link');
            const currentPage = window.location.pathname.split('/').pop(); // gets last part of path (e.g. "employee-page.php")

            links.forEach(link => {
                const linkPage = link.getAttribute('href');
                if (linkPage === currentPage) {
                    link.classList.add('active');
                }
            });
        </script>

    </body>
</html>