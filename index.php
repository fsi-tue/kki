<?php
require_once('DB.php');
$db = new DB();


// get all entries
try {
    $allLocations = $db->getAllLocations();
} catch (Exception $e) {
    echo "Couldn't retrieve entries: {$e}";
}


// show table and add edit/delete buttons

foreach ($allLocations as $item) {
    echo "<h3>{$item['name']}</h3>";
    echo "<tr>";
    echo "<td>{$item['is_active']}</td><td>{$item['address']}</td>";
}

//

//


