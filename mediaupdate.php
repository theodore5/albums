<?php
    require 'includes/config.php';
    require 'includes/functions.php';    
    $Med_ID = $_POST['Med_ID'];
    if (empty($_POST['Med_Album']))
        $Med_Album = 'NULL';
    else
        $Med_Album = $_POST['Med_Album'];

    $Med_Description = $_POST['Med_Description'];
    $Med_Filename = $_FILES['Med_Filename']['name'];
    $LoggedUser = new User();
    $Med_Creator = $LoggedUser->GetLoggedUser();
    $Med_Timestamp = date('Y-m-d H:i:s');
    if (strlen ($Med_Filename) > 0)
    {
        $strSQL = "UPDATE media SET Med_Album=$Med_Album, Med_Description='$Med_Description', ".
                  "Med_Notes='$Med_Notes', Med_Filename='$Med_Filename' ".
                  "WHERE Med_ID=$Med_ID";
        //echo $strSQL;
        $result = mysql_query($strSQL);
        if ($result)
        {
            move_uploaded_file($_FILES["Med_Filename"]["tmp_name"], "cms/" . $_FILES["Med_Filename"]["name"]);
            header("Location: message.php?msgid=4");
        }
    }
    else
    {
        $strSQL = "UPDATE media SET Med_Album=$Med_Album, Med_Description='$Med_Description', ".
                  "Med_Notes='$Med_Notes' ".
                  "WHERE Med_ID=$Med_ID";
        $result = mysql_query($strSQL);
        if ($result)
        {
            header("Location: message.php?msgid=4");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title></title>
    </head>
    <body>
        
    </body>
</html>
