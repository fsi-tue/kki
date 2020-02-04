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

    public function getDumpDir() {
        return $this->storagepath;
    }

    /**
     * @param $id
     * @return stdClass
     */
    public function getLocationById($id) {
        $query = "SELECT id, is_active, name, address, price_beer, price_softdrink, url, phone, has_food, has_beer, has_wifi, has_cocktails, has_togo, is_smokers, is_nonsmokers, description, category, last_update FROM {$this->mysql_table} WHERE id = ?;";
        if(!$stmt = $this->db->prepare($query)) {
            echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        // bind result to variables... thanks PHP developers.
        $stmt->bind_result($id, $is_active, $name, $address, $price_beer, $price_softdrink, $url, $phone, $has_food, $has_beer, $has_wifi, $has_cocktails, $has_togo, $is_smokers, $is_nonsmokers, $description, $category, $last_update);
        $stmt->fetch();

        // create a new object with all the variables
        $locationObject = new stdClass();
        $locationObject->id = $id;
        $locationObject->is_active = $is_active;
        $locationObject->name = $name;
        $locationObject->address = $address;
        $locationObject->price_beer = $price_beer;
        $locationObject->price_softdrink = $price_softdrink;
        $locationObject->url = $url;
        $locationObject->phone = $phone;
        $locationObject->has_food = $has_food;
        $locationObject->has_beer = $has_beer;
        $locationObject->has_wifi = $has_wifi;
        $locationObject->has_cocktails = $has_cocktails;
        $locationObject->has_togo = $has_togo;
        $locationObject->is_smokers = $is_smokers;
        $locationObject->is_nonsmokers = $is_nonsmokers;
        $locationObject->description = $description;
        $locationObject->category = $category;
        $locationObject->last_update = $last_update;
        return $locationObject;
    }

    /**
     * enables the user to insert a new location into the database.
     * @param $locationObject : data of the location in PHP object format.
     * @return bool
     */
    public function insertLocation($obj) {
        $query = "INSERT INTO {$this->mysql_table} (is_active, name, address, price_beer, price_softdrink, url, phone, has_food, has_beer, has_wifi, has_cocktails, has_togo, is_smokers, is_nonsmokers, description, category, last_update) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
        if(!$stmt = $this->db->prepare($query)) {
            echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
        }
        $stmt->bind_param("issddssiiiiiiisss", $obj->is_active, $obj->name, $obj->address, $obj->price_beer, $obj->price_softdrink, $obj->url, $obj->phone, $obj->has_food, $obj->has_beer, $obj->has_wifi, $obj->has_cocktails, $obj->has_togo, $obj->is_smokers, $obj->is_nonsmokers, $obj->description, $obj->category, $obj->last_update);
        $result = $stmt->execute();
        if($stmt->error){
            printf("Error: %s.\n", $stmt->error);
        }
        $stmt->close();
        return $result;
    }

    /**
     * @param $obj
     * @return bool
     */
    public function alterLocation($obj) {
        // set all fields that aren't set in the object to NULL for SQL
        $query = "UPDATE {$this->mysql_table} SET is_active = ?, name = ?, address = ?, price_beer = ?, price_softdrink = ?, url = ?, phone = ?, has_food = ?, has_beer = ?, has_wifi = ?, has_cocktails = ?, has_togo = ?, is_smokers = ?, is_nonsmokers = ?, description = ?, category = ?, last_update = ? WHERE id = ?;";
        if(!$stmt = $this->db->prepare($query)) {
            echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
        }
        $stmt->bind_param("issddssiiiiiiisssi", $obj->is_active, $obj->name, $obj->address, $obj->price_beer, $obj->price_softdrink, $obj->url, $obj->phone, $obj->has_food, $obj->has_beer, $obj->has_wifi, $obj->has_cocktails, $obj->has_togo, $obj->is_smokers, $obj->is_nonsmokers, $obj->description, $obj->category, $obj->last_update, $obj->id);
        $result = $stmt->execute();
        if($stmt->error){
            printf("Error: %s.\n", $stmt->error);
        }
        $stmt->close();
        return $result;
    }

    /**
     * @param $id
     * @return bool
     */
    public function deleteLocationById($id) {
        $query = "DELETE from {$this->mysql_table} WHERE ID = ?;";
        if(!$stmt = $this->db->prepare($query)) {
            echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
        }
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        if($stmt->error){
            printf("Error: %s.\n", $stmt->error);
        }
        $stmt->close();
        return $result;
    }

    /**
     * @return array|bool
     */
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

    /**
     * @return array|bool
     */
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
     * @param $filename
     * @param $delimiter
     */
    public function dumpToCSV($delimiter, $filename = null) {
        // adapted from: https://phppot.com/php/php-csv-file-export-using-fputcsv/

        // create temporary file for download
        $fp = fopen('php://output', 'w');

        // Set header row for CSV file
        $header = array('is_active', 'name', 'address', 'price_beer', 'price_softdrink', 'url', 'phone', 'has_food', 'has_beer', 'has_wifi', 'has_cocktails', 'has_togo', 'is_smokers', 'is_nonsmokers', 'description', 'category', 'last_update');
        if(isset($filename)) {
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename=' . $filename);
        }
        else {
            header('Content-Type: text/plain');
        }
        fputcsv($fp, $header, $delimiter);

        // query DB for all rows and dump below the header row
        $query = "SELECT is_active, name, address, price_beer, price_softdrink, url, phone, has_food, has_beer, has_wifi, has_cocktails, has_togo, is_smokers, is_nonsmokers, description, category, last_update FROM {$this->mysql_table};";
        $result = mysqli_query($this->db, $query);
        while ($row = mysqli_fetch_row($result)) {
            // we will replace the delimiter inside the row with the escaped version of it.
            fputcsv($fp, (str_replace($delimiter, '', $row )), $delimiter);
        }
        fclose($fp);
    }
}

