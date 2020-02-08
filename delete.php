<?php
session_start();
require_once('DB.php');
include("header.php");

// check if the user is logged in
if(!isset($_SESSION['userid'])) {
    echo "<div id='message'><p class='fail'>Bitte zuerst <a href='login.php'>einloggen!</a></p></div>";
    exit();
}
if(isset($_SESSION['userid']) && ($_SESSION['userid'] != 'f0a8ed5d51a3229f154450fa55dac748')) {
    echo "<div id='message'><p class='fail'>Netter Versuch!</a></p></div>";
    exit();
}

$db = new DB();

$objectID = base64_decode($_POST['id']);
if($db->deleteLocationById($objectID)) {
    echo "<div id='message'><p class='success'>Eintrag erfolgreich gelöscht.</p></div>";
} else {
    echo "<div id='message'><p class='fail'>Löschen fehlgeschlagen.</p></div>";
}

echo "<a href='index_admin.php'>Zurück zur Übersicht</a>";

include('footer.php');
