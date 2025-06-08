<?php
    include_once 'include/config.php';
    if(isset($_POST['loginBtn'])){
		$ecode = $_POST["empcode"];
		$pwd = $_POST["password"];
        $cnt = $dbo->query("SELECT COUNT(*) FROM user WHERE empcode = '$ecode' AND pwd = '$pwd'")->fetchColumn(); 
		if($cnt == 0){
            echo "<script>alert('Invalid Employee Code or Password');</script>";
        } 
        else{
            session_start();
            $_SESSION['empcode'] = $ecode;
            $catg=$dbo->query("SELECT CATEGORY FROM user WHERE empcode = '$ecode' ")->fetchColumn();
            if($catg=='E'){
                header("Location: pages/eComplaint.php");
            }
            else if($catg=='O'){
                header("Location: pages/oComplaint.php");
            }
            else if($catg=='A'){
                header("Location: pages/aComplaint.php");
            }
            exit();
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <link rel="stylesheet" href="css/styles.css" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Edu+SA+Hand:wght@400..700&family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
        <title>Welcome</title>
    </head>
    <body>
        <nav class="navbar">
            <div class="logo">
                <img src="images/Indian-Railways.jpg" alt="logo">
            </div>
        </nav>
        
        
        <main class="main-content">
            <h1>Welcome!</h1>
            <div id="login-container">
                <form action="" method="post" class="login-form">
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
                </form>
            </div>
        </main>
    </body>
</html>