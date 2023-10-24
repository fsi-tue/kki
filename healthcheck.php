<?php

// The script shall only be run from the command line
if (php_sapi_name() !== 'cli') {
    http_response_code(400);
    echo('This script can only be run from the command line.');
    exit(1);
}

// Include all required DB functions
require('DB.php');

$db = new DB();

// Name of the Healthcheck row entry
$healtheck_entry_name = "healthcheckTestEntry";

// initialize new healthcheck locationObject to be passed to the insert method and fill with test data
$locationObject = new stdClass();
$locationObject->name = $healtheck_entry_name;
$locationObject->address = "Musterstraße 1, 1234 Musterstadt";
$locationObject->url = "https://www.example.com";
$locationObject->phone = "049123456789";
$locationObject->description = "Healthcheck test entry";
$locationObject->category = "Bar";
$locationObject->price_beer = 0;
$locationObject->price_softdrink = 0;
$locationObject->has_food = 2;
$locationObject->has_beer = 2;
$locationObject->has_togo = 2;
$locationObject->has_cocktails = 2;
$locationObject->is_smokers = 2;
$locationObject->is_nonsmokers = 2;
$locationObject->has_wifi = 2;
$locationObject->last_update = date('Y-m-d');
$locationObject->is_active = FALSE;

// Create a new record in the database using insertLocation
if (!$db->insertLocation($locationObject)) {
    echo("Could not insert table!");
    exit(1);
}

// Get all locations from the database
$allLocations = $db->getAllLocations();

if (!$allLocations) {
    echo("Could not get all locations!");
    exit(1);
}


// Get the location id of the healthcheck entry
$locationID = -1;

foreach ($allLocations as $location) {
    // Access the data within each $location and check for our entry
    if ($location['name'] == $healtheck_entry_name){
        $locationID = $location['id'];
    }
    break;
}

// Delete the healthcheck entry
if (!$db->deleteLocationById($locationID)) {
    echo("Could not delete location!");
    exit(1);
}

// If all runs through the healthcheck was successful
echo("Healthcheck successful!");
exit(0);