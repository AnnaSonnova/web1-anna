<?php

/**
 * Classe Contrôleur des requêtes sur l'entité Utilisateur de l'application admin
 */

class MembreEnchere extends Membre {

  private $enchere_id;

  protected $methodes = [
   
     'aa'           => ['nom'    =>'ajouterTimbreParId', 'droits' => [Utilisateur::PROFIL_MEMBRE]],
     'ae' => ['nom'    => 'ajouterEnchere'],
     'me' => ['nom'    => 'modifierEnchere'],
     's' => ['nom'    => 'supprimerEnchere']
   
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
    $this->timbre_id = $_GET['timbre_id'] ?? null;
    $this->oRequetesSQL = new RequetesSQL;
    self::$action = $_GET['action'] ?? 'l';
    date_default_timezone_set('America/Toronto');
  }

 
 
  

  



  /**
   * Lister les encheres
   */
  public function listerEncheres() {
    //print_r('listerEncheres dans MembreEnchere');
    $encheres = $this->oRequetesSQL->getEncheres();
   
     //$utilId = $_SESSION["oUtilConn"]->utilisateur_id;
      // echo "<pre>".  print_r( $encheres) . "<pre>";
      // exit;
    (new Vue)->generer(
      'vListeTimbresMembre',
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

    // print_r('listerEnchereParIdUtilisateur dans MembreEnchere');
    $utilId = $_SESSION["oUtilConn"]->utilisateur_id;
    // echo "<pre>".  print_r($_SESSION["oUtilConn"]->utilisateur_id, true) . "<pre>"; exit;
    $encheres = $this->oRequetesSQL->getEnchereParIdUtilisateur($utilId
    
  );

    (new Vue)->generer(
      'vListeMesTimbre',
      [
        'oUtilConn'           => $_SESSION["oUtilConn"],
        'titre'               => 'Mes Encheres',
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
     //print_r('ajouterenchere dans MembreEnchere');
    
    //echo "<pre>".  print_r($_SESSION["oUtilConn"]->utilisateur_id, true) . "<pre>"; exit;
    $pays= $this->oRequetesSQL->getPays();
    $enchere = [];
    $erreurs     = [];
    if (count($_POST) !== 0){
      $enchere = $_POST;
      // echo "<pre>".  print_r($enchere) . "<pre>";
      $oEnchere = new Enchere($enchere);         
      $erreurs = $oEnchere->getErreurs();
      // $aujourdui = date('Y-m-d');
      // echo($aujourdui);
      // $dateFin = date('Y-m-d',strtotime($aujourdui."+14 day"));
      // echo($dateFin);
      
      if (count($erreurs) === 0 ) {
        
        $utilId = $_SESSION["oUtilConn"]->utilisateur_id;
        $retour=$this->oRequetesSQL->ajouterEnchere([
          'enchere_date_debut'      => $oEnchere->enchere_date_debut ,   
          'enchere_date_fin'   => $oEnchere->enchere_date_fin ,
          'enchere_utilisateur_id' => $utilId
        ]); 
        //  echo "<pre>".  print_r($retour, true) . "<pre>"; exit;
        $retour=$this->oRequetesSQL->ajouterTimbre([
          'timbre_nom'      => $oEnchere->timbre_nom ,
          'timbre_date'   => $oEnchere->timbre_date,
          'timbre_utilisateur_id'   => $utilId,
          'timbre_tirage'      => $oEnchere->timbre_tirage,
          'timbre_description'   => $oEnchere->timbre_description,
          'timbre_prix_plancher'   => $oEnchere->timbre_prix_plancher,
          'timbre_dimension'   => $oEnchere->timbre_dimension,
          'timbre_pays_id'   => $oEnchere->timbre_pays_id,
          'timbre_enchere_id' =>$retour   
        ]);
        //echo "<pre>".  print_r($retour , true) . "<pre>"; exit;
        $nom_fichier = $_FILES['userfile']['name'];
        $fichier = $_FILES['userfile']['tmp_name'];
         //echo "<pre>".  print_r($_FILES , true) . "<pre>"; exit;
        move_uploaded_file($fichier, "images/".$nom_fichier);
        
        $retour=$this->oRequetesSQL->ajouterImg(
          [
            'img_url'      => $nom_fichier,
          'img_timbre_id'=> $retour] 
      
        );
        if ( $retour  > 0 ) { // test de la clé d'enchere ajouté
          $this->messageRetourAction = "Ajout d'enchere numéro $retour  effectué.";
        } else {
          $this->classRetour = "erreur";
          $this->messageRetourAction = "Ajout d'enchere non effectué.";
        }
          $this->listerEnchereParIdUtilisateur(); // retour sur la page de liste des timbres
           exit;
       } 
    }

    (new Vue)->generer(
      'vMembreTimbreAjouter',
      [
        'oUtilConn'   =>  $_SESSION["oUtilConn"],
        'titre'       => 'Ajouter un enchere',
        'enchere' => $enchere,
        'pays' => $pays,
        'erreurs'     => $erreurs
      ],
      'gabarit-membre');

  }

  
  /**
   * Voir les informations d'une enchere
   * 
   */  
 

  public function voirEnchere(){
    //print_r( "voir enchere sur membreEnchere" ); 
    
     $enchere = false;
     if (!is_null($this->enchere_id)) {
       $enchere         = $this->oRequetesSQL->getEnchere($this->enchere_id);
       //echo "<pre>".  print_r($enchere) . "<pre>";  
     }
     if (!$enchere) throw new Exception("Enchere inexistant.");
     // $nom = $enchere['timbre_nom'];
     $donnees = [
       "oUtilConn"           => $_SESSION["oUtilConn"],
       "enchere"=> $enchere
      ];
    (new Vue)->generer("vTimbreMembre", $donnees, "gabarit-frontend-membre1enchere");
  }

  /**
   * Modifier une enchere
   */
  public function modifierEnchere() {
    print_r( "modifier enchere sur membreEnchere" );
    $pays= $this->oRequetesSQL->getPays(); 
      if (!preg_match('/^\d+$/', 
      $this->enchere_id))
        throw new Exception("Numéro de enchere non renseigné pour une modification");
    if (count($_POST) !== 0){
      $enchere = $_POST;
      // echo "<pre>".  print_r($enchere) . "<pre>";
      $oEnchere = new Enchere($enchere); 
      $erreurs = $oEnchere->getErreurs();
      if (count($erreurs) === 0 ) {
        
        $utilId = $_SESSION["oUtilConn"]->utilisateur_id;
        $retour=$this->oRequetesSQL->modifierEnchere([
          'enchere_date_debut'      => $oEnchere->enchere_date_debut,   
          'enchere_date_fin'   => $oEnchere->enchere_date_fin,
          'enchere_utilisateur_id' => $utilId
        ]); 
         //echo "<pre>".  print_r($retour, true) . "<pre>"; exit;
        $retour=$this->oRequetesSQL->modifierTimbre([
          'timbre_nom'      => $oEnchere->timbre_nom ,
          'timbre_date'   => $oEnchere->timbre_date,
          'timbre_utilisateur_id'   => $utilId,
          'timbre_tirage'      => $oEnchere->timbre_tirage,
          'timbre_description'   => $oEnchere->timbre_description,
          'timbre_prix_plancher'   => $oEnchere->timbre_prix_plancher,
          'timbre_dimension'   => $oEnchere->timbre_dimension,
          'timbre_pays_id'   => $oEnchere->timbre_pays_id,
          'timbre_enchere_id' =>$retour   
        ]);
        //echo "<pre>".  print_r($retour , true) . "<pre>"; exit;
        $nom_fichier = $_FILES['userfile']['name'];
        $fichier = $_FILES['userfile']['tmp_name'];
         //echo "<pre>".  print_r($_FILES , true) . "<pre>"; exit;
        move_uploaded_file($fichier, "images/".$nom_fichier);
        // $dernierTimbreId = $enchere_id[0]["timbre_id"];
        // echo "<pre>".  print_r($nom_fichier, true) . "<pre>"; exit;
        $retour=$this->oRequetesSQL->modifierImg(
          ['img_url'      => $nom_fichier,
          'img_timbre_id'=> $retour] 
      
        );
        
          if ($retour === true)  {
            $this->messageRetourAction = "Modification de enchere numéro $this->enchere_id effectuée.";    
          } else {  
            $this->classRetour = "erreur";
            $this->messageRetourAction = "Modification de enchere numéro $this->enchere_id non effectuée.";
          }
          // $this->listerTimbres();
          // exit;
       
      }
    } else {
      $enchere = $this->oRequetesSQL->getEnchere($this->enchere_id);
      $erreurs = [];
    } 

    (new Vue)->generer(
      'vMembreEnchereModifier',
      [
        'oUtilConn'   => $_SESSION["oUtilConn"],
        'titre'       => "Modifier enchere numéro $this->enchere_id",
        // 'titre'       => "Modifier enchere numéro $this->timbre_id",
        'enchere' => $enchere,
        'pays' => $pays,
        'erreurs'     => $erreurs
      ],
      'gabarit-membre');

    
  }

  /**
   * Supprimer enchere
   */
  public function supprimerEnchere() {

      if(!is_null($this->enchere_id)){
      $enchere = $this->oRequetesSQL->getEnchere($this->enchere_id);
      //echo "<pre>".  print_r($enchere, true) . "<pre>";  exit;
      $enchere_id = $enchere["enchere_id"];
      //echo "<pre>".  print_r($enchere_id, true) . "<pre>";  exit;
      $timbre_id = $enchere["timbre_id"];
      //echo "<pre>".  print_r($timbre_id, true) . "<pre>";  exit;
      $img_id = $enchere["img_id"];
      //echo "<pre>".  print_r($img_id, true) . "<pre>";  exit;
      $retour = $this->oRequetesSQL->supprimerImg($img_id );
      $retour = $this->oRequetesSQL->supprimerTimbre($timbre_id);
      $retour = $this->oRequetesSQL->supprimerEnchere($enchere_id);
      if ($retour === false) $this->classRetour = "erreur";
      $this->messageRetourAction = "Suppression de l'enchere numéro $this->enchere_id ".($retour ? "" : "non ")."effectuée.";
      $this->listerEnchereParIdUtilisateur();
    }
    
   
    

      // throw new Exception("Numéro d'enchere incorrect pour une suppression.");
      //   //echo "<pre>".  print_r($this->enchere_id, true) . "<pre>"; exit; 
  }

  public function rechercheEnchere(){
    if(count($_POST)!==0){
      $valeurRecherchee = $_POST["enchere_recherchee"];

      $retour = $this->oRequetesSQL->rechercheEnchere($valeurRecherchee);
      if($retour){
        $this->listerEncheres($retour);
      }else{
        $retour = $this->oRequetesSQL->getEncheres();
        $this->listerEncheres($retour);
      }
    }
  }
  
  
}