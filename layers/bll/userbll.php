<?php
declare(strict_types=1);
require_once __DIR__."/../../inc/class/classautoload.php";

class UserBll
{
	public const CREATE = "CREATE";
	public const READ = "READ";
	public const UPDATE = "UPDATE";
	public const DELETE = "DELETE";

	private string $actionCRUD=self::READ;
	private UserDal $userDal;

	public function getAction() :string {
		return empty($this->actionCRUD)?self::READ:$this->actionCRUD;
	}

	public function setAction(string $actionCRUD):string {
		switch (strtoupper($actionCRUD)) { 	//VOS ACTIONS au minimum le CRUD
			case self::CREATE:	
				$this->actionCRUD=self::CREATE;
				break;
			case self::UPDATE:
				$this->actionCRUD=self::UPDATE;
				break;
			case self::DELETE:
				$this->actionCRUD=self::DELETE;
				break;
			default:	//READ
				$this->actionCRUD=self::READ;
				break;
		}
		return $this->actionCRUD;
	}

	private function init() :void{
		if(!isset($this->userDal))
			$this->userDal = new UserDal();
	}

	public function getUsersList() :?array {
		$this->init();
		return $this->userDal->getUsersList();
	}

	public function getUserbyId(int $id) :?User {
		$this->init();
		return $this->userDal->getUserbyId($id);
	}

	public function updateUser(int $id, string $name, string $surname, int $status,
			?string $birthdate=null, ?string $email=null) :bool {
		$this->init();
		$usr = new User($name, $surname, $status, $birthdate, $email, $id);
		return $this->userDal->updateUser($usr);
	}

	public function getNewUser($id=-1) :User{
		return new User("","",User::STATUS_INACTIVE,null,null, $id);
	}

	public function createUser(?int $id, string $name, string $surname, int $status,
			?string $birthdate=null, ?string $email=null) :bool {
		$this->init();
		$usr = new User($name, $surname, $status, $birthdate, $email, null);
		$bResult = $this->userDal->createUser($usr);
		// if($bResult)
		// 	$this->lastId = $this->userDal->getLastInsertId();
		return $bResult;
	}

	public function getLastInsertId() :int {
		$this->init();
		return $this->userDal->getLastInsertId();
	}
	
	public function deleteUser(int $id) :bool {
		$this->init();
		$usr = $this->getNewUser($id);
		return $this->userDal->deleteUser($usr);
	}
}