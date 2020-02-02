<?php

/*
 * This file dumps the contents of the database to either stdout (the browser)
 * or into a CSV file, which forces the browser into a download.
 * If dump.php is called either without a GET parameter or with ?type=echo, the output is done as plain text.
 * If dump.php is called with ?type=file, the output stream is piped into a file which is downloaded by the browser.
 */
require_once('DB.php');
$db = new DB();
$filename = "kki_locations.csv";
// no GET parameter, default to plain text output
if(!(isset($_GET['type']))) {
    try {
        $db->dumpToCSV('|');
        exit();
    } catch (Exception $e) {
        echo "Dump nach CSV fehlgeschlagen: {$e}";
        exit();
    }
}
// file parameter given, call dumpToCSV with optional $filename parameter
if($_GET['type'] == 'file') {
    try {
        $db->dumpToCSV('|', $filename);
        exit();
    } catch (Exception $e) {
        echo "Dump nach CSV fehlgeschlagen: {$e}";
        exit();
    }
}
// echo parameter given, forces plain text output
if($_GET['type'] == 'echo') {
    try {
        $db->dumpToCSV('|');
        exit();
    } catch (Exception $e) {
        echo "Dump nach CSV fehlgeschlagen: {$e}";
        exit();
    }
}

