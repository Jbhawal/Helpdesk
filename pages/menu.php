<!-- this contains the navbar and sidebar -->

<?php
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }

    $current = basename($_SERVER['PHP_SELF']); 
    // session_start();
    include_once '../include/config.php';
    $ecode = $_SESSION['empcode'];
    $deptname = '';
    $secname = '';

    $listemp = $dbo->query("SELECT EMPNAME, DESG, SEC, DEPT, CATEGORY FROM user WHERE empcode ='$ecode'");
    while($rowemp = $listemp->fetch(PDO::FETCH_ASSOC)){	
        $empname = $rowemp["EMPNAME"];
        $desg = $rowemp["DESG"];
        $sec = $rowemp['SEC'];
        $dept = $rowemp['DEPT'];
        $catg = $rowemp['CATEGORY'];
        $deptname = $dbo->query("SELECT LONGDESC FROM mastertable WHERE UCODE = '$dept' AND CODEHEAD='DEPT'")->fetchColumn();
        $secname = $dbo->query("SELECT LONGDESC FROM mastertable WHERE UCODE = '$sec' AND CODEHEAD='SEC'")->fetchColumn();
    }
?>

<div class="page-container">
    <!-- Navigation bar  -->
    <nav class="navbar">
        <button id="toggle-btn">☰</button>
        <div class="logo">
            <img src="../images/Indian-Railways.jpg" alt="logo">
            <span style="display:flex;"><h2>Helpdesk</h2></span>
        </div>
        <div class="welcome-text">
            <span><p><strong>Welcome!  </strong><?php echo $empname; ?>, <?php echo $desg; ?>, <?php echo $deptname; ?>, <?php echo $secname; ?></span>
        </div>
    </nav>

    <div class="wrapper">
        <?php if($catg === 'Employee'){ ?>
            <aside class="sidebar" id="sidebar">
                <ul class="sidebar-menu">
                    <li><a href="dashboard.php" class="sidebar-link <?= $current === 'dashboard.php' ? 'active' : '' ?>">Dashboard</a></li>
                    <li><a href="employee-page.php" class="sidebar-link <?= $current === 'employee-page.php' ? 'active' : '' ?>">New Complaint</a></li>
                    <li><a href="view-status.php" class="sidebar-link <?= $current === 'view-status.php' ? 'active' : '' ?>">Show Status</a></li>
                    <li><a href="logout.php" onclick="return confirm('Are you sure you want to logout?')" class="sidebar-link <?= $current === 'logout.php' ? 'active' : '' ?>">Logout</a></li>
                </ul>
            </aside>
        <?php } 
        else if($catg === 'Officer'){ ?>
            <?php
                $currentView = isset($_GET['view']) ? $_GET['view'] : '';
                $currentPage = basename($_SERVER['PHP_SELF']);
            ?>
            <aside class="sidebar" id="sidebar">
                <ul class="sidebar-menu">
                    <li><a href="dashboard.php" class="sidebar-link <?= $currentPage === 'dashboard.php' ? 'active' : '' ?>">Dashboard</a></li>
                    <li><a href="officer-page.php?view=my" class="sidebar-link <?= ($currentPage === 'officer-page.php' && $currentView === 'my') ? 'active' : '' ?>">My Complaints</a></li>
                    <li><a href="officer-page.php?view=received" class="sidebar-link <?= ($currentPage === 'officer-page.php' && $currentView === 'received') ? 'active' : '' ?>">Received Complaints</a></li>
                    <li><a href="employee-page.php" class="sidebar-link <?= $currentPage === 'employee-page.php' ? 'active' : '' ?>">New Complaint</a></li>
                    <li><a href="logout.php" onclick="return confirm('Are you sure you want to logout?')" class="sidebar-link <?= $currentPage === 'logout.php' ? 'active' : '' ?>">Logout</a></li>
                </ul>
            </aside>

        <?php } 
        else if($catg === 'Admin'){ ?>
            <aside class="sidebar" id="sidebar">
                <ul class="sidebar-menu">
                    <li><a href="dashboard.php" class="sidebar-link <?= $current === 'dashboard.php' ? 'active' : '' ?>">Dashboard</a></li>
                    <li><a href="admin-page.php" class="sidebar-link <?= $current === 'admin-page.php' ? 'active' : '' ?>">All Complaints</a></li>
                    <li><a href="logout.php" onclick="return confirm('Are you sure you want to logout?')" class="sidebar-link <?= $current === 'logout.php' ? 'active' : '' ?>">Logout</a></li>

                </ul>
            </aside>
        <?php } ?>