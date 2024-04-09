<?php
global $dbservername, $dbname, $dbusername, $dbpassword;
 // Database 
 $dbservername = "localhost";
 //$dbservername = "localhost:3306"; //HostnameOuIpAdress:PortDuServerDB
 //ou 127.0.0.1  // HostnameOuAddresseIP:PortDuServerSQL  
 //ex: localhost:3306 pour mySQL ou ex: localhost:1433 pour SqlServer 
 $dbusername = "root";	//pour mon uwamp/xammp si admin
 $dbpassword = "root";	//pour xamp mettre vide ""
 $dbname = "db_crud";

 // exemple avec alwaysdata
 // $dbservername = "mysql-dev-frm.alwaysdata.net";	
 // $dbusername = "dev-frm_dbuser";
 // $dbpassword = "password";
 // $dbname = "dev-frm_db";