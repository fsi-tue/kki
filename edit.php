<?php
require_once('DB.php');
include("header.php");

$db = new DB();
/*
 * Since I couldn't be bothered to use sessions just yet, the parameter 'id' needed to identify the database record
 * is passed as a POST parameter. This file recieves the parameter as a Base64 encoded string (see index_admin.php),
 * which is then decoded. I'm fully aware that this does not improve security in any way whatsoever.
 */

//  consume ID of location to be edited and fetch corresponding object
$objectID = base64_decode($_POST['id']);
$locationObject = new stdClass();
$locationObject = $db->getLocationById($objectID);

/*
 * To fill in the values of the database record inside the form below, we have to put each individual object value
 * inside a new variable. We can't access the object directly because of restrictions of the Heredoc format used.
 */
$name = $locationObject->name;
$address = $locationObject->address;
$price_beer = $locationObject->price_beer;
$price_softdrink = $locationObject->price_softdrink;
$url = $locationObject->url;
$phone = $locationObject->phone;
$description = $locationObject->description;
$has_food = $locationObject->has_food;
$has_beer = $locationObject->has_beer;
$has_togo = $locationObject->has_togo;
$has_cocktails = $locationObject->has_cocktails;
$is_smokers = $locationObject->is_smokers;
$is_nonsmokers = $locationObject->is_nonsmokers;
$has_wifi = $locationObject->has_wifi;
$last_update = $locationObject->last_update = date('Y-m-d'); // I hate SQL.
$is_active = $locationObject->is_active;
$category = $locationObject->category;

/*
 * (Pseudo-) Boolean values can't be displayed directly inside HTML.
 * If a dropdown menu should display a specific option per default, this option has to have the attribute 'selected'
 * added to it. The same principle applies to radio buttons with their 'checked' attribute.
 * Hence we're creating a new variable for each and every selection and radio button. If the item is supposed
 * to be selected, the variable outputs 'selected' or 'checked' depending on the type of HTML element.
 * if it's not supposed to be selected, the variable outputs an empty string, which is ignored inside HTML.
 */
$category_bar = ($category == 'bar') ? 'selected' : '';
$category_fastfood = ($category == 'fastfood') ? 'selected' : '';
$category_restaurant = ($category == 'restaurant') ? 'selected' : '';
$category_club = ($category == 'club') ? 'selected' : '';
$radio_food_0 = ($has_food == 0) ? 'checked' : '';
$radio_food_1 = ($has_food == 1) ? 'checked' : '';
$radio_food_2 = ($has_food == 2) ? 'checked' : '';
$radio_togo_0 = ($has_togo == 0) ? 'checked' : '';
$radio_togo_1 = ($has_togo == 1) ? 'checked' : '';
$radio_togo_2 = ($has_togo == 2) ? 'checked' : '';
$radio_beer_0 = ($has_beer == 0) ? 'checked' : '';
$radio_beer_1 = ($has_beer == 1) ? 'checked' : '';
$radio_beer_2 = ($has_beer == 2) ? 'checked' : '';
$radio_cocktails_0 = ($has_cocktails == 0) ? 'checked' : '';
$radio_cocktails_1 = ($has_cocktails== 1) ? 'checked' : '';
$radio_cocktails_2 = ($has_cocktails == 2) ? 'checked' : '';
$radio_smokers_0 = ($is_smokers == 0) ? 'checked' : '';
$radio_smokers_1 = ($is_smokers == 1) ? 'checked' : '';
$radio_smokers_2 = ($is_smokers == 2) ? 'checked' : '';
$radio_nonsmokers_0 = ($is_nonsmokers == 0) ? 'checked' : '';
$radio_nonsmokers_1 = ($is_nonsmokers == 1) ? 'checked' : '';
$radio_nonsmokers_2 = ($is_nonsmokers == 2) ? 'checked' : '';
$radio_wifi_0 = ($has_wifi == 0) ? 'checked' : '';
$radio_wifi_1 = ($has_wifi == 1) ? 'checked' : '';
$radio_wifi_2 = ($has_wifi == 2) ? 'checked' : '';
$checkbox_is_active = ($is_active) ? 'checked' : '';
$base = base64_encode($objectID);

