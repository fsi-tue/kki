<?php

class DB
{
    protected $db;
    protected $mysql_table;
    protected $storagepath;

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
        if (mysqli_connect_errno()) {
            throw new Exception("Couldn't connect to database! \n", mysqli_connect_error());
        }
        $this->mysql_table = $mysql_table;
        $this->storagepath = $storagepath;
    }

    public function __destruct() {
        $this->db->close();
    }

    public function getLocationById($id) {
        $query = "SELECT is_active, name, address, price_beer, price_softdrink, has_food, has_beer, has_cocktails, has_wifi, has_togo, url, description, category, last_update, phone, is_smokers, is_nonsmokers FROM {$this->mysql_table} WHERE id = ?;";
        if(!$stmt = $this->db->prepare($query)) {
            echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
        }
        $stmt->bind_param("i", $id);
        // use bind_result and create a new object with all the required fields

    }

    /**
     * enables the user to insert a new location into the database.
     * @param $locationObject: data of the location in PHP object format.
     */
    public function insertLocation($locationObject) {
        // set all fields that aren't set in the object to NULL for SQL
        //foreach($locationObject as $key => $value) {
        //    if(empty($value))
        //        $value = NULL;
        //}
        $query = "INSERT INTO {$this->mysql_table} (is_active, name, address, price_beer, price_softdrink, has_food, has_beer, has_cocktails, has_wifi, has_togo, url, description, category, last_update, phone, is_smokers, is_nonsmokers) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
        if(!$stmt = $this->db->prepare($query)) {
            echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
        }
        $stmt->bind_param("issddiiiiisssssii", $locationObject->is_active, $locationObject->name, $locationObject->address, $locationObject->price_beer, $locationObject->price_softdrink, $locationObject->has_food, $locationObject->has_beer, $locationObject->has_cocktails, $locationObject->has_wifi, $locationObject->has_togo, $locationObject->url, $locationObject->description, $locationObject->category, $locationObject->last_update, $locationObject->phone, $locationObject->is_smokers, $locationObject->is_nonsmokers);
        $result = $stmt->execute();
        if($stmt->error){
            printf("Error: %s.\n", $stmt->error);
        }
        $stmt->close();
        return $result;
    }

    public function alterLocation($locationObject) {
        // set all fields that aren't set in the object to NULL for SQL
        foreach($locationObject as $key => $value) {
            if(empty($value))
                $value = NULL;
        }
        $query = "UPDATE {$this->mysql_table} SET is_active = ?, name = ?, address = ?, price_beer = ?, price_softdrink = ?, has_food = ?, has_beer = ?, has_cocktails = ?, has_wifi = ?, has_togo = ?, url = ?, description = ?, category = ?, last_update = ?, phone = ?, is_smokers = ?, is_nonsmokers = ? WHERE id = ?;";
        if(!$stmt = $this->db->prepare($query)) {
            echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
        }
        $stmt->bind_param("issddiiiiisssssii", $this->mysql_table, $locationObject->is_active, $locationObject->name, $locationObject->address, $locationObject->price_beer, $locationObject->price_softdrink, $locationObject->has_food, $locationObject->has_beer, $locationObject->has_cocktails, $locationObject->has_wifi, $locationObject->has_togo, $locationObject->url, $locationObject->description, $locationObject->category, $locationObject->last_update, $locationObject->phone, $locationObject->is_smokers, $locationObject->is_nonsmokers, $locationObject->id);
        $result = $stmt->execute();
        if($stmt->error){
            printf("Error: %s.\n", $stmt->error);
        }
        $stmt->close();
        return $result;
    }

    public function deleteLocationById($id) {
        $query = "DELETE from {$this->mysql_table} WHERE ID = ?;";
        if(!$stmt = $this->db->prepare($query)) {
            echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
        }
        $stmt->bind_param("ss", $this->mysql_table, $id);
        $result = $stmt->execute();
        if($stmt->error){
            printf("Error: %s.\n", $stmt->error);
        }
        $stmt->close();
        return $result;
    }

    public function getAllLocations() {
        $query = "SELECT * FROM {$this->mysql_table};";
        $result = $this->db->query($query);
        if(!($result->num_rows > 0)) {
            echo "No results to be shown!";
            return false;
        } else {
            $locations = [];
            while ($row = $result->fetch_assoc()) {
                $locations[] = $row;
            }
            $result->free();
            return $locations;
        }
    }

    public function getActiveLocations() {
        $query = "SELECT * FROM {$this->mysql_table} WHERE is_active = 1;";
        $result = $this->db->query($query);
        if(!($result->num_rows > 0)) {
            echo "No results to be shown!";
            return false;
        } else {
            $locations = [];
            while ($row = $result->fetch_assoc()) {
                $locations[] = $row;
            }
            $result->free();
            return $locations;
        }
    }

    /**
     * @param $table
     * @param $filename
     * @param $delimiter
     * @return bool
     * @throws Exception
     */
    public function dumpToCSV($filename, $delimiter) {
        $filepath = $this->storagepath . DIRECTORY_SEPARATOR . $filename;
        $query = "TABLE {$this->mysql_table} ORDER BY asc INTO OUTFILE ? FIELDS TERMINATED BY ? OPTIONALLY ENCLOSED BY '\"', ESCAPED BY '\' LINES TERMINATED BY '\n';";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $filepath, $delimiter);
        $result = $stmt->execute();
        if($stmt->error){
            printf("Error: %s.\n", $stmt->error);
        }
        $stmt->close();

        // check if file was created successfully (surround call to function with try/catch)
        if(!file_exists($filepath)) {
            throw new Exception("file {$filename} does not exist in specified path.");
        }
        return true;
    }

    public function dumpToSQL($table, $filename, $delimiter) {
        // stolen from https://www.php.net/manual/de/function.str-getcsv.php#117692
        $filepath = $this->storagepath . DIRECTORY_SEPARATOR . $filename;
        // This method clears the entire table to avoid doubled entries. Use with caution.

    }
}

