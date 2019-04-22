<?php
    ob_start();
    require 'includes/config.php';
    require 'includes/functions.php';

    $Selection = 1;   // Default επιλογή
    // Προσδιορίζει την επιλογή του χρήστη
    // Ανάλογα φορτώνονται τα μενού.
    if (isset($_GET['ds']))
        $Selection = $_GET['ds'];

    // Selections:
    // 1 - Campaigns
    // 2 - Groups
    // 3 - Brands
    // 4 - Channel

    // Βρίσκει τον logged χρήστη και το ρόλο του
    // Αν είναι administrator, το $AdminAccess παίρνει τιμή True
    $LoggedUser = new User();
    $Logged_ID = $LoggedUser->GetLoggedUser();
    $AdminAccess = $LoggedUser->IsAdmin($Logged_ID);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <script  type="text/javascript" src="scripts/phpalbums.js"></script>
        <!-- Τα επόμενα 2 include (script & link) είναι για το javascript plugin "Tables"  -->
        <script type="text/javascript" src="scripts/table.js"></script>
        <link href="scripts/table.css" rel="stylesheet" type="text/css" />

        <link href="styles/styles.css" rel="stylesheet" type="text/css" />
        <title></title>
    </head>
    <body>


<!-- Αριστερό μενού                             -->
<!-- ****************************************** -->
<nav>
    <?php 
    echo "<ul style=\"margin-left:  -20px;\">";
    echo "<li class=\"menuButton\"><a href=\"albumreview.php\">Albums</a></li>";
    if (strcmp ($AdminAccess,"True")==0)
    {
        include ('adminmenu.php');
    }
    else
    {
        die ("Unauthorized access encountered");
    }
    echo "</ul>";
    ?>
