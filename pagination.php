 <?php
 session_start();

 require_once('Models/DeliveryPointDataSet.php');


$view = new stdClass();
$view->pageTitle = 'Pagination View';
$view->showNavbar = $_SESSION['showNavbar'] = false;

 if ($_SERVER['REQUEST_METHOD'] === 'POST') {

     // Set pagination variables
     $current_page = $_GET['page'] ?? 1;
     $records_per_page = 10;

     $DeliveryPointDataSet = new DeliveryPointDataSet();
     $username = $_SESSION['username'];
     $order = $_POST['ASC'];
     if ($_SESSION['adminFlag']) {
// Fetch data based on pagination
         $view->DeliveryPointDataSet = $DeliveryPointDataSet->fetchAllDeliveryPointsPaginated(
             $order,
             $records_per_page,
             $current_page
         );
     }
if ($_SESSION['adminFlag']) {
    $total_records = $DeliveryPointDataSet->countTotalRecordsAdmin();
}
else {
    // Count total records (implement this method in DeliveryPointDataSet)
    $total_records = $DeliveryPointDataSet->countTotalRecords($username);
}


// Calculate total pages
     $total_pages = ceil($total_records / $records_per_page);

// Pass pagination variables to the view
     $view->currentPage = $current_page;
     $view->totalPages = $total_pages;








 }

require_once('Views/deliveryPoints.phtml');

