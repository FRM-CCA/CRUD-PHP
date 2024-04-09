<?php
//Sous controleur USER
$bRedirectionPOST = false;
$msg = $oldaction = "";

//AUTRE PARAM POUR LA PAGE USER
if (isset($_REQUEST["msg"])) {
	$msg=test_input($_REQUEST["msg"]);
}
if (isset($_REQUEST["oldaction"])) {
	$oldaction=test_input($_REQUEST["oldaction"]);
}

//GARDER VOTRE COUCHE METIER
require('src/user/model.php');
if($bRedirectionPOST) //SI POST DONC MODIFCATION EN CreateUpdateDelete
	redirect("$currUrl?page=user&action=read&oldaction=$action&msg=".urlencode($msg));
//ICI READ
switch ($action) {
	case 'CREATE':
		require('templates/user/create.php');
		break;
	case 'UPDATE':
	case 'DELETE':
		require('templates/user/update.php');
		break;
	
	default:	//Read -> List
		//get users list : $users
		require('templates/user/list.php');
		break;
}