<?php
//session_start();

require_once('Models/DeliveryPointDataSet.php');


$view = new stdClass();
$view->pageTitle = 'Edit delivery point';
$view->showNavbar = $_SESSION['showNavbar'] = false;
$DeliveryPointDataSet = new DeliveryPointDataSet();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deliveryIdToEdited = null;
    if (isset($_POST['delivery_id'])) {


        // Get the delivery ID to be edited
        //$_SESSION['deliveryIdToEdited'] = $_POST['delivery_id'];
        $deliveryIdToEdited = $_POST['delivery_id'];
        $_SESSION['deliveryIdToEdited'] = $deliveryIdToEdited;

        //output the delivery points and specifically deliverers and statuses in the database in a dropbox
        //$view->deliverers = $DeliveryPointDataSet->fetchDeliverersForID($deliveryIdToEdited);//Uncomment
        //$view->statuses = $DeliveryPointDataSet->fetchStatusForID($deliveryIdToEdited);//Uncomment
        $view->deliverers = $DeliveryPointDataSet->fetchDeliverers();//Uncomment
        $view->statuses = $DeliveryPointDataSet->fetchStatus();//Uncomment
        $view->DeliveryPointDataSet = $DeliveryPointDataSet->fetchDeliveryPointByID($_SESSION['deliveryIdToEdited']);
        //var_dump($view->DeliveryPointDataSet);
    }

    if (isset($_POST['editU'])) {


        $name = $_POST['Name'] ?? '';
        $address1 = $_POST['Address1'] ?? '';
        $address2 = $_POST['Address2'] ?? '';
        $postcode = $_POST['Postcode'] ?? '';
        $deliverer = $_POST['Deliverer'] ?? 1;
        $lat = floatval($_POST['Latitude'] ?? '0');
        $lng = floatval($_POST['Longitude'] ?? '0');
        $status = $_POST['Status'] ?? 1;
        $deliveryPhoto = $_POST['DeliveryPhoto'] ?? '';

        // Edit the delivery by its ID
        //var_dump($deliverer);
        $isEdited = $DeliveryPointDataSet->editDeliveryPoint($_POST['ID'], $name, $address1, $address2, $postcode, $deliverer, $lat, $lng, $status, $deliveryPhoto);


        // Optionally handle the success or failure of editing
        if ($isEdited) {
            // Deletion was successful
            $view->isEdited = "Delivery Point with ID" . $_SESSION['deliveryIdToEdited'] . " has been edited successfully!";
            require_once('deliveryPoints.php');

            exit();


        } else {
            $view->isEdited = "Delivery point has not been edited.";

        }
    }

}

require_once('Views/editDelivery.phtml');