// fill form with pre-defined values from $locationObject
echo <<<EOL
<div id="inputform">
    <form name="editentry" action="store.php" method="POST">
        <input type="hidden" name="id" value="{$base}">
        <p>Name der Location: <input type="text" name="name" value="$name" required></p>
        <p>Adresse (Straße und Hausnummer): <input type="text" name="address" value="$address" required></p>
        <p>Kategorie: <select name="category" required>
                <option value="none">--------</option>
                <option value="bar" $category_bar>Bar / Kneipe</option>
                <option value="fastfood" $category_fastfood >Fastfood / Döner und Co.</option>
                <option value="restaurant" $category_restaurant>Restaurant</option>
                <option value="club" $category_club>Club / Disco</option> <!-- disco disco party party! -->
            </select></p>
        <p>Preis für die Halbe (0,5l Export): <input type="number" name="price_beer" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" value="$price_beer"></p>
        <p>Preis für großen Softdrink: <input type="number" name="price_softdrink" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" value="$price_softdrink"></p>
        <p>Homepage-URL (falls vorhanden): <input type="text" name="url" value="$url"></p>
        <p>Telefonnummer (falls vorhanden): <input type="text" name="phone" value="$phone"></p>
            <table>
                <td>&nbsp;</td><td>Ja</td><td>Nein</td><td>kA</td>
            <tr>
                <td>Gibt es Essen?</td>
                <td><input type="radio" name="has_food" value="1" $radio_food_1></td>
                <td><input type="radio" name="has_food" value="0" $radio_food_0></td>
                <td><input type="radio" name="has_food" value="2"$radio_food_2></td>
            </tr>
                <tr>
                    <td>Kann man Essen zum Mitnehmen bestellen?</td>
                    <td><input type="radio" name="has_togo" value="1" $radio_togo_1></td>
                    <td><input type="radio" name="has_togo" value="0" $radio_togo_0></td>
                    <td><input type="radio" name="has_togo" value="2" $radio_togo_2></td>
                </tr>
                <tr>
                    <td>Gibt es Bier?</td>
                    <td><input type="radio" name="has_beer" value="1" $radio_beer_1></td>
                    <td><input type="radio" name="has_beer" value="0" $radio_beer_0></td>
                    <td><input type="radio" name="has_beer" value="2" $radio_beer_2></td>
                </tr>
                <tr>
                    <td>Gibt es Cocktails?</td>
                    <td><input type="radio" name="has_cocktails" value="1" $radio_cocktails_1</td>
                    <td><input type="radio" name="has_cocktails" value="0" $radio_cocktails_0></td>
                    <td><input type="radio" name="has_cocktails" value="2" $radio_cocktails_2></td>
                </tr>
                <tr>
                    <td>Gibt es einen Raucherraum bzw. Raucherbereich?</td>
                    <td><input type="radio" name="is_smokers" value="1" $radio_smokers_1></td>
                    <td><input type="radio" name="is_smokers" value="0" $radio_smokers_0></td>
                    <td><input type="radio" name="is_smokers" value="2" $radio_smokers_2></td>
                </tr>
                <tr>
                    <td>Gibt es einen Nichtraucherbereich?</td>
                    <td><input type="radio" name="is_nonsmokers" value="1" $radio_nonsmokers_1></td>
                    <td><input type="radio" name="is_nonsmokers" value="0" $radio_nonsmokers_0></td>
                    <td><input type="radio" name="is_nonsmokers" value="2" $radio_nonsmokers_2></td>
                </tr>
                <tr>
                    <td>Gibt es kostenloses WLAN?</td>
                    <td><input type="radio" name="has_wifi" value="1" $radio_wifi_1></td>
                    <td><input type="radio" name="has_wifi" value="0" $radio_wifi_0></td>
                    <td><input type="radio" name="has_wifi" value="2" $radio_wifi_2></td>
                </tr>
            </table>
        <textarea name="description" cols="10" rows="5">$description</textarea>
            <p>Eintrag aktiv <input type="checkbox" name="is_active" value="is_active" $checkbox_is_active></p>

        <input type="submit" value="Eintrag absenden">
    </form>
</div>
EOL;

include('footer.php');
