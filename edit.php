<?php
require_once('DB.php');
require_once('locationObject.php');
include("header.php");

$db = new DB();
$loc = new locationObject();

//  consume ID of location to be edited and fetch corresponding object
$objectID = $_POST['id'];
$locationObject = $db->getLocationById($objectID);

$name = $locationObject['name'];
$address = $locationObject['address'];
$price_beer = $locationObject['price_beer'];
$price_softdrink = $locationObject['price_softdrink'];
$url = $locationObject['url'];
$phone = $locationObject['phone'];
$description = $locationObject['description'];
$has_food = $locationObject['has_food'];
$has_beer = $locationObject['has_beer'];
$has_togo = $locationObject['has_togo'];
$has_cocktails = $locationObject['has_cocktails'];
$is_smokers = $locationObject['is_smokers'];
$is_nonsmokers = $locationObject['is_nonsmokers'];
$has_wifi = $locationObject['has_wifi'];
$last_update = $locationObject['last_update'] = date(Y-m-d); // I hate SQL.
$is_active = $locationObject["is_active"];
$category = $locationObject["category"];

// handling of select fields and checkboxes (beware, hacky af)
$category_bar = ($category == 'bar') ? 'selected' : '';
$category_fastfood = ($category == 'fastfood') ? 'selected' : '';
$category_restaurant = ($category == 'restaurant') ? 'selected' : '';
$category_club = ($category == 'club') ? 'selected' : '';
$checkbox_has_food = ($has_food) ? 'checked' : '';
$checkbox_has_beer = ($has_beer) ? 'checked' : '';
$checkbox_has_togo = ($has_togo) ? 'checked' : '';
$checkbox_has_cocktails = ($has_cocktails) ? 'checked' : '';
$checkbox_is_smokers = ($is_smokers) ? 'checked' : '';
$checkbox_is_nonsmokers = ($is_nonsmokers) ? 'checked' : '';
$checkbox_has_wifi = ($has_wifi) ? 'checked' : '';
$checkbox_is_active = ($is_active) ? 'checked' : '';

// fill form with pre-defined values from $locationObject
echo <<<EOL
<div id="inputform">
    <form name="editentry" action="store.php" method="POST">
        <p>Name der Location: <input type="text" name="name" value="$name" required></p>
        <p>Adresse (Straße und Hausnummer): <input type="text" name="address" value="$address" required></p>
        <p>Kategorie: <select id="category" required>
EOL;
echo <<<EOL
                <option value="none">--------</option>
                <option value="bar" $category_bar>Bar / Kneipe</option>
                <option value="fastfood" $category_fastfood >Fastfood / Döner und Co.</option>
                <option value="restaurant" $category_restaurant>Restaurant</option>
                <option value="club" $category_bar>Club / Disco</option> <!-- disco disco party party! -->
            </select></p>
        <p>Preis für die Halbe (0,5l Export): <input type="number" name="price_beer" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" value="$price_beer"></p>
        <p>Preis für großen Softdrink: <input type="number" name="price_softdrink" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" value="$price_softdrink"></p>
        <p>Homepage-URL (falls vorhanden): <input type="text" name="url" value="$url"></p>
        <p>Telefonnummer (falls vorhanden): <input type="text" name="phone" value="$phone"></p>
        <div class="lefty">
            <p>Gibt es Essen? <input type="checkbox" name="has_food" value="has_food" $checkbox_has_food></p>
            <p>Kann man Essen zum Mitnehmen bestellen? <input type="checkbox" name="has_togo" value="has_togo" $checkbox_has_togo></p>
            <p>Gibt es Bier? <input type="checkbox" name="has_beer" value="has_beer" $checkbox_has_beer></p>
            <p>Gibt es Cocktails? <input type="checkbox" name="has_cocktails" value="has_cocktails" $checkbox_has_cocktails></p>
            <p>Gibt es einen Raucherraum bzw. Raucherbereich? <input type="checkbox" name="is_smokers" value="is_smokers" $checkbox_is_smokers></p>
            <p>Gibt es einen Nichtraucherbereich? <input type="checkbox" name="is_nonsmokers" value="is_nonsmokers" $checkbox_is_nonsmokers></p>
            <p>Gibt es kostenloses WLAN? <input type="checkbox" name="has_wifi" value="has_wifi" $checkbox_has_wifi></p>
        </div>
        <textarea name="description" cols="10" rows="5" form="editentry">$description</textarea>
            <p>Eintrag aktiv <input type="checkbox" name="is_active" value="is_active" $checkbox_is_active></p>

        <input type="submit" value="Eintrag absenden">
    </form>
</div>
EOL;
