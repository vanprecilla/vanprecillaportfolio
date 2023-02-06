<?php
session_start();
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        header("location: main.php");
        exit;
    }

require_once "db.php";

$username = $password = "";
$username_err = $password_err = $login_err = "" ;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter Username.";
    } else {
        $username = trim($_POST["username"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($username_err) && empty($password_err)) {
        $sql = "SELECT id, username, password FROM user WHERE username = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            $param_username = $username;

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $username, $password);

                    if (mysqli_stmt_fetch($stmt)) {
                        if ($password === trim($_POST["password"])) {
                            session_start();
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;

                            header("location: main.php");
                        } else {
                            $login_err = "Invalid username or password.";
                        }
                    }

                } else {
                    $login_err = "Invalid username or password.";
                }
            } else {
                echo "Oops! Error. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    mysqli_close($link);

}
?>

<!DOCTYPE html>
<html lang = "en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Namelix Login</title>
        <link rel ="stylesheet" href="loginstyle.css"/>
        <link rel = "font"  href="https://fonts.google.com/specimen/Poppins"/>
        <link rel = "font"  href="https://fonts.google.com/specimen/Roboto+Mono"/>
    </head>

    <body>
        <?php 
            if(!empty($login_err)){
                echo "<script>alert('$login_err');</script>";
            }
        ?>
        
        <form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "post">
            <div class ="loginform">
                <h1 class="logtitle">Namelix Login</h1>

                <label>Username: </label><input type ="text" class ="loginbox <?php echo (!empty($username_err)) ? 'is-invalid' : '' ; ?>" 
                value ="<?php echo $username; ?>" name="username" placeholder="Enter your Username" autofocus="true"/>
                <span class = "invalid-feedback"> <?php echo $username_err ?> </span><br>
         
                <label>Password: </label><input type ="password" id = "pwd" class ="loginbox <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" 
                name="password" placeholder="Enter your password"/><br>
                <input type = "checkbox" onclick = "showPassword()"> Show Password<br>
                <span class = "invalid-feedback"> <?php echo $password_err; ?> </span><br>
                    <script>
                        function showPassword() {
                         var a = document.getElementById("pwd");
                            if (a.type === "password") {
                             a.type = "text";
                             } else {
                             a.type = "password";
                            }
                        }
                    </script>

                <p class="link"><a class ="forgot" href="resetpassword.php">Forgot Password? Reset</a></p>
             
                <input type="submit" href="main.php" value="Login" name="submit" class="btnsubmit"/>
                <p class="link"><a class ="create" href="createacct.php">No Existing Account? Create Account</a></p>

            </div>
        </form>
</body>
</html>