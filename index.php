<?php
define('CONTEXT', 'WebService');

$request = $_SERVER['REQUEST_URI'];
$baserequest = basename($request);

//  echo "<p>$request</p>";
//  echo "<p>$baserequest</p>";

switch ($baserequest) {
    case CONTEXT :
    case 'connection':
        require './views/0-Connect.php';
        break;
    case 'connect':
        require './config/0-ConnectConfig.php';
        break;
    case 'selection':
        require './controllers/1-SelectController.php';
        break;
    case 'query' :
        require './controllers/2-QueryController.php';
        break;
    case 'insert' :
        require './controllers/3-InsertController.php';
        break;
    case 'restart' :
        require './controllers/4-RestartController.php';
        break;

    default:
        http_response_code(404);
        // require './views/errors/404.php';
        break;
}