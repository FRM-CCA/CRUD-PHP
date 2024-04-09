<?php
$__isPosted="";

function readHttp_isPosted(){
	global $__isPosted;
	if($__isPosted==="")
		exit("'readHttp_isPosted()' need be called after method 'readHttp_Method()' for initialisation");
	return $__isPosted;
}

// fonction de lecture de method HTTP (GET/POST)
function readHttp_Method(){
	global $method, $__isPosted;
	if($_SERVER["REQUEST_METHOD"] == HttpMethodStatus::POST){
		$method = HttpMethodStatus::POST;
		$__isPosted=true;
	}
	else{
		$method = HttpMethodStatus::GET;
		$__isPosted=false;
	}
	return $method;
}

// fonction de redirection de page
function redirect($data, $replace=true)
{
	header("Location: " . $data, $replace);
	exit;		//NE PAS OUBLIER EXIT SINON LA MISERE ...
}