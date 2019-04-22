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
    <div id="NewRecord" class="FormDialog" style="z-index: 2000;">
        <?php
            echo "<form name=\"newrecordform\" action=\"".$_SERVER['PHP_SELF']."?ds=".$Selection."\" method=\"POST\">";
        ?>
                <table style="margin-left:  auto; margin-right: auto; margin-top: 50px;">
                <tr>
                <td class="Label" style="background-color: transparent;">
                    <ul>
                    <li style="color:  white;">
                    <?php 
                    // 1 - Albums
                    switch ($Selection)
                    {
                        case 1:
                             echo Column_Album;
                            break;
                    }
                    ?>
                    </li>
                    </ul>
                </td>
                <td class=\InputField">
                    <input type="text" required name="Add_Text" value="" size="30" ></input>
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
                    <input class="dbButton" type="submit" value="Save" onclick="ResetFieldChange('UnsavedChanges');"></input>
                    <input class="dbButton" type="reset" value="Cancel" onclick="collapseElement('NewRecord')"></input>
                </td>
                </tr>
            </table>
        </form>
    </div>
    
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $rec_Title = $_POST['Add_Text'];
        if (strlen($_POST['Add_Text'])>0)
        {
            $LoggedUser = new User();
            $rec_Creator = $LoggedUser->GetLoggedUser();
            $rec_Timestamp = date('Y-m-d H:i:s');

            // 1 - Albums
            switch ($Selection)
            {
                case 1:
                    $strSQL = "INSERT INTO albums (alb_Title, alb_Creator, alb_Timestamp) VALUES (".
                              "'$rec_Title', $rec_Creator, '$rec_Timestamp')";
                    break;
            }

            echo "<br>";
            $result = mysql_query($strSQL);
            if (!$result)
            {
                //  Μήνυμα για διπλή εγγραφή
                // 1 - Albums
                switch ($Selection)
                {
                    case 1:
                        header("Location: message.php?msgid=5");
                        break;
                }
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
                        echo "<a href=\"loginmain.php\">Login</a>";
                    }
                ?>
                </td>
                <!-- Δεξί κελί (περιέχει τα κουμπιά -->
                <td style="text-align: right; padding-right: 10px; color: white;">
                </td>
            </tr>
        </table>
    </div>      <!-- Fixed header της σελίδας -->
    
    <div class="section">
        <?php
        // 1 - Albums
        switch ($Selection)
        {
            case 1:
                echo "<h1 style=\"color: #4084b9; margin-left:  12px;\">Διαχείριση ".Column_Album. "s</h1>";
                break;
        }
        ?>
        <?php
            echo "<form name=\"datasetform\" action=\"".$_SERVER['PHP_SELF']."?ds=".$Selection."\" method=\"POST\">";
        ?>
            <div class="formarea">
            <input type="text" hidden name="UnsavedChanges" id="UnsavedChanges" value="0"></input>
                <?php
                if (strcmp ($AdminAccess,"True")==0)
                {
                    $HideDebug = "hidden";
                    // 1 - Albums
                    switch ($Selection)
                    {
                        case 1:
                            $strSQL = "SELECT alb_ID, alb_Title FROM albums ORDER BY alb_ID";
                            break;
                    }

                    $result = mysql_query($strSQL);
                    echo "<table>";
                    echo "<tr>";
                    echo "<td></td>";
                    echo "<td><strong>".Column_ID."</strong></td>";
                    // 1 - Albums
                    switch ($Selection)
                    {
                        case 1:
                            echo "<td><strong>".Column_Album."</strong></td>";
                            break;
                    }
                    echo "</tr>";
                    $i = 1;
                    while ($row = mysql_fetch_array($result))
                    {
                        // 1 - Album
                        switch ($Selection)
                        {
                            case 1:
                                $fldValID = $row['alb_ID'];
                                $fldValText = $row['alb_Title'];
                                break;
                        }
                        echo "<tr>";
                        echo "<td>";
                        echo "<input type=\"checkbox\" name=\"Data_Array[$i][recSelected]\"></input>";
                        echo "</td>";
                        echo "<td>";
                        echo "<input type=\"text\" readonly name=\"Data_Array[$i][groupID]\" value=\"".$fldValID."\" size=\"2\"></input>";
                        echo "</td>";
                        echo "<td>";
                        echo "<input type=\"text\" name=\"Data_Array[$i][groupTitle]\" value=\"".$fldValText."\" size=\"60\" onchange=\"HandleFieldChange('UnsavedChanges')\"></input>";
                        echo "</td>";
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
                echo "<input class=\"dbButton\" type=\"button\" name=\"New\" onclick=\"expandElement('NewRecord');\" value=\"Νέο\" style=\"margin-left: 313px;\">";
                echo "<input class=\"dbButton\" type=\"submit\" name=\"Save\" value=\"Αποθήκευση\" onclick=\"ResetFieldChange('UnsavedChanges');\" style=\"margin-left: 20px;\"/>";
                echo "</div>";
            ?>
            </form>
    </div>          <!-- section  -->
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (isset($_POST["Save"]))
        {
            foreach ($_POST["Data_Array"] as $dt) 
            {
                $setTitleLocal = stripslashes($dt['groupTitle']);
                $setTitleLocal = mysql_real_escape_string($setTitleLocal);
                $setIDLocal = stripslashes($dt['groupID']);
                $setIDLocal = mysql_real_escape_string($setIDLocal);

                // 1 - Albums
                switch ($Selection)
                {
                    case 1:
                        $strSQL = "UPDATE albums SET alb_Title = '".$setTitleLocal."' WHERE alb_ID = ".$setIDLocal;
                        $result = mysql_query($strSQL);
                        header ("Location: manageset.php?ds=$Selection");
                        break;
                }
            }
        }

        if (isset($_POST["Delete"]))
        {
            $TotalErrors = 0;
            foreach ($_POST["Data_Array"] as $dt) 
            {
                $setTitleLocal = stripslashes($dt['groupTitle']);
                $setTitleLocal = mysql_real_escape_string($setTitleLocal);
                $setIDLocal = stripslashes($dt['groupID']);
                $setIDLocal = mysql_real_escape_string($setIDLocal);

                if (isset($dt['recSelected']))
                {
                    // 1 - Album
                    switch ($Selection)
                    {
                        case 1:
                            $strSQL = "DELETE FROM albums WHERE alb_ID = ".$setIDLocal;
                            break;
                    }
                    $result = mysql_query($strSQL);
                    if (!$result)
                        $TotalErrors++;
                }
            }
            //Μηνύματα επιτυχημένης διαγραφής
            switch ($Selection)
            {
                case 1:
                    if ($TotalErrors > 0)
                        header("Location: message.php?msgid=16");    // Failure
                    else
                        header("Location: message.php?msgid=8");    // Success
                    break;
            }
        }           // Delete
    }   
    ?>
    </body>
</html>
