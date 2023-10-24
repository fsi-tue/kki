<?php

/**
 * Class DB
 *
 * This class provides access to the database using public functions.
 * Inside the constructor, the file 'credentials.ini' and the content is passed into variables.
 * All database statements, if possible, use $this->mysql_table to make this whole thing a bit more portable.
 */
class DB
{
    protected $db;
    protected $mysql_table;
    protected $pwhash;

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
        $this->pwhash = $backend;
    }

    /**
     * Closes the database connection.
     */
    public function __destruct() {

        $this->db->close();
    }

    /**
     * returns the password hash given inside 'credentials.ini'
     */
    public function getHash() {

        return $this->pwhash;
    }

    /**
     * Consumes the id of a database entry, selects all the fields and binds them to variables.
     * This is done via bind_result() since get_result() is not available on all platforms.
     * The variables are piped into a locationObject (using stdClass) and returned.
     * @param $id the id of the database row
     * @return stdClass the information packed into an object
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
     * @return bool was the insert statement successful?
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
     * Updates all fields of a given entry to new values passed from the locationObject.
     * @param $obj the locationObject with updated information
     * @return bool was the update statement successful?
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
     * Deletes a row from the database with a given ID.
     * @param $id the row ID
     * @return bool was the statement successful?
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
     * Fetches all entries from the table and returns them as an array.
     * The associative array from fetch_assoc() is transformed into a regular array
     * to simplify iteration when displaying the contents as HTML tables.
     * @return array|bool
     */
    public function getAllLocations() {
        $query = "SELECT * FROM {$this->mysql_table};";
        $result = $this->db->query($query);
        if(!($result->num_rows > 0)) {
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
     * Fetches all entries from the table which have the "is_active" column set to 1 and returns them as an array.
     * The associative array from fetch_assoc() is transformed into a regular array to simplify iteration
     * when displaying the contents as HTML tables.
     * @return array|bool
     */
    public function getActiveLocations() {
        $query = "SELECT * FROM {$this->mysql_table} WHERE is_active = 1;";
        $result = $this->db->query($query);
        if(!($result->num_rows > 0)) {
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
     * Dumps all entries which have the column 'is_active' set to 1 into CSV format.
     * A PHP file handle is created for output. To display the header row, an array with all
     * column names is created and written to the file handle using fputcsv.
     * If the method is called with a filename parameter (see 'dump.php'), the output is set to
     * attachment format so the browser is forced into a download. Otherwise, the output is set to plain text.
     * @param $filename additional filename (which is given to the downloaded file only)
     * @param $delimiter CSV delimiter (see 'dump.php')
     */
    public function dumpToCSV($delimiter, $filename = null) {
        // adapted from: https://phppot.com/php/php-csv-file-export-using-fputcsv/

        // create temporary file for download
        $fp = fopen('php://output', 'w');

        // Set header row for CSV file
        $header = array('name', 'address', 'price_beer', 'price_softdrink', 'url', 'phone', 'has_food', 'has_beer', 'has_wifi', 'has_cocktails', 'has_togo', 'is_smokers', 'is_nonsmokers', 'description', 'category', 'last_update');
        if(isset($filename)) {
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename=' . $filename);
        }
        else {
            header('Content-Type: text/plain');
        }
        fputcsv($fp, $header, $delimiter);

        // query DB for all rows and dump below the header row
        $query = "SELECT name, address, price_beer, price_softdrink, url, phone, has_food, has_beer, has_wifi, has_cocktails, has_togo, is_smokers, is_nonsmokers, description, category, last_update FROM {$this->mysql_table} WHERE is_active = TRUE;";
        $result = mysqli_query($this->db, $query);
        while ($row = mysqli_fetch_row($result)) {
            // we will replace the delimiter inside the row with an empty string to avoid complications with CSV export.
            // additionally, the enclosure of strings with spaces in them is set to an empty string. This is purely
            // because of parsing reasons inside of LaTeX.
            fputcsv($fp, (str_replace($delimiter, '', $row )), $delimiter, ' ');
        }
        fclose($fp);
    }

    /**
     * Set next primary key (id) to lowest value possible.
     * Without this function, this could have a value of millions with the healthcheck script.
     * MariaDB automatically set the value to max(primary_key) + 1, but this function is just a fail safe.
     * @return bool
     */
    public function setAutoIncrementToLowestValue() {
        $query = "SELECT max(id) FROM {$this->mysql_table};";
        $result = mysqli_query($this->db, $query);
        $max_id = -1;
        if (!($result->num_rows > 0)) {
            return FALSE;
        }
        while ($row = mysqli_fetch_row($result)) {
            $max_id = $row[0];
        }
        $result->free();

        $max_id++;
        $query = "ALTER TABLE {$this->mysql_table} AUTO_INCREMENT = {$max_id};";
        if(!$result = $this->db->query($query)) {
            echo "Query failed: (" . $this->db->errno . ") " . $this->db->error;
            return FALSE;
        }
        return TRUE;
    }
}
