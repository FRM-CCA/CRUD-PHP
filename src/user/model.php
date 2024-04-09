<?php
require_once __DIR__."/../../inc/class/classautoload.php";

//INITIALISATION COUCHE METIER USER
$userBll = new UserBll();
//VOS ACTIONS au minimum le CRUD
$action = $userBll->setAction($action);	//CRUD
// var_dump($action, $method);
// die();
$bRedirectionPOST = false;
$user=null;
$readonly = "";
$userId = -1; $userActive=0;
$userName = $userSurname = $userEmail = $userBirthdate = "";

// Le tablo avec les données nécéssaires a notre vue (ou directement avec les variables)
$datas = [
	'currUrl' => $currUrl,
	'page' => $page,
	'method' => $method,
	'action' => $action,
	'msg' => $msg,
	'oldaction' => $oldaction
];

if ($method == 'POST'){	//POST = POUR MODIFICATION
	if (isset($_POST["id"])) {
		$userId = test_input($_POST["id"]);
	}
	if (isset($_POST["name"])) {
		$userName = test_input($_POST["name"]);
	}
	if (isset($_POST["surname"])) {
		$userSurname = test_input($_POST["surname"]);
	}
	if (isset($_POST["email"])) {
		$userEmail = test_input($_POST["email"]);
	}
	if (isset($_POST["bd"])) {
		$userBirthdate = test_input($_POST["bd"]);
	}
	if (isset($_POST["act"])) {
		$userActive = test_input($_POST["act"]);
	}

	switch ($action) {
		case UserBll::CREATE :
		case UserBll::UPDATE : 
			if($action=="UPDATE"){
				//$userBll->setAction($action);
				$result = $userBll->updateUser($userId, $userName,$userSurname,$userActive, 
					empty($userBirthdate)?null:date_Js2Db($userBirthdate, false),empty($userEmail)?null:$userEmail);
				if($result)
					$msg="OK - Updated Id : " . $userId;
			}
			else{
				$result = $userBll->createUser($userId, $userName,$userSurname,$userActive, 
					empty($userBirthdate)?null:date_Js2Db($userBirthdate, false),empty($userEmail)?null:$userEmail);
				if($result)
					$msg="OK - Last Insert Id : " . $userBll->getLastInsertId();
			}
			if(!$result){
				$msg = db_lastErr_Message();
			}
			break;
		case UserBll::DELETE : 
			$datas["userId"] = $userId;	//ICI ON AJOUTE 1 ELEMENT O TABLO (normalent id cliquer)
			$result = $userBll->deleteUser($userId);
			if($result){
				$msg="OK - $userId Deleted";
			}else{
				$msg = db_lastErr_Message();
			}
			//RECUP DATA
			//$userActive=0;
			//$userName = $userSurname = $userEmail = $userBirthdate = "";
			break;
		// default:	//READ
		// 	//echo UserBll::READ;
		// 	$users = $userBll->getUsersList();	//recup la liste des users
		// 	$datas["users"] = $users;	//ICI ON AJOUTE 1 ELEMENT O TABLO ici les liste des users
		// 	break;
	}
	$bRedirectionPOST=true;
		//redirect("$currUrl?page=user&action=read&oldaction=$action&msg=".urlencode($msg));
}
else{	//GET	= LECTURE
	if (isset($_GET["id"])) {
		$userId = test_input($_GET["id"]);
	}

	switch ($action) {
		case UserBll::CREATE : 
			//$user = $userBll->getNewUser();
			//$datas["userId"] = $userId;	//ICI ON AJOUTE 1 ELEMENT O TABLO (normalent -1)
			//$datas["user"] = $user;
			break;
		case UserBll::UPDATE : 
		case UserBll::DELETE : 
			$user = $userBll->getUserbyId($userId);
			$datas["userId"] = $userId;	//ICI ON AJOUTE 1 ELEMENT O TABLO (normalent -1)
			$datas["user"] = $user;
			break;
		default:	//READ
			//echo UserBll::READ;
			$users = $userBll->getUsersList();	//recup la liste des users
			$datas["users"] = $users;	//ICI ON AJOUTE 1 ELEMENT O TABLO ici les liste des users
			break;
	}
}
