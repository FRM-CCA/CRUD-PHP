<?php
declare(strict_types=1);

class User
{
   public const STATUS_ACTIVE = 1;
   public const STATUS_INACTIVE = 0;

   private ?int $userid;
   private string $username;
   private string $usersurname;
   private ?string $useremail;
   private ?DateTime $userbirthdate;
   private int $userstatus;

   // public function __construct(public string $username, private int $status = self::STATUS_ACTIVE){
   // }
   public function __construct(string $userName, string $userSurname, int $userStatus = self::STATUS_INACTIVE,
         ?string $userBirthdate=null, ?string $userEmail=null, ?int $userId=null){
      $this->userid = $userId;
      $this->username = $userName;
      $this->usersurname = $userSurname;
      $this->useremail = $userEmail;
      $this->setBirthdate($userBirthdate);
      $this->setStatus($userStatus);
   }

   public function setStatus(int $userStatus = self::STATUS_INACTIVE): void {
      if (!in_array($userStatus, [self::STATUS_ACTIVE, self::STATUS_INACTIVE])) {
         trigger_error(sprintf('Le status %s n\'est pas valide. Les status possibles sont : %s', 
            $userStatus, implode(', ', [self::STATUS_ACTIVE, self::STATUS_INACTIVE])), E_USER_ERROR);
      };
      $this->userstatus = $userStatus;
   }

   public function getStatus(): int { 
      return $this->userstatus;
   }

   public function getId(): ?int { 
      return $this->userid;
   }

   public function getName(): string { 
      return $this->username;
   }

   public function getSurname(): string { 
      return $this->usersurname;
   }

   public function getEmail(): ?string { 
      return $this->useremail;
   }

   public function getBirthdate(): ?DateTime { 
      return $this->userbirthdate;
   }

   public function setBirthdate(?string $value): void { 
      $bd=null;
      try {
         $bd = new DateTime($value);
         //return $bd->format('d/m/Y H:i:s');
      } catch (\Throwable $th) {
         $bd=null;
      }
      $this->userbirthdate=$bd;
   }

   public function getBirthdate2Str(string $format="Y-m-d H:i:s"): ?string { 
      if(is_null($this->getBirthdate()) || empty($this->getBirthdate())){
         return null;
      }
      return date_format($this->getBirthdate(), $format);
   }

   public function __toString() :string
   {
      return "<BR>id:".$this->userid . "-name:" . $this->username. "-surname:" . $this->usersurname 
      . "-email:" . $this->useremail . "-status:" . $this->userstatus . "-birthdate:" . $this->getBirthdate2Str();
   }
}

function Test_UserCls(){
   $dDate = new DateTime();
   $user1 = new User("toto", "tototo", User::STATUS_ACTIVE, 
      $dDate->format('Y-m-d H:i:s'), null,  null);
   echo $user1->getName();
   echo $user1->getBirthdate2Str();
   echo $user1->__toString();

   $user2 = new User("titi", "titi", User::STATUS_INACTIVE);
   echo $user2->getName();
   echo $user2->getBirthdate2Str();
   echo $user2;   //idem __toString car echo prend une string
}
//Test_UserCls();
//WORK IN PHP 8.2