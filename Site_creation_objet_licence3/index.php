<?php 



// le repertoire source 
set_include_path("./src");

require_once("Router.php");
require_once("Model/MontreStorageMySQL.php");
require_once("Accounts/AccountStorageSQL.php");

// connexion a la bd
$bd = new PDO('mysql:host=; mysql:port=3306; dbname=', '', '');
$router = new Router(new MontreStorageMySQL($bd),new AccountStorageSQL($bd));

$router->main();


?>
