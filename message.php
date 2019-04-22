<!DOCTYPE html>

<?php
   include 'Includes/config.php';
   include 'Includes/functions.php';
?>

<html>
<head>
    <link href="styles/styles.css" rel="stylesheet" type="text/css">
    <script>
    function goBack() {
        window.history.back()
    }

    function goLogin() 
    {
        window.location.href = "index.php?id=3";
    }

    function goHome() 
    {
        window.location.href = "index.php";
    }
    
    function goAlbumsReview() 
    {
        window.location.href = "albumreview.php";
    }

    function goManageAlbums()
    {
        window.location.href = "manageset.php?ds=1";
    }

    function goManageUsers()
    {
        window.location.href = "manageusers.php";
    }
    </script>
</head>

<body>
    <div class="message">
        <h1>Ειδοποίηση</h1>
        <br>
        
        <?php
            $ErrorID = $_GET['msgid'];
            switch ($ErrorID) 
            {
                case 1:
                    $MessageText = "Τα στοιχεία εισόδου που δώσατε δεν είναι σωστά.<br><br>".
                                   "Πληκτρολογήστε ξανά με προσοχή το Όνομα χρήστη και το Συνθηματικό σας.<br>";
                    echo "<p class=\"ErrorText\">".$MessageText."</p>";
                    echo "<p style=\"text-align:  center; margin-top:  3em;\"><button type=\"button\" onclick=\"goBack()\" style=\"margin-left: auto; margin-right: auto; text-align: center; width: 100px; height: 36px; background-color: #4d9ddb; color: #fff;\">OK</button></p>";
                    break;
                case 2:
                    $MessageText = "Η σελίδα που προσπαθήσατε να εμφανίσετε απαιτεί να είσαστε συνδεδεμένος στο σύστημα.<br><br>".
                                   "Παρακαλούμε εισάγετε τα στοιχεία εισόδου σας στη σελίδα σύνδεσης.<br>";
                    echo "<p class=\"ErrorText\">".$MessageText."</p>";
                    echo "<p style=\"text-align:  center; margin-top:  3em;\"><button type=\"button\" onclick=\"goLogin()\" style=\"margin-left: auto; margin-right: auto; text-align: center; width: 100px; height: 36px; background-color: #4d9ddb; color: #fff;\">OK</button></p>";
                    break;
                case 3:
                    // remove all session variables
                    session_unset(); 
                    // destroy the session 
                    session_destroy(); 
                    $MessageText = "Έχετε αποσυνδεθεί από το σύστημα.<br><br>";
                    echo "<p class=\"ErrorText\">".$MessageText."</p>";
                    echo "<p style=\"text-align:  center; margin-top:  3em;\"><button type=\"button\" onclick=\"goLogin()\" style=\"margin-left: auto; margin-right: auto; text-align: center; width: 100px; height: 36px; background-color: #4d9ddb; color: #fff;\">OK</button></p>";
                    break;
                case 4:
                    $MessageText = "Οι αλλαγές αποθηκεύτηκαν.<br><br>".
                                   "Πατήστε ΟΚ για συνέχεια.";
                    echo "<p class=\"ErrorText\">".$MessageText."</p>";
                    echo "<p style=\"text-align:  center; margin-top:  3em;\"><button type=\"button\" onclick=\"goAlbumsReview()\" style=\"margin-left: auto; margin-right: auto; text-align: center; width: 100px; height: 36px; background-color: #4d9ddb; color: #fff;\">OK</button></p>";
                    break;
                case 5:
                    $MessageText = "Το νέο album δεν καταχωρήθηκε.<br><br>Υπάρχει ήδη καταχωρημένο album με το ίδιο όνομα.<br><br>".
                                   "Πατήστε ΟΚ για συνέχεια.";
                    echo "<p class=\"ErrorText\">".$MessageText."</p>";
                    echo "<p style=\"text-align:  center; margin-top:  3em;\"><button type=\"button\" onclick=\"goAlbumsReview()\" style=\"margin-left: auto; margin-right: auto; text-align: center; width: 100px; height: 36px; background-color: #4d9ddb; color: #fff;\">OK</button></p>";
                    break;
                case 7:
                    $MessageText = "Η σελίδα που προσπαθήσατε να εμφανίσετε απαιτεί δικαιώματα Διαχειριστή.<br><br>";
                    echo "<p class=\"ErrorText\">".$MessageText."</p>";
                    echo "<p style=\"text-align:  center; margin-top:  3em;\"><button type=\"button\" onclick=\"goLogin()\" style=\"margin-left: auto; margin-right: auto; text-align: center; width: 100px; height: 36px; background-color: #4d9ddb; color: #fff;\">OK</button></p>";
                    break;

                // Μηνύματα επιτυχούς καταχώρησης και επιστροφή στην αντίστοιχη φόρμα εισαγωγής
                case 8:                         // 
                    $MessageText = "Οι αλλαγές αποθηκεύτηκαν.<br><br>".
                                   "Πατήστε ΟΚ για συνέχεια.";
                    echo "<p class=\"ErrorText\">".$MessageText."</p>";
                    echo "<p style=\"text-align:  center; margin-top:  3em;\"><button type=\"button\" onclick=\"goManageAlbums()\" style=\"margin-left: auto; margin-right: auto; text-align: center; width: 100px; height: 36px; background-color: #4d9ddb; color: #fff;\">OK</button></p>";
                    break;

                // Μηνύματα αποτυχίας εισαγωγής
                case 12:                         // albums
                    $MessageText = "Η δημιουργία του νέου album δεν ολοκληρώθηκε.<br><br>Υπάρχει ήδη καταχωρημένο album με το ίδιο όνομα.<br><br>".
                                   "Πατήστε ΟΚ για συνέχεια.";
                    echo "<p class=\"ErrorText\">".$MessageText."</p>";
                    echo "<p style=\"text-align:  center; margin-top:  3em;\"><button type=\"button\" onclick=\"goManageAlbums()\" style=\"margin-left: auto; margin-right: auto; text-align: center; width: 100px; height: 36px; background-color: #4d9ddb; color: #fff;\">OK</button></p>";
                    break;

                // ***************************** DELETE FAILURE ***********************************************************************************
                case 16:                         // Delete failure CAMPAIGNS
                    $MessageText = "Δεν είναι δυνατό να διαγραφεί το album.<br><br>Η διαγραφή παραβιάζει έναν περιορισμό ξένου κλειδιού<br>(υπάρχουν φωτογραφίες καταχωρημένες στο album αυτό).<br><br>".
                                   "Πατήστε ΟΚ για συνέχεια.";
                    echo "<p class=\"ErrorText\">".$MessageText."</p>";
                    echo "<p style=\"text-align:  center; margin-top:  3em;\"><button type=\"button\" onclick=\"goManageAlbums()\" style=\"margin-left: auto; margin-right: auto; text-align: center; width: 100px; height: 36px; background-color: #4d9ddb; color: #fff;\">OK</button></p>";
                    break;

                // Μηνύματα αποτυχίας εισαγωγής Users
                case 26:                         // Users
                    $MessageText = "Δεν ήταν δυνατό να δημιουργηθεί ο νέος χρήστης.<br><br>Υπάρχει ήδη χρήστης με το ίδιο όνομα.<br><br>".
                                   "Πατήστε ΟΚ για συνέχεια.";
                    echo "<p class=\"ErrorText\">".$MessageText."</p>";
                    echo "<p style=\"text-align:  center; margin-top:  3em;\"><button type=\"button\" onclick=\"goManageUsers()\" style=\"margin-left: auto; margin-right: auto; text-align: center; width: 100px; height: 36px; background-color: #4d9ddb; color: #fff;\">OK</button></p>";
                    break;

                // ***************************** DELETE USER FAILURE ***********************************************************************************
                case 27:                         // Delete failure USERS
                    $MessageText = "Δεν είναι δυνατή η διαγραφή του χρήστη.<br><br>Παραβιάζεται ένας περιορισμός ξένου κλειδιού.<br>(Ο χρήστης έχει εισάγει ήδη φωτογραφίες σε κάποιο album).<br><br>".
                                   "Πατήστε ΟΚ για συνέχεια.";
                    echo "<p class=\"ErrorText\">".$MessageText."</p>";
                    echo "<p style=\"text-align:  center; margin-top:  3em;\"><button type=\"button\" onclick=\"goManageUsers()\" style=\"margin-left: auto; margin-right: auto; text-align: center; width: 100px; height: 36px; background-color: #4d9ddb; color: #fff;\">OK</button></p>";
                    break;

                case 28:                         // Delete success USERS
                    $MessageText = "Η διαγραφή του χρήστη ολοκληρώθηκε.<br><br>".
                                   "Πατήστε ΟΚ για συνέχεια.";
                    echo "<p class=\"ErrorText\">".$MessageText."</p>";
                    echo "<p style=\"text-align:  center; margin-top:  3em;\"><button type=\"button\" onclick=\"goManageUsers()\" style=\"margin-left: auto; margin-right: auto; text-align: center; width: 100px; height: 36px; background-color: #4d9ddb; color: #fff;\">OK</button></p>";
                    break;

                // ***************************** SAVE USER  ***********************************************************************************
                case 29:                         // Save failure USERS
                    $MessageText = "Η αποθήκευση των αλλαγών δε μπόρεσε να ολοκληρωθεί.<br><br>".
                                   "Πατήστε ΟΚ για συνέχεια.";
                    echo "<p class=\"ErrorText\">".$MessageText."</p>";
                    echo "<p style=\"text-align:  center; margin-top:  3em;\"><button type=\"button\" onclick=\"goManageUsers()\" style=\"margin-left: auto; margin-right: auto; text-align: center; width: 100px; height: 36px; background-color: #4d9ddb; color: #fff;\">OK</button></p>";
                    break;

                case 30:                         // Save success USERS
                    $MessageText = "Οι αλλαγές των στοιχείων του χρήστη αποθηκεύτηκαν.<br><br>".
                                   "Πατήστε ΟΚ για συνέχεια.";
                    echo "<p class=\"ErrorText\">".$MessageText."</p>";
                    echo "<p style=\"text-align:  center; margin-top:  3em;\"><button type=\"button\" onclick=\"goManageUsers()\" style=\"margin-left: auto; margin-right: auto; text-align: center; width: 100px; height: 36px; background-color: #4d9ddb; color: #fff;\">OK</button></p>";
                    break;
            }
        ?>
    </div>
</body>
</html>