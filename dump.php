<?php

require_once('DB.php');
$db = new DB();
$filename = "kki_locations.csv";

try {
    $db->dumpToCSV($filename, '|');
} catch (Exception $e) {
    echo "Dump nach CSV fehlgeschlagen: {$e}";
    exit();
}
