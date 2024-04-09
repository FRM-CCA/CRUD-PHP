<?php
$pageTitle = "CRUD Examples MVC (Users) Create";
$libFrom="Création";

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
		<legend><?=$libFrom?></legend>
		<input type="hidden" name="action" id="action" value="<?=$action?>">
		<input type="hidden" name="id" id="id" value="-1">
		<label for="name">Nom</label>
		<input type="text" required placeholder="Saisir nom" name="name" id="name" value="" autofocus><br>
		<label for="surname">Prénom</label>
		<input type="text" required placeholder="Saisir prénom" name="surname" id="surname" value=""><br>
		<label for="email">Email</label>
		<input type="email" placeholder="Saisir email" name="email" id="email" value=""><br>
		<label for="email">Date de Naissance</label>
		<input type="date" title="Saisir date" name="bd" id="bd" value=""><br>
		<label for="act">Active</label>
		<input type="range" min="0" max="1" value="0" class="slider" id="act" name="act"><br>
		<input type="submit" value="<?=$libFrom?>">
	</fieldset>
</form>
<?php 
	$htmlContent = ob_get_clean(); 	//IMPORTANT POUR RECUPER LE FLUX EN CACHE
	require "site/layout.php";
?>