<?php
    session_start();

    if(!isset($_SESSION['ecode'])){
        header("Location: ../index.php");
    }
    else if(isset($_SESSION['ecode'])!=""){
        header("Location: ../index.php");
    }
    
    session_destroy();
    unset($_SESSION['ecode']);
    header("Location: ../index.php");
?>