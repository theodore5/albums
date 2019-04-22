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
        <link rel="stylesheet" type="text/css" href="shadowbox/shadowbox.css">
        <script  type="text/javascript" src="scripts/phpalbums.js"></script>
        <!-- Τα επόμενα 2 include (script & link) είναι για το javascript plugin "Tables"  -->
        <script type="text/javascript" src="scripts/table.js"></script>
        <script type="text/javascript" src="Shadowbox/shadowbox.js"></script>
        <script type="text/javascript">
            Shadowbox.init();
        </script>
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
        echo "<li class=\"menuButton\"><a href=\"medianew.php\">Νέα φωτογραφία</a></li>";

        if (strcmp ($AdminAccess,"True")==0)
        {
            include ('adminmenu.php');
        }
        echo "</ul>";
        ?>
    </nav>

    <!-- Έναρξη φόρμας επεξεργασίας δεδομένων                                   -->
    <!-- Ξεκινά πάνω από το header div της σελίδας, για να περιλάβει τα κουμπιά -->
    <!-- αποθήκευσης και reset                                                  -->
    <!--<form name="edit" action="scheduleupdate.php" method="POST" enctype="multipart/form-data"> -->

    <!-- Fixed Header ---------------------------------------------------- -->
    <!-- Εμφανίζει το logged χρήστη και τα κουμπιά για αποθήκευση στη βάση -->
    <div class ="PageHeader">
        <table style="width: 100%">
            <tr>
                <!-- Αριστερό κελί (αναφέρει το logged χρήστη και login/logout -->
                <td style="width: 50%; color:  white; padding-left: 8px;">
                <?php
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
                    <?php
                        /*
                        if ($TableEditable != Locked)
                        {
                            echo "<input class=\"dbButton\" style=\"margin-left: 0px;\" type=\"submit\" name=\"Submit\" value=\"Save\" onclick=\"ResetFieldChange('UnsavedChanges');\" />".
                                 "<input class=\"dbButton\" style=\"margin-left: 6px;\" type=\"submit\" name=\"Submit\" value=\"Lock\"/>".
                                 "<input class=\"dbButton\" style=\"margin-left: 6px;\" type=\"reset\" value=\"Reset\"/>";
                        }
                        */
                    ?>
                    <input type="text" hidden  name="UnsavedChanges" id="UnsavedChanges" value="0"></input>
                </td>
            </tr>
        </table>
    </div>      <!-- Fixed header της σελίδας -->

    <div class="section">
        <?php
        if (isset ($_SESSION['LoggedUserID']))
        {
            $HideDebug = "Hidden";

            echo"<table id=\"MasterTable\" class=\"example sort01 table-autosort:0 table-stripeclass:alternate\">";
            // ********************************************************************
            // Header block του πίνακα
            // ********************************************************************
            echo"<thead>";

            // Γραμμή κεφαλίδας πίνακα
            echo"<tr>";
            // Στήλη ID
            echo"   <th class=\"table-sortable:Numeric\">";
            echo"        <p class=\"Header\">" . Column_ID . "</p>";
            echo"    </th>";
            
            // Στήλη Group
            echo "<th class=\"table-sortable:default\">";
            echo "<p class=\"Header\">" . Column_Album . "</p>";
            echo "</th>";
            
            // Στήλη Subject/Title
            echo "<th class=\"table-sortable:default\">";
            echo "<p class=\"Header\">" . Column_Title . "</p>";
            echo "</th>";

            // Στήλη Notes
            echo "<th class=\"table-sortable:default\">";
            echo "<p class=\"Header\">" . Column_Notes . "</p>";
            echo "</th>";
            
            echo "<th class=\"table-sortable:default\">";
            echo "<p class=\"Header\">" . "Φωτογραφία" . "</p>";
            echo "</th>";
            
            echo"</tr>";

            // *****************************************************************************
            // Γραμμή φίλτρων
            // *****************************************************************************
	        echo "<tr>";
		    echo "<th>-</th>";
		    
            
            // Φίλτρο ALBUMS
		    echo "<th><select class=\"filter\" onchange=\"Table.filter(this,this)\"><option value=\"\">All</option>";
            // Ενημέρωση του φίλτρου των Groups
            $strSQL = "SELECT albums.alb_Title FROM albums";
            $result = mysql_query($strSQL);
            while($row = mysql_fetch_array($result))
            {
                echo "<option value=\"".$row['alb_Title']."\">".$row['alb_Title']."</option>";
            }
            echo "</select>";
            echo "</th>";
            
            // Φίλτρο SUBJECT / TITLE (δεν εφαρμόζουμε φίλτρο)
            echo "<th><input class=\"filter\" name=\"filter\" size=\"8\" onkeyup=\"Table.filter(this,this)\"></th>";

            // Φίτρο NOTES (δεν εφαρμόζουμε φίλτρο)
		    echo "<th>-</th>";

            // Φίλτρο Φωτογραφία (δεν εφαρμόζω φίλτρο)
		    echo "<th>-</th>";
            
	        echo "</tr>";

            // *****************************************************************************
            // Τέλος του header block του πίνακα
            // *****************************************************************************
            echo"</thead>";

            // Βρίσκει τα data των στηλών
            $strSQL = "SELECT media.Med_ID, media.Med_Album, media.Med_Description, media.Med_Notes, media.Med_Filename, 
                        users.Usr_ID, users.Usr_Lastname, users.Usr_Firstname, media.Med_Timestamp, albums.alb_ID, 
                        albums.alb_Title, status.sts_ID, status.sts_Title
                        FROM users RIGHT JOIN (status RIGHT JOIN (albums RIGHT JOIN media ON 
                        albums.alb_ID = media.Med_Album) ON status.sts_ID = media.Med_Status) ON 
                        users.Usr_ID = media.Med_Creator";
            $result = mysql_query($strSQL);
            $rowNum = 0;
            
            // *****************************************************************************
            // Περιοχή δεδομένων πίνακα
            // *****************************************************************************
            echo "<tbody>";
            while($row = mysql_fetch_array($result))
            {
                $CurrentMediaID = $row['Med_ID'];
                echo"<tr>";

                // Στήλη No (Είναι το ID του media)
                echo "<td class=\"Data\" style=\"width: 30px;\">";
                if (strcmp ($AdminAccess,"True")==0)
                    echo "<a href=\"mediaedit.php?id=$CurrentMediaID\"><p class=\"Data\" style=\"text-align: center;\">" . $row['Med_ID']. "</p></a>";
                else
                    echo "<p class=\"Data\" style=\"text-align: center;\">" . $row['Med_ID']. "</p>";
                echo "</td>";
                
                // Στήλη Album
                echo "<td class=\"Data\">";
                echo "<p class=\"Data\">" . $row['alb_Title']. "</p>";
                echo "</td>";
                
                // Στήλη SUBJECT/TITLE
                echo "<td class=\"Data\">";
                echo "<p class=\"Data\">" . $row['Med_Description']. "</p>";
                echo "</td>";

                // Στήλη Notes
                echo "<td class=\"Data\">";
                echo "<p class=\"Data\">" . $row['Med_Notes']. "</p>";
                echo "</td>";
                        
                // Στήλη φωτογραφία του αρχείου media
                echo "<td class=\"Data\" style=\"width: 30px;\">";
                //echo "<p class=\"Data\">" . $row['Med_URL']. "</p>";
                //echo "<img class=\"Thumbnail\" src=\"cms/".$row['Med_Filename']."\" alt=\"Φωτογραφία\" />";
                echo "<a href=\"cms/" . $row['Med_Filename'] . "\" rel=\"shadowbox[gallery]\"><img class=\"Thumbnail\" alt=\"Φωτογραφία\" src=\"cms/".$row['Med_Filename']."\" /> </a>";
                //echo "cms/".$row['Med_Filename'];
                echo "</td>";

                echo"</tr>";
                $rowNum++;
            }
            echo "</tbody>";
            echo"</table>";
        }
        else
        {
            header("Location: message.php?msgid=2");
        }
        ?>
    </div>
<!--    </form>  -->
    </body>
</html>
