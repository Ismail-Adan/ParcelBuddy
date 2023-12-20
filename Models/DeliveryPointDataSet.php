<?php

require_once ('Models/Database.php');
require_once ('Models/DeliveryPointData.php');

class DeliveryPointDataSet {
    protected $_dbHandle, $_dbInstance;
        
    public function __construct() {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }
    /*
    * Function to get the username associated with a deliverer so a manager/admin
    * can assign deliveries to them, or else the admin will just see numbers.
    */
    public function fetchDeliverers() {
        $sqlQuery = 'SELECT DISTINCT deliverer, username FROM delivery_point JOIN delivery_users ON delivery_point.deliverer = delivery_users.userid';

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = [
                'deliverer' => $row['deliverer'],
                'username' => $row['username']
            ];
        }
        return $dataSet;
    }

    /*
    * Function to get the actual status in text associated with a status (number) so a manager/admin
    * can assign a status to a delivery point, or else the admin will just see numbers.
    */
    public function fetchStatus() {
        $sqlQuery = 'SELECT DISTINCT status, status_text FROM delivery_point 
    JOIN delivery_status ON delivery_point.status = delivery_status.id';

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = [
                'status' => $row['status'],
                'status_text' => $row['status_text']
            ];
        }
        return $dataSet;
    }

    //Function to display all data in delivery_point
    public function fetchAllDeliveryPoints() {
        $sqlQuery = 'SELECT * FROM delivery_point';
                
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement
        
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new DeliveryPointData($row);
        }
        return $dataSet;
    }
    //Exact same as function above expect user can order the results in either ascending or descending order
    public function fetchAllDeliveryPointsOrdered($order) {
        // Validate $order to ensure it's either ASC or DESC
        if ($order !== 'ASC' && $order !== 'DESC') {
            // Handle invalid input or set a default order
            $order = 'ASC';
        }

        $sqlQuery = "SELECT * FROM delivery_point ORDER BY name $order";

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new DeliveryPointData($row);
        }
        return $dataSet;
    }
    /*
     * Fetch a delivery point based on id
     */
    public function fetchDeliveryPointByID($id) {
        $sqlQuery = 'SELECT * FROM delivery_point WHERE id=?';

        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindParam(1, $id);
        $statement->execute(); // execute the PDO statement

        $row = $statement->fetch();

        if ($row) {
            return new DeliveryPointData($row); // Return the single DeliveryPointData object
        }

        return null; // Or handle the case where the delivery point with that ID isn't found
    }

    //Function to display all data in delivery_point specific to $username
    public function fetchDeleveriesForUser($username) {
        $sqlQuery = 'SELECT * FROM delivery_point JOIN delivery_users ON delivery_point.deliverer = delivery_users.userid 
         WHERE delivery_users.username = :username';

        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindParam(':username', $username, PDO::PARAM_STR); //store the username as a string
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new DeliveryPointData($row);
        }
        return $dataSet;
    }
    //Exact same as function above expect user can order the results in either ascending or descending order
    public function fetchDeleveriesForUserOrdered($username, $order) {
        // Validate $order to ensure it's either ASC or DESC
        if ($order !== 'ASC' && $order !== 'DESC') {
            // Handle invalid input or set a default order
            $order = 'ASC';
        }

        $sqlQuery = 'SELECT * FROM delivery_point 
                 JOIN delivery_users ON delivery_point.deliverer = delivery_users.userid 
                 WHERE delivery_users.username = :username 
                 ORDER BY delivery_point.name ' . $order;

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindParam(':username', $username, PDO::PARAM_STR);
        $statement->execute();

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new DeliveryPointData($row);
        }
        return $dataSet;
    }


    //Function to display parcels based on input from user, linked to the search form in the navbar
    public function searchDeliveries($searchQuery) {
        $sqlQuery = 'SELECT * FROM delivery_point WHERE 
                 id = :search_query OR name= :search_query OR postcode = :search_query OR address_2 = :search_query';

        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindParam(':search_query', $searchQuery);
        $statement->execute(); // execute the PDO statement

        // Fetch and return the filtered deliveries
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new DeliveryPointData($row);
        }
        return $dataSet;
    }
    /**
     * @param $searchQuery
     * @param $id
     * @return array
     * Display searched results for a deliverer, this is to avoid showing all the data from delivery_points to deliverer
     */
    public function searchDeliveriesForDeliverer($searchQuery, $id) {
        $sqlQuery = 'SELECT * FROM delivery_point JOIN delivery_users ON delivery_point.deliverer = delivery_users.userid WHERE 
                 id = :search_query OR name= :search_query OR postcode = :search_query OR address_1 = :search_query
         AND delivery_users.username = :username ';

        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindParam(':search_query', $searchQuery);
        $statement->bindParam(':username', $id, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement

        // Fetch and return the filtered deliveries
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new DeliveryPointData($row);
        }
        return $dataSet;
    }


    /*
     * Function that takes 9 parameters (there are 10 fields in delivery_point but the id is auto-generated.
     * It then stores the user input into a new entry into the database.
     */
    public function addDeliveryPoint($name,$address1,$address2,$postcode,$deliverer,$lat,
                                     $lng,$status,$deliveryPhoto) {
            $sqlQuery = 'INSERT INTO delivery_point (name, address_1, address_2, postcode, deliverer, lat,
                            lng, status, del_photo) VALUES (?,?,?,?,?,?,?,?,?)';
            $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
            $statement->bindParam(1, $name);
            $statement->bindParam(2, $address1);
            $statement->bindParam(3, $address2);
            $statement->bindParam(4, $postcode);
            $statement->bindParam(5, $deliverer);
            $statement->bindParam(6, $lat);
            $statement->bindParam(7, $lng);
            $statement->bindParam(8, $status);
            $statement->bindParam(9, $deliveryPhoto);

            $statement->execute(); // execute the PDO statement

            return $statement->rowCount();
    }
