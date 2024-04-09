<?php
declare(strict_types=1);
require_once __DIR__."/../../inc/class/classautoload.php";
require_once __DIR__."/../../inc/db/db_pdo.php";

class UserDal{
	private $aUsers = array();	// ou []
	private User $user;
	private int $lastId=-1;

	private function clear() :void{
		unset($this->user);
		unset($this->aUsers);
		$this->lastId=-1;
	}

	public function getLastInsertId() :int {
		return $this->lastId;
	}

	public function getUsersList() :?array{
		$ret=null;
		$this->clear();
		$query="SELECT `id`, `name`, `surname`, `birthdate`, `email`, `active` FROM `user` 
			order by name, surname, birthdate";
		$rows = db_select($query);
		if($rows==null){
			$msgError=db_lastErr_Message();
			if(!empty($msgError)){
				trigger_error(
					$msgError,
					E_USER_ERROR);  //ERROR stop le code
			}
		}else{
			foreach ($rows as $row) {
				$user = new User($row["name"], $row["surname"], $row["active"], $row["birthdate"], $row["email"], $row["id"]);
				$this->aUsers[$row['id']] = $user;
			}
			$ret = $this->aUsers;	//DATA(S)
		}
		db_close();
		return $ret;	//NO DATA
	}

	public function getUserbyId(int $id) :?User {
		$ret=null;
		$this->clear();
		$query="SELECT `name`, `surname`, `birthdate`, `email`, `active` FROM `user` where id=:id";
		$queryParam = array(
			":id" => $id,
		);
		$rows = db_select($query, $queryParam);
		if($rows==null){
			$msgError=db_lastErr_Message();
			if(!empty($msgError)){
				trigger_error(
					$msgError,
					E_USER_ERROR);  //ERROR stop le code
			}
		}else{
			foreach ($rows as $row) {
				$this->user = new User($row["name"], $row["surname"], $row["active"], $row["birthdate"], $row["email"], $id);
			}
			$ret = $this->user;	//DATA(S)
		}
		db_close();
		return $ret;	//NO DATA
	}

	public function updateUser(User $usr) :bool {
		$this->clear();
		$query="UPDATE `user` SET `name`=:name,`surname`=:surname,
				`birthdate`=:birthdate,`email`=:email,`active`=:active WHERE id=:id";
		$queryParam = array(
			":id" => $usr->getId(),
			":name" => $usr->getName(),
			":surname" => $usr->getSurname(),
			":email" => $usr->getEmail(),
			":birthdate" => $usr->getBirthdate2Str("Y-m-d"),
			":active" => $usr->getStatus(),
		);
		$result=db_execute($query, $queryParam);
		db_close();
		return $result;
	}

	public function createUser(User $usr) :bool {
		$this->clear();
		$query="INSERT INTO `user`(`id`, `name`, `surname`, `birthdate`, `email`, `active`) 
										VALUES (:id,:name,:surname,:birthdate,:email,:active)";
		$queryParam = array(
			":id" => $usr->getId(),
			":name" => $usr->getName(),
			":surname" => $usr->getSurname(),
			":email" => $usr->getEmail(),
			":birthdate" => $usr->getBirthdate2Str("Y-m-d"),
			":active" => $usr->getStatus(),
		);
		$result=db_execute($query, $queryParam);
		if($result)
			$this->lastId = db_lastId();
		db_close();
		return $result;
	}

	public function deleteUser(User $usr) :bool {
		$this->clear();
		$query="DELETE FROM `user` WHERE id=:id";
		$queryParam = array(
			":id" => $usr->getId(),
		);
		$result=db_execute($query, $queryParam);
		db_close();
		return $result;
	}
}
