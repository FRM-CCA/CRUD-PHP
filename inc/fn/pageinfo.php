<?php

function pageInfo_Uri(){
	//return basename(__FILE__);
	return pathinfo($_SERVER['REQUEST_URI'], PATHINFO_BASENAME);
}

function pageInfo_Basename(){
	//return basename(__FILE__);
	return basename($_SERVER['PHP_SELF']);
}

function pageInfo_Formname(){
	//return basename(__FILE__);
	return htmlspecialchars($_SERVER['PHP_SELF']);
}

function pageInfo_CurrentDir(){
	//return __DIR__;
	//return str_replace("/".pageInfo_Basename(), "", $_SERVER['PHP_SELF']);
	return str_replace(pageInfo_Basename(), "", $_SERVER['PHP_SELF']);
	
}

//return pathinfo($_SERVER['REQUEST_URI'], PATHINFO_BASENAME);
// PATHINFO_DIRNAME, PATHINFO_BASENAME, PATHINFO_EXTENSION et PATHINFO_FILENAME.
// echo pathinfo['dirname'], "\n";
// echo pathinfo['basename'], "\n";
// echo pathinfo['extension'], "\n";
// echo pathinfo['filename'], "\n";

//$uri = $_SERVER['QUERY_STRING'];
//$uri = basename($_SERVER['REQUEST_URI']);