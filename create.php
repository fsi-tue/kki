<?php
    include("header.php");
    ?>
<div id="inputform">
    <form name="newentry" action="create.php" method="POST">
        Name der Location: <input type="text" name="name">
        Adresse (Straße und Hausnummer): <input type="text" name="address">
        Preis für die Halbe (0,5l Export): <input type="number" name="price_beer" pattern="[0-9]+([\.,][0-9]+)?" step="0.01">
        Preis für großen Softdrink: <input type="number" name="price_softdrink" pattern="[0-9]+([\.,][0-9]+)?" step="0.01">
        Homepage-URL (falls vorhanden): <input type="text" name="url">
        Gibt es Essen? <input type="checkbox" name="food" value="has_food">
        Kategorie? <input type="checkbox" name="category"

        <textarea name="description" form="newentry">Beschreibungstext...</textarea>

        <input type="submit" value="Eintrag absenden"
               <span>Falls du ein Feld nicht ausfüllen kannst (z.B. den Softdrink-Preis), ist dies nicht weiter tragisch.</span>
               <span>Bitte hab Verständnis dafür, dass dein Eintrag zuerst durch einen Moderator freigeschaltet werden muss.</span>
    </form>
</div>

<?php
 // consume POST data


// default "active" value to 0 for moderation purposes
$locationObject["is_active"] = FALSE;

include("footer.php");
