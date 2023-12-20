 <?php

 require_once('Models/DeliveryPointDataSet.php');


$view = new stdClass();
$view->pageTitle = 'Create delivery point';
$view->showNavbar = $_SESSION['showNavbar'] ?? false;

 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     // Fetch existing deliverers from the database
     $DeliveryPointDataSet = new DeliveryPointDataSet();
     $view->deliverers = $DeliveryPointDataSet->fetchDeliverers();
     $view->statuses = $DeliveryPointDataSet->fetchStatus();
     //var_dump($view->statuses);
     // Check if the adminFlag is set (indicating admin/manager privileges)
     // Extract form data and insert into variables


     $name = $_POST['Name'] ?? '';
     $address1 = $_POST['Address1'] ?? '';
     $address2 = $_POST['Address2'] ?? '';
     $postcode = $_POST['Postcode'] ?? '';
     $deliverer = $_POST['Deliverer'] ?? '';
     $lat = floatval($_POST['Latitude'] ?? '');
     $lng = floatval($_POST['Longitude'] ?? '');
     $status = $_POST['Status'] ?? ''; //$status = floatval($_POST['Status'] ?? '');
     $deliveryPhoto = $_POST['DeliveryPhoto'] ?? '';

     //To handle if the admin does not fill every single field
     if (!empty($deliverer) && !empty($status) && !empty($name) &&
         !empty($address1) && !empty($address2) && !empty($postcode) &&
         !empty($lat) && !empty($lng) && !empty($deliveryPhoto)) {
         //echo "Deliverer ID: " . $deliverer; // Debug line
         // Call the addDeliveryPoint method with form data
         $deliveryPointDataSet = new DeliveryPointDataSet();
         $rowCount = $deliveryPointDataSet->addDeliveryPoint($name, $address1, $address2, $postcode, $deliverer, $lat, $lng, $status, $deliveryPhoto);
         // Check if the data was actually inserted
         if ($rowCount > 0) {
             // Handle successful insertion (redirect, message, etc.)
             $view->isCreated = "Delivery point added";
             require_once('deliveryPoints.php');
             exit();
         } else {
             // Handle insertion failure
             //$_SESSION['error'] = "Failed to add delivery point.";
             $view->isCreated = "Failed to add delivery point.";

         }
     }
     else {
         $view->isCreated = "Failed to add delivery point, please fill in all the fields.";
     }



 }

require_once('Views/createDelivery.phtml');

