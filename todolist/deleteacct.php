
<?php
include_once "db.php";

?>
<?php
	session_start();
	if(!ISSET($_SESSION['username'])){

	}

if (isset($_POST['submit_delete'])) {
        $id_confirm_del = $_POST['id_user_del'];
        $user_del = $_POST['user_del'];
        $delete_user_tasks = mysqli_query($link, "DELETE FROM tasks WHERE username = '$user_del'");
        $delete_user = mysqli_query($link, "DELETE FROM user WHERE id= '$id_confirm_del'");
        header("location: logout.php");

    } 

?>