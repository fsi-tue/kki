<?php
require_once('DB.php');
try {
    $db = new DB();
} catch(Exception $e) {
    echo "Couldn't create DB instance: {$e}";
}

// get all entries

if(!($allLocations = $db->getActiveLocations())){
    echo "No active locations found in the database :(";
    exit();
}

// show table for active locations
include('header.php');
    echo "<table class='locationlist'>";
    echo "<tr>";
    echo "<td>Name</td><td>Kategorie</td><td>Adresse</td><td>Bier(€)</td><td>Softdrink(€)</td><td>URL</td><td><i class='fas fa-utensils'></i></td><td><i class='fas fa-beer'></i></td><td><i class='fas fa-cocktail'></i></td><td><i class='fas fa-wifi'></i></td><td><i class='fas fa-shopping-bag'></i></td><td><i class='fas fa-smoking'></i></td><td><i class='fas fa-smoking-ban'></i></td>";
    echo "</tr>";
    foreach ($allLocations as $item) {
        echo "<tr>";
        echo "<td>{$item['name']}</td>";
        echo "<td>{$item['category']}</td>";
        echo "<td>{$item['address']}</td>";
        echo "<td>{$item['price_beer']}</td>";
        echo "<td>{$item['price_softdrink']}</td>";
        echo "<td><a href='{$item['url']}'>{$item['url']}</a></td>";
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
        echo "</tr>";
}
echo "</table>";
echo "<div id='buttons'>";
echo "<a href='create.php'><button type='button'>Neuen Eintrag anlegen</button></a>";
echo "</div>";

include('footer.php');

