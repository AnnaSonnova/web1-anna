<?php

/**
 * Classe Contrôleur des requêtes sur l'entité Utilisateur de l'application admin
 */

class MembreTimbre extends Membre {

  

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
    $this->timbre_id = $_GET['timbre_id'] ?? null;
    $this->img_id = $_GET['img_id'] ?? null;
    $this->oRequetesSQL = new RequetesSQL;
    self::$action = $_GET['action'] ?? 'l';
  }

 
 
  

  



  /**
   * Lister les timbres
   */
  public function listerTimbres() {
    print_r('listerTimbres dans MembreTimbre');
    $timbres = $this->oRequetesSQL->getTimbres();
    $utilId = $_SESSION["oUtilConn"]->utilisateur_id;
    (new Vue)->generer(
      'vListeTimbres',
      [
        'oUtilConn'           => $_SESSION["oUtilConn"],
        'titre'               => 'Encheres',
        'timbres'               => $timbres,
        'classRetour'         => $this->classRetour,  
        'messageRetourAction' => $this->messageRetourAction        
      ],
      'gabarit-frontend-membre');
  }

  /**
   * Lister les timbres
   */
  public function listerTimbreParIdUtilisateur() {

print_r('listerTimbreParIdUtilisateur dans MembreTimbre');
    // $timbre = [];
    // $oTimbre = new Timbre($timbre);
    $utilId = $_SESSION["oUtilConn"]->utilisateur_id;
    // echo "<pre>".  print_r($_SESSION["oUtilConn"]->utilisateur_id, true) . "<pre>"; exit;
    $timbres = $this->oRequetesSQL->getTimbreParIdUtilisateur($utilId
    
  );

   
    // var_dump($timbres);
    // exit;
    (new Vue)->generer(
      'vListeTimbres',
      [
        'oUtilConn'           => $_SESSION["oUtilConn"],
        'titre'               => 'Mes Timbres',
        'timbres'               => $timbres,
        'classRetour'         => $this->classRetour,  
        'messageRetourAction' => $this->messageRetourAction        
      ],
      'gabarit-frontend-membre');
  }


  /**
   * Ajouter un timbre
   */
  public function ajouterTimbre() {
    print_r('ajouterTimbre dans MembreTimbre');
    $utilId = $_SESSION["oUtilConn"]->utilisateur_id;
    $pays= $this->oRequetesSQL->getPays();
    $encheres= $this->oRequetesSQL->getEncheres();
     //echo "<pre>".  print_r($_SESSION["oUtilConn"]->utilisateur_id, true) . "<pre>"; exit;
    $timbre = [];
    $erreurs     = [];
    if (count($_POST) !== 0){
      $timbre = $_POST;
      echo "<pre>".  print_r($timbre) . "<pre>";
       // $img = $_POST;
       //echo "<pre>". print_r($img) . "<pre>";
      $oTimbre = new Timbre($timbre);         
      // $oImg = new Img($img);
      // echo "<pre>". print_r($oImg) . "<pre>";
     
//       //print_r($_FILES); '<br>';
//        $nom_fichier = $_FILES['img_url']['name'];
//  $fichier = $_FILES['img_url']['tmp_name'];

      //var_dump("isi"); exit;
       $erreurs = $oTimbre->getErreurs();
      if (count($erreurs) === 0){
        // $asd = $_SESSION["oUtilConn"]->utilisateur_id;
        // echo "<pre>".  print_r( $asd , true) . "<pre>"; exit;
        $timbre_id=$this->oRequetesSQL->ajouterTimbre([
         
          'timbre_nom'      => $oTimbre->getTimbre_nom() ,
          'timbre_date'   => $oTimbre->getTimbre_date(),
          'timbre_utilisateur_id' =>$oTimbre->getTimbre_utilisateur_id(),
          'timbre_tirage'      => $oTimbre->getTimbre_tirage(),
          'timbre_description'   => $oTimbre->getTimbre_description(),
          'timbre_prix_plancher'   => $oTimbre->getTimbre_prix_plancher(),
          'timbre_dimension'   => $oTimbre->getTimbre_dimension(),
          'timbre_pays_id'   => $oTimbre->getTimbre_pays_id(),
          'timbre_enchere_id' => 
          $oTimbre->getTimbre_enchere_id()
          
        ]);
        // $img_id=$this->oRequetesSQL->ajouterImg(
        //   ['img_url'      => $oImg->getImg_url()] 
         
        // );

        
      //   if(move_uploaded_file($fichier, "images/".$nom_fichier)){
      //     echo "fichier copie";
      
      // }else{
      //     echo "fichier non copie";
      // }
        
        if ( $timbre_id  > 0 ) { // test de la clé de timbre ajouté
          $this->messageRetourAction = "Ajout de timbre numéro $timbre_id  effectué.";
        } else {
          $this->classRetour = "erreur";
          $this->messageRetourAction = "Ajout de timbre non effectué.";
        }
        $this->listerTimbreParIdUtilisateur(); // retour sur la page de liste des timbres
        exit;
       }
    
     
    }

    (new Vue)->generer(
      'vMembreTimbreAjouter',
      [
        'oUtilConn'   =>  $_SESSION["oUtilConn"],
        'titre'       => 'Ajouter un timbre',
        'timbre' => $timbre,
        'pays' => $pays,
        'encheres' => $encheres,
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