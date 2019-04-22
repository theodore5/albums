<?php
    require 'includes/config.php';
    require 'includes/functions.php';

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
        echo "</ul>";
        $Med_ID=$_GET['id'];
        $strSQL = "SELECT media.Med_ID, media.Med_Album, media.Med_Description, media.Med_Notes, media.Med_URL, 
                        users.Usr_ID, users.Usr_Lastname, users.Usr_Firstname, media.Med_Timestamp, albums.alb_ID, 
                        albums.alb_Title, status.sts_ID, status.sts_Title
                        FROM users RIGHT JOIN (status RIGHT JOIN (albums RIGHT JOIN media ON 
                        albums.alb_ID = media.Med_Album) ON status.sts_ID = media.Med_Status) ON 
                        users.Usr_ID = media.Med_Creator WHERE Med_ID = $Med_ID";
        $result = mysql_query($strSQL);
        if ($result)
        {
            $row = mysql_fetch_array($result);
        }
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
                    <?php echo Column_Album; ?>
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
                      "'$alb_Title', $alb_Creator, '$alb_Timestamp')";
            $result = mysql_query($strSQL);
            if (!$result)
                //  Μήνυμα για διπλή εγγραφή
                header("Location: message.php?msgid=5");
            else
                // Μήνυμα επιτυχημένης εγγραφής
                header("Location: message.php?msgid=4");
        }
    }
    ?>    <!-- Τέλος του Popup καμπάνιας                                              -->

    <!-- Έναρξη φόρμας επεξεργασίας δεδομένων                                   -->
    <!-- Ξεκινά πάνω από το header div της σελίδας, για να περιλάβει τα κουμπιά -->
    <!-- αποθήκευσης και reset                                                  -->
    <form name="edit" action="mediaupdate.php" method="POST" enctype="multipart/form-data">

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
                        echo "Καλωσήρθες ".$_SESSION['LoggedName']." ";
                        echo "<a href=\"message.php?msgid=3\">(press here to logout)</a>";
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

        <?php
        if (isset ($_SESSION['LoggedUserID']))
        {
            $Med_ID=$row['Med_ID'];
            $Med_Album=$row['Med_Album'];
            $Med_Description=$row['Med_Description'];
            $Med_Status=$row['Med_Status'];
            $Med_Notes=$row['Med_Notes'];
            $Med_URL=$row['Med_URL'];

            $HideDebug = "hidden";
            echo "<input type=\"text\" hidden name=\"Med_ID\" value=\"$Med_ID\"></input>";
            echo "<table id=\"MasterTable\">";
            echo "<tr>";
            
            // Πεδίο Media ID
            echo "<td class=\"Label\">";
            echo "<li>".Column_ID."</li>";
            echo "</td>";
            echo "<td class=\"InputField\">";
            echo $Med_ID;
            echo "</td>";
            echo"</tr>";
            echo"<tr>";
            
            // Πεδίο ALBUM
            echo "<td class=\"Label\">";
            echo "<li>".Column_Album."</li>";
            echo "</td>";
            echo "<td class=\"InputField\">";
            // Ενημέρωση drop down list με τα ονόματα των Groups
            echo "<select name=\"Med_Album\">";
            $strSelectSQL = "SELECT alb_ID, alb_Title FROM albums";
            $resultSelect = mysql_query($strSelectSQL);
            while($rowSelect = mysql_fetch_array($resultSelect))
            {
                if ($rowSelect['alb_ID'] == $Med_Album)
                    echo "<option value=\"".$rowSelect['alb_ID']."\" selected=\"selected\">".$rowSelect['alb_Title']."</option>";
                else
                    echo "<option value=\"".$rowSelect['alb_ID']."\">".$rowSelect['alb_Title']."</option>";
            }
            echo"</select>";
            echo "</td>";
            echo"</tr>";
            echo"<tr>";
            
            // Πεδίο SUBJECT/TITLE
            echo "<td class=\"Label\">";
            echo "<li>".Column_Title."</li>";
            echo "</td>";
            echo "<td class=\"InputField\">";
            echo "<input type=\"text\" name=\"Med_Description\" value=\"$Med_Description\" size=\"60\"></input>";
            echo "</td>";
            echo "</tr>";
            echo "<tr>";
            
            // Πεδίο Notes του αρχείου media
            echo "<tr>";
            echo "<td class=\"Label\">";
            echo "<li>".Column_Notes."</li>";
            echo "</td>";
            echo "<td class=\"InputField\">";
            echo "<input type=\"text\" size=\"100\" name=\"Med_Notes\" value=\"".$Med_Notes."\"></input>";
            echo "</td>";
            echo "</tr>";
            
            // Πεδίο filename του αρχείου media
            if (strcmp ($AdminAccess,"True")==0)
            {
                echo"<tr>";
                echo "<td class=\"Label\">";
                echo "<li>".Column_Filename."</li>";
                echo "</td>";
                echo "<td class=\"InputField\">";
                echo "<input type=\"text\" name=\"Med_Filename\" value=\"".$Med_Filename."\"></input>";
                echo "</td>";
                echo "</tr>";
            }
            
            echo"</table>";
        }
        else
        {
            header("Location: message.php?msgid=2");
        }
        ?>
    </div>  <!-- DIV Section -->
    </form>
    </body>
</html>
