<?php
require_once('DB.php');
include("header.php");

$db = new DB();

$objectID = base64_decode($_POST['id']);
if($db->deleteLocationById($objectID)) {
    echo "<div id='message'><p class='success'>Eintrag erfolgreich gelöscht.</p></div>";
} else {
    echo "<div id='message'><p class='fail'>Löschen fehlgeschlagen.</p></div>";
}

echo "<a href='index_admin.php'>Zurück zur Übersicht</a>";

include('footer.php');
