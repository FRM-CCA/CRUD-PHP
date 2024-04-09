<?php

declare(strict_types=1);

class Pont
{
   private const UNITE = 'm²';

    // Définition de la propriété statique. Elle sera partagée
   private static int $nombrePietons = 0;

   private float $longueur=0;
   private float $largeur=0;

   //un seul constructeur paramétré mais avec php référe construteur sans parametre
//    public function __construct(float $longueur=50, float $largeur=1)
//    {
//        //$this->longueur = $longueur;
//        //$this->largeur = $largeur;
//        $this->setLongueur($longueur);
//        $this->setLargeur($largeur);
//    }
   public function __construct()
   {
       $this->setLongueur(50);
       $this->setLargeur(1);
   }
   public function init(float $longueur=50, float $largeur=1)
   {
       $this->setLongueur($longueur);
       $this->setLargeur($largeur);
   }

   public function __destruct() //pas obligatoire
   {
        print "Destroying " . __CLASS__ . "\n<br>";
   }

   public static function validerTaille(float $taille): bool{
        if ($taille < 50.0) {
            trigger_error(
                'La Longueur est trop petite. (min 50m)',
                E_USER_NOTICE  //ne stop pas le code
            );
            return false;
        }
        return true;
   }

   public static function getPietion(){
        return self::$nombrePietons;
   }
   public static function addPietion(){
        self::$nombrePietons++;
   }

   public function nouveauPieton()
   {
       // Mise à jour de la propriété statique.
       self::addPietion();
   }

   //getter/setter Longueur
   public function setLongueur(float $longueur): void
   {
        if ($longueur < 0) {
            trigger_error(
                'La longueur est trop courte. (min 1)',
                E_USER_ERROR);  //stop le code
        }
        self::validerTaille($longueur);
        
        $this->longueur = $longueur;
   }
   public function getLongueur(): float {
        return $this->longueur;
   }
   //getter/setter Largeur
   public function setLargeur(float $largeur): void
   {
        if ($largeur < 0) {
            trigger_error(
                'La largeur est trop courte. (min 1)',
                E_USER_ERROR);  //stop le code
        }
        $this->largeur = $largeur;
   }
   public function getLargueur(): float {
        return $this->largeur;
   }

   public function getSurface(): float
   {
      return $this->longueur * $this->largeur ;
   }
   public function getSurfaceStr(): string
   {
      return $this->getSurface() ." ". self::UNITE;
   }
}

function Test_PontCls(){
$towerBridge = new Pont;
$towerBridge->setLongueur(286.0);
$towerBridge->setLargeur(15.0);

$manhattanBridge = new Pont;
$manhattanBridge->setLongueur(2089.0);
$manhattanBridge->setLargeur(36.6);

$towerBridgeSurface = $towerBridge->getSurface();
$manhattanBridgeSurface = $manhattanBridge->getSurface();
$manhattanBridgeSurfaceStr = $manhattanBridge->getSurfaceStr();

Pont::validerTaille(49);
Pont::validerTaille(51);

var_dump($towerBridgeSurface);
echo "<br>";
var_dump($manhattanBridgeSurface);
echo "<br>";
var_dump($manhattanBridgeSurfaceStr);
echo "<br>";
$pontLondres = new Pont;
$pontLondres->nouveauPieton();
$pontLondres->nouveauPieton();

$pontManhattan = new Pont;
$pontManhattan->nouveauPieton();
echo Pont::getPietion();
echo "<br>";

$pontPanam = new Pont(50, 50);   //avec constructeur (eviter)
echo $pontPanam->getSurface();
echo "<br>";

$pontPanam = new Pont();   //avec function
echo $pontPanam->init(50,50);
echo $pontPanam->getSurface();
echo "<br>";
}

Test_UserCls();
//WORK IN PHP 8.2