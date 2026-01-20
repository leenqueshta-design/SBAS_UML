<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);


spl_autoload_register(function ($class_name) {
    $directories = ['Database/', 'AppLogic/', 'UserInterface/'];
    
    foreach ($directories as $directory) {
        $file = __DIR__ . '/' . $directory . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});


$connection = "sqlite:sbas.db";


$userDB = new UserDB($connection);
$invoiceDB = new InvoiceDB($connection);
$taxSystem = new TaxSystem();
$authManager = new AuthManager($userDB);
$invoiceManager = new InvoiceManager($taxSystem, $invoiceDB);


if (isset($_SESSION['user'])) {
    $authManager->setCurrentSession($_SESSION['user']);
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>