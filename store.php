<?php
/**
 * This file consumes POST parameters sent by edit.php (i.e. when an entry is edited from the admin backend).
 * If the user is logged in, the POST data is recorded, sanitized using appropriate filter_var() configurations,
 * turned into a new Location object (using stdClass()) and then passed to alterLocation().
 * If altering the entry was successful, the user is greeted with a success message. If it wasn't, an error
 * message is displayed instead.
 *
 * If the user is not logged in, the file displays an error message and exits.
 */
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
     * all fields where users can input strings of any kind are passed through either filter_var() 
     * or htmlspecialchars() with appropriate settings for each field.
     * @see https://www.php.net/manual/en/filter.filters.sanitize.php
     */
    $db = new DB();
    $id = base64_decode($_POST['id']);
    $name = htmlspecialchars(trim($_POST["name"]), ENT_SUBSTITUTE);
    $address = htmlspecialchars(trim($_POST["address"]));
    $price_beer = filter_var($_POST["price_beer"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $price_softdrink = filter_var($_POST["price_softdrink"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $url = filter_var($_POST["url"], FILTER_SANITIZE_URL);
    $phone = htmlspecialchars(trim($_POST["phone"]), ENT_SUBSTITUTE);
    $description = htmlspecialchars(trim($_POST["description"]), ENT_SUBSTITUTE);
    $is_smokers = htmlspecialchars(trim($_POST["is_smokers"]), ENT_SUBSTITUTE);
    $is_nonsmokers = htmlspecialchars(trim($_POST["is_nonsmokers"]), ENT_SUBSTITUTE);
    $has_wifi = htmlspecialchars(trim($_POST["has_wifi"]), ENT_SUBSTITUTE);
    $category = htmlspecialchars(trim($_POST["category"]), ENT_SUBSTITUTE);

    /*
     * If the user 'forgot' to put either http:// or https:// in front of the URL given inside $url,
     * the string is prepended with https://.
     */
    if(!empty($url) && (!(substr($url, 0, strlen('http')) === 'http'))) {
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
