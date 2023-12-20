 <?php
 session_start();
 require_once('Models/DeliveryPointDataSet.php');

 $view = new stdClass();
 $view->pageTitle = 'Delivery points View';
 $view->showNavbar = $_SESSION['showNavbar'] ?? false;

 if ($_SESSION['isLoggedIn']) {
     //$_SESSION['show_form'] = false;
     
     $DeliveryPointDataSet = new DeliveryPointDataSet();


     if (isset($_SESSION['username']) && ($_SESSION['username'] === 'lee')) {
         $username = $_SESSION['username'];
         $DeliveryPointDataSet = new DeliveryPointDataSet();
         //$view->DeliveryPointDataSet = $DeliveryPointDataSet->fetchAllDeliveryPoints();
         if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ASC'])) {
             $order = $_POST['ASC'];
             $view->DeliveryPointDataSet = $DeliveryPointDataSet->fetchDeleveriesForUserOrdered($username, $order);
         } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['DESC'])) {
             $order = $_POST['DESC'];
             $view->DeliveryPointDataSet = $DeliveryPointDataSet->fetchDeleveriesForUserOrdered($username, $order);
         } else {
             $view->DeliveryPointDataSet = $DeliveryPointDataSet->fetchDeleveriesForUser($username);
         }
     } elseif ($_SESSION['username'] == 'admin') {
         if (isset($_POST['ASC'])) {
             $order = $_POST['ASC'];
             $view->DeliveryPointDataSet = $DeliveryPointDataSet->fetchAllDeliveryPointsOrdered($order);
         } elseif (isset($_POST['DESC'])) {
             $order = $_POST['DESC'];
             $view->DeliveryPointDataSet = $DeliveryPointDataSet->fetchAllDeliveryPointsOrdered($order);
         }
         elseif (isset($_POST['pagination'])) {
             require_once('pagination.php');
         }
         else {
             $view->DeliveryPointDataSet = $DeliveryPointDataSet->fetchAllDeliveryPoints();
         }
     }



 }
 // Handle logout
 if (isset($_POST['logout']) ) {
     $_SESSION['isLoggedIn'] = false;
     session_destroy();
 }
require_once('Views/deliveryPoints.phtml');

