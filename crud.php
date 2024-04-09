<?php
require_once "inc/fn/cache_clear.php";
require_once "inc/fn/http_inc.php";
require_once "inc/fn/functions.php";

//init var
$currUrl = pageInfo_Basename();
$method = $action = $readonly = $disabled = $msg= $oldaction = "";
$query = $queryAffich = "";
$userId = -1; $userActive=0;
$userName = $userSurname = $userEmail = $userBirthdate = "";

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

switch (strtoupper($action)) { 	//VOS ACTIONS au minimum le CRUD
	case 'CREATE':	
		$action="CREATE";
		break;
	case 'UPDATE':
		$action="UPDATE";
		break;
	case 'DELETE':
		$action="DELETE";
		break;
	default:	//READ
		$action="READ";
		break;
}

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

require_once "inc/db/db_pdo.php";
db_connection();

if($method == "POST" && $action!="READ"){	//MAJ DB
	switch ($action) {
		case 'CREATE':
		case 'UPDATE':
			if($action=="UPDATE"){
				$query="UPDATE `user` SET `name`=:name,`surname`=:surname,
									`birthdate`=:birthdate,`email`=:email,`active`=:active WHERE id=:id";
			}
			else{
				$query="INSERT INTO `user`(`id`, `name`, `surname`, `birthdate`, `email`, `active`) 
										VALUES (:id,:name,:surname,:birthdate,:email,:active)";
				$userId=null;
			}
			$queryAffich=$query;
			$queryParam = array(
				":id" => $userId,
				":name" => $userName,
				":surname" => $userSurname,
				":email" => empty($userEmail)?null:$userEmail,
				":birthdate" => empty($userBirthdate)?null:date_Js2Db($userBirthdate, false),
				":active" => $userActive,
			);
			$result=db_execute($query, $queryParam);
			if($result){
				$msg="OK - ($queryAffich)";
			}else{
				$msg = db_lastErr_Message();
			}
			break;
		case 'DELETE':
			$query="DELETE FROM `user` WHERE id=:id";
			$queryAffich=$query;
			$queryParam = array(
				":id" => $userId,
			);
			$result=db_execute($query, $queryParam);
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
		$query="SELECT `id`, `name`, `surname`, `birthdate`, `email`, `active` FROM `user` 
								order by name, surname, birthdate";
		$queryAffich=$query;
		$rows = db_select($query);
		if(rowcount($rows)>0){
			foreach ($rows as $row) {
				//print_r($row);
				echo ("<tr>");
				echo ("<td>" . $row["id"] . "</td>");
				echo ("<td>" . $row["name"] . "</td>");
				echo ("<td>" . $row["surname"] . "</td>");
				echo ("<td>" . $row["email"] . "</td>");
				//$datetime = new DateTime($row["birthdate"]);
				//echo ("<td>" . $datetime->format('d/m/Y H:i:s') . "</td>");
				echo ("<td>" . date_Db2Html_fr($row["birthdate"], false) . "</td>");
				echo ("<td>" . ($row["active"]==0?"<i class='fa-solid fa-circle-xmark'></i>":"<i class='fa-solid fa-check'></i>") . "</td>");
				echo ("<td><a href='$currUrl?action=update&id=".$row["id"]."'><i class='fa-solid fa-pen-to-square'></i></a></td>");
				echo ("<td><a href='$currUrl?action=delete&id=".$row["id"]."'><i class='fa-solid fa-trash'></i></a></td>");
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
$bModeCreation;
$libFrom="Envoyer";
	if($method=="GET" && ($action!="READ")) {	//Lecture pour CreateUpdateDelete
		switch ($action) {
			case 'UPDATE':
			case 'DELETE':
				$bModeCreation=false;
				if($action=="UPDATE"){
					$libFrom="Mise à jour";
				}else{
					$libFrom="Effacement";
					$readonly="readonly";
					$disabled="disabled";
				}	
				$query="SELECT `name`, `surname`, `birthdate`, `email`, `active` FROM `user` where id=:id";
				$queryAffich=$query;
				$queryParam = array(
					":id" => $userId,
				);
				$rows = db_select($query, $queryParam);
				foreach ($rows as $row) {
					$userName = $row["name"];
					$userSurname = $row["surname"];
					$userEmail = $row["email"];
					$userBirthdate = date_Db2Js($row["birthdate"], false);
					$userActive = $row["active"];
				}
				break;
			default:
				$libFrom="Création";
				$bModeCreation=true;
				break;
		}
		if($bModeCreation==false && db_rowcount($rows)<0){	//si pas mode creation et si pas de datas
			echo "<h3>No Data Found</h3>";
		}
		else{
?>
	<form action="<?=$currUrl?>" method="post">
		<fieldset>
			<legend><?=$libFrom?></legend>
			<input type="hidden" name="action" id="action" value="<?=$action?>">
			<input type="hidden" name="id" id="id" value="<?=$userId?>">
			<label for="name">Nom</label>
			<input type="text" required placeholder="Saisir nom" name="name" id="name" value="<?=$userName?>" <?= $readonly?> autofocus><br>
			<label for="surname">Prénom</label>
			<input type="text" required placeholder="Saisir prénom" name="surname" id="surname" value="<?=$userSurname?>" <?= $readonly?>><br>
			<label for="email">Email</label>
			<input type="email" placeholder="Saisir email" name="email" id="email" value="<?=$userEmail?>" <?= $readonly?>><br>
			<label for="email">Date de Naissance</label>
			<input type="date" title="Saisir date" name="bd" id="bd" value="<?=$userBirthdate?>" <?= $readonly?>><br>
			<label for="act">Active</label>
			<input type="range" min="0" max="1" value="<?=$userActive?>" class="slider" id="act" name="act" <?= $disabled?>><br>
			<input type="submit" value="<?=$libFrom?>">
	</fieldset>
	</form>
<?php
		}
	echo "<h3>".$queryAffich."</h3>";
	}
	db_close();
?>
</section>
</body>
</html>