<?php
    include "includes/config.php";
    include 'includes/functions.php';   
?>

<html>
    <head>
        <link href="Styles/Site.css" rel="stylesheet" type="text/css" />
        <title>Σύνδεση...</title>
    </head>

<body>

<?php
    session_start();

    $username = mysql_escape_string($_POST['username']);
    $password = mysql_escape_string($_POST['password']);

    $LoggedUser = new User();
    if ($LoggedUser->login($username, $password) == TRUE)
        header("Location: albumreview.php");
    else
        header("Location: message.php?msgid=1");
 ?> 

</body>
</html>
