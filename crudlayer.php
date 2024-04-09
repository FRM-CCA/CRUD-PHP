<?php
require_once "inc/fn/cache_clear.php";
require_once "inc/fn/http_inc.php";
require_once "inc/fn/functions.php";
require_once "inc/class/classautoload.php";

///init var
$currUrl = pageInfo_Basename();
$method = $action = $readonly = $msg = $oldaction = "";
$query = $queryAffich = "";
$userId = -1; $userActive=0;
$userName = $userSurname = $userEmail = $userBirthdate = "";
$user=null;

//PHP GET/POST PARAM
if (isset($_REQUEST["action"])) {
	$action=test_input($_REQUEST["action"]);
}
if (isset($_REQUEST["msg"])) {
	$msg=test_input($_REQUEST["msg"]);
}
if (isset($_REQUEST["oldaction"])) {
	$oldaction=test_input($_REQUEST["oldaction"]);
}
if (isset($_REQUEST["query"])) {
	$queryAffich=test_input($_REQUEST["query"]);
}

//INITIALISATION COUCHE METIER USER
$userBll = new UserBll();
//VOS ACTIONS au minimum le CRUD
$action=$userBll->setAction($action);

//RECUPERATION PARAM
if ($_SERVER['REQUEST_METHOD'] == 'POST') {		//ICI pour la modification plus secure POST
	$method = "POST";
	$state = "$method=Writing";
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
}else {	//ICI pour la lecture (si on le décide/veut)	GET
	$method = "GET";
	$state = "$method=Reading";
	if (isset($_GET["id"])) {
		$userId = test_input($_GET["id"]);
	}
}

if($method == "POST" && $action!="READ"){	//MAJ DB
	$result;
	switch ($action) {
		case 'CREATE':
		case 'UPDATE':
			if($action=="UPDATE"){
				//$userBll->setAction($action);
				$result = $userBll->updateUser($userId, $userName,$userSurname,$userActive, 
					empty($userBirthdate)?null:date_Js2Db($userBirthdate, false),empty($userEmail)?null:$userEmail);
			}
			else{
				$result = $userBll->createUser($userId, $userName,$userSurname,$userActive, 
					empty($userBirthdate)?null:date_Js2Db($userBirthdate, false),empty($userEmail)?null:$userEmail);
			}
	//var_dump("-->".$result);
			$queryAffich=$query;
			if($result){
				$msg="OK - ($queryAffich)";
			}else{
				$msg = db_lastErr_Message();
			}
			break;
		case 'DELETE':
			$result = $userBll->deleteUser($userId);
			$queryAffich=$query;
			if($result){
				$msg="OK - ($queryAffich)";
			}else{
				$msg = db_lastErr_Message();
			}
			break;
		default:
			break;
	}
	redirect("$currUrl?action=read&oldaction=$action&msg=".urlencode($msg)."&query=".urlencode($queryAffich));
}

$pageTitle = "CRUD Examples (Users)";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="css/style.css">
	<title><?=$pageTitle?></title>
</head>
<body>
<?php
 include_once "site/nav/navigation.php";
?>
<section class="main">
<h2 class="secondtitle"><?=$pageTitle. " [" .$state . " - Action=" . $action . "]"?></h2>
<?php
	if($method=="GET" && $action=="READ") {	//Lecture pour Read
?>
	<a href="<?=$currUrl?>?action=create"><button id="create">Create</button></a>
	<table>
		<tr>
			<th>Id</a></th>
			<th>Name</a></th>
			<th>Surname</a></th>
			<th>Email</a></th>
			<th>Birthdate</th>
			<th>Active</th>
			<th>Update</th>
			<th>Delete</th>
		</tr>
<?php
		$users = $userBll->getUsersList();
		if(rowcount($users)>0){
			foreach ($users as $user) {
				//print_r($row);
				echo ("<tr>");
				echo ("<td>" . $user->getId() . "</td>");
				echo ("<td>" . $user->getName() . "</td>");
				echo ("<td>" . $user->getSurname() . "</td>");
				echo ("<td>" . $user->getEmail() . "</td>");
				//$datetime = new DateTime($row["birthdate"]);
				//echo ("<td>" . $datetime->format('d/m/Y H:i:s') . "</td>");
				//echo ("<td>" . date_Db2Html_fr($user->getBirthdate(), false) . "</td>");
				echo ("<td>" . date_Db2Html_fr($user->getBirthdate2Str(), false) . "</td>");
				echo ("<td>" . ($user->getStatus()==0?"<i class='fa-solid fa-circle-xmark'></i>":"<i class='fa-solid fa-check'></i>") . "</td>");
				echo ("<td><a href='$currUrl?action=update&id=".$user->getId()."'><i class='fa-solid fa-pen-to-square'></i></a></td>");
				echo ("<td><a href='$currUrl?action=delete&id=".$user->getId()."'><i class='fa-solid fa-trash'></i></a></td>");
				echo ("</tr>");
			}
		}
		else{
			echo ("<tr><td colspan='8'>No Data Found</td></tr>");
		}
		echo "</table>";
		echo "<h3>".$queryAffich."</h3>";
		if(!empty($msg)){
			if( str_contains($msg, "OK")){
				echo "<h3 id='h3msg' class='green'>$oldaction=$msg</h3>";
			}else{
				echo "<h3 id='h3msg' class='red'>$oldaction=$msg</h3>";
			}
		}
	}
	$libFrom="Envoyer";
	if($method=="GET" && ($action!="READ")) {	//Lecture pour CreateUpdateDelete
		switch ($action) {
			case 'UPDATE':
			case 'DELETE':
				if($action=="UPDATE"){
					$libFrom="Mise à jour";
				}else{
					$libFrom="Effacement";
					$readonly="readonly";
				}	
				$user = $userBll->getUserbyId($userId);
				break;
			default:
				$user = $userBll->getNewUser();
				$libFrom="Création";
				break;
		}
		if($user==null){
			echo "<h3>No Data Found</h3>";
		}
		else{
?>
	<form action="<?=$currUrl?>" method="post">
		<fieldset>
			<legend><?=$libFrom?></legend>
			<input type="hidden" name="action" id="action" value="<?=$action?>">
			<input type="hidden" name="id" id="id" value="<?=$user->getId()?>">
			<label for="name">Nom</label>
			<input type="text" required placeholder="Saisir nom" name="name" id="name" value="<?=$user->getName()?>" <?= $readonly?> autofocus><br>
			<label for="surname">Prénom</label>
			<input type="text" required placeholder="Saisir prénom" name="surname" id="surname" value="<?=$user->getSurname()?>" <?= $readonly?>><br>
			<label for="email">Email</label>
			<input type="email" placeholder="Saisir email" name="email" id="email" value="<?=$user->getEmail()?>" <?= $readonly?>><br>
			<label for="email">Date de Naissance</label>
			<input type="date" title="Saisir date" name="bd" id="bd" value="<?=date_Db2Js($user->getBirthdate2Str(), false)?>" <?= $readonly?>><br>
			<label for="act">Active</label>
			<input type="range" min="0" max="1" value="<?=$user->getStatus()?>" class="slider" id="act" name="act" <?= $readonly?>><br>
			<input type="submit" value="<?=$libFrom?>">
	</fieldset>
	</form>
<?php
		}
	echo "<h3>".$queryAffich."</h3>";
	}
?>
</section>
</body>
</html>