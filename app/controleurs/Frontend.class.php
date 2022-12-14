<?php

/**
 * Classe Contrôleur des requêtes de l'interface frontend
 * 
 */

class Frontend extends Routeur {
  private $enchere_id;
  
  
  /**
   * Constructeur qui initialise des propriétés à partir du query string
   * et la propriété oRequetesSQL déclarée dans la classe Routeur
   * 
   */
  public function __construct() {
    $this->enchere_id = $_GET['enchere_id'] ?? null;
   
    $this->oRequetesSQL = new RequetesSQL;
  }


  /**
   * Lister les timbres à l'affiche
   */  
  public function listerFavorit() {
    $encheres = $this->oRequetesSQL->getEncheres();
    (new Vue)->generer("vListeTimbres",
            array(
              'titre'  => "Favorit",
              'encheres'               => $encheres,
            ),
            "gabarit-frontend-accueil");
  }

  public function listerTimbres(){
    //echo "lister timbres" ; 
    $timbres =$this->oRequetesSQL->getTimbres();
    $titre = "Catalogue d'enchères";
    $donnees = ["titre" => $titre, 
    "timbres"=> $timbres, 
  ];
    (new Vue)->generer("vListeTimbres", $donnees, "gabarit-frontend");
  }

  /**
   * Lister les encheres
   */
  public function listerEncheres() {
    // print_r('listerEncheres dans Frontend');
    $encheres = $this->oRequetesSQL->getEncheres();
       //echo "<pre>".  print_r( $encheres) . "<pre>";
       //exit;
    (new Vue)->generer(
      'vListeTimbres',
      [
        'titre'               => 'Encheres',
        'encheres'               => $encheres,      
      ],
      'gabarit-frontend');
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
   * Voir les informations d'une enchere 
   */  
  public function voirEnchere(){
    //print_r( "voir enchere" ); 
    $enchere = false;
    
     if (!is_null($this->enchere_id)) {
      
      $enchere = $this->oRequetesSQL->getEnchere($this->enchere_id);
      //echo "<pre>".  print_r( $enchere) . "<pre>"; exit;
       
     }
    if (!$enchere) throw new Exception("Enchere inexistant.");
    // $nom = $timbre['timbre_nom'];
    $donnees = [
      //"nom" => $nom,
       "enchere"=> $enchere
      ];
    (new Vue)->generer("vTimbre", $donnees, "gabarit-frontend-1enchere");
}
}