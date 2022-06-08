<?php
session_start();
# PASSEM LES DADES DEL FORMULARI A CONSTANTS CREADES MANUALMENT
if (isset($_POST['connexion_data'])) {
    
    $log = $_POST['connexion_data'];

    # DEFINIM LES CONSTANTS PER ACCEDIR A LA BASE DE DADES
    // define('HOST', $log['server-ip']);
    // define('DATABASE_NAME', $log['dbname']);
    // define('USER', $log['user']);
    // define('PASSWORD', $log['password']);

    $_SESSION['HOST'] = $log['server-ip'];
    $_SESSION['DB_NAME'] = $log['dbname'];
    $_SESSION['USER'] = $log['user'];
    $_SESSION['PASSWORD'] = $log['password'];


    require './controllers/1-SelectController.php';
}