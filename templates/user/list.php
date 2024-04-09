<?php
$pageTitle = "CRUD Examples MVC (Users)";

if(!$datas){	//TEST SI DATAS EST VIDE (voir un vrai msg d'erreur ou autre)
	http_response_code(404);
	require('site/notfound.php'); // notre page HTML 404
	die();
}

ob_clean();	//IMPORTANT POUR NETTOYER LE FLUX HTTP
//var_dump($datas);
?>
	<h2 class="secondtitle"><?=$pageTitle. " [Method=" . $datas['method'] . " - Action=" . $datas['action'] . "]"?></h2>
	<a href="<?=$currUrl?>?page=<?=$page?>&action=create"><button id="create">Create</button></a>
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
			//OU DIRECTEMENT AVEC OBJETS
			// if(!empty($users)){
			// 	foreach ($users as $user) {
			// 		//print_r($row);
			// 		echo ("<tr>");
			// 		echo ("<td>" . $user->getId() . "</td>");
			// 		echo ("<td>" . $user->getName() . "</td>");
			// 		echo ("<td>" . $user->getSurname() . "</td>");
			// 		echo ("<td>" . $user->getEmail() . "</td>");
			// 		//$datetime = new DateTime($row["birthdate"]);
			// 		//echo ("<td>" . $datetime->format('d/m/Y H:i:s') . "</td>");
			// 		//echo ("<td>" . date_Db2Html_fr($user->getBirthdate(), false) . "</td>");
			// 		echo ("<td>" . date_Db2Html_fr($user->getBirthdate2Str(), false) . "</td>");
			// 		echo ("<td>" . ($user->getStatus()==0?"<i class='fa-solid fa-circle-xmark'></i>":"<i class='fa-solid fa-check'></i>") . "</td>");
			// 		echo ("<td><a href='$currUrl?page=$page&action=update&id=".$user->getId()."'><i class='fa-solid fa-pen-to-square'></i></a></td>");
			// 		echo ("<td><a href='$currUrl?page=$page&action=delete&id=".$user->getId()."'><i class='fa-solid fa-trash'></i></a></td>");
			// 		echo ("</tr>");
			// 	}
			// }
			//OU DIRECTEMENT avec tablo data pour indépendance
	//var_dump($datas['users']);
			if(!empty($datas['users'])){	//
				foreach ($datas['users'] as $user) {
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
					$currUrl = $datas['currUrl'];
					$page=  $datas['page'];
					echo ("<td><a href='$currUrl?page=$page&action=update&id=".$user->getId()."'><i class='fa-solid fa-pen-to-square'></i></a></td>");
					echo ("<td><a href='$currUrl?page=$page&action=delete&id=".$user->getId()."'><i class='fa-solid fa-trash'></i></a></td>");
					echo ("</tr>");
				}
			}
			else{
				echo ("<tr><td colspan='8'>No Data Found</td></tr>");
			}
			echo "</table>";
			$msg = $datas['msg'];
			$oldaction = $datas['oldaction'];
			if(!empty($msg)){
				if( str_contains($msg, "OK")){
					echo "<h3 id='h3msg' class='green'>$oldaction=$msg</h3>";
				}else{
					echo "<h3 id='h3msg' class='red'>$oldaction=$msg</h3>";
				}
			}
?>
<?php 
	$htmlContent = ob_get_clean(); 	//IMPORTANT POUR RECUPER LE FLUX EN CACHE
	require "site/layout.php";
?>