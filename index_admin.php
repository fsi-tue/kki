<?php
/**
 * This file displays all entries in the database using getAllLocations().
 * Once logged in, The user has the ability to edit or delete entries,
 * this is done by transferring a Base64-encoded version of the entry ID
 * as a POST parameter to edit.php or delete.php, respectively.
 * If the user is not logged in, the file displays an error message and exits.
 *
 * This file uses TableSort.js by Jürgen Berkheimer, released under CC BY-SA 4.0.
 */
require_once('DB.php');
session_start();

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
include('header.php');

// check if the user is logged in
if(!isset($_SESSION['userid'])) {
    echo "<div id='message'><p class='fail'>Bitte zuerst <a href='login.php'>einloggen!</a></p></div>";
    exit();
}
if(isset($_SESSION['userid']) && ($_SESSION['userid'] != 'f0a8ed5d51a3229f154450fa55dac748')) {
    echo "<div id='message'><p class='fail'>Netter Versuch!</a></p></div>";
    exit();
}

echo "<table class='locationlist sortierbar'>";
echo "<thead>";
echo "<tr>";
echo "<th class='sortierbar'>aktiv?<span></span></th><th class='sortierbar'>Name<span></span></th><th class='sortierbar'>Kategorie<span></span></th><th>Adresse<span></span></th><th class='sortierbar'>Bier (€)<span></span></th><th class='sortierbar'>Softdrink (€)<span></span></th><th>URL</th><th class='sortierbar'>Stand<span></span></th><th class='sortierbar'><i class='fas fa-utensils tooltip'><span class='tooltiptext'>Gibt es Essen?</span></i></th><th class='sortierbar'><i class='fas tooltip fa-beer'><span class='tooltiptext'>Gibt es Bier?</span></i></th><th class='sortierbar'><i class='fas tooltip fa-cocktail'><span class='tooltiptext'>Gibt es Cocktails?</span></i></th><th class='sortierbar'><i class='fas tooltip fa-wifi'><span class='tooltiptext'>Gibt es WLAN?</span></i></th><th class='sortierbar'><i class='fas tooltip fa-shopping-bag'><span class='tooltiptext'>Gibt es Essen to go?</span></i></th><th class='sortierbar'><i class='fas tooltip fa-smoking'><span class='tooltiptext'>Raucher?</span></i></th><th class='sortierbar'><i class='fas tooltip fa-smoking-ban'><span class='tooltiptext'>Nichtraucher?</span></i></th><th>Edit</th><th>Löschen</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";
foreach ($allLocations as $item) {
    echo "<tr>";
    if($item['is_active'] == 1) {
        echo "<td><span class='hidden'>1</span><i class='fas fa-check'></i></td>";
    }
    if($item['is_active'] == 0) {
        echo "<td><span class='hidden'>0</span><i class='fas fa-times'></i></td>";
    }
    echo "<td>{$item['name']}</td>";
    $item['category'] = ucfirst($item['category']);
    echo "<td>{$item['category']}</td>";
    echo "<td>{$item['address']}</td>";
    echo "<td>{$item['price_beer']}</td>";
    echo "<td>{$item['price_softdrink']}</td>";
    echo "<td><a href='{$item['url']}'>{$item['url']}</a></td>";
    echo "<td>{$item['last_update']}</td>";
    switch ($item['has_food']) {
        case 0:
            echo "<td><span class='hidden'>0</span><i class='fas fa-times'></i></td>";
            break;
        case 1:
            echo "<td><span class='hidden'>1</span><i class='fas fa-check'></i></td>";
            break;
        case 2:
            echo "<td><span class='hidden'>2</span><i class='fas fa-question'></i></td>";
            break;
    }
    switch ($item['has_beer']) {
        case 0:
            echo "<td><span class='hidden'>0</span><i class='fas fa-times'></i></td>";
            break;
        case 1:
            echo "<td><span class='hidden'>1</span><i class='fas fa-check'></i></td>";
            break;
        case 2:
            echo "<td><span class='hidden'>2</span><i class='fas fa-question'></i></td>";
            break;
    }
    switch ($item['has_cocktails']) {
        case 0:
            echo "<td><span class='hidden'>0</span><i class='fas fa-times'></i></td>";
            break;
        case 1:
            echo "<td><span class='hidden'>1</span><i class='fas fa-check'></i></td>";
            break;
        case 2:
            echo "<td><span class='hidden'>2</span><i class='fas fa-question'></i></td>";
            break;
    }
    switch ($item['has_wifi']) {
        case 0:
            echo "<td><span class='hidden'>0</span><i class='fas fa-times'></i></td>";
            break;
        case 1:
            echo "<td><span class='hidden'>1</span><i class='fas fa-check'></i></td>";
            break;
        case 2:
            echo "<td><span class='hidden'>2</span><i class='fas fa-question'></i></td>";
            break;
    }
    switch ($item['has_togo']) {
        case 0:
            echo "<td><span class='hidden'>0</span><i class='fas fa-times'></i></td>";
            break;
        case 1:
            echo "<td><span class='hidden'>1</span><i class='fas fa-check'></i></td>";
            break;
        case 2:
            echo "<td><span class='hidden'>2</span><i class='fas fa-question'></i></td>";
            break;
    }
    switch ($item['is_smokers']) {
        case 0:
            echo "<td><span class='hidden'>0</span><i class='fas fa-times'></i></td>";
            break;
        case 1:
            echo "<td><span class='hidden'>1</span><i class='fas fa-check'></i></td>";
            break;
        case 2:
            echo "<td><span class='hidden'>2</span><i class='fas fa-question'></i></td>";
            break;
    }
    switch ($item['is_nonsmokers']) {
        case 0:
            echo "<td><span class='hidden'>0</span><i class='fas fa-times'></i></td>";
            break;
        case 1:
            echo "<td><span class='hidden'>1</span><i class='fas fa-check'></i></td>";
            break;
        case 2:
            echo "<td><span class='hidden'>2</span><i class='fas fa-question'></i></td>";
            break;
    }
    // form for submitting edit
    echo "<td>";
    $base = base64_encode($item['id']);
    echo "<form name='edit' method='POST' action='edit.php'>";
    echo "<input type='hidden' name='id' value='{$base}'>";
    echo "<input type='submit' class='blue' value='Bearbeiten'>";
    echo "</form>";
    echo "</td>";
    echo "<td>";
    echo "<form name='delete' method='POST' action='delete.php'>";
    echo "<input type='hidden' name='id' value='{$base}'>";
    echo "<input type='submit' class='red' value='Löschen'>";
    echo "</form>";
    echo "</td>";
    echo "</tr>";
}
echo "</tbody>";
echo "</table>";
echo "<div id='buttons'>";
echo "<a href='create.php'><button type='button' class='blue'>Neuen Eintrag anlegen</button></a> &nbsp; <a href='dump.php'><button type='button' class='green'>Export nach CSV</button></a>&nbsp; <a href='logout.php'><button type='button' class='red'>Logout</button></a>";
echo "</div>";

include('footer.php');

