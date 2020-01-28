<?php

class DB
{
    protected $db;
    protected $mysql_table;

    public function __construct() {
        $credentials = parse_ini_file("credentials.ini");
        /**
         * @var string $mysql_server
         * @var string $mysql_user
         * @var string $mysql_pw
         * @var string $mysql_db
         */
        extract($credentials);
        $this->db = new mysqli($mysql_server, $mysql_user, $mysql_pw, $mysql_db);
    }

    public function __destruct() {
        $this->db->close();
    }

    /**
     * returns a specific location based on its id.
     * @param $id: location id
     * @return array|null based on whether the location exists
     */
    public function getLocationById($id) {
        $query = "SELECT * from ? WHERE ID = ?;";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $this->mysql_table, $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * enables the user to insert a new location into the database.
     * @param $locationObject: data of the location in PHP object format.
     */
    public function insertLocation($locationObject) {
        // set all fields that aren't set in the object to NULL for SQL
        foreach($locationObject as $key => $value) {
            if(!isset($value))
                $value = NULL;
        }
        $query = "INSERT INTO ? (is_active, name, address, price_beer, price_softdrink, has_food, url, desc, category, last_update) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sissddisssss", $this->mysql_table, $locationObject["is_active"], $locationObject["name"], $locationObject["address"], $locationObject["price_beer"], $locationObject["price_softdrink"], $locationObject["has_food"], $locationObject["url"], $locationObject["desc"], $locationObject["category"], $locationObject["last_update"]);

    }

    public function alterLocation($locationObject) {
        // set all fields that aren't set in the object to NULL for SQL
        foreach($locationObject as $key => $value) {
            if(!isset($value))
                $value = NULL;
        }
        $query = "UPDATE ? SET is_active = ?, name = ?, address = ?, price_beer = ?, price_softdrink = ?, has_food = ?, url = ?, desc = ?, category = ?, last_update = ? WHERE ID=?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sissddissssi", $this->mysql_table, $locationObject["is_active"], $locationObject["name"], $locationObject["address"], $locationObject["price_beer"], $locationObject["price_softdrink"], $locationObject["has_food"], $locationObject["url"], $locationObject["desc"],$locationObject["category"], $locationObject["last_update"], $locationObject["id"]);
        $stmt->execute();
    }

    public function deleteLocationById($id) {
        $query = "DELETE from ? WHERE ID = ?;";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $this->mysql_table, $id);
        $stmt->execute();
    }

    public function getAllLocations() {
        $query = "SELECT * FROM ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $this->mysql_table);
        $stmt->execute();
        $result = $stmt->get_result();
        if(empty($result)) {
            throw new Exception("No results to be shown!");
        }
        return $result->fetch_assoc();
    }

    /**
     * @param $table
     * @param $filename
     * @param $delimiter
     * @return bool
     * @throws Exception
     */
    public function dumpToCSV($table, $filename, $delimiter) {
        $filepath = $storagepath . DIRECTORY_SEPARATOR . $filename;
        $query = "TABLE ? ORDER BY id INTO OUTFILE ? FIELDS TERMINATED BY ? OPTIONALLY ENCLOSED BY '\"', ESCAPED BY '\' LINES TERMINATED BY '\n';";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sss", $table, $filepath, $delimiter);
        $stmt->execute();

        // check if file was created successfully (surround call to function with try/catch)
        if(!file_exists($filepath)) {
            throw new Exception("file {$filename} does not exist in specified path.");
        }
        return true;
    }

    public function dumpToSQL($table, $filename, $delimiter) {
        // stolen from https://www.php.net/manual/de/function.str-getcsv.php#117692
        $filepath = $storagepath . DIRECTORY_SEPARATOR . $filename;
        // This method clears the entire table to avoid doubled entries. Use with caution.

    }

}

