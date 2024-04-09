<?php
// require_once "autoload.php";
// require_once "session.php";
//require_once "inc/constants.php";
#require_once dirname(__FILE__). "/../constants.php";
require_once "pageinfo.php";

// fonction de test saisie
function test_input($data)
{
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

// fonctions dates
function date_Db2Html_fr($dateStr, $defaultWithTime=true){
	if(!isset($dateStr))
		return "";
	try {
		if($defaultWithTime)
			return date('d/m/Y H:i:s', strtotime($dateStr));
		else
			return date('d/m/Y', strtotime($dateStr));	//code...
	} catch (\Throwable $th) {
		return null;
	}
	
}

function date_Db2Js($dateStr, $defaultTime=true){
	if(!isset($dateStr))
		return "";
	//date from datetime html
	//2018-06-12T19:30 (ou sans l'heure)
	//if(DateTime::createFromFormat('Y-m-d H:i:s', $myString) !== false)
	try {
		if(strtotime($dateStr)){
			if($defaultTime)
				return str_replace(" ", "T", $dateStr);
			else{
				$i = strpos($dateStr, " ");
				if($i>0)
					return substr($dateStr, 0, $i);	
			}
		}
	} catch (\Throwable $th) {
		return null;
	}
	//return str_replace(" ", "T", $dateStr);
	return "";
}
function date_Js2Db($dateStr, $defaultTime=true){
	//date from datetime db
	//2018-06-12 19:30:00 (ou sans l'heure)
	if(isset($dateStr) && trim($dateStr)!=""){
		if($defaultTime)
			return str_replace("T", " ", $dateStr);
		else{
			return $dateStr;
		}
	}	
	return ""; 
}

// fonction compteur de ligne tableau / d'objets / ...
function rowcount($param){
	if(!isset($param) || $param==null)
		return -1;
	try {
		return count($param);
	} catch (\Throwable $th) {
		return -1; 
	}
}

// fonction de validation de l'email
function validate_email($email)
{
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
		return true;
	}
	return false;
}
// fonction de validation du format de téléphone
function validate_phoneNumber($phone)
{
	// Allow +, - and . in phone number
	$filtered_phone_number = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
	// Remove "-" and "." from number
	$phone_to_check = str_replace("-", "", $filtered_phone_number);
	$phone_to_check = str_replace(".", "", $filtered_phone_number);
	// Check the lenght of number
	// This can be customized if you want phone number from a specific country
	if (strlen($phone_to_check) < 10 || strlen($phone_to_check) > 12) {
		return false;
	}
	if (strlen($phone_to_check) > 10 && strpos($phone_to_check,"+")===false) {
		return false;
	}
	return true;
}
