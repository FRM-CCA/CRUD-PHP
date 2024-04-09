<?php
$pageTitle = "CRUD Examples MVC (Users) ";
$readonly="";
$libFrom="";
if($action=="UPDATE"){
	$libFrom="Mise à jour";
}
else{
	$libFrom="Effacement";
	$readonly="readonly";
}
$pageTitle.=$libFrom;

if(!$datas){	//TEST SI DATAS EST VIDE (voir un vrai msg d'erreur ou autre)
	http_response_code(404);
	require('site/notfound.php'); // notre page HTML 404
	die();
}

ob_clean();	//IMPORTANT POUR NETTOYER LE FLUX HTTP
//var_dump($datas);
?>
<h2 class="secondtitle"><?=$pageTitle. " [Method=" . $datas['method'] . " - Action=" . $datas['action'] . "]"?></h2>
<form action="<?=$currUrl?>?page=<?=$page?>" method="post">
	<fieldset>
		<?php 
			if(empty($datas["user"])){
				echo "No data found";
			}
			else{
		?>
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
		<?php } ?>
	</fieldset>
</form>
<?php 
	$htmlContent = ob_get_clean(); 	//IMPORTANT POUR RECUPER LE FLUX EN CACHE
	require "site/layout.php";
?>