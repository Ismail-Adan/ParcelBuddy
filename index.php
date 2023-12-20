<?php
session_start();
require_once('Models/DeliveryUserDataSet.php');
require_once('Models/DeliveryPointDataSet.php');

/*
 * Initialize $view, $view->result, $view->show_form, $view->show_logout, $view->showNavbar, $view->adminFlag
 */
$view = new stdClass();
$view->pageTitle = '';
$view->result = '';
$view->failedLogin = '';
$view->show_form = true;
$_SESSION['show_form'] = true;
$view->show_logout = false;
$view->showNavbar = false;
$view->adminFlag = false;
$_SESSION['adminFlag'] = false;
$_SESSION['isLoggedIn'] = false;
$view->deliveryAdded = '';


// Handle user login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {

    //if the user presses "submit" and they have entered a username and password
    if(isset($_POST['username']) && isset($_POST['password'])) {
        //store the username and password
        $username = $_POST['username'];
        $password = $_POST['password'];

        //check to catch a user that tries to submit either a username and password only
        if (!empty($username) && !empty($password)) {
            $DeliveryUserDataSet = new DeliveryUserDataSet();

            /*if(!isset($_SESSION['passwordsHashed'])) {
                $hashedLee = password_hash('123456', PASSWORD_DEFAULT);
                $hashedAdmin = password_hash('123456', PASSWORD_DEFAULT);
                $DeliveryUserDataSet->hashPasswords($hashedLee, $hashedAdmin);
                $result = $DeliveryUserDataSet->verifyPassword($username, $password);
                $_SESSION['passwordsHashed'] = true;
            }*/

            // Check the login credentials against the table
            //$result = $DeliveryUserDataSet->checkLoginCredentials($username, $password);
            $result = $DeliveryUserDataSet->verifyPassword($username, $password);

            //save the username in a session to show the view and for later use
            $_SESSION['username'] = $username;
            $_SESSION['isLoggedIn'] = true;

            if ($username === "admin") {
                $view->adminFlag = true;
                $_SESSION['adminFlag'] = true;
            }



            if ($result) {
                // Login successful
                $view->show_form = false;
                $_SESSION['show_form'] = false;
                $view->show_logout = true;
                $view->showNavbar = true;
                $_SESSION['showNavbar'] = true;



                //Value output to view
                $view->result = "Hi " . $username . "!";

                //Flag for when an admin logs in, this is to show a custom view: CRUD
                /*if ($username === "admin") {
                    $view->adminFlag = true;
                    $_SESSION['adminFlag'] = true;
                }*/

            } else {
                // Login failed
                $view->show_form = true; // Show the form again
                $_SESSION['show_form'] = true;

                $view->failedLogin = "Invalid username or password!";
            }
        }
        else {
            // Error message if username or password field are left empty and user attempts to submit
            $view->failedLogin = "Please fill in both username and password fields.";
        }
    }
}

/*
 * If user has successfully logged in and the logout button is showing output the data to the view
 * and show the navigation bar.
 */
if ($_SESSION['isLoggedIn']) {
    //$_SESSION['show_form'] = false;

    $view->showNavbar = $_SESSION['showNavbar'] ?? false; //To show the navBar across the different pages

    $DeliveryPointDataSet = new DeliveryPointDataSet();



    if (isset($_SESSION['username']) && !$_SESSION['adminFlag']) {
        $username = $_SESSION['username'];
        //$DeliveryPointDataSet = new DeliveryPointDataSet();
        //$view->DeliveryPointDataSet = $DeliveryPointDataSet->fetchAllDeliveryPoints();
        $view->DeliveryPointDataSet = $DeliveryPointDataSet->fetchDeleveriesForUser($username);
    }
    elseif ($_SESSION['adminFlag']) {
        $view->DeliveryPointDataSet = $DeliveryPointDataSet->fetchAllDeliveryPoints();
    }

    /*
    * Special case where deliverer or admin want to narrow down the deliveries shown on the view
    * to perhaps certain areas or specific status
    */





}
// Handle logout
if (isset($_POST['logout']) ) {
    $_SESSION['isLoggedIn'] = false;
    session_destroy();
}

require_once('Views/index.phtml');