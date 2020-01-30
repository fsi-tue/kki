<?php
$credentials = parse_ini_file("credentials.ini");
/**
 * @var string $mysql_server
 * @var string $mysql_user
 * @var string $mysql_pw
 * @var string $mysql_db
 */
extract($credentials);
$db = new mysqli($mysql_server, $mysql_user, $mysql_pw, $mysql_db);
if (mysqli_connect_errno()) {
    printf("Could not connect to MySQL databse: %s\n", mysqli_connect_error());
    exit();
}

$query = "SELECT ID FROM kki_locations";
$result = mysqli_query($db, $query);
if(empty($result)) {
    $query = "CREATE TABLE IF NOT EXISTS kki_locations (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        is_active BOOLEAN,
        name VARCHAR(64),
        address TEXT,
        price_beer FLOAT,
        price_softdrink FLOAT,
        url VARCHAR(100),
        phone VARCHAR(20),
        has_food BOOLEAN,
        has_beer BOOLEAN,
        has_wifi BOOLEAN,
        has_cocktails BOOLEAN,
        has_togo BOOLEAN,
        is_smokers BOOLEAN,
        is_nonsmokers BOOLEAN,
        description TEXT,
        category ENUM('bar', 'fastfood', 'restaurant', 'club') NOT NULL,
        last_update DATE
        );";
    if (mysqli_query($db, $query)) {
        echo "Tabelle wurde erfolgreich erstellt.";
    }
    else {
        echo "Fehler: " . $query . "<br>" . mysqli_error($db);
    }
} else {
    echo "Tabelle existiert bereits. Abbruch.";
}

mysqli_close($db);