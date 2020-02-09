<?php
/**
 * If the user is logged in, this file consumes a Base64-encoded version of the entry ID and deletes said entry
 * using deleteLocationById(). If deleting fails, it displays an error message.
 *
 * If the user is not logged in, the file displays an error message and exits.
 */
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