</nav>

    <!-- Popupup για συμπλήρωση νέας εγγραφής  -->
    <div id="NewRecord" class="FormDialog" style="height: 500px; width: 540px; z-index: 2000;">
        <?php
            echo "<form name=\"newalbumform\" action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">";
        ?>
                <table style="margin-left:  auto; margin-right: auto; margin-top: 50px;">
                <!-- Γραμμή εισαγωγής Username -->
                <tr>
                <td class="Label" style="background-color: transparent;">
                    <ul><li style="color:  white;">Username:</li></ul>
                </td>
                <td class="InputField">
                    <input type="text" required name="usr_username" value="" size="10" ></input>
                </td>
                </tr>

                <!-- Γραμμή εισαγωγής Password -->
                <tr>
                <td class="Label" style="background-color: transparent;">
                    <ul><li style="color:  white;">Password:</li></ul>
                </td>
                <td class="InputField">
                    <input type="text" required name="usr_password" value="" size="10" ></input>
                </td>
                </tr>

                <!-- Γραμμή εισαγωγής Ονόματος -->
                <tr>
                <td class="Label" style="background-color: transparent;">
                    <ul><li style="color:  white;">First name:</li></ul>
                </td>
                <td class="InputField">
                    <input type="text" name="usr_firstname" value="" size="10" ></input>
                </td>
                </tr>

                <!-- Γραμμή εισαγωγής Επωνύμου -->
                <tr>
                <td class="Label" style="background-color: transparent;">
                    <ul><li style="color:  white;">Last name:</li></ul>
                </td>
                <td class="InputField">
                    <input type="text" name="usr_lastname" value="" size="10" ></input>
                </td>
                </tr>

                <!-- Γραμμή εισαγωγής email -->
                <tr>
                <td class="Label" style="background-color: transparent;">
                    <ul><li style="color:  white;">E-mail:</li></ul>
                </td>
                <td class="InputField">
                    <input type="text" name="usr_email" value="" size="20" ></input>
                </td>
                </tr>

                <!-- Γραμμή εισαγωγής phone -->
                <tr>
                <td class="Label" style="background-color: transparent;">
                    <ul><li style="color:  white;">Phone:</li></ul>
                </td>
                <td>
                    <input type="text" name="usr_phone" value="" size="10" ></input>
                </td>
                </tr>

                <!-- Γραμμή εισαγωγής role -->
                <tr>
                <td class="Label" style="background-color: transparent;">
                    <ul><li style="color:  white;"></li></ul>
                </td>
                <td class="Label" style="color: white; background-color : transparent;">
                    <input type="radio" name="usr_admin" value="True">Administrator</input><input type="radio" name="usr_admin" value="False" checked>Contributor</input>
                </td>
                </tr>
                <tr>
                    <td><p></p></td>
                    <td>                    
                    </td>
                </tr>
                <tr>
                <td>
                </td>
                <td>
                    <input class="dbButton" type="submit" name="AddRecord" value="Save" onclick="ResetFieldChange('UnsavedChanges');"></input>
                    <input class="dbButton" type="reset" value="Cancel" onclick="collapseElement('NewRecord')"></input>
                </td>
                </tr>
            </table>
        </form>
    </div>
    
    <?php
    // Αποθήκευση εγγραφής
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if ($_POST["AddRecord"] == "Save")
        {
            $usr_id = $_POST['usr_id'];
            $usr_username = $_POST['usr_username'];
            $usr_password = $_POST['usr_password'];
            $usr_lastname = $_POST['usr_lastname'];
            $usr_firstname = $_POST['usr_firstname'];
            $usr_email = $_POST['usr_email'];
            $usr_phone = $_POST['usr_phone'];
            $usr_admin = $_POST['usr_admin'];
            $usr_contributor = "False";
            if (strcmp($usr_admin, "False") == 0)
                $usr_contributor = "True";

            $LoggedUser = new User();
            $rec_Creator = $LoggedUser->GetLoggedUser();
            $rec_Timestamp = date('Y-m-d H:i:s');

            $strSQL = "INSERT INTO users ".
                      "(Usr_Username, Usr_Password, Usr_Lastname, Usr_Firstname, Usr_Email, ".
                      "Usr_Phone, Usr_Role_Admin, Usr_Role_Contributor) VALUES (".
                      "'$usr_username', '$usr_password', '$usr_lastname', '$usr_firstname', ".
                      "'$usr_email', '$usr_phone', '$usr_admin', '$usr_contributor')";
            echo $strSQL;
        
            $result = mysql_query($strSQL);
            if (!$result)
            {
                //  Μήνυμα για διπλή εγγραφή
                header("Location: message.php?msgid=26");
            }
        }
    }
    ?>
    <!-- Τέλος του Popup καμπάνιας                                          -->

    <!-- Fixed Header ---------------------------------------------------- -->
    <!-- Εμφανίζει το logged χρήστη και τα κουμπιά για αποθήκευση στη βάση -->
    <div class ="PageHeader">
        <table style="width: 100%">
            <tr>
                <!-- Αριστερό κελί (αναφέρει το logged χρήστη και login/logout -->
                <td style="width: 50%; color:  white; padding-left: 8px;">
                <?php
                    $LoggedUser = new User();
                    $Logged_ID = $LoggedUser->GetLoggedUser();
                    if (strlen($_SESSION['LoggedName']) > 0)
                    {
                        echo "Καλωσήρθατε ".$_SESSION['LoggedName']." ";
                        echo "<a href=\"message.php?msgid=3\">(πατήστε για αποσύνδεση)</a>";
                    }
                    else
                    {
                        echo "Δεν έχετε συνδεθεί στο σύστημα"."<br>";
                        echo "<a href=\"loginmain.php\">Σύνδεση</a>";
                    }
                ?>
                </td>
                <!-- Δεξί κελί (περιέχει τα κουμπιά -->
                <td style="text-align: right; padding-right: 10px; color: white;">
                    <!--
                    -->
                </td>
            </tr>
        </table>
    </div>      <!-- Fixed header της σελίδας -->
    
    <div class="section">
        <?php
        echo "<h1 style=\"color: #4084b9; margin-left:  12px;\">Manage users</h1>";
        echo "<form name=\"datasetform\" action=\"".$_SERVER['PHP_SELF']."?ds=".$Selection."\" method=\"POST\">";
        ?>
            <div class="formarea" style="width: 1060px;">
            <input type="text" hidden name="UnsavedChanges" id="UnsavedChanges" value="0"></input>
                <?php
                if (strcmp ($AdminAccess,"True")==0)
                {
                    $HideDebug = "hidden";
                    $strSQL = "SELECT Usr_ID, Usr_Username, Usr_Password, Usr_Lastname, ".
                              "Usr_Firstname, Usr_Email, Usr_Phone, Usr_Mobile, Usr_Role_Admin, ".
                              "Usr_Role_Contributor, Usr_Status ".
                              "FROM users ".
                              "ORDER BY Usr_Role_Admin, Usr_LastName, Usr_FirstName";
                    $result = mysql_query($strSQL);
                    echo "<table>";
                    echo "<tr>";
                    echo "<td></td>";
                    echo "<td><strong>"."ID"."</strong></td>";
                    echo "<td><strong>"."Όνομα χρήστη"."</strong></td>";
                    echo "<td><strong>"."Συνθηματικό"."</strong></td>";
                    echo "<td><strong>"."Επώνυμο"."</strong></td>";
                    echo "<td><strong>"."Όνομα"."</strong></td>";
                    echo "<td><strong>"."E-mail"."</strong></td>";
                    echo "<td><strong>"."Τηλέφωνο"."</strong></td>";
                    echo "<td><strong>"."Διαχειριστής"."</strong></td>";
                    echo "<td><strong>"."Χρήστης"."</strong></td>";
                    echo "</tr>";
                    $i = 1;
                    while ($row = mysql_fetch_array($result))
                    {
                        $usr_id = $row['Usr_ID'];
                        $usr_username = $row['Usr_Username'];
                        $usr_password = $row['Usr_Password'];
                        $usr_lastname = $row['Usr_Lastname'];
                        $usr_firstname = $row['Usr_Firstname'];
                        $usr_email = $row['Usr_Email'];
                        $usr_phone = $row['Usr_Phone'];
                        $usr_admin = $row['Usr_Role_Admin'];
                        $usr_contributor = $row['Usr_Role_Contributor'];

                        // Γραμμές με data των users
                        echo "<tr>";
                        echo "<td>";
                        echo "<input type=\"checkbox\" name=\"Data_Array[$i][recSelected]\"></input>";
                        echo "</td>";
                        echo "<td>";
                        echo "<input type=\"text\" readonly name=\"Data_Array[$i][usr_id]\" value=\"".$usr_id."\" size=\"1\"></input>";
                        echo "</td>";
                        echo "<td>";
                        echo "<input type=\"text\" name=\"Data_Array[$i][usr_username]\" value=\"".$usr_username."\" size=\"10\" onchange=\"HandleFieldChange('UnsavedChanges')\"></input>";
                        echo "</td>";
                        echo "<td>";
                        echo "<input type=\"text\" name=\"Data_Array[$i][usr_password]\" value=\"".$usr_password."\" size=\"10\" onchange=\"HandleFieldChange('UnsavedChanges')\"></input>";
                        echo "</td>";
                        echo "<td>";
                        echo "<input type=\"text\" name=\"Data_Array[$i][usr_lastname]\" value=\"".$usr_lastname."\" size=\"10\" onchange=\"HandleFieldChange('UnsavedChanges')\"></input>";
                        echo "</td>";
                        echo "<td>";
                        echo "<input type=\"text\" name=\"Data_Array[$i][usr_firstname]\" value=\"".$usr_firstname."\" size=\"10\" onchange=\"HandleFieldChange('UnsavedChanges')\"></input>";
                        echo "</td>";
                        echo "<td>";
                        echo "<input type=\"text\" name=\"Data_Array[$i][usr_email]\" value=\"".$usr_email."\" size=\"10\" onchange=\"HandleFieldChange('UnsavedChanges')\"></input>";
                        echo "</td>";
                        echo "<td>";
                        echo "<input type=\"text\" name=\"Data_Array[$i][usr_phone]\" value=\"".$usr_phone."\" size=\"10\" onchange=\"HandleFieldChange('UnsavedChanges')\"></input>";
                        echo "</td>";
                        if (strcmp ($usr_admin, "True") == 0)
                        {
                        echo "<td>";
                            echo "<input type=\"radio\" name=\"Data_Array[$i][usr_admin]\" value=\"True\" checked style=\"margin-left: 40px;\">Administrator</input>";
                        echo "</td>";
                        echo "<td>";
                            echo "<input type=\"radio\" name=\"Data_Array[$i][usr_admin]\" value=\"False\" style=\"margin-left: 40px;\">Contributor</input>";
                        echo "</td>";
                        }
                        else
                        {
                        echo "<td>";
                            echo "<input type=\"radio\" name=\"Data_Array[$i][usr_admin]\" value=\"True\" style=\"margin-left: 40px;\">Administrator</input>";
                        echo "</td>";
                        echo "<td>";
                            echo "<input type=\"radio\" name=\"Data_Array[$i][usr_admin]\" value=\"False\" checked style=\"margin-left: 40px;\">Contributor</input>";
                        echo "<td>";
                        }
                        echo "</tr>";
                        $i++;
                    }
                    echo "</table>";
                }
                else
                {
                    // Requires administrator privileges
                    header("Location: message.php?msgid=7");
                }
                ?>
            </div>      <!-- formarea -->
            <?php
                echo "<div class=\"formarea\" style=\"height: 120px; width: 650px; overflow-y: hidden; border-width: 0px;\">";
                echo "<br><br>";
                echo "<input class=\"dbButton\" type=\"submit\" name=\"Delete\" value=\"Διαγραφή\" onclick=\"return action_confirm('Delete selected records?');\"/>";
                echo "<input class=\"dbButton\" type=\"reset\" value=\"Επαναφορά\" style=\"margin-left: 20px;\"/>";
                echo "<input class=\"dbButton\" type=\"button\" name=\"New\" value=\"Νέος\"onclick=\"expandElement('NewRecord');\"  style=\"margin-left: 313px;\">";
                echo "<input class=\"dbButton\" type=\"submit\" name=\"Save\" value=\"Αποθήκευση\" onclick=\"ResetFieldChange('UnsavedChanges');\" style=\"margin-left: 20px;\"/>";
                echo "</div>";
            ?>
            </form>
    </div>          <!-- section  -->
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (isset ($_POST["Save"]))
        {
            $TotalErrors = 0;
            foreach ($_POST["Data_Array"] as $dt) 
            {
                $usr_id = $dt['usr_id'];
                $usr_username = stripslashes($dt['usr_username']);
                $usr_username =  mysql_real_escape_string($usr_username);
                $usr_password = stripslashes($dt['usr_password']);
                $usr_password =  mysql_real_escape_string($usr_password);
                $usr_lastname = stripslashes($dt['usr_lastname']);
                $usr_lastname =  mysql_real_escape_string($usr_lastname);
                $usr_firstname = stripslashes($dt['usr_firstname']);
                $usr_firstname =  mysql_real_escape_string($usr_firstname);
                $usr_email = stripslashes($dt['usr_email']);
                $usr_email =  mysql_real_escape_string($usr_email);
                $usr_phone = stripslashes($dt['usr_phone']);
                $usr_phone =  mysql_real_escape_string($usr_phone);
                $usr_admin = stripslashes($dt['usr_admin']);
                $usr_admin =  mysql_real_escape_string($usr_admin);
                if (strcmp ($usr_admin, "True") == 0)
                    $usr_contributor = "False";
                else
                    $usr_contributor = "True";

                $strSQL = "UPDATE users SET ".
                            "Usr_Username = '$usr_username', ".
                            "Usr_Password =  '$usr_password', ".
                            "Usr_Lastname = '$usr_lastname', ".
                            "Usr_Firstname = '$usr_firstname', ".
                            "Usr_Email = '$usr_email', ".
                            "Usr_Phone = '$usr_phone', ".
                            "Usr_Mobile = '', ".
                            "Usr_Role_Admin = '$usr_admin', ".
                            "Usr_Role_Contributor = '$usr_contributor', ".
                            "Usr_Status = '' ".
                            "WHERE Usr_ID = $usr_id";
                
                echo $strSQL."<br>";
                $result = mysql_query($strSQL);
                if (!$result)
                    $TotalErrors++;
            }
            if ($TotalErrors > 0)
                header("Location: message.php?msgid=29");    // Failure
            else
                header("Location: message.php?msgid=30");    // Success
        }

        if (isset ($_POST["Delete"]))
        {
            $TotalErrors = 0;
            foreach ($_POST["Data_Array"] as $dt) 
            {
                if (isset($dt['recSelected']))
                {
                    $strSQL = "DELETE FROM users WHERE Usr_ID = ".$dt['usr_id'];
                    echo $strSQL."<br>";
                    $result = mysql_query($strSQL);
                    if (!$result)
                        $TotalErrors++;
                }
            if ($TotalErrors > 0)
                header("Location: message.php?msgid=27");    // Failure
            else
                header("Location: message.php?msgid=28");    // Success
            }
        }           // Delete
    }   
    ?>
    </body>
</html>
