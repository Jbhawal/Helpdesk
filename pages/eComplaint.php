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
                <span><?php echo $empname; ?></span>
                <span><?php echo $desg; ?></span>
                <span><?php echo $sec; ?></span>
                <span><?php echo $dept; ?></span></p>
            </div>
        </nav>

        <!-- Sidebar + Main content wrapper -->
        <div class="wrapper">
            <!-- Sidebar -->
            <aside class="sidebar" id="sidebar">
                <ul>
                    <li><a href="#">Dashboard</a></li>
                    <li><a href="#">Show Status</a></li>
                    <li><a href="#">Logout</a></li>
                </ul>
            </aside>
            <main class="main-content"> 
                <h2>Employee Complaint Form</h2>
                <div id="complaint-container">
                    <form action="" method="post" enctype="multipart/form-data" class="complaint-form">
                        <div class="input-group">
                            <label for="type">Type</label>
                            <select id="type" name="type" required>
                                <option value="" disabled selected>select type</option>
                                <option value="HW">Hardware</option>
                                <option value="SW">Software</option>
                                <option value="NW">Network</option>
                            </select>
                        </div>
                        <div class="input-group">
                            <label for="desc">Description </label>
                            <textarea id="desc" name="desc" rows="5" cols="40" placeholder="Describe your complaint here..."></textarea>
                        </div>
                        <div class="input-group">
                            <label for="file">Upload File:</label>
                            <input type="file" id="file" name="uploadedFile" accept=".jpg, .jpeg, .png, .pdf" />
                        </div>
                        <div class="input-group">
                            <label for="forward">Forward To</label>
                            <select id="forward" name="forward" required>
                                <option value="" disabled selected>select officer</option>
                                <option value="o1">Officer1</option>
                                <option value="o2">Officer2</option>
                                <option value="o3">Officer3</option>
                            </select>
                        </div>
                        <div class="submit-btn">
                            <button type="submit" name="loginBtn">Submit</button>
                        </div>
                    </form>
                </div>
            </main>
        </div>

    <!-- Overlay for small screen dark background -->
    <div class="overlay" id="overlay"></div>

        <script src="../js/script.js"></script>
    </body>
</html>