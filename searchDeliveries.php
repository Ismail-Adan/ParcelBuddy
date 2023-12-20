 <?php
 session_start();

 require_once('Models/DeliveryPointDataSet.php');


$view = new stdClass();
$view->pageTitle = 'Searched delivery points';
$view->showNavbar = $_SESSION['showNavbar'] ?? false;

 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     //Get the search query and output results that match the searched parameters
     $searchQuery = $_POST['search_query'];
     $DeliveryPointDataSet = new DeliveryPointDataSet();
     if ($_SESSION['adminFlag']) {
         $view->DeliveryPointDataSet = $DeliveryPointDataSet->searchDeliveries($searchQuery);
     }
     else {
         $username = $_SESSION['username'];
         $view->DeliveryPointDataSet = $DeliveryPointDataSet->searchDeliveriesForDeliverer($searchQuery, $username);
     }




 }

require_once('Views/deliveryPoints.phtml');

