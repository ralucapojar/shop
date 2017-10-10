<?php 
    require "common.php";
    
    $nameErr = $passErr = "";
    $name= $pass = "";
    function checkUser(){
        $name = $_POST['name'];
        $pass = $_POST['pass'];

        if ($name === admin && $pass === adminPass) {
            if (!isset($_SESSION['logged'])) { 
                $_SESSION['logged'] = true;  
            } 
            header("Location:products.php");
            die();
        } else {
            header("Location:logIn.php"); 
            die(); 
        }
    }
    
    if (isset($_POST['name'])) {
        checkUser();
    }
       
?>
<html>
<head>
<style type="text/css">
    .error {color:red;}
</style>
</head>
<body>  
    <h2>LogIn Form</h2>
    <p><span class="error">* Please complete the input fields below</span></p>
    <form method="post" action="logIn.php" > 
        <?php echo translate('username')?> <input type="text" name="name" value="<?= protect($name);?>" >
        <span class="error">* <?= $nameErr ?></span><br><br>
        <?php echo translate('password')?>  <input type="password" name="pass" value="<?= protect($pass);?>" >
        <span class="error">* <?= $passErr ?></span><br><br>
        <input type="submit" name="submit" value="<?= translate('signIn') ?>" >  
    </form>
</body>
</html>
