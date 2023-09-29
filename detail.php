<?php
/**
 * This file displays a detailed view of an item including the description text (which is normally
 * only visible inside the Anfiheft). For the address, the Nominatim service is queried to return geo coordinates
 * for that address. These coordinates are then displayed inside an OSM mini map on the bottom of the page.
 */

session_start();
require_once('DB.php');
include('header.php');
try {
    $db = new DB();
} catch(Exception $e) {
    echo "<div id='message'><p class='fail'>Couldn't create DB instance: {$e}</p></div>";
    exit();
}

// consume GET parameter
if(empty($_GET['id'])) {
    echo "<div id='message'><p class='fail'>Es wurde kein Parameter übergeben.</a></p></div>";
    exit();
} else {
    $queryID = $_GET['id'];
}

// check if item is present in DB, fetch it if it is, throw error otherwise
if(!empty($db->getLocationById($queryID))) {
    $locationObject = $db->getLocationById($queryID);
    // did the user try to access an entry that is set as inactive?
    if(!$locationObject->is_active) {
        echo "<div id='message'><p class='fail'>Dieser Eintrag ist nicht aktiv.</a></p></div>";
        exit();
    }
} else {
    echo "<div id='message'><p class='fail'>Diese ID existiert nicht in der Datenbank :(</a></p></div>";
    exit();
}

// unpack location object info
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
$last_update = $locationObject->last_update;
$is_active = $locationObject->is_active;
$category = ucfirst($locationObject->category);

// dump standard info to user
echo <<<EOL
<div id="detail">
<span>Name der Location</span><p>$name&nbsp;($category)</p>
<span>Adresse</span><p>$address</p>
<span>Preis für die Halbe (0,5l Export)</span><p>$price_beer&nbsp;&euro;</p>
<span>Preis für großen Softdrink</span><p>$price_softdrink&nbsp;&euro;</p>
<span>Telefon:</span><p>$phone</p>
EOL;
echo "<span>Gibt es Essen?</span>";
$features = array(
    "Essen" => $has_food,
    "Essen zum Mitnehmen" => $has_togo,
    "Bier" => $has_beer,
    "Cocktails" => $has_cocktails,
    "Raucherraum" => $is_smokers,
    "Nichtraucherbereich" => $is_nonsmokers,
    "Kostenloses WLAN" => $has_wifi
);

foreach ($features as $feature => $value) {
    echo "<span>Gibt es $feature?</span>";
    switch ($value) {
        case 0:
            echo "<p><i class='fas fa-times'></i></p>";
            break;
        case 1:
            echo "<p><i class='fas fa-check'></i></p>";
            break;
        case 2:
            echo "<p><i class='fas fa-question'></i></p>";
            break;
    }
}

echo "<span>Beschreibungstext</span>";
echo "<p>" . $description . "</p>";

echo "<span>Location auf der Karte</span>";
// OSM API lookup
// set a recognized HTTP header (debug stuff)
ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0)');

$url = "https://nominatim.openstreetmap.org/search?q=" . urlencode($address) . ',' . urlencode('Tübingen') . "&limit=1&format=json";
$json = file_get_contents($url);
$data = json_decode($json, TRUE);
// does location actually exist on the map?
if(empty($data)) {
    echo "<p class='fail'>Zur angegebenen Adresse scheint keine Geolocation zu existieren. :(</p>";
} else {
    // grab lat/lon from the JSON
    $locLat = floatval($data[0]["lat"]);
    $locLon = floatval($data[0]["lon"]);

echo '<div id="mapwrapper"><div id="mapdiv"></div></div>';
echo "<script>";
echo "map = new OpenLayers.Map('mapdiv');";
echo "map.addLayer(new OpenLayers.Layer.OSM());";
echo "var lonLat = new OpenLayers.LonLat( " . $locLon . "," . $locLat . ").transform(new OpenLayers.Projection('EPSG:4326'),map.getProjectionObject());";
echo "var zoom=18;";
echo "var markers = new OpenLayers.Layer.Markers('Markers');";
echo "map.addLayer(markers);";
echo "markers.addMarker(new OpenLayers.Marker(lonLat));";
echo "map.setCenter(lonLat, zoom);";
echo "</script>";
}
echo "<span>Die angezeigten Kartendaten stammen vom Drittanbieter <a href='https://nominatim.openstreetmap.org/'>Nominatim</a> und sind ungefähre Werte. Der auf der Karte gezeigte Marker zeigt oft nicht die tatsächliche Position der Location an.</span>";
echo "<br>";
echo "<span>Stand der Daten:</span><p>$last_update</p>";
echo "<div id='buttons'>";
echo "<a href='index.php'><button type='button' class='blue'>Zurück zur Übersicht</button></a>";
echo "</div>";
echo "</div>";

include('footer.php');

