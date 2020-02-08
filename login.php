<?php
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
<div class="locationlist">
<form action="?login=1" method="post">
    Dein Passwort:<br>
    <input type="password" size="40"  maxlength="250" name="passwort"><br>
    <input type="submit" value="Abschicken">
</form>
</div>
