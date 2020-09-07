<?php
/**
 * This file displays an HTML form in which users can supply new entries to the database.
 * If the file is called via GET, it displays the form. If it's called via POST, it records the POST
 * data passed via the form and each form field is turned into an attribute for a new LocationObject based on stdClass().
 * This locationObject is then inserted into the DB using insertLocation().
 *
 * Entries supplied via this form default to "not active" for moderation purposes and have to be set active
 * via the admin backend (index_admin.php).
 */
include("header.php");
require_once('DB.php');

echo <<<EOL
<div id="inputform">
    <span>Falls du ein Feld nicht ausfüllen kannst, ist dies nicht weiter tragisch. Lasse das Feld dann einfach leer.</span>
    <span>Bitte hab Verständnis dafür, dass dein Eintrag zuerst durch einen Moderator freigeschaltet werden muss.</span>
    <form name="newentry" action="create.php" method="POST" accept-charset="UTF-8">
        <p>Name der Location: <input type="text" name="name" required></p>
        <p>Adresse (Straße und Hausnummer): <input type="text" name="address" required></p>
        <p>Kategorie: <select name="category" id="category" required>
                <option value="none">--------</option>
                <option value="bar">Bar / Kneipe</option>
                <option value="fastfood">Fastfood / Döner und Co.</option>
                <option value="restaurant">Restaurant</option>
                <option value="club">Club / Disco</option> <!-- disco disco party party! -->
            </select></p>
        <p>Preis für die Halbe (0,5l Export): <input type="number" name="price_beer" pattern="[0-9]+([\.,][0-9]+)?" step="0.01"></p>
        <p>Preis für großen Softdrink: <input type="number" name="price_softdrink" pattern="[0-9]+([\.,][0-9]+)?" step="0.01"></p>
        <p>Homepage-URL (falls vorhanden): <input type="text" name="url"></p>
        <p>Telefonnummer (falls vorhanden): <input type="text" name="phone"></p>
            <!-- replace with Y/N/? for each choice -->
            Du kannst die folgenden Fragen mit (Ja/Nein/weiß ich nicht) beantworten:
            <table>
                <td>&nbsp;</td><td>Ja</td><td>Nein</td><td>kA</td>
            <tr>
                <td>Gibt es Essen?</td>
                <td><input type="radio" name="has_food" value="1"></td>
                <td><input type="radio" name="has_food" value="0"></td>
                <td><input type="radio" name="has_food" value="2" checked></td>
            </tr>
                <tr>
                    <td>Kann man Essen zum Mitnehmen bestellen?</td>
                    <td><input type="radio" name="has_togo" value="1"></td>
                    <td><input type="radio" name="has_togo" value="0"></td>
                    <td><input type="radio" name="has_togo" value="2" checked></td>
                </tr>
                <tr>
                    <td>Gibt es Bier?</td>
                    <td><input type="radio" name="has_beer" value="1"></td>
                    <td><input type="radio" name="has_beer" value="0"></td>
                    <td><input type="radio" name="has_beer" value="2" checked></td>
                </tr>
                <tr>
                    <td>Gibt es Cocktails?</td>
                    <td><input type="radio" name="has_cocktails" value="1"></td>
                    <td><input type="radio" name="has_cocktails" value="0"></td>
                    <td><input type="radio" name="has_cocktails" value="2" checked></td>
                </tr>
                <tr>
                    <td>Gibt es einen Raucherraum bzw. Raucherbereich?</td>
                    <td><input type="radio" name="is_smokers" value="1"></td>
                    <td><input type="radio" name="is_smokers" value="0"></td>
                    <td><input type="radio" name="is_smokers" value="2" checked></td>
                </tr>
                <tr>
                    <td>Gibt es einen Nichtraucherbereich?</td>
                    <td><input type="radio" name="is_nonsmokers" value="1"></td>
                    <td><input type="radio" name="is_nonsmokers" value="0"></td>
                    <td><input type="radio" name="is_nonsmokers" value="2" checked></td>
                </tr>
                <tr>
                    <td>Gibt es kostenloses WLAN?</td>
                    <td><input type="radio" name="has_wifi" value="1"></td>
                    <td><input type="radio" name="has_wifi" value="0"></td>
                    <td><input type="radio" name="has_wifi" value="2" checked></td>
                </tr>
            </table>
        <textarea name="description" cols="10" rows="5" placeholder="Beschreibungstext der Location"></textarea>
        <input type="submit" value="Eintrag absenden">
    </form>
</div>
EOL;
// consume POST data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    /*
     * initialize new DB opject and grab POST parameters given by the form.
     * all fields where users can input strings of any kind are passed through filter_var() with appropriate settings
     * for each field.
     */
    $db = new DB();
    $name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
    $address = filter_var($_POST["address"], FILTER_SANITIZE_STRING);
    $price_beer = filter_var($_POST["price_beer"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $price_softdrink = filter_var($_POST["price_softdrink"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $url = filter_var($_POST["url"], FILTER_SANITIZE_URL);
    $phone = filter_var($_POST["phone"], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST["description"], FILTER_SANITIZE_STRING);
    $category = filter_var($_POST["category"], FILTER_SANITIZE_STRING);
    /*
     * If the user 'forgot' to put either http:// or https:// in front of the URL given inside $url,
     * the string is prepended with https://.
     */
    if(!empty($url) && (!(substr($url, 0, strlen('http')) === 'http'))) {
        $url = 'https://' . $url;
    }

    // initialize new locationObject to be passed to the insert method and fill with POST data
    $locationObject = new stdClass();
    $locationObject->name = $name;
    $locationObject->address = $address;
    $locationObject->url = $url;
    $locationObject->phone = $phone;
    $locationObject->description = $description;
    $locationObject->category = $category;
    $locationObject->price_beer = $price_beer;
    $locationObject->price_softdrink = $price_softdrink;
    $locationObject->has_food = $_POST["has_food"];
    $locationObject->has_beer = $_POST["has_beer"];
    $locationObject->has_togo = $_POST["has_togo"];
    $locationObject->has_cocktails = $_POST["has_cocktails"];
    $locationObject->is_smokers = $_POST["is_smokers"];
    $locationObject->is_nonsmokers = $_POST["is_nonsmokers"];
    $locationObject->has_wifi = $_POST["has_wifi"];
    $locationObject->last_update = date('Y-m-d'); // I hate SQL.
    /*
     * If an entry is first submitted, it defaults to "not active" to avoid drunk vandalism of any sort.
     * Entries have to be activated first (using edit.php) before they are shown inside index.php.
     */
    $locationObject->is_active = FALSE;
    // create a new record in the database using insertLocation
    if ($db->insertLocation($locationObject)) {
        echo "<div id='message'><p class='success'>Dein Eintrag wurde erfolgreich eintragen und wird von einem Moderator geprüft.</p></div>";
    } else {
        echo "<div id='message'><p class='fail'>Eintragen in die Datenbank fehlgeschlagen!</p></div>";
    }
}
include("footer.php");
