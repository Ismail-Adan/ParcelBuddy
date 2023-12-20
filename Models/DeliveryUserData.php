<?php


class DeliveryUserData
{

    protected $_id, $_username, $_password, $_usertype;

    public function __construct($dbRow)
    {
        $this->_id = $dbRow['id'];
        $this->_username = $dbRow['username'];
        $this->_password = $dbRow['password'];
        $this->_usertype = $dbRow['usertype'];
    }

    public function getDeliveryUserID()
    {
        return $this->_id;
    }

    public function getUsername()
    {
        return $this->_username;
    }

    public function getPassword()
    {
        return $this->_password;
    }

    public function getUserType()
    {
        return $this->_usertype;
    }




}