<?php

require_once ('Models/Database.php');
require_once ('Models/DeliveryUserData.php');

class DeliveryUserDataSet {
    protected $_dbHandle, $_dbInstance;
        
    public function __construct() {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    public function fetchAllDeliveryPoints() {
        $sqlQuery = 'SELECT * FROM delivery_users';
                
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement
        
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new DeliveryUserData($row);
        }
        return $dataSet;
    }



    public function checkLoginCredentials($username, $password)
    {
        $sqlQuery = 'SELECT * FROM delivery_users WHERE username = ? AND password = ?';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$username, $password]);

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @param $hashedLee
     * @param $hashedAdmin
     * @return void
     * hashing the passwords
     */
    public function hashPasswords($hashedLee, $hashedAdmin)
    {
        $sqlQueryLee = 'UPDATE delivery_users SET password = ? WHERE username = "lee"';
        $sqlQueryAdmin = 'UPDATE delivery_users SET password = ? WHERE username = "admin"';

        $statementLee = $this->_dbHandle->prepare($sqlQueryLee);
        $statementAdmin = $this->_dbHandle->prepare($sqlQueryAdmin);

        $statementLee->execute([$hashedLee]);
        $statementAdmin->execute([$hashedAdmin]);
    }

    /**
     * @param $username
     * @param $password
     * @return bool
     * as name states password verfication
     */
    public function verifyPassword($username, $password)
    {
        $sqlQuery = 'SELECT password FROM delivery_users WHERE username = ?';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([$username]);

        $hashedPassword = $statement->fetchColumn();

        if ($hashedPassword && password_verify($password, $hashedPassword)) {
            // Password is correct
            return true;
        }

        // Incorrect password or user not found
        return false;
    }


}


