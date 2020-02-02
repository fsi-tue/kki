<?php
require_once('DB.php');
include("header.php");

$db = new DB();

$objectID = base64_decode($_POST['id']);
if($db->deleteLocationById($objectID)) {
    echo "Eintrag erfolgreich gelöscht.\n";
} else {
    echo "Löschen fehlgeschlagen. :(\n";
}

echo "<a href='index_admin.php'>Zurück zur Übersicht</a>";

include('footer.php');
