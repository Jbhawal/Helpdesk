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
        <div class="welcome-text">
            <span><p>Welcome!</span>
            <span><?php echo $empname; ?></span>
            <span><?php echo $desg; ?></span>
            <span><?php echo $sec; ?></span>
            <span><?php echo $dept; ?></span></p>
        </div>


        <div id="complaint-container">
            
                <h2>Login to your account</h2>
                <div class="input-group">
                    <label for="empcode">Employee Code </label>
                    <input type="text" id="empcode" name="empcode" placeholder="enter empcode" required />
                </div>
                <div class="input-group">
                    <label for="password">Password </label>
                    <input type="password" id="password" name="password" placeholder="enter password" required />
                </div>
                <div class="login-btn">
                    <button type="submit" name="loginBtn">Login</button>
                </div>
                <div class="switch-link">
                    <p>Don't have an account? <a href="pages/register.php" class="switch-btn">Register</a>
                    </p>
                </div>
        </div>
        
    </body>
</html>