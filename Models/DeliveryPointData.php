<?php


class DeliveryPointData
{
    /*
     * Class specific fields
     */
    protected $_id, $_name, $_address1, $_address2, $_postcode, $_deliverer, $_lat, $_lng, $_status, $_del_photo;
    /*
     * Constructor
     */
    public function __construct($dbRow)
    {
        $this->_id = $dbRow['id'];
        $this->_name = $dbRow['name'];
        $this->_address1 = $dbRow['address_1'];
        $this->_address2 = $dbRow['address_2'];
        $this->_postcode = $dbRow['postcode'];
        $this->_deliverer = $dbRow['deliverer'];
        $this->_lat = $dbRow['lat'];
        $this->_lng = $dbRow['lng'];
        $this->_status = $dbRow['status'];
        $this->_del_photo = $dbRow['del_photo'];

    }
    /*
     * Getters for above methods/functions
     */
    public function getDeliveryPointID()
    {
        return $this->_id;
    }

    public function getDeliveryPointName()
    {
        return $this->_name;
    }

    public function getAddress1()
    {
        return $this->_address1;
    }

    public function getAddress2()
    {
        return $this->_address2;
    }

    public function getPostcode()
    {
        return $this->_postcode;
    }

    public function getDeliverer()
    {
        return $this->_deliverer;
    }

    public function getLatitude()
    {
        return $this->_lat;
    }

    public function getLongitude()
    {
        return $this->_lng;
    }

    public function getDeliveryStatus()
    {
        return $this->_status;
    }

    public function getDeliveryPhoto()
    {
        return $this->_del_photo;
    }





}