<?php
    require 'includes/config.php';
    require 'includes/functions.php';
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
    echo "</ul>";
    ?>
</nav>

    <!-- Popupup για συμπλήρωση νέου album  -->
    <div id="NewAlbum" class="FormDialog" style="z-index: 2000;">
        <form name="newalbumform" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" enctype="multipart/form-data">
            <table style="margin-left:  auto; margin-right: auto; margin-top: 50px;">
                <tr>
                <td class="Label" style="background-color: transparent;">
                    <ul>
                    <li style="color:  white;">
                    <?php echo Column04; ?>
                    </li>
                    </ul>
                </td>
                <td class=\InputField">
                    <input type="text" required name="Add_Album" value="" size="30" ></input>
                </td>
                </tr>
                <tr>
                    <td><p></p></td>
                    <td></td>
                </tr>
                <tr>
                <td>
                </td>
                <td>
                    <input class="dbButton" type="submit" value="Save" onclick="ResetFieldChange('UnsavedChanges');"></input>
                    <input class="dbButton" type="reset" value="Cancel" onclick="collapseElement('NewAlbum')"></input>
                </td>
                </tr>
            </table>
        </form>
    </div>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $alb_Title = $_POST['Add_Album'];
        if (strlen($_POST['Add_Album'])>0)
        {
            $LoggedUser = new User();
            $alb_Creator = $LoggedUser->GetLoggedUser();
            $alb_Timestamp = date('Y-m-d H:i:s');

            $strSQL = "INSERT INTO albums (alb_Title, alb_Creator, alb_Timestamp) VALUES (".
                      "'$alb_Title', $alb_Creator,  '$alb_Timestamp')";
            $result = mysql_query($strSQL);
            if (!$result)
                //  Μήνυμα για διπλή εγγραφή
                header("Location: message.php?msgid=5");
            else
                // Μήνυμα επιτυχημένης εγγραφής
                header("Location: message.php?msgid=4");
        }
    }
    ?>
    <!-- Τέλος του Popup νέου album                                          -->

    <form name="edit" action="mediainsert.php" method="POST" enctype="multipart/form-data">
    <!-- Fixed Header ---------------------------------------------------- -->
    <!-- Εμφανίζει το logged χρήστη και τα κουμπιά για αποθήκευση στη βάση  -->
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
                    <input class="dbButton" type="submit" value="Save" onclick="ResetFieldChange('UnsavedChanges');"/><input class="dbButton" style="margin-left: 6px;" type="reset" value="Reset"/>
                    <input type="text" hidden name="UnsavedChanges" id="UnsavedChanges" value="0"></input>
                </td>
            </tr>
        </table>
    </div>      <!-- Fixed header της σελίδας -->
    
    <div class="section">
    <h1 style="color: #4084b9; margin-left:  12px;">Νέα φωτογραφία</h1>
    <input type="text" hidden name="UnsavedChanges1" id="UnsavedChanges" value="0"></input>

        <?php
        if (isset ($_SESSION['LoggedUserID']))
        {
            $HideDebug = "hidden";
            $AdminAccess = "False";
            $Logged_ID = $LoggedUser->GetLoggedUser();
            $AdminAccess = $LoggedUser->IsAdmin($Logged_ID);

            echo"<table id=\"MasterTable\">";

            // Πεδίο GROUP
            echo"<tr>";
            echo "<td class=\"Label\">";
            echo "<li>".Column_Album."</li>";
            echo "</td>";
            echo "<td class=\"InputField\">";

            // Ενημέρωση drop down list με τα ονόματα των Groups
            echo "<select name=\"Med_Album\">";
            $strSelectSQL = "SELECT alb_ID, alb_Title FROM albums";
                            "ORDER BY alb_Title";
            $resultSelect = mysql_query($strSelectSQL);
            while($rowSelect = mysql_fetch_array($resultSelect))
            {
                echo "<option value=\"".$rowSelect['alb_ID']."\">".$rowSelect['alb_Title']."</option>";
            }
            echo"</select>";
            echo"<input type=\"button\" onclick=\"expandElement('NewAlbum');\" value=\"New\" style=\"margin-left: 30px; width: 80px; text-align: center;\"/>"; //@@
            echo "</td>";
            echo"</tr>";

            // Πεδίο SUBJECT/TITLE
            echo"<tr>";
            echo "<td class=\"Label\">";
            echo "<li>".Column_Title."</li>";
            echo "</td>";
            echo "<td class=\"InputField\">";
            echo "<input type=\"text\" required name=\"Med_Description\" value=\"\" size=\"60\" onchange=\"HandleFieldChange()\"></input>";
            echo "</td>";
            echo "</tr>";

            // Πεδίο STATUS (Hold/Play)
            echo "<tr hidden>";
            echo "<td class=\"Label\">";
            echo "<li>".Column09."</li>";
            echo "</td>";
            echo "<td class=\"InputField\">";
            $strSelectSQL = "SELECT sts_ID, sts_Title FROM status WHERE sts_ID=1";
            $resultSelect = mysql_query($strSelectSQL);
            while($rowSelect = mysql_fetch_array($resultSelect))
            {
                echo "<input type=\"text\" readonly name=\"Med_StatusText\" value=\"".$rowSelect['sts_Title']."\"></input><input type=\"text\" hidden name=\"Med_Status\" value=\"".$rowSelect['sts_ID']."\"></input>";
            }
            echo "</td>";
            echo"</tr>";

            // Πεδίο Notes του αρχείου media
            echo "<tr>";
            echo "<td class=\"Label\">";
            echo "<li>".Column_Notes."</li>";
            echo "</td>";
            echo "<td class=\"InputField\">";
            echo "<input type=\"text\" size=\"100\" name=\"Med_Notes\" value=\"\"></input>";
            echo "</td>";
            echo "</tr>";

            // Πεδίο filename του αρχείου media
            if (strcmp ($AdminAccess,"True")==0)
            {
                echo "<tr>";
                echo "<td class=\"Label\">";
                echo "<li>"."Φωτογραφία"."</li>";
                echo "</td>";
                echo "<td class=\"InputField\">";
                //echo "<input type=\"text\" name=\"Med_Filename\" value=\"\"></input>";
                echo "<input type=\"file\" required name=\"Med_Filename\" size=\"10\"/></li>";
                echo "</td>";
                echo "</tr>";
            }

            // Πεδίο URL του αρχείου media
            if (strcmp ($AdminAccess,"True")==0)
            {
                echo "<tr>";
                echo "<td class=\"Label\">";
                echo "<li>".Column13."</li>";
                echo "</td>";
                echo "<td class=\"InputField\">";
                echo "<p class=\"Data\">" . $row['Med_URL']. "</p>";
                echo "<input type=\"text\" name=\"Med_URL\" value=\"\"></input>";
                echo "</td>";
            }            
            echo"</tr>";
            echo"</table>";
        }
        else
        {
            header("Location: message.php?msgid=2");
        }
        ?>
    </div>
    </form>

    </body>
</html>
