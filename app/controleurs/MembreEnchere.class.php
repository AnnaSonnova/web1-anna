<?php

/**
 * Classe Contrôleur des requêtes sur l'entité Utilisateur de l'application admin
 */

class MembreEnchere extends Membre {

  

  protected $methodes = [
   
     'aa'           => ['nom'    =>'ajouterTimbreParId', 'droits' => [Utilisateur::PROFIL_MEMBRE]],
     'at' => ['nom'    => 'ajouterTimbre']
   
  ];

  /**
   * Constructeur qui initialise des propriétés à partir du query string
   * et la propriété oRequetesSQL déclarée dans la classe Routeur
   * 
   */
  public function __construct() {
    $this->utilisateur_id = $_GET['utilisateur_id'] ?? null; 
    $this->utilisateur_nom = $_GET['utilisateur_nom'] ?? null; 
    $this->utilisateur_prenom = $_GET['utilisateur_prenom'] ?? null; 
    $this->enchere_id = $_GET['enchere_id'] ?? null;
   
    $this->oRequetesSQL = new RequetesSQL;
    self::$action = $_GET['action'] ?? 'l';
  }

 
 
  

  



  /**
   * Lister les encheres
   */
  public function listerEncheres() {
    print_r('listerEncheres dans MembreEnchere');
    $encheres = $this->oRequetesSQL->getEncheres();
    $utilId = $_SESSION["oUtilConn"]->utilisateur_id;
    (new Vue)->generer(
      'vListeTimbres',
      [
        'oUtilConn'           => $_SESSION["oUtilConn"],
        'titre'               => 'Encheres',
        'encheres'               => $encheres,
        'classRetour'         => $this->classRetour,  
        'messageRetourAction' => $this->messageRetourAction        
      ],
      'gabarit-frontend-membre');
  }

  /**
   * Lister les timbres
   */
  public function listerEnchereParIdUtilisateur() {

print_r('listerEnchereParIdUtilisateur dans MembreEnchere');
    // $timbre = [];
    // $oTimbre = new Timbre($timbre);
    $utilId = $_SESSION["oUtilConn"]->utilisateur_id;
    // echo "<pre>".  print_r($_SESSION["oUtilConn"]->utilisateur_id, true) . "<pre>"; exit;
    $encheres = $this->oRequetesSQL->getEnchereParIdUtilisateur($utilId
    
  );

   
    // var_dump($timbres);
    // exit;
    (new Vue)->generer(
      'vListeTimbres',
      [
        'oUtilConn'           => $_SESSION["oUtilConn"],
        'titre'               => 'Mes Timbres',
        'encheres'               => $encheres,
        'classRetour'         => $this->classRetour,  
        'messageRetourAction' => $this->messageRetourAction        
      ],
      'gabarit-frontend-membre');
  }


  /**
   * Ajouter un timbre
   */
  public function ajouterEnchere() {
    print_r('ajouterenchere dans MembreEnchere');
    $utilId = $_SESSION["oUtilConn"]->utilisateur_id;
   
     //echo "<pre>".  print_r($_SESSION["oUtilConn"]->utilisateur_id, true) . "<pre>"; exit;
    $enchere = [];
    $erreurs     = [];
    if (count($_POST) !== 0){
      $enchere = $_POST;
      echo "<pre>".  print_r($enchere) . "<pre>";
      $oEnchere = new Enchere($enchere);         

      //var_dump("isi"); exit;
       $erreurs = $oEnchere->getErreurs();
      if (count($erreurs) === 0){
        // $asd = $_SESSION["oUtilConn"]->utilisateur_id;
        // echo "<pre>".  print_r( $asd , true) . "<pre>"; exit;
        $enchere_id=$this->oRequetesSQL->ajouterEnchere([
         
          'enchere_date_debut'      => $oEnchere->enchere_date_debut,
          'enchere_date_fin'   => $oEnchere->enchere_date_fin,
          'enchere_utilisateur_id' =>$oEnchere->enchere_utilisateur_id 
        ]); 
        
        if ( $enchere_id  > 0 ) { // test de la clé d'enchere ajouté
          $this->messageRetourAction = "Ajout d'enchere numéro $enchere_id  effectué.";
        } else {
          $this->classRetour = "erreur";
          $this->messageRetourAction = "Ajout d'enchere non effectué.";
        }
        // $this->listerTimbreParIdUtilisateur(); // retour sur la page de liste des timbres
        // exit;
       } 
    }

    (new Vue)->generer(
      'vMembreTimbreAjouter',
      [
        'oUtilConn'   =>  $_SESSION["oUtilConn"],
        'titre'       => 'Ajouter un timbre',
        'enchere' => $enchere,
        'erreurs'     => $erreurs
      ],
      'gabarit-membre');

  }

  
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
    (new Vue)->generer("vTimbre", $donnees, "gabarit-frontend-membre");
}
  
  
}