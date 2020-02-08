<?php
require_once('DB.php');
include('header.php');
try {
    $db = new DB();
} catch(Exception $e) {
    echo "<div id='message'><p class='fail'>Couldn't create DB instance: {$e}</p></div>";
    exit();
}

// get all entries

if(!($allLocations = $db->getActiveLocations())){
    echo "<div id='message'><p class='fail'>No active locations found in the database :( <a href='create.php'>Create one?</a></p></div>";
    exit();
}

// show table for active locations
    echo "<div id='login'><a href='login.php'>Login</a></div>";
    echo "<table class='locationlist sortierbar'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th class='sortierbar'>Name<span></span></th><th class='sortierbar'>Kategorie<span></span></th><th>Adresse<span></span></th><th class='sortierbar'>Bier (€)<span></span></th><th class='sortierbar'>Softdrink (€)<span></span></th><th>URL</th><th class='sortierbar'><i class='fas fa-utensils tooltip'><span class='tooltiptext'>Gibt es Essen?</span></i></th><th class='sortierbar'><i class='fas tooltip fa-beer'><span class='tooltiptext'>Gibt es Bier?</span></i></th><th class='sortierbar'><i class='fas tooltip fa-cocktail'><span class='tooltiptext'>Gibt es Cocktails?</span></i></th><th class='sortierbar'><i class='fas tooltip fa-wifi'><span class='tooltiptext'>Gibt es WLAN?</span></i></th><th class='sortierbar'><i class='fas tooltip fa-shopping-bag'><span class='tooltiptext'>Gibt es Essen to go?</span></i></th><th class='sortierbar'><i class='fas tooltip fa-smoking'><span class='tooltiptext'>Raucher?</span></i></th><th class='sortierbar'><i class='fas tooltip fa-smoking-ban'><span class='tooltiptext'>Nichtraucher?</span></i></th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach ($allLocations as $item) {
        echo "<tr>";
        echo "<td>{$item['name']}</td>";
        $item['category'] = ucfirst($item['category']);
        echo "<td>{$item['category']}</td>";
        echo "<td>{$item['address']}</td>";
        echo "<td>{$item['price_beer']}</td>";
        echo "<td>{$item['price_softdrink']}</td>";
        echo "<td><a href='{$item['url']}'>{$item['url']}</a></td>";
        /*
         * For each of the pseudo-boolean fields, we have to check its value.
         * 0: no ('times' symbol)
         *  1: yes ('check' symbol)
         *  2: unknown('?' symbol).
         */
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
echo "</tbody>";
echo "</table>";
echo "<div id='buttons'>";
echo "<a href='create.php'><button type='button' class='blue'>Neuen Eintrag anlegen</button></a>";
echo "</div>";

include('footer.php');

