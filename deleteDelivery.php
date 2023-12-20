 <?php
 //session_start();

 require_once('Models/DeliveryPointDataSet.php');


$view = new stdClass();
$view->pageTitle = 'Delete delivery point';
$view->showNavbar = $_SESSION['showNavbar'] ?? false;

 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     if (isset($_POST['delivery_id'])) {
         // Get the delivery ID to be deleted
         $deliveryIdToDelete = $_POST['delivery_id'];

         $deliveryPointDataSet = new DeliveryPointDataSet();


         // Delete the delivery by its ID
         $isDeleted = $deliveryPointDataSet->deleteDeliveryPoint($deliveryIdToDelete);

         // Optionally handle the success or failure of deletion
         if ($isDeleted) {
             // Deletion was successful
             $view->isDeleted = "Delivery Point with ID $deliveryIdToDelete has been deleted successfully!";
             //header('Location: index.php');
             require_once('deliveryPoints.php');
             exit();

         }
         elseif (empty($isDeleted)) {
             $view->isDeleted = "Please input a Delivery Point ID.";

         }


         else {
             // Deletion failed
             $view->isDeleted = "Delivery Point with ID: $deliveryIdToDelete, does not exist";
         }
     }
 }

require_once('Views/deleteDelivery.phtml');

