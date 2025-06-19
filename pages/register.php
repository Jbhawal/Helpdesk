<?php
    include_once '../include/config.php';
     $secquery = "SELECT UCODE, LONGDESC FROM mastertable WHERE CODEHEAD='SEC' ORDER BY UCODE";
     $deptquery = "SELECT UCODE, LONGDESC FROM mastertable WHERE CODEHEAD='DEPT' ORDER BY UCODE";

    if(isset($_POST['registerBtn'])){
		$ecode = $_POST["empcode"];
		$ename = $_POST["empname"];
		$email = $_POST["email"];
		$phn = $_POST["phn"];
        $pwd = $_POST["password"];
		$cpwd = $_POST["confirm-password"];
		$desg = $_POST["desg"];
		$sec = $_POST["sec"];
		$cat = $_POST["category"];
		$dept = $_POST["dept"];
        $cnt = $dbo->query("SELECT COUNT(*) FROM user WHERE empcode = '$ecode' ")->fetchColumn(); 
		if($cnt > 0){
            echo "<script>alert('Employee already exists');</script>";
        } 
        else{
            if($pwd != $cpwd) {
                echo "<script>alert('Passwords do not match');</script>";
            }
            else{
                $db_user = $dbo->query("INSERT INTO user (empcode, empname, email, phnno, pwd, desg, sec, category, dept) VALUES ('$ecode', '$ename', '$email', '$phn', '$pwd', '$desg', '$sec', '$cat', '$dept')");
                echo "<script>
                    alert('Registration successful. Login again.');
                    window.location.href = '../index.php';
                </script>";
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
        <title>Register Page</title>
    </head>
    <body>
        <!-- navbar -->
        <nav class="navbar">
            <div class="logo">
                <img src="../images/Indian-Railways.jpg" alt="logo">
                <h2>Helpdesk</h2>
            </div>
        </nav>
        <!-- Main content (Registration form) -->
        <main class="main-content">
            <h1 style="margin-top: 20px;">Register a new account</h1>
            <div id="register-container">
                <form action="" method="post" class="register-form">
                    <div class="form-grid">
                        <div class="input-group">
                            <label for="empcode">Employee Code</label>
                            <input type="text" id="empcode" name="empcode" placeholder="enter empcode" required />
                        </div>

                        <div class="input-group">
                            <label for="empname">Employee Name</label>
                            <input type="text" id="empname" name="empname" placeholder="enter name" required />
                        </div>

                        <div class="input-group">
                            <label for="email">Email</label>
                            <input type="text" id="email" name="email" placeholder="enter email" onkeyup="validateEmail()" />
                            <div id="emailError" class="error-message"></div>
                        </div>

                        <div class="input-group">
                            <label for="phn">Phone Number</label>
                            <input type="text" id="phn" name="phn" placeholder="enter phone number" required onkeyup="validatePhoneNumber()" />
                            <div id="phoneError" class="error-message"></div>
                        </div>

                        <div class="input-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" placeholder="enter password" required />
                        </div>

                        <div class="input-group">
                            <label for="confirm-password">Confirm Password</label>
                            <input type="password" id="confirm-password" name="confirm-password" placeholder="re-enter password" required onkeyup="validatePasswordMatch()" />
                            <div id="passwordMatchError" class="error-message"></div>
                        </div>

                        <div class="input-group">
                            <label for="desg">Designation</label>
                            <input type="text" id="desg" name="desg" placeholder="enter designation" required />
                        </div>

                        <div class="input-group">
                            <label for="category">Category</label>
                            <select id="category" name="category" required>
                            <option value="" disabled selected>select category</option>
                            <option value="Employee">Employee</option>
                            <option value="Officer">Officer</option>
                            <option value="Admin">Admin</option>
                        </select>
                        </div>

                        <div class="input-group">
                            <label for="dept">Department</label>
                                <select id="dept" name="dept" required>
                                <option value="" disabled selected>select department</option>
                                <?php
                                        $listdept = $dbo->query($deptquery);
                                        while ($rowdept = $listdept->fetch(PDO::FETCH_ASSOC)){	
                                    ?>
                                    <!-- <option value="PF">PF Section</option>  (format of option) -->
                                    <option value="<?php echo $rowdept['UCODE']?>"><?php echo $rowdept['LONGDESC']?></option>
                                    <?php
                                        }
                                    ?>
                            </select>
                        </div>

                        <div class="input-group">
                            <label for="sec">Section</label>
                                <select id="sec" name="sec" required>
                                    <option value="" disabled selected>select section</option>
                                    <?php
                                        $listsec = $dbo->query($secquery);
                                        while ($rowsec = $listsec->fetch(PDO::FETCH_ASSOC)){	
                                    ?>
                                    <!-- <option value="PF">PF Section</option>  (format of option) -->
                                    <option value="<?php echo $rowsec['UCODE']?>"><?php echo $rowsec['LONGDESC']?></option>
                                    <?php
                                        }
                                    ?>
                            </select>
                        </div>
                    </div>

                    <div class="register-btn">
                        <button type="submit" name="registerBtn">Register</button>
                    </div>
                    
                    <div class="switch-link">
                        <p>Already have an account? <a href="../index.php" class="switch-btn">Login</a></p>
                        </div>
                    </form>
            </div>
        </main>
        <script src="../js/validation.js"></script>
    </body>
</html>