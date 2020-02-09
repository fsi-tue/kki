<?php
/**
 * This file displays a simple password field which enables users to access the administrative features.
 * The password hash is grabbed from the file 'credentials.ini' using getHash() and matched against the user input
 * using PHP's built-in password_verify(). If the password is correct, the 'userid' field inside the PHP
 * $_SESSION superglobal is set to an MD5 hash (which has nothing to do with the password supplied,
 * for anyone wondering. It's just an arbitrary choice).
 *
 * Each of the administrative functions starts the PHP session and checks for this value to be present.
 */
session_start();
include('header.php');
require_once('DB.php');
$db = new DB();
// extract POST parameter
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userpw = $_POST['passwort'];

    if (password_verify($userpw, $db->getHash())) {
        $_SESSION['userid'] = 'f0a8ed5d51a3229f154450fa55dac748';
        echo "<div id='message'><p class='success'>Login erfolgreich. Weiter zur <a href='index_admin.php'>Verwaltung</a></p></div>";
        exit;
    } else {
        echo "<div id='message'><p class='fail'>Passwort nicht korrekt.</p></div>";
        exit();
    }
}
?>
<div class="locationlist" id="inputform">
<form action="?login=1" method="post">
    Dein Passwort:<br>
    <input type="password" size="40"  maxlength="250" name="passwort"><br>
    <input type="submit" class='green' value="Abschicken">
</form>
</div>
