<?php
require_once "db.php";
include('deleteacct.php')
?>


<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>My Account</title>
        <link rel ="stylesheet" href="acctmenustyle.css"/>
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
    <a class ="side" href = "main.php"> <img class = "icon "src ="designs\icon\notes.png" alt ="notes icon"/>To-Do-List</a>

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

<div class = "schedule_main_con">
    <form name = "acctInfo" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
        <?php
          $querytask = mysqli_query($link, "SELECT * FROM `user` WHERE `username`='$_SESSION[username]'");
          $fetchtask = mysqli_fetch_array($querytask);
          $fetchuser = $fetchtask['username'];
         ?>    
   
     <div id = "personal_info">
        <?php
                $showinfo= mysqli_query($link, "SELECT * from `user` WHERE `username`='$fetchuser'");
                $i = 1;
                while ($row = mysqli_fetch_array($showinfo)) {
                    $id = $row['id'];
        ?>          
            <br>
            <p>Account Information</p>
            <input type="hidden" name="id_user" value = "<?php echo $id; ?>"><br>
            <p>Username: <?php echo $row['username']; ?>  </p><br>
            <p>Full Name: <?php echo $row["fullname"]; ?> </p><br>
            <p>Birthday: <?php echo $row["birthday"]; ?>  </p><br>
            <p>Country: <?php echo $row["country"]; ?>    </p><br>
            <p>Email Address: <?php echo $row["email"]; ?> </p><br>
            <p>Password: <?php echo $row["password"]; ?> </p><br>
            <a href="acctmenu.php?edit=<?php echo $row['id']?>">Edit Info</a>
            <a href="acctmenu.php?delete=<?php echo $row['id']?>">Delete Account</a><br>
        <?php 
                $i++;
                }                         
         ?>
    </div>


    <form  name = "delete_acct"  action="deleteacct.php" method="post">
        <div class = "del_acct">
            <?php
                 if (isset($_GET['delete'])) {
                 $id_del = $_GET['delete'];
                 $update_del = true;
                 $record_del = mysqli_query($link, "SELECT * FROM `user` WHERE `id`='$id_del'");
                 $k = 1;
                    while ($row = mysqli_fetch_array($record_del)) {
                        $id_del = $row['id'];
                        $user_del = $row['username'];
            ?>
        <br>
            <h4> After Clicking the "Confirm Delete" Button, Your Namelix Account will be deleted and be directed to Login Page </h2><br>
            <input type="hidden" name="id_user_del" value = "<?php echo $id_del; ?>">
            <input type="hidden" name="user_del" value = "<?php echo $user_del; ?>"><h4>Username: <?php echo strtoupper($user_del); ?></h4><br>
            <button type="submit" name="submit_delete" id="confirm_delete" class="btnsubmit" >Confirm Delete</button> 
            <button type="submit" name="submit_cancel" id="confirm_cancel" class="btnsubmit">Cancel Delete</button> 
            <?php 
                    $k++;
                    }
                }
            ?>
        </div>
    </form>

