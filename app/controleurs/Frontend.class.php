<?php

/**
 * Classe Contrôleur des requêtes de l'interface frontend
 * 
 */

class Frontend extends Routeur {

  // private $timbre_id;
  private $timbre_id;
  
  /**
   * Constructeur qui initialise des propriétés à partir du query string
   * et la propriété oRequetesSQL déclarée dans la classe Routeur
   * 
   */
  public function __construct() {
    // $this->timbre_id = $_GET['timbre_id'] ?? null;
    $this->timbre_id = $_GET['timbre_id'] ?? null;  
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
      // $realisateurs = $this->oRequetesSQL->getRealisateurstimbre($this->timbre_id);
      // $pays         = $this->oRequetesSQL->getPaystimbre($this->timbre_id);
      // $acteurs      = $this->oRequetesSQL->getActeurstimbre($this->timbre_id);
      // $seancesTemp  = $this->oRequetesSQL->getSeancestimbre($this->timbre_id);
      // $seances = [];
      // foreach ($seancesTemp as $seance) {
      //   $seances[$seance['seance_date']]['jour']     = $seance['seance_jour'];
      //   $seances[$seance['seance_date']]['heures'][] = $seance['seance_heure'];
      // }
    }
    if (!$timbre) throw new Exception("Timbre inexistant.");
    $nom = $timbre['timbre_nom'];
    $donnees = ["nom" => $nom, "timbre"=> $timbre];
    (new Vue)->generer("vTimbre", $donnees, "gabarit-frontend");
}
}