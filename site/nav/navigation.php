<?php
	require_once __DIR__."/../../inc/fn/pageinfo.php";
	$activeIndex=$activeCrud=$activeCrudlayer=$activeCrudlayermvc="";
	$uri = pageInfo_Basename();
	$activeIndex=" class='active'";
	if(str_contains($uri, "crud.php")){
		$activeCrud=" class='active'";
		$activeIndex="";
	}
	if(str_contains($uri, "crudlayer.php")){
		$activeCrudlayer=" class='active'";
		$activeIndex="";
	}
	$uri = pageInfo_Uri();
	if(str_contains($uri, "index.php?page=user")){
		$activeCrudlayermvc=" class='active'";
		$activeIndex="";
	}
?>
<nav>
	<ul>
		<li><a <?=$activeIndex?> href="index.php">Home</a></li>
		<li><a <?=$activeCrud?> href="crud.php">CRUD (with Functions)</a></li>
		<li><a <?=$activeCrudlayer?> href="crudlayer.php">CRUD (with Layers)</a></li>
		<li><a <?=$activeCrudlayermvc?> href="index.php?page=user">CRUD (with MVC)</a></li>
		<li style="float:right"><a href="#about">About</a></li>
	</ul>
</nav>