<form name = "update_acct"  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">    
        <div class = "edit_info">
            <?php
               if (isset($_GET['edit'])) {
                $id_edit = $_GET['edit'];
                $update = true;
                $record = mysqli_query($link, "SELECT * FROM `user` WHERE `id`='$id_edit'");
                $j = 1;
                while ($row = mysqli_fetch_array($record)) {
                    $id_user = $row['id'];
                    $user = $row['username'];
                    $full_name = $row['fullname'];
                    $bday = $row['birthday'];
                    $country= $row['country'];
                    $mail = $row['email'];
                    $password = $row['password'];
        ?>
            <p>After Edit, Please Refresh the Page</p>
            <input type="hidden" name="id_user" value = "<?php echo $id_user; ?>"><br>
            <label>Username</label> <input type="text" name="user" class="type" value = "<?php echo $user; ?>" ><br>
            <label>Full Name</label> <input type="text" name="full_name" class="type" value = "<?php echo $full_name; ?>"><br>
            <label>Birthday</label> <input type="date" name="bday" class="type" value = "<?php echo $bday ; ?>"><br>
            <label>Country</label> 
            <select class ="type" name="country" >
    <option><?php echo $country; ?></option>
    <option value="Afghanistan">Afghanistan</option>
    <option value="Aland Islands">Aland Islands</option>
    <option value="Albania">Albania</option>
    <option value="Algeria">Algeria</option>
    <option value="American Samoa">American Samoa</option>
    <option value="Andorra">Andorra</option>
    <option value="Angola">Angola</option>
    <option value="Anguilla">Anguilla</option>
    <option value="Antarctica">Antarctica</option>
    <option value="Antigua and Barbuda">Antigua and Barbuda</option>
    <option value="Argentina">Argentina</option>
    <option value="Armenia">Armenia</option>
    <option value="Aruba">Aruba</option>
    <option value="Australia">Australia</option>
    <option value="Austria">Austria</option>
    <option value="Azerbaijan">Azerbaijan</option>
    <option value="Bahamas">Bahamas</option>
    <option value="Bahrain">Bahrain</option>
    <option value="Bangladesh">Bangladesh</option>
    <option value="Barbados">Barbados</option>
    <option value="Belarus">Belarus</option>
    <option value="Belgium">Belgium</option>
    <option value="Belize">Belize</option>
    <option value="Benin">Benin</option>
    <option value="Bermuda">Bermuda</option>
    <option value="Bhutan">Bhutan</option>
    <option value="Bolivia">Bolivia</option>
    <option value="Bonaire, Sint Eustatius and Saba">Bonaire, Sint Eustatius and Saba</option>
    <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
    <option value="Botswana">Botswana</option>
    <option value="Bouvet Island">Bouvet Island</option>
    <option value="Brazil">Brazil</option>
    <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
    <option value="Brunei Darussalam">Brunei Darussalam</option>
    <option value="Bulgaria">Bulgaria</option>
    <option value="Burkina Faso">Burkina Faso</option>
    <option value="Burundi">Burundi</option>
    <option value="Cambodia">Cambodia</option>
    <option value="Cameroon">Cameroon</option>
    <option value="Canada">Canada</option>
    <option value="Cape Verde">Cape Verde</option>
    <option value="Cayman Islands">Cayman Islands</option>
    <option value="Central African Republic">Central African Republic</option>
    <option value="Chad">Chad</option>
    <option value="Chile">Chile</option>
    <option value="China">China</option>
    <option value="Christmas Island">Christmas Island</option>
    <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
    <option value="Colombia">Colombia</option>
    <option value="Comoros">Comoros</option>
    <option value="Congo">Congo</option>
    <option value="Congo, Democratic Republic of the Congo">Congo, Democratic Republic of the Congo</option>
    <option value="Cook Islands">Cook Islands</option>
    <option value="Costa Rica">Costa Rica</option>
    <option value="Cote D'Ivoire">Cote D'Ivoire</option>
    <option value="Croatia">Croatia</option>
    <option value="Cuba">Cuba</option>
    <option value="Curacao">Curacao</option>
    <option value="Cyprus">Cyprus</option>
    <option value="Czech Republic">Czech Republic</option>
    <option value="Denmark">Denmark</option>
    <option value="Djibouti">Djibouti</option>
    <option value="Dominica">Dominica</option>
    <option value="Dominican Republic">Dominican Republic</option>
    <option value="Ecuador">Ecuador</option>
    <option value="Egypt">Egypt</option>
    <option value="El Salvador">El Salvador</option>
    <option value="Equatorial Guinea">Equatorial Guinea</option>
    <option value="Eritrea">Eritrea</option>
    <option value="Estonia">Estonia</option>
    <option value="Ethiopia">Ethiopia</option>
    <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
    <option value="Faroe Islands">Faroe Islands</option>
    <option value="Fiji">Fiji</option>
    <option value="Finland">Finland</option>
    <option value="France">France</option>
    <option value="French Guiana">French Guiana</option>
    <option value="French Polynesia">French Polynesia</option>
    <option value="French Southern Territories">French Southern Territories</option>
    <option value="Gabon">Gabon</option>
    <option value="Gambia">Gambia</option>
    <option value="Georgia">Georgia</option>
    <option value="Germany">Germany</option>
    <option value="Ghana">Ghana</option>
    <option value="Gibraltar">Gibraltar</option>
    <option value="Greece">Greece</option>
    <option value="Greenland">Greenland</option>
    <option value="Grenada">Grenada</option>
    <option value="Guadeloupe">Guadeloupe</option>
    <option value="Guam">Guam</option>
    <option value="Guatemala">Guatemala</option>
    <option value="Guernsey">Guernsey</option>
    <option value="Guinea">Guinea</option>
    <option value="Guinea-Bissau">Guinea-Bissau</option>
    <option value="Guyana">Guyana</option>
    <option value="Haiti">Haiti</option>
    <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
    <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
    <option value="Honduras">Honduras</option>
    <option value="Hong Kong">Hong Kong</option>
    <option value="Hungary">Hungary</option>
    <option value="Iceland">Iceland</option>
    <option value="India">India</option>
    <option value="Indonesia">Indonesia</option>
    <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
    <option value="Iraq">Iraq</option>
    <option value="Ireland">Ireland</option>
    <option value="Isle of Man">Isle of Man</option>
    <option value="Israel">Israel</option>
    <option value="Italy">Italy</option>
    <option value="Jamaica">Jamaica</option>
    <option value="Japan">Japan</option>
    <option value="Jersey">Jersey</option>
    <option value="Jordan">Jordan</option>
    <option value="Kazakhstan<">Kazakhstan</option>
    <option value="Kenya">Kenya</option>
    <option value="Kiribati">Kiribati</option>
    <option value="South Korea">South Korea</option>
    <option value="North Korea">North Korea</option>
    <option value="Kosovo">Kosovo</option>
    <option value="Kuwait">Kuwait</option>
    <option value="Kyrgyzstan">Kyrgyzstan</option>
    <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
    <option value="Latvia">Latvia</option>
    <option value="Lebanon">Lebanon</option>
    <option value="Lesotho">Lesotho</option>
    <option value="Liberia">Liberia</option>
    <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
    <option value="Liechtenstein">Liechtenstein</option>
    <option value="Lithuania">Lithuania</option>
    <option value="Luxembourg">Luxembourg</option>
    <option value="Macao">Macao</option>
    <option value="Macedonia, the Former Yugoslav Republic of">Macedonia, the Former Yugoslav Republic of</option>
    <option value="Madagascar">Madagascar</option>
    <option value="Malawi">Malawi</option>
    <option value="Malaysia">Malaysia</option>
    <option value="Maldives">Maldives</option>
    <option value="Mali">Mali</option>
    <option value="Malta">Malta</option>
    <option value="Marshall Islands">Marshall Islands</option>
    <option value="Martinique">Martinique</option>
    <option value="Mauritania">Mauritania</option>
    <option value="Mauritius">Mauritius</option>
    <option value="Mayotte">Mayotte</option>
    <option value="Mexico">Mexico</option>
    <option value="Micronesia, Federated States o">Micronesia, Federated States of</option>
    <option value="Moldova, Republic of">Moldova, Republic of</option>
    <option value="Monaco">Monaco</option>
    <option value="Mongolia">Mongolia</option>
    <option value="Montenegro">Montenegro</option>
    <option value="Montserrat">Montserrat</option>
    <option value="Morocco">Morocco</option>
    <option value="Mozambique">Mozambique</option>
    <option value="Myanmar">Myanmar</option>
    <option value="Namibia">Namibia</option>
    <option value="Nauru">Nauru</option>
    <option value="Nepal">Nepal</option>
    <option value="Netherlands">Netherlands</option>
    <option value="Netherlands Antilles">Netherlands Antilles</option>
    <option value="New Caledonia">New Caledonia</option>
    <option value="New Zealand">New Zealand</option>
    <option value="Nicaragua">Nicaragua</option>
    <option value="Niger">Niger</option>
    <option value="Nigeria">Nigeria</option>
    <option value="Niue">Niue</option>
    <option value="Norfolk Island">Norfolk Island</option>
    <option value="Northern Mariana Islands">Northern Mariana Islands</option>
    <option value="Norway">Norway</option>
    <option value="Oman">Oman</option>
    <option value="Pakistan">Pakistan</option>
    <option value="Palau">Palau</option>
    <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
    <option value="Panama">Panama</option>
    <option value="Papua New Guinea">Papua New Guinea</option>
    <option value="Paraguay">Paraguay</option>
    <option value="Peru">Peru</option>
    <option value="Philippines">Philippines</option>
    <option value="Pitcairn">Pitcairn</option>
    <option value="Poland">Poland</option>
    <option value="Portugal">Portugal</option>
    <option value="Puerto Rico">Puerto Rico</option>
    <option value="Qatar">Qatar</option>
    <option value="Reunion">Reunion</option>
    <option value="Romania">Romania</option>
    <option value="Russian Federation">Russian Federation</option>
    <option value="Rwanda">Rwanda</option>
    <option value="Saint Barthelemy">Saint Barthelemy</option>
    <option value="Saint Helena">Saint Helena</option>
    <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
    <option value="Saint Lucia">Saint Lucia</option>
    <option value="Saint Martin">Saint Martin</option>
    <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
    <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
    <option value="Samoa">Samoa</option>
    <option value="San Marino">San Marino</option>
    <option value="Sao Tome and Principe">Sao Tome and Principe</option>
    <option value="Saudi Arabia">Saudi Arabia</option>
    <option value="Senegal">Senegal</option>
    <option value="Serbia">Serbia</option>
    <option value="Serbia and Montenegro">Serbia and Montenegro</option>
    <option value="Seychelles">Seychelles</option>
    <option value="Sierra Leone">Sierra Leone</option>
    <option value="Singapore">Singapore</option>
    <option value="Sint Maarten">Sint Maarten</option>
    <option value="Slovakia">Slovakia</option>
    <option value="Slovenia">Slovenia</option>
    <option value="Solomon Islands">Solomon Islands</option>
    <option value="Somalia">Somalia</option>
    <option value="South Africa">South Africa</option>
    <option value="South Georgia and the South Sandwich Islands">South Georgia and the South Sandwich Islands</option>
    <option value="South Sudan">South Sudan</option>
    <option value="Spain">Spain</option>
    <option value="Sri Lanka">Sri Lanka</option>
    <option value="Sudan">Sudan</option>
    <option value="Suriname">Suriname</option>
    <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
    <option value="Swaziland">Swaziland</option>
    <option value="Sweden">Sweden</option>
    <option value="Switzerland">Switzerland</option>
    <option value="Syrian Arab Republic">Syrian Arab Republic</option>
    <option value="Taiwan, Province of China">Taiwan, Province of China</option>
    <option value="Tajikistan">Tajikistan</option>
    <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
    <option value="Thailand">Thailand</option>
    <option value="Timor-Leste">Timor-Leste</option>
    <option value="Togo">Togo</option>
    <option value="Tokelau">Tokelau</option>
    <option value="Tonga">Tonga</option>
    <option value="Trinidad and Tobago">Trinidad and Tobago</option>
    <option value="Tunisia">Tunisia</option>
    <option value="Turkey">Turkey</option>
    <option value="Turkmenistan">Turkmenistan</option>
    <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
    <option value="Tuvalu">Tuvalu</option>
    <option value="Uganda">Uganda</option>
    <option value="Ukraine">Ukraine</option>
    <option value="United Arab Emirates">United Arab Emirates</option>
    <option value="United Kingdom<">United Kingdom</option>
    <option value="United States">United States</option>
    <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
    <option value="Uruguay">Uruguay</option>
    <option value="Uzbekistan">Uzbekistan</option>
    <option value="Vanuatu">Vanuatu</option>
    <option value="Venezuela">Venezuela</option>
    <option value="Viet Nam">Viet Nam</option>
    <option value="Virgin Islands, British">Virgin Islands, British</option>
    <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
    <option value="Wallis and Futuna">Wallis and Futuna</option>
    <option value="Western Sahara">Western Sahara</option>
    <option value="Yemen">Yemen</option>
    <option value="Zambia">Zambia</option>
    <option value="Zimbabwe">Zimbabwe</option>
    </select>
            <br>
            <label>Email Address</label><br>
            <input type="email" name="mail" class="type" value = "<?php echo $mail; ?>"><br>

            <br>
            <label>Password</label><br>
            <input type="text" name="passwd" class="type" value = "<?php echo $password; ?>"><br>

		    <button type="submit" name="submit_edit" id="edit_info" class="btnsubmit">Save Changes</button> 
            <button type="submit" name="submit_cancel" id="edit_cancel" class="btnsubmit" >Cancel</button> <br>
    <?php $j++;
        }
    }
            if (isset($_POST['submit_edit'])) {
                    $id_upd = $_POST['id_user'];
                    $user_upd = $_POST['user'];
                    $full_name_upd = $_POST['full_name'];
                    $bday_upd = $_POST['bday'];
                    $country_upd = $_POST['country'];
                    $email_upd = $_POST['mail'];
                    $password_upd = $_POST['passwd'];

                    $update_user = mysqli_query($link, "UPDATE user SET username = '$user_upd', password = '$password_upd', fullname = '$full_name_upd', birthday = '$bday_upd', country = '$country_upd', email = '$email_upd' WHERE id=$id_upd");
                    $update_user_task = mysqli_query($link, "UPDATE tasks SET username = '$user_upd' WHERE username = '$_SESSION[username]'");
     /* login again after update of info */             
                    $user_upd = $password_upd = "";
                    $user_upd_err = $password_upd_err = $login_upd_err = "" ;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["user"]))) {
        $user_upd_err = "Please enter Username.";
    } else {
        $user_upd = trim($_POST["user"]);
    }

    if (empty(trim($_POST["passwd"]))) {
        $password_upd_err = "Please enter your password.";
    } else {
        $password_upd = trim($_POST["passwd"]);
    }

    if (empty($user_upd_err) && empty($password_upd_err)) {
        $sql = "SELECT id, username, password FROM user WHERE username = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            $param_username = $user_upd;

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $user_upd, $password_upd);

                    if (mysqli_stmt_fetch($stmt)) {
                        if ($password_upd === trim($_POST["passwd"])) {
           
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $user_upd;

                            echo "Successfully Updated Info, Please Refresh the page"; 
                        } else {
                            $login_upd_err = "Unsucessful";
                        }
                    }
                } else {
                    $login_upd_err = "Unsucessful";
                }
            } else {
                echo "Oops! Error. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($link);
}             
}
    ?>
     </div>
    </form>       



</form>

</div>
  
</body>

</html>