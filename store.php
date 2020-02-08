<?php
session_start();

require_once('DB.php');
include('header.php');

// check if the user is logged in
if(!isset($_SESSION['userid'])) {
    echo "<div id='message'><p class='fail'>Bitte zuerst <a href='login.php'>einloggen!</a></p></div>";
    exit();
}
if(isset($_SESSION['userid']) && ($_SESSION['userid'] != 'f0a8ed5d51a3229f154450fa55dac748')) {
    echo "<div id='message'><p class='fail'>Netter Versuch!</a></p></div>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    /*
     * initialize new DB opject and grab POST parameters given by the form.
     * all fields where users can input strings of any kind are passed through filter_var() with appropriate settings
     * for each field.
     */
    $db = new DB();
    $id = base64_decode($_POST['id']);
    $name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
    $address = filter_var($_POST["address"], FILTER_SANITIZE_STRING);
    $price_beer = filter_var($_POST["price_beer"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $price_softdrink = filter_var($_POST["price_softdrink"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $url = filter_var($_POST["url"], FILTER_SANITIZE_URL);
    $phone = filter_var($_POST["phone"], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST["description"], FILTER_SANITIZE_STRING);
    $is_smokers = filter_var($_POST["is_smokers"], FILTER_SANITIZE_STRING);
    $is_nonsmokers = filter_var($_POST["is_nonsmokers"], FILTER_SANITIZE_STRING);
    $has_wifi = filter_var($_POST["has_wifi"], FILTER_SANITIZE_STRING);
    $category = filter_var($_POST["category"], FILTER_SANITIZE_STRING);

    /*
     * If the user 'forgot' to put either http:// or https:// in front of the URL given inside $url,
     * the string is prepended with https://.
     */
    if(isset($url) && (!(substr($url, 0, strlen('http')) === 'http'))) {
        $url = 'https://' . $url;
    }

    // initialize new locationObject to be passed to the insert method and fill with sanitized POST data
    $locationObject = new stdClass();
    $locationObject->id = $id;
    $locationObject->name = $name;
    $locationObject->address = $address;
    $locationObject->price_beer = $price_beer;
    $locationObject->price_softdrink = $price_softdrink;
    $locationObject->url = $url;
    $locationObject->phone = $phone;
    $locationObject->description = $description;
    $locationObject->category = $category;
    $locationObject->has_food = $_POST["has_food"];
    $locationObject->has_beer = $_POST["has_beer"];
    $locationObject->has_togo = $_POST["has_togo"];
    $locationObject->has_cocktails = $_POST["has_cocktails"];
    $locationObject->is_smokers = $_POST["is_smokers"];
    $locationObject->is_nonsmokers = $_POST["is_nonsmokers"];
    $locationObject->has_wifi = $_POST["has_wifi"];
    $locationObject->last_update = date('Y-m-d'); // I hate SQL.
    $locationObject->is_active = (isset($_POST["is_active"])) ? TRUE : FALSE;
    try {
        $db->alterLocation($locationObject);
        echo "<div id='message'><p class='success'>Änderungen wurden erfolgreich eingetragen.</p><br><a href='index_admin.php'>zurück zur Übersicht</a></div>";
    } catch (Exception $e) {
        echo "<div id='message'><p class='fail'>Eintragen in die Datenbank fehlgeschlagen!</p><br><a href='index_admin.php'>zurück zur Übersicht</a></div>";
    }
} else {
    exit();
}
