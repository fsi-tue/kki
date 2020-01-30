<?php
    include("header.php");
    require_once('DB.php');
    require_once('locationObject.php');
    ?>
<div id="inputform">
    <span>Falls du ein Feld nicht ausfüllen kannst (z.B. den Softdrink-Preis oder ob es WLAN gibt), ist dies nicht weiter tragisch. Lasse das Feld dann einfach leer.</span>
    <span>Bitte hab Verständnis dafür, dass dein Eintrag zuerst durch einen Moderator freigeschaltet werden muss.</span>
    <form name="newentry" action="create.php" method="POST">
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
        <div class="lefty">
            <!-- replace with Y/N/? for each choice -->
            <table>
                <td>&nbsp;</td><td>Ja</td><td>Nein</td><td>kA</td>
            <tr>

            <p>Gibt es Essen? <input type="checkbox" name="has_food" value="has_food"></p>
            </tr>
                <tr>
            <p>Kann man Essen zum Mitnehmen bestellen? <input type="checkbox" name="has_togo" value="has_togo"></p>
                </tr>
                <tr>
            <p>Gibt es Bier? <input type="checkbox" name="has_beer" value="has_beer"></p>
                </tr>
            <p>Gibt es Cocktails? <input type="checkbox" name="has_cocktails" value="has_cocktails"></p>
            <p>Gibt es einen Raucherraum bzw. Raucherbereich? <input type="checkbox" name="is_smokers" value="is_smokers"></p>
            <p>Gibt es einen Nichtraucherbereich? <input type="checkbox" name="is_nonsmokers" value="is_nonsmokers"></p>
            <p>Gibt es kostenloses WLAN? <input type="checkbox" name="has_wifi" value="has_wifi"></p>
            </table>
        </div>
        <textarea name="description" cols="10" rows="5">Beschreibungstext...</textarea>
        <input type="submit" value="Eintrag absenden">
    </form>
</div>

<?php
// consume POST data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    var_dump($_POST);
    $db = new DB();
    $name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
    $address = filter_var($_POST["address"], FILTER_SANITIZE_STRING);
    $price_beer = filter_var($_POST["price_beer"], FILTER_SANITIZE_NUMBER_FLOAT);
    $price_softdrink = filter_var($_POST["price_softdrink"], FILTER_SANITIZE_NUMBER_FLOAT);
    $url = filter_var($_POST["url"], FILTER_SANITIZE_STRING);
    $phone = filter_var($_POST["phone"], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST["description"], FILTER_SANITIZE_STRING);
    //$has_food = filter_var($_POST["has_food"], FILTER_SANITIZE_STRING);
    //$has_togo = filter_var($_POST["has_togo"], FILTER_SANITIZE_STRING);
    //$has_beer = filter_var($_POST["has_beer"], FILTER_SANITIZE_STRING);
    //$is_smokers = filter_var($_POST["is_smokers"], FILTER_SANITIZE_STRING);
    //$is_nonsmokers = filter_var($_POST["is_nonsmokers"], FILTER_SANITIZE_STRING);
    //$has_wifi = filter_var($_POST["has_wifi"], FILTER_SANITIZE_STRING);

    // handle the select field
    $category = filter_var($_POST["category"]);

    // initialize new locationObject to be passed to the insert method and fill with POST data
    $locationObject = new stdClass();
    $locationObject->name = $name;
    $locationObject->address = $address;
    $locationObject->price_beer = $price_beer;
    $locationObject->price_softdrink = $price_softdrink;
    $locationObject->url = $url;
    $locationObject->phone = $phone;
    $locationObject->description = $description;
    $locationObject->category = $category;

    // if a checkbox is checked client-side, its value field is submitted. If it wasn"t set, it isn"t submitted at all, hence the check if the value isset().
    $locationObject->has_food = (isset($_POST["has_food"])) ? TRUE : FALSE;
    $locationObject->has_beer = (isset($_POST["has_beer"])) ? TRUE : FALSE;
    $locationObject->has_togo = (isset($_POST["has_togo"])) ? TRUE : FALSE;
    $locationObject->has_cocktails = (isset($_POST["has_cocktails"])) ? TRUE : FALSE;
    $locationObject->is_smokers = (isset($_POST["has_food"])) ? TRUE : FALSE;
    $locationObject->is_nonsmokers = (isset($_POST["has_food"])) ? TRUE : FALSE;
    $locationObject->has_wifi = (isset($_POST["has_wifi"])) ? TRUE : FALSE; // I regret nothing.
    $locationObject->last_update = date('Y-m-d'); // I hate SQL.
    $locationObject->is_active = FALSE;
    if ($db->insertLocation($locationObject)) {
        echo "<p class='success'>Dein Eintrag wurde erfolgreich eintragen und wird von einem Moderator geprüft.</p>";
    } else {
        echo "<p class='fail'>Eintragen in die Datenbank fehlgeschlagen!";
    }
}
include("footer.php");
