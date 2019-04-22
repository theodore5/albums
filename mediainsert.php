<?php
    require 'includes/config.php';
    require 'includes/functions.php';    

    if (empty($_POST['Med_Album']))
        $Med_Album = 'NULL';
    else
        $Med_Album = $_POST['Med_Album'];

    $Med_Description = $_POST['Med_Description'];
    $Med_Notes = $_POST['Med_Notes'];
    $Med_Filename = $_FILES['Med_Filename']['name'];
    $Med_URL = $_POST['Med_URL'];
    
    $LoggedUser = new User();
    $Med_Creator = $LoggedUser->GetLoggedUser();
    $Med_Timestamp = date('Y-m-d H:i:s');

    $strSQL = "INSERT INTO media (Med_Album, Med_Description, ".
              "Med_Notes, Med_Filename, ".
              "Med_Creator, Med_Timestamp) VALUES (".
              "$Med_Album, '$Med_Description', ".
              "'$Med_Notes', '$Med_Filename', ".
              "$Med_Creator,  '$Med_Timestamp')";
    echo $strSQL;
    $result = mysql_query($strSQL);
    if ($result)
    {
        move_uploaded_file($_FILES["Med_Filename"]["tmp_name"], "cms/" . $_FILES["Med_Filename"]["name"]);
        header("Location: message.php?msgid=4");
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
