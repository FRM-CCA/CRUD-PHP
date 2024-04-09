<?php
//Pour faire propre il faudrait placer ces informations
//dans un fichier externe type XML ici include
require_once __DIR__."/../../config/constDb.php"; //ajout lib pdo

//global $dbconn, $dbrowcount, $dblastid, $dblastErr, $dblastErrInfo;
$dbconn = null; //variable de cnx db (version PDO)
$dbrowcount = -1;
$dblastid = -1;
$dblastErr = null;
$dblastErrInfo = null;

function db_init()
{
  global $dbrowcount, $dblastid, $dblastErr, $dblastErrInfo; //pour utiliser les variable ci-dessus
  $dbrowcount = $dblastid = -1;
  $dblastErr = $dblastErrInfo = null;
}

function db_connection()
{
  global $dbconn, $dbservername, $dbname, $dbusername, $dbpassword, $dblastErr;
  $dblastErr = null;
  $dsn = "mysql:host=$dbservername;dbname=$dbname;charset=utf8";
//var_dump($dsn);
  $options = array(
    PDO::ATTR_PERSISTENT => true,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  );
  try {
    $dbconn = new PDO($dsn, $dbusername, $dbpassword, $options);
    //$dbconn->exec("SET CHARACTER SET utf8"); // Ajout Encodage du texte
    //SI PB TESTER $dbconn->exec("SET NAMES 'utf8'");
  } catch (PDOException $e) {
    $dblastErr = $e;
    //exit("Db Connection failed: " . $e->getMessage() . " Try later... :(");
    return null;
  }
  return $dbconn;
}

function db_close()
{
  global $dbconn;
  return $dbconn = null;
}

function db_select($sqlQuery, $paramArray = null)
{
  global $dbrowcount, $dblastErr, $dblastErrInfo;
  db_init();
//dump($sqlQuery, $paramArray);
  $stmt = db_query($sqlQuery, $paramArray);
  try {
    $result = $stmt->execute();
  } catch (PDOException $e) {
//var_dump($stmt->errorInfo());
    $dblastErr = $e;
    $dblastErrInfo = $stmt->errorInfo();;
    //echo $e->getMessage();
    //exit("Db Select failed: " . $e->getMessage());
    return db_close();
  }
  if ($result) {
    $dbrowcount = $stmt->rowCount();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  return null;
}

function db_execute($sqlQuery, $paramArray = null)
{
  global $dbconn, $dbrowcount, $dblastid, $dblastErr, $dblastErrInfo;
  $result;
  db_init();
  $stmt = db_query($sqlQuery, $paramArray);
  try {
    $result = $stmt->execute();
  } catch (PDOException $e) {
    $dblastErr = $e;
    $dblastErrInfo = $stmt->errorInfo();
    //echo $e->getMessage();
    //echo $e->getCode();
    //die("Db Execute failed: " . $e->getMessage()) . " - " .$dblastErrInfo;
    $result = false;
  }

  if ($result) {
    $dblastid = $dbconn->lastInsertId();
    $dbrowcount = $stmt->rowCount();
    return true;
  }
  return false;
}

function db_query($sqlQuery, $paramArray = null)
{
  global $dbconn;
  if ($dbconn == null) {
    if (db_connection() == null) {
      return null;
    }
  }
  $stmt = $dbconn->prepare($sqlQuery);
  if ($paramArray != null && is_array($paramArray)) {
    foreach ($paramArray as $x_key => $x_value) {
      $stmt->bindValue($x_key, $x_value);
    }
  }
  return $stmt;
}

function db_lastId():int
{
  global $dblastid;
  return $dblastid;
}

function db_lastRowcount()
{
  global $dbrowcount;
  return $dbrowcount;
}

function db_rowcount($rows=null)  //idem functions/rowcount()
{
  if(!isset($rows) || $rows==null)
		return -1;
	try {
		return count($rows);
	} catch (\Throwable $th) {
		return -1; 
	}
}

function db_lastErr_Code()
{
  global $dblastErr;
  if($dblastErr==null)
    return 0;
  return $dblastErr->getCode();
}

function db_lastErr_Message()
{
  global $dblastErr;
  if($dblastErr==null)
    return "";
  return $dblastErr->getMessage();
}

function db_lastErr_Info()
{
//  global $dblastErrInfo;
//  if($dblastErrInfo==null)
// 	return "";
//  return $dblastErrInfo[1]; // 0, 1 ou 2 // ne fonctionne pas???
  global $dblastErr;
  if($dblastErr==null)
    return "";
  $str = explode(":",$dblastErr->getMessage());
  $code = trim(filter_var($str[2], FILTER_SANITIZE_NUMBER_INT));
  return $code;
}