<?php
/**
 * This file parses the database credentials given inside 'credentials.ini', creates a connection to the database
 * using mysqli and creates a table named 'kki_locations' if it doesn't exist already.
 * This file only has to be called once under normal circumstances and can safely be deleted once the table is created.
 *
 * The fields 'has_*' and 'is_*' default to the value 2, which corresponds to 'don't know' (see 'create.php').
 */
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
        price_beer DECIMAL(3,2),
        price_softdrink DECIMAL(3,2),
        url VARCHAR(100),
        phone VARCHAR(20),
        has_food BOOLEAN NOT NULL DEFAULT 2,
        has_beer BOOLEAN NOT NULL DEFAULT 2,
        has_wifi BOOLEAN NOT NULL DEFAULT 2,
        has_cocktails BOOLEAN NOT NULL DEFAULT 2,
        has_togo BOOLEAN NOT NULL DEFAULT 2,
        is_smokers BOOLEAN NOT NULL DEFAULT 2,
        is_nonsmokers BOOLEAN NOT NULL DEFAULT 2,
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