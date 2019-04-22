

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <link href="styles/styles.css" rel="stylesheet" type="text/css" />
        <title></title>
    </head>
    <body>
        <?php
            // Προσδιορίζει την επιλογή του χρήστη
            // Ανάλογα φορτώνονται τα μενού.
            if (isset($_GET['id']))
                $Selection = $_GET['id'];
            else
                $Selection = 3;
            $Med_ID=$_GET['mediaid'];

            switch ($Selection) 
            {
                case 1:
                    include 'albumreview.php';
                    break;
                case 4:
                    include 'medianew.php';
                    break;
                case 3:
                    include 'loginmain.php';
                    break;
                case 5:
                    include 'message.php?msgid=3';
                    break;
                case 6:
                    include 'mediaedit.php';
                    break;
                default:
                    include 'loginmain.php';
            }
        ?>
    </body>
</html>
