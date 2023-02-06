<!--connect to database-->
<?php
include_once "db.php";
?>

<!--php code for mark as done/undone the task, delete task in database-->
<?php
	session_start();
	if(!ISSET($_SESSION['username'])){
		header('location:main.php');
	}

    if (isset($_GET['delete'])) {
        $id_del = $_GET['delete'];
        mysqli_query($link, "DELETE FROM tasks WHERE id=$id_del");
        $success_delete = "Successfully Deleted";
        echo "<script>alert('$success_delete');</script>";
    }

    if (isset($_GET['done'])) {
        $id_done = $_GET['done'];
        $done = "done";
        mysqli_query($link, "UPDATE tasks SET status ='$done' WHERE id=$id_done");
    }

    if (isset($_GET['undone'])) {
        $id_undone = $_GET['undone'];
        $undone = "undone";
        mysqli_query($link, "UPDATE tasks SET status ='$undone' WHERE id=$id_undone");

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Today Schedule</title>
    <link rel ="stylesheet" href="mainstyle.css"/>
    <link rel = "font" href="https://fonts.google.com/specimen/Poppins"/>
    <link rel = "font" href="https://fonts.google.com/specimen/Roboto+Mono"/>
    <link rel = "font" href="https://fonts.google.com/specimen/ABeeZee"/>  
</head>

<body>
<header class="pageheader">  <!--fixed header-->
    
     <!--display company logo-->
    <div class = "logo">
        <h1>namelix</h1>
    </div>

     <!--display date today-->
    <div class = "datebox">
        <img class = "dateicon" src = "designs\icon\calendar.png" alt ="calendar icon" />
        <label id = "datetoday"></label>
            <!--script to display current date of user-->
            <script>
                const dateDisplay = document.getElementById('datetoday');
                const dateJs = new Date().toDateString(); 
                dateDisplay.textContent = dateJs;
            </script>
    </div>        
        
    <!--display my account & logout-->
    <div class = "acct">
        <button class ="accthover" type="submit"> <img class="accticon" src= "designs\icon\user.png" alt = "accticon" /></button>
            <div class = "hovermenu">
                <a class = "hoverlink" href="acctmenu.php">My Account</a>
                <a class = "hoverlink" href="logout.php">Log-Out</a>
            </div>
    </div>

    <!--display username-->
    <div class="usernamebox">
        <?php
              $query = mysqli_query($link, "SELECT * FROM `user` WHERE `username`='$_SESSION[username]'");
              $fetch = mysqli_fetch_array($query);
              echo "<h2 class='username_hi'> Hi " . strtoupper($fetch['username']) ."! </h2>";
        ?>
    </div>
</header>
    
<!--display fixed side navigationbar-->
<div class="sidenav">
    <!--display fixed side navigationbar link-->
    <a href = "main.php"> <img class = "icon "src ="designs\icon\notes.png" alt ="notes icon"/>To-Do-List</a>

    <!--display fixed side navigationbar calendar-->
    <label><img class = "icon "src ="designs\icon\calendar1.png" alt ="calendar icon"/>Calendar</label>
    <input type= "date" class = "calendar" name = "show_calendar" id = "show_calendar">
        <!--display fixed side navigationbar calendar current date of user-->
        <script>
             const calendar_today = document.getElementById("show_calendar")
             calendar_today.value = formatDateC();
             console.log(formatDateC());
             function current_date(num) {
                return num.toString().padStart(2, '0');
             }
             function formatDateC(date = new Date()){
                return [
                    date.getFullYear(),
                    current_date(date.getMonth() + 1),
                    current_date(date.getDate()),
                ].join('-');
             }
        </script>
</div>

<!--display main form container-->
<div class = "schedule_main_con">

<!--start - display form show today to-do-list-->
<div class ="show_today">      
    <h3>To Do List</h3>
        <form name = "today" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <!--get active username  -->
            <?php
                $querytask = mysqli_query($link, "SELECT * FROM `user` WHERE `username`='$_SESSION[username]'");
                $fetchtask = mysqli_fetch_array($querytask);
                $fetchuser = $fetchtask['username'];
             ?>
            
            <!--display current date-->
            <?php echo "Today is " . date("Y/m/d") . "<br>"; ?>

            <!--input task for adding today to-do-list into tasks database for the active username-->
            <input type="text" name="title"class="type_input" placeholder="type title of here" ><br>
            <input type="text" name="task" class="type_input" placeholder="type tasks here"><br>
            <input type="time" name="time" class="type_input"><br>
		    <button type="submit" name="submit_add" id="add_task" class="btnsubmit">Add Task</button> <br>

            <!--php code for adding into tasks database for the active username-->
            <?php
            if (isset($_POST['submit_add'])) {
                if (empty($_POST['task']) || (empty($_POST['time']))) {
                    $error = "Oophs, No Task Entered, Please Enter your Task.";
                    echo "<script>alert('$error');</script>";
                } else {
                     $title = $_POST['title'];
                     $task = $_POST['task'];
                     $ondue = date('Y-m-d');
                     $time = $_POST['time'];
                     $sql = "INSERT INTO tasks (username, title, description, ondue_date, time) 
                     VALUES ('$fetchuser','$title','$task','$ondue', '$time')";
                     mysqli_query($link, $sql);
                     echo "Successfully Added";
                }
            }
            ?>

            <!--print button to-do-list for today only-->
            <input type="button" value="Print Today To Do List" onclick="printTODO()" class ="btnsubmit">
                <!--js script for printing to-do-list for today only-->
                <script>
                     function printTODO() {
                     var printToday = document.getElementById("show_todo").innerHTML;
                     var a = window.open('', '', 'height=1000, width=1000');
                     a.document.write('<html>');
                     a.document.write('<body > <h1>Namelix: To Do List<br>');
                     a.document.write(printToday);
                     a.document.write('</body></html>');
                     a.document.close();
                     a.print();
                    }
                </script>
            
            <!--display to-do-list for today only-->
            <div id = "show_todo" class = "show_todo_table">
                <!--table of to-do-list for today only-->
                
                    <!--start - php code to get of to-do-list for today only from tasks database-->
                    <?php
                        echo " <table class = 'table_todo'>
                            <thead>
                            <th>No.</th>
                            <th>Title</th>
                            <th>Task Description</th>
                            <th>On Due Date</th>
                            <th>Designated Time</th>
                            <th>Status</th>
                            </thead>";

                        $showtodo = mysqli_query($link, "SELECT * from `tasks` WHERE `ondue_date` = CURDATE() AND
                         `username`='$fetchuser' ORDER BY `time` ASC");
                        $i = 1;
                        while ($row = mysqli_fetch_array($showtodo)) {
                            $id = $row['id'];
                    ?>

                    <!--display table with php code to display result of code and show to-do-list for today only from tasks database-->
                    <tbody>
                    <tr>
				        <td class="task"> <?php echo $i; ?> </td>
                        <td class="task"> <?php echo $row['title']; ?> </td>
				        <td class="task"> <?php echo $row['description']; ?> </td>
                        <td class="task"> <?php echo $row['ondue_date']; ?> </td>
                        <td class="task"> <?php echo $row['time']; ?> </td>
                        <td class="task"> <?php echo $row['status']; ?> </td>
                        <td id = "done" class = "btn"><a href="main.php?done=<?php echo $row['id'] ?>">Mark as Done</a></td>
                        <td id = "undone" class = "btn"><a href="main.php?undone=<?php echo $row['id'] ?>">Mark as Undone</a></td>
                        <td id = "edit" class = "btn"><a href="main.php?edit=<?php echo $row['id'] ?>">Edit Task</a></td>
                        <td id = "del" class = "btn"><a href="main.php?delete=<?php echo $row['id'] ?>">Delete Task</a></td>
  			        </tr>
                    <?php 
                            $i++;
                        }                         
                    ?>
                    <!--end - php code to get of to-do-list for today only from tasks database-->
                            </tbody>
                     </table>
            </div>
        </form>
</div><!--end - display form show today to-do-list -->

<!--start - display form other date to-do-list -->
<div class = "show_other_date" id="add_task"> 
<form name = "other_date" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
   
        <h3>See Other Day Task</h3>
            <!--get active username  -->
            <?php
                $querytask_od = mysqli_query($link, "SELECT * FROM `user` WHERE `username`='$_SESSION[username]'");
                $fetchtask_od = mysqli_fetch_array($querytask_od);
                $fetchuser_od = $fetchtask_od['username'];
            ?>

            <!--pick date to show to-do-list -->
            <input type= "date" class= "calendar" name = "ondue_od" id = "date_od" placeholder="type date (yyyy/mm/dd) here" >
                <!--js script to display current date to show to-do-list -->
                <script>
                    const date_today = document.getElementById("date_od")
                    date_today.value = formatDate();
                    console.log(formatDate());
                        function current_date(num) {
                            return num.toString().padStart(2, '0');
                        }
                        function formatDate(date = new Date()){
                            return [
                            date.getFullYear(),
                            current_date(date.getMonth() + 1),
                            current_date(date.getDate()),
                            ].join('-');
                        }
                </script>
            
            <!--reset button calendar to current date -->
            <input type = "submit" class= "btncalendar" onclick = "reset_click()" id="reset_date_od" value= "Reset">
                <!--js script to display reset current date to show to-do-list -->
                <script>
                    function reset_click(){
                        const date_today_od = document.getElementById("date_od")    
                        date_today_od.value = formatDate();
                        console.log(formatDate());
                        function current_date(num) {
                            return num.toString().padStart(2, '0');
                        }
                    function formatDate(date = new Date()){
                            return [
                            date.getFullYear(),
                            current_date(date.getMonth() + 1),
                            current_date(date.getDate()),
                            ].join('-');
                        }
                     }   
                </script>

            <!--show tasks button for selected date on calendar-->
            <input type = "submit" class= "btncalendar"  name="show_task" value= "Show Task for the Day"><br>

            <!--show tasks add text box for selected date on calendar-->
            <input type="text" name="title_od" class="type_input" placeholder="type title of here" ><br>
            <input type="text" name="task_od" class="type_input" placeholder="type tasks here"><br>
            <input type="time" name="time_od" class="type_input"><br>

            <!--submit button for add text box for selected date on calendar-->
		    <button type="submit" name="submit_add_od" id="add_task_od" class="btnsubmit">Add Task</button> <br>

            <!--start - display show tasks for selected date on calendar-->
            <div id = "add_display">
                <!--start - php code to add of to-do-list for selected date on calendar from tasks database-->
                <?php
                    if (isset($_POST['submit_add_od'])) {
                        if (empty($_POST['task_od']) || (empty($_POST['time_od']))) {
                            $error_od = "Oophs, No Task Entered, Please Enter your Task.";
                            echo $error_od;
                        } else {
                            $title_od = $_POST['title_od'];
                            $task_od = $_POST['task_od'];
                            $ondue_od = $_POST['ondue_od'];
                            $time_od = $_POST['time_od'];
                            echo "<h3>" . "To-Do List for " . $ondue_od . "</h3>"; 

                    $sql_od = "INSERT INTO tasks (username, title, description, ondue_date, time) VALUES ('$fetchuser_od','$title_od','$task_od','$ondue_od', '$time_od')";
                    mysqli_query($link, $sql_od);

                    echo "<input type='button' value='Print Today To Do List' onclick='printAddTodo()'>";
                    echo "<script>
                            function printAddTodo() {
                                var printToday = document.getElementById('add_display').innerHTML;
                                var a = window.open('', '', 'height=1000, width=1000');
                                a.document.write('<html>');
                                a.document.write('<body > <h1>Namelix: To Do List<br>');
                                a.document.write(printToday);
                                a.document.write('</body></html>');
                                a.document.close();
                                a.print();
                            }
                        </script>";

                    echo "<table class ='table_todo'> 
                            <thead>
                                <th>No.</th>
                                <th>Title</th>
                                <th>Task Description</th>
                                <th>On Due Date</th>
                                <th>Designated Time</th>
                                <th>Status</th>
                            </thead> ";
                    
                        $ondue = $_POST['ondue_od'];
                        $task_other = mysqli_query($link, "SELECT * from `tasks` WHERE `ondue_date` = '$ondue_od' AND `username`='$fetchuser_od' ORDER BY `time` ASC");
                            $i = 1;
                            while ($row = mysqli_fetch_array($task_other)) {
                            $id_add = $row['id'];
                ?>
                        <!--display table with php code to display result of code and show to-do-list for selected date on calendar from tasks database after adding new tasks-->
                        <tr>
                            <td class="task"> <?php echo $i; ?> </td>
                            <td class="task"> <?php echo $row['title']; ?> </td>
                            <td class="task"> <?php echo $row['description']; ?> </td>
                            <td class="task"> <?php echo $row['ondue_date']; ?> </td>
                            <td class="task"> <?php echo $row['time']; ?> </td>
                            <td class="task"> <?php echo $row['status']; ?> </td>
                            <td id = "done" class = "btn"><a href="main.php?done=<?php echo $row['id'] ?>">Mark as Done</a></td>
                            <td id = "undone" class = "btn"><a href="main.php?undone=<?php echo $row['id'] ?>">Mark as Undone</a></td>
                            <td id = "edit" class = "btn"><a href="main.php?edit=<?php echo $row['id'] ?>">Edit Task</a></td>
                            <td id = "del" class = "btn"><a href="main.php?delete=<?php echo $row['id'] ?>">Delete Task</a></td>
  			            </tr>
                <?php 
                        $i++;
                        }
                    }
                }
                ?><!-- end - php code to add of to-do-list for selected date on calendar from tasks database-->
            </div><!--end - display show tasks for selected date on calendar after adding task-->


<!--start- display to-do-list from selected date in calendar for show task button-->
    <div class = "show_tasks" id = "show_tasks">
            <!--start- php code to display to-do-list from selected date in calendar for show task button-->
            <?php
                if (isset($_POST['show_task'])) {
                    $ondue_task = $_POST['ondue_od'];
                    echo "<h3>" . "To-Do List for " . $ondue_task . "</h3>";
                    echo "<input type='button' value='Print Today To Do List' class = 'btnsubmit' onclick='printShowTodo()'>";
                    echo "<script>
                        function printShowTodo() {
                            var printToday = document.getElementById('show_tasks').innerHTML;
                            var a = window.open('', '', 'height=1000, width=1000');
                            a.document.write('<html>');
                            a.document.write('<body > <h1>Namelix: To Do List<br>');
                            a.document.write(printToday);
                            a.document.write('</body></html>');
                            a.document.close();
                            a.print();
                        }
                        </script>";
                    echo "<table>
                        <thead>
                        <th>No.</th>
                        <th>Title</th>
                        <th>Task Description</th>
                        <th>On Due Date</th>
                        <th>Designated Time</th>
                        </thead> 
                        <tbody>";
                    
                    $task = mysqli_query($link, "SELECT * from `tasks` WHERE `ondue_date` = '$ondue_task' AND `username`='$fetchuser' ORDER BY `time` ASC");
                    $i = 1;
                    while ($row = mysqli_fetch_array($task)) {
                            $id_show = $row['id'];
            ?>
                <tr>
				    <td class="task"> <?php echo $i; ?> </td>
                    <td class="task"> <?php echo $row['title']; ?> </td>
				    <td class="task"> <?php echo $row['description']; ?> </td>
                    <td class="task"> <?php echo $row['ondue_date']; ?> </td>
                    <td class="task"> <?php echo $row['time']; ?> </td>
                    <td class="task"> <?php echo $row['status']; ?> </td>
                    <td id = "done" class = "btn"><a href="main.php?done=<?php echo $row['id'] ?>">Mark as Done</a></td>
                    <td id = "undone" class = "btn"><a href="main.php?undone=<?php echo $row['id'] ?>">Mark as Undone</a></td>
                    <td id = "edit" class = "btn"><a class="edit_link" href="main.php?edit=<?php echo $row['id'] ?>">Edit Task</a></td>
                    <td id = "del" class = "btn"><a href="main.php?delete=<?php echo $row['id'] ?>">Delete Task</a></td>
  			    </tr> 
            <?php 
                    $i++;
                }
            }
            ?> <!--end- php code to display to-do-list from selected date in calendar for show task button-->
                    </tbody>
                    </table>

</form> 
</div><!--end - display to-do-list from selected date in calendar for show task button-->
</div><!--end- display form other date to-do-list --> 

<!--start - display to edit to-do-list from selected date in calendar for add & show task button-->
<form name = "update_todo" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <!--get active username  -->
    <?php 
          $querytask_up = mysqli_query($link, "SELECT * FROM `user` WHERE `username`='$_SESSION[username]'");
          $fetchtask_up = mysqli_fetch_array($querytask_up);
          $fetchuser_up = $fetchtask_up['username'];
    ?>
        <!--start - edit form for all-->
        <div class = "input_update">
           
            <!--start - php code edit for all-->
            <?php 
                if (isset($_GET['edit'])){
                    $id_edit = $_GET['edit'];
                    $update = true;
                    $record = mysqli_query($link, "SELECT * FROM `tasks` WHERE `id`='$id_edit'");
                    $j = 1;
                while ($row = mysqli_fetch_array($record)) {
                    $id_ed = $row['id'];
                    $title_edit = $row['title'];
                    $des_edit = $row['description'];
                    $ondue_date_edit = $row['ondue_date'];
                    $time_edit = $row['time'];
            ?>
                <!-- edit input text box for all-->
        <div class="edit_tasks">
                <h3>Edit Task Here</h3>
                <input type="hidden" name="id_ed" class="type_input"    value = "<?php echo $id_ed;?>">
                <input type="text" name="title_ed" class="type_input"   placeholder="select task" value = "<?php echo $title_edit; ?>" required><br>
                <input type="text" name="task_ed" class="type_input" placeholder="select task" value = "<?php echo $des_edit; ?>"><br>
                <input type="date" name="date_ed" class="type_input" value = "<?php echo $ondue_date_edit; ?>"><br>
                <input type="time" name="time_ed" class="type_input" value = "<?php echo $time_edit; ?>"><br>
                <!--edit submit button for all-->
		        <button type="submit" name="update" id="update_task" class="btnsubmit">Update</button><br>
                <button type="submit" name="cancel" id="update_cancel" class="btnsubmit">Cancel Edit</button><br>
            
           
            <!--php code for edit submit button for all-->
            <?php    
                        $j++;
                    } 
                }

                if (isset($_POST['update'])) {
                    if (empty($_POST['id_ed'])){
                        $error_upd = "No Selected Task.";
                        echo "<script>alert('$error_upd');</script>";
                    } else {
                        $id_upd = $_POST['id_ed'];
                        $title_upd = $_POST['title_ed'];
                        $des_upd = $_POST['task_ed'];
                        $ondue_date_upd = $_POST['date_ed'];
                        $time_upd = $_POST['time_ed'];
    
                    mysqli_query($link, "UPDATE tasks SET title='$title_upd', description='$des_upd', ondue_date='$ondue_date_upd', time = '$time_upd' WHERE id=$id_upd");
                    echo "Successfully Updated";
                    echo "<h3>" . "To-Do List for " . $ondue_date_upd . "</h3>";
                    echo " 
                            <table>
                                <thead>
                                <th>No.</th>
                                <th>Title</th>
                                <th>Task Description</th>
                                <th>On Due Date</th>
                                <th>Designated Time</th>
                                </thead>";
              
                    $task_show = mysqli_query($link, "SELECT * from `tasks` WHERE `ondue_date` = '$ondue_date_upd' AND `username`='$fetchuser' ORDER BY `time` ASC");
                    $i = 1;
                    while ($row = mysqli_fetch_array($task_show)) {
                    $id_add = $row['id'];
            ?>
            <!--display to-do-list after editing-->
            <tbody>        
                <tr>
                    <td class="task"> <?php echo $i; ?> </td>
                    <td class="task"> <?php echo $row['title']; ?> </td>
                    <td class="task"> <?php echo $row['description']; ?> </td>
                    <td class="task"> <?php echo $row['ondue_date']; ?> </td>
                    <td class="task"> <?php echo $row['time']; ?> </td>
                    <td class="task"> <?php echo $row['status']; ?> </td>
                    <td id = "done" class = "btn"><a href="main.php?done=<?php echo $row['id'] ?>">Mark as Done</a></td>
                    <td id = "undone" class = "btn"><a href="main.php?undone=<?php echo $row['id'] ?>">Mark as Undone</a></td>
                    <td id = "edit" class = "btn"><a href="main.php?edit=<?php echo $row['id'] ?>">Edit Task</a></td>
                    <td id = "del" class = "btn"><a href="main.php?delete=<?php echo $row['id'] ?>">Delete Task</a></td>
                </tr>
            </tbody> 
        </div>
            <?php 
                        $i++;
                        }
                    }
                }
            ?><!--edit - php code edit for all-->
        </div>
    </form><!-- end - display to edit to-do-list from selected date in calendar for add & show task button-->
</div><!--display main form container-->


</body>
</html>