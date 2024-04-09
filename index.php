<?php
//CONTROLLER PRINCIPAL (voir router)
ob_start();	//ACTIVE LA GESTION DU CACHE
//EN MVC, "index.php" jouera le role du controleur principale, donc aucun code html
require "inc/fn/cache_clear.php";
require_once "inc/fn/pageinfo.php";
require_once "inc/fn/functions.php";
require_once "inc/fn/http_inc.php";

$currUrl = $uri = $method = $page = $action = "";

$method = $_SERVER['REQUEST_METHOD'];	//GET/POST
$currUrl = strtolower(pageInfo_Basename());	//nom de la page
$uri = strtolower(pageInfo_Uri());					//url de la page (avec param)

if(isset($_REQUEST["page"]) && !empty($_REQUEST["page"])){
	$page = strtolower(test_input($_REQUEST["page"]));
}
if(isset($_REQUEST["action"])) {
	$action = strtoupper(test_input($_REQUEST["action"]));
}

if($currUrl=="index.php"){
	switch ($page) {
		case "":	//ici on fait rien (ou redirection homepage) [aucun parametre]
			require('homepage.php');
			break;
		case "user":	//USER CRUD
			require "src/controllers/user.php";
			break;

		default:
			ob_clean();
			# code... ou une erreur 404
			http_response_code(404);
			require('site/notfound.php'); // notre page HTML 404
			break;
	}
}
