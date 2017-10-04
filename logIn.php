<?php 
    require "common.php";
    
    $nameErr = $passErr = "";
    $name = $pass = "";
    $dbMessage = '';

    if (! $conn) {
        $dbMessage = 'Data Base Connection Fail!';
        exit;
    }
    
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
  
    function checkUser(){
        $name = $_POST['name'];
        $pass = $_POST['pass'];

        if (strcmp($name, 'user') == 0 && strcmp($pass, 'user') == 0) {
            header("Location:index.php");
        } else {
            header("Location:logIn.php");  
        }
    }

    if (isset($_POST['name'])) {
        if (empty($_POST["name"])) {
            $nameErr = "Name is required";
        } else {
            $name = test_input($_POST["name"]);
            if (preg_match("/^[a-zA-Z ]*$/", $name)) {
                $nameErr = "Only letters and white space allowed";
            }
            checkUser();
        }

        if (isset($_POST['pass'])) {
            if (empty($_POST["pass"])) {
                $passErr = "Password is required";
            } else {
                $pass = test_input($_POST["pass"]);
                if (preg_match("/^[A-Za-z].*[0-9] | [0-9].*[A-Za-z]/", $pass)) {
                    $passErr = "Only letters and numbers"; 
                }
            }
            if (strlen($pass) < 3){
                $passErr = "Password length is too small"; 
            }
        } 
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
    <h2 class="error"> <?= $dbMessage ?> </h2><br>
    <p><span class="error">* Please complete the input fields below</span></p>
    <form method="post" action="logIn.php" > 
        UserName: <input type="text" name="name" value="<?= protect($name);?>" >
        <span class="error">* <?= $nameErr ?></span><br><br>
        Password:  <input type="password" name="pass" value="<?= protect($pass);?>" >
        <span class="error">* <?= $passErr ?></span><br><br>
        <input type="submit" name="submit" value="<?= translate('signIn') ?>" >  
    </form>
</body>
</html>
