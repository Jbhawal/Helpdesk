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
        <!-- navbar+sidebar(from menu.php)+ main content  --> 
        <div class="wrapper">
            <?php include 'menu.php';?>
                <h2>Admin Page</h2>
                <main class="main-content">
                    <div class="complaintList-container">
                        <h2>Lsit of all complaints</h2>
                    </div>

                    <div class="description-container">
                        <h2>description of chosen complain</h2>
                    </div>
                </main>
        </div>
    </body>
</html>