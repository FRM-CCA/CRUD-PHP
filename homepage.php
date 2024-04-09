<?php
	$pageTitle = "CRUD Examples";	
	ob_clean();	//IMPORTANT POUR NETTOYER LE FLUX HTTP
	//var_dump($datas);
?>
	<h1 class="maintitle">CRUD EXEMPLES</h1>
<?php 
	$htmlContent = ob_get_clean(); 	//IMPORTANT POUR RECUPER LE FLUX EN CACHE
	require "site/layout.php";
?>