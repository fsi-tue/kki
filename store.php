<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
    $address = filter_var($_POST["address"], FILTER_SANITIZE_STRING);
    $price_beer = filter_var($_POST["price_beer"], FILTER_SANITIZE_NUMBER_FLOAT);
    $price_softdrink = filter_var($_POST["price_softdrink"], FILTER_SANITIZE_NUMBER_FLOAT);
    $url = filter_var($_POST["url"], FILTER_SANITIZE_STRING);
    $phone = filter_var($_POST["phone"], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST["description"], FILTER_SANITIZE_STRING);
    $has_food = filter_var($_POST["has_food"], FILTER_SANITIZE_STRING);
    $has_togo = filter_var($_POST["has_togo"], FILTER_SANITIZE_STRING);
    $has_beer = filter_var($_POST["has_beer"], FILTER_SANITIZE_STRING);
    $is_smokers = filter_var($_POST["is_smokers"], FILTER_SANITIZE_STRING);
    $is_nonsmokers = filter_var($_POST["is_nonsmokers"], FILTER_SANITIZE_STRING);
    $has_wifi = filter_var($_POST["has_wifi"], FILTER_SANITIZE_STRING);

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

    // if a checkbox is checked client-side, its value field is submitted. If it wasn"t set, it isn"t submitted at all, hence the check if the value isset().
    $locationObject->has_food = (isset($has_food)) ? TRUE : FALSE;
    $locationObject->has_beer = (isset($has_beer)) ? TRUE : FALSE;
    $locationObject->has_togo = (isset($has_togo)) ? TRUE : FALSE;
    $locationObject->has_cocktails = (isset($has_cocktails)) ? TRUE : FALSE;
    $locationObject->is_smokers = (isset($has_food)) ? TRUE : FALSE;
    $locationObject->is_nonsmokers = (isset($has_food)) ? TRUE : FALSE;
    $locationObject->has_wifi = (isset($has_wifi)) ? TRUE : FALSE; // I regret nothing.
    $locationObject->last_update = date(Y-m-d); // I hate SQL.
    $locationObject->is_active = (isset($_POST["is_active"])) ? TRUE : FALSE;
    try {
        $db->insertLocation($locationObject);
        echo "<p class='success'>Dein Eintrag wurde erfolgreich eintragen.</p>";
    } catch (Exception $e) {
        echo "<p class='fail'>Eintragen in die Datenbank fehlgeschlagen!", $e;
    }
} else {
    exit();
}
