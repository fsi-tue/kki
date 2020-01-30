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
    echo "<table>";
    echo "<tr>";
    echo "<td>Name</td><td>Kategorie</td><td>Adresse</td><td>Bier(€)</td><td>Softdrink(€)</td><td>URL</td><td>Stand</td><td><i class=\"fas fa-utensils\"></i></td><td><i class=\"fas fa-beer\"></i></td><td><i class=\"fas fa-wifi\"></i></td><td><i class=\"fas fa-cocktail\"></i></td><td><i class=\"fas fa-shopping-bag\"></i></td><td><i class=\"fas fa-smoking\"></i></td><td><i class=\"fas fa-smoking-ban\"></i></td>";
    echo "</tr>";
    foreach ($allLocations as $item) {
        echo "<tr>";
        echo "<td>{$item['name']}</td>";
        echo "<td>{$item['category']}</td>";
        echo "<td>{$item['address']}</td>";
        echo "<td>{$item['price_beer']}</td>";
        echo "<td>{$item['price_softdrink']}</td>";
        echo "<td>{$item['url']}</td>";
        echo "<td>{$item['last_update']}</td>";
        if($item['has_food']) {
            echo "<td><i class=\"fas fa-check\"></i></td>";
        } else {
            echo "<td><i class=\"fas fa-times\"></i></td>";
        }
        if($item['has_beer']) {
            echo "<td><i class=\"fas fa-check\"></i></td>";
        } else {
            echo "<td><i class=\"fas fa-times\"></i></td>";
        }
        if($item['has_wifi']) {
            echo "<td><i class=\"fas fa-check\"></i></td>";
        } else {
            echo "<td><i class=\"fas fa-times\"></i></td>";
        }
        if($item['has_cocktails']) {
            echo "<td><i class=\"fas fa-check\"></i></td>";
        } else {
            echo "<td><i class=\"fas fa-times\"></i></td>";
        }
        if($item['has_togo']) {
            echo "<td><i class=\"fas fa-check\"></i></td>";
        } else {
            echo "<td><i class=\"fas fa-times\"></i></td>";
        }
        if($item['is_smokers']) {
            echo "<td><i class=\"fas fa-check\"></i></td>";
        } else {
            echo "<td><i class=\"fas fa-times\"></i></td>";
        }
        if($item['is_nonsmokers']) {
            echo "<td><i class=\"fas fa-check\"></i></td>";
        } else {
            echo "<td><i class=\"fas fa-times\"></i></td>";
        }
        echo "</tr>";
}
echo "</table>";
echo "<div id='buttons'>";
echo "<a href='create.php'><button type='button'>Neuen Eintrag anlegen</button></a>";
echo "</div>";

include('footer.php');

