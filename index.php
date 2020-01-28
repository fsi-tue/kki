<?php
require_once('DB.php');
try {
    $db = new DB();
} catch(Exception $e) {
    echo "Couldn't create DB instance: {$e}";
}

// get all entries
try {
    $allLocations = $db->getAllLocations();
} catch (Exception $e) {
    echo "Couldn't retrieve entries: {$e}";
    exit();
}

// show table and add edit/delete buttons

foreach ($allLocations as $item) {
    echo "<h3>{$item['name']}</h3>";
    echo "<tr>";
    echo "<td>{$item['is_active']}</td><td>{$item['address']}</td>";
}


