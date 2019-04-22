<!DOCTYPE html>

<html>
<head>
    <link href="styles/styles.css" rel="stylesheet" type="text/css" />
    <title></title>
</head>

<body>
    <div class="login">        
    <h1>PHP Albums</h1>
    <p class=\"ErrorText\">Εισάγετε Όνομα χρήστη και Συνθηματικό.<br><br></p>
    <form action="logintry.php" method="POST" enctype="multipart/form-data">
    <table class="style1" style="margin-left: auto; margin-right:  auto;">
        <tr>
            <td><p class="Content" style="margin:  2px;">
                <label for="username">Όνομα χρήστη:</label></td>
            <td><p class="Content"  style="margin:  2px;">
                <input id="username" title="Your username" name="username" required type="text" style="border: 0px; background-color: #1e5d8a; color:  white;"/></p></td>
        </tr>
        <tr>
            <td><p class="Content"  style="margin:  2px;">
                <label for="password">Συνθηματικό:</label></td>
            <td><p class="Content"  style="margin:  2px;">
                <input id="Text1" title="Your password"  name="password" required type="password" style="border: 0px; background-color: #1e5d8a; color:  white;"/></p></td>
        </tr>
        <tr>
            <td><br><br></td>
            <td><br><p class="Content" style="margin:  2px;"><input type="submit" value="Σύνδεση" style="border: 0px; text-align: center; width: 100px; background-color: #4d9ddb; color: #fff"/></p></td>
        </tr>
    </table>
    </form>
    </div>
    <!--
    <p class="Content">Δεν έχετε ακόμη λογαριασμό;</p>
    <p class="Content"><a href="Registration.php"><img alt="Click to register" src="images/click.png" /> Κλικ εδώ για εγγραφή</a></p>
    -->
</body>
</html>
