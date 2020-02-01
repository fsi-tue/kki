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
include('header.php');
echo "<table class='locationlist'>";
echo "<tr>";
echo "<td>aktiv?</td><td>Name</td><td>Kategorie</td><td>Adresse</td><td>Bier(€)</td><td>Softdrink(€)</td><td>URL</td><td>Stand</td><td><i class='fas fa-utensils'></i></td><td><i class='fas fa-beer'></i></td><td><i class='fas fa-wifi'></i></td><td><i class='fas fa-cocktail'></i></td><td><i class='fas fa-shopping-bag'></i></td><td><i class='fas fa-smoking'></i></td><td><i class='fas fa-smoking-ban'></i></td><td>Edit</td><td>Delete</td>";
echo "</tr>";
foreach ($allLocations as $item) {
    echo "<tr>";
    if($item['is_active'] == 1) {
        echo "<td><i class='fas fa-check'></i></td>";
    }
    if($item['is_active'] == 0) {
        echo "<td><i class='fas fa-times'></i></td>";
    }
    echo "<td>{$item['name']}</td>";
    echo "<td>{$item['category']}</td>";
    echo "<td>{$item['address']}</td>";
    echo "<td>{$item['price_beer']}</td>";
    echo "<td>{$item['price_softdrink']}</td>";
    echo "<td>{$item['url']}</td>";
    echo "<td>{$item['last_update']}</td>";
    switch ($item['has_food']) {
        case 0:
            echo "<td><i class='fas fa-times'></i></td>";
            break;
        case 1:
            echo "<td><i class='fas fa-check'></i></td>";
            break;
        case 2:
            echo "<td><i class='fas fa-question'></i></td>";
            break;
    }
    switch ($item['has_beer']) {
        case 0:
            echo "<td><i class='fas fa-times'></i></td>";
            break;
        case 1:
            echo "<td><i class='fas fa-check'></i></td>";
            break;
        case 2:
            echo "<td><i class='fas fa-question'></i></td>";
            break;
    }
    switch ($item['has_wifi']) {
        case 0:
            echo "<td><i class='fas fa-times'></i></td>";
            break;
        case 1:
            echo "<td><i class='fas fa-check'></i></td>";
            break;
        case 2:
            echo "<td><i class='fas fa-question'></i></td>";
            break;
    }
    switch ($item['has_cocktails']) {
        case 0:
            echo "<td><i class='fas fa-times'></i></td>";
            break;
        case 1:
            echo "<td><i class='fas fa-check'></i></td>";
            break;
        case 2:
            echo "<td><i class='fas fa-question'></i></td>";
            break;
    }
    switch ($item['has_togo']) {
        case 0:
            echo "<td><i class='fas fa-times'></i></td>";
            break;
        case 1:
            echo "<td><i class='fas fa-check'></i></td>";
            break;
        case 2:
            echo "<td><i class='fas fa-question'></i></td>";
            break;
    }
    switch ($item['is_smokers']) {
        case 0:
            echo "<td><i class='fas fa-times'></i></td>";
            break;
        case 1:
            echo "<td><i class='fas fa-check'></i></td>";
            break;
        case 2:
            echo "<td><i class='fas fa-question'></i></td>";
            break;
    }
    switch ($item['is_nonsmokers']) {
        case 0:
            echo "<td><i class='fas fa-times'></i></td>";
            break;
        case 1:
            echo "<td><i class='fas fa-check'></i></td>";
            break;
        case 2:
            echo "<td><i class='fas fa-question'></i></td>";
            break;
    }
    // form for submitting edit
    echo "<td>";
    $base = base64_encode($item['id']);
    echo "<form name='edit' method='POST' action='edit.php'>";
    echo "<input type='hidden' name='id' value='{$base}'>";
    echo "<input type='submit' value='Bearbeiten'>";
    echo "</form>";
    echo "</td>";
    echo "<td>";
    echo "<form name='delete' method='POST' action='delete.php'>";
    echo "<input type='hidden' name='id' value='{$base}'>";
    echo "<input type='submit' value='Löschen'>";
    echo "</form>";
    echo "</td>";
    echo "</tr>";
}
echo "</table>";
echo "<div id='buttons'>";
echo "<a href='create.php'><button type='button'>Neuen Eintrag anlegen</button></a>";
echo "</div>";

include('footer.php');

