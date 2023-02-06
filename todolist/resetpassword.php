<?php


  
  require_once "db.php";

  $username = $password = $confirm_password = "";
  $username_err = $password_err = $confirm_password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter Username.";
    } else {
        $username = trim($_POST["username"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have at least 6 characters";
     }else {
        $password = trim($_POST["password"]);
    }

    if(empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
} else {
        $confirm_password = trim($_POST["confirm_password"]);
if (empty($password_err) && ($password != $confirm_password)) {
        $confirm_password_err = "Password did not match.";
    }   
}

    if (empty($username_err)) {
        $sql = "SELECT id, username FROM user WHERE username = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            $param_username = $username;

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $username);

                    if(mysqli_stmt_fetch($stmt)) {
                        $_SESSION["id"] = $id;
                        $_SESSION["username"] = $username;
                        

                        if (empty($password_err) && empty($confirm_password_err)) {
                            $sql = "UPDATE user SET password = ? WHERE id = ?";
                            if ($stmt = mysqli_prepare($link, $sql)) {

                                mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);


                                $param_password = $password;
                                $param_id = $_SESSION["id"];


                                if (mysqli_stmt_execute($stmt)) {
                                    session_destroy();
                                    echo "Successful Change of Password";
                                    header ("location: login.php");
                                
                                }
                            }
                        }
                    }
                    } else {
                                $login_err = "Username does not exist. Please enter correct username to change password.";
                                echo "<script>alert('$login_err');</script>";
                            }
                        
                } else {
                    $login_err = "Username does not exist. Please enter correct username to change password.";
                    echo "<script>alert('$login_err');</script>";
                }
            } else {
                echo "Oops! Error. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    
    mysqli_close($link);

}
?>

<!DOCTYPE html>
<html lang ="en" >
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Reset Password</title>
        <link rel ="stylesheet" href="loginstyle.css"/>
        <link rel = "font"  href="https://fonts.google.com/specimen/Poppins"/>
        <link rel = "font"  href="https://fonts.google.com/specimen/Roboto+Mono"/>
    </head>

    <body>
       
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <div class ="loginform">
                <h1 class="regtitle">Reset Password</h1>
         
                <label class= "labeltag">Username: </label> <input type ="text" class ="loginbox <?php echo (!empty($username_err)) ? 'is-invalid' : '' ; ?>" 
                value ="<?php echo $username; ?>" name="username" placeholder="Enter your Username" autofocus="true"/>
                <span class = "invalid-feedback"> <?php echo $username_err ?> </span><br>
 
                <label  class= "labeltag">New Password: </label> <input type ="password" id = "newpwd" class ="loginbox <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>"
                 name="password" value ="<?php echo $password; ?>"placeholder="Enter your New Password" autofocus="true"/>
                <span class = "invalid-feedback"> <?php echo $password_err; ?> </span><br>

                <label class= "labeltag" >Confirm Password: </label> <input type ="password" id = "confirmpwd" class ="loginbox <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>"
                name="confirm_password" value = "<?php echo $confirm_password; ?>" placeholder="Confirm your New Password"/>
                <input type = "checkbox" onclick = "showPassword()"> Show Password<br>
                <span class = "invalid-feedback"> <?php echo $confirm_password_err; ?> </span><br>
                    <script>
                        function showPassword() {
                         var a = document.getElementById("newpwd");
                             b = document.getElementById("confirmpwd");
                            if ((a.type === "password") || (b.type === "password") ) {
                             a.type = "text";
                             b.type = "text";
                             } else {
                             a.type = "password";
                             b.type = "password";
                            }
                        }
                    </script>


                <input type ="submit" class ="btnsubmit " value="Reset" name="resetbutton" placeholder="Login"/>

                <p>Back to login? <a href ="login.php"> Login Here </a></p>        
            </div>
        </form>
    </body>
</html>