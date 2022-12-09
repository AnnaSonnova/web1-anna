<?php

/**
 * Classe Contrôleur des requêtes de l'interface frontend
 * 
 */

class Frontend extends Routeur {

  // private $timbre_id;
  private $timbre_id;
  private $img_id;
  
  /**
   * Constructeur qui initialise des propriétés à partir du query string
   * et la propriété oRequetesSQL déclarée dans la classe Routeur
   * 
   */
  public function __construct() {
    // $this->timbre_id = $_GET['timbre_id'] ?? null;
    $this->timbre_id = $_GET['timbre_id'] ?? null;
    // $this->img_id = $_GET['img_id'] ?? null;
    // $this->oUser = $_SESSION['oUser'] ?? null;  
    $this->oRequetesSQL = new RequetesSQL;
  }


  // /**
  //  * Lister les timbres à l'affiche
  //  * 
  //  */  
  // public function listerAlaffiche() {
  //   $timbres = $this->oRequetesSQL->gettimbres('enSalle');
  //   (new Vue)->generer("vListetimbres",
  //           array(
  //             'titre'  => "À l'affiche",
  //             'timbres' => $timbres
  //           ),
  //           "gabarit-frontend");
  // }

  public function listerTimbres(){
    //echo "lister timbres" ; 
    $timbres =$this->oRequetesSQL->getTimbres();
    $titre = "Catalogue d'enchères";
    $donnees = ["titre" => $titre, "timbres"=> $timbres];
    (new Vue)->generer("vListeTimbres", $donnees, "gabarit-frontend");
  }

  // public function listerImg(){
  //   echo "lister img" ; 
  //   $img =$this->oRequetesSQL->getImg();
  //   $titre = "Catalogue d'enchères";
  //   $donnees = ["titre" => $titre, "img"=> $img];
  //   (new Vue)->generer("vListeTimbres", $donnees, "gabarit-frontend");
  // }

  // /**
  //  * Lister les timbres diffusés prochainement
  //  * 
  //  */  
  // public function listerProchainement() {
  //   $timbres = $this->oRequetesSQL->gettimbres('prochainement');
  //   (new Vue)->generer("vListetimbres",
  //           array(
  //             'titre'  => "Prochainement",
  //             'timbres' => $timbres
  //           ),
  //           "gabarit-frontend");
  // }

  /**
   * Voir les informations d'une timbre
   * 
   */  
 

  public function voirTimbre(){
    //echo "voir timbre" ; 
    $timbre = false;
    if (!is_null($this->timbre_id)) {
      
      $timbre         = $this->oRequetesSQL->getTimbre($this->timbre_id);
      
    }
    if (!$timbre) throw new Exception("Timbre inexistant.");
    $nom = $timbre['timbre_nom'];
    $donnees = ["nom" => $nom, "timbre"=> $timbre];
    (new Vue)->generer("vTimbre", $donnees, "gabarit-frontend");
}
}