/*
 * Function that deletes a delivery point based on/according to the delivery ID provided by the admin.
 */
    public function deleteDeliveryPoint($idToDelete) {
        $sqlQuery = 'DELETE FROM delivery_point WHERE id=?;';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindParam(1, $idToDelete);
        $statement->execute(); // execute the PDO statement

        return $statement->rowCount();
    }

    public function editDeliveryPoint($id, $name,$address1,$address2,$postcode,$deliverer,$lat,
                                     $lng,$status,$deliveryPhoto): int
    {
        $sqlQuery = 'UPDATE delivery_point SET name = ?, address_1 = ?, address_2 = ?, postcode = ?, deliverer = ?, lat = ?,
                            lng = ?, status = ?, del_photo = ? WHERE id=?';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindParam(1, $name);
        $statement->bindParam(2, $address1);
        $statement->bindParam(3, $address2);
        $statement->bindParam(4, $postcode);
        $statement->bindParam(5, $deliverer);
        $statement->bindParam(6, $lat);
        $statement->bindParam(7, $lng);
        $statement->bindParam(8, $status);
        $statement->bindParam(9, $deliveryPhoto);
        $statement->bindParam(10, $id);

        $statement->execute(); // execute the PDO statement

        return $statement->rowCount();
    }


    /**
     * @param $username
     * @param $order
     * @param $records_per_page
     * @param $current_page
     * @return array
     * Same as the other function with other name, just passes two new parameters so limit number of
     * deliveries shown.
     */
    public function fetchAllDeliveryPointsPaginated($order, $records_per_page, $current_page) {
        // Validate $order to ensure it's either ASC or DESC
        if ($order !== 'ASC' && $order !== 'DESC') {
            // Handle invalid input or set a default order
            $order = 'ASC';
        }
        $offset = ($current_page - 1) * $records_per_page;

        $sqlQuery = "SELECT * FROM delivery_point ORDER BY name $order LIMIT :limit OFFSET :offset";

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindValue(':limit', $records_per_page, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new DeliveryPointData($row);
        }
        return $dataSet;
    }

    public function countTotalRecords($username) {
        $sqlQuery = "SELECT COUNT(*) AS total_records FROM delivery_point JOIN delivery_users ON delivery_point.deliverer = delivery_users.userid 
        WHERE delivery_users.username = :username";

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindValue(':username', $username, PDO::PARAM_STR);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result['total_records'];
    }

    /**
     * @return mixed
     * Same as method above but when admin logs in show them all the records
     */
    public function countTotalRecordsAdmin() {
        $sqlQuery = "SELECT COUNT(*) AS total_records FROM delivery_point ";

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result['total_records'];
    }
}


