<?php

/**
 * Classe Contrôleur des requêtes sur l'entité Utilisateur de l'application admin
 */

class MembreEnchere extends Membre {

  

  protected $methodes = [
   
     'aa'           => ['nom'    =>'ajouterTimbreParId', 'droits' => [Utilisateur::PROFIL_MEMBRE]],
     'ae' => ['nom'    => 'ajouterEnchere']
   
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
    // print_r('ajouterenchere dans MembreEnchere');
    
    //echo "<pre>".  print_r($_SESSION["oUtilConn"]->utilisateur_id, true) . "<pre>"; exit;
    $pays= $this->oRequetesSQL->getPays();
    $enchere = [];
    $erreurs     = [];
    if (count($_POST) !== 0){
      $enchere = $_POST;
      // [
      //   'enchere_date_debut'=>$_POST['enchere_date_debut'], 
      //   'enchere_date_fin'=>$_POST['enchere_date_fin']];
      // echo "<pre>".  print_r($enchere) . "<pre>";
      $oEnchere = new Enchere($enchere);         

      $erreurs = $oEnchere->getErreurs();
      
      // $timbre =$_POST;
      // // [
      // //   'timbre_nom'=>$_POST['timbre_nom'], 
      // //   'timbre_date'=>$_POST['timbre_date'],
      // //   // 'timbre_utilisateur_id'=>$_POST['timbre_utilisateur_id'],
      // //   'timbre_tirage'=>$_POST['timbre_tirage'],
      // //   'timbre_description'=>$_POST['timbre_description'],
      // //   'timbre_prix_plancher'=>$_POST['timbre_prix_plancher'],
      // //   'timbre_dimension'=>$_POST['timbre_dimension'],
      // //   'timbre_pays_id'=>$_POST['timbre_pays_id'],
      // //    'timbre_enchere_id'=>$_POST['timbre_enchere_id'],
      
      // // ];
      // // echo "<pre>".  print_r($timbre) . "<pre>";
      // $oTimbre = new Timbre($timbre);
      // $erreursTimb = $oTimbre->getErreurs();
      // $img = $_POST;
      // // [
      // //   'img_url'=>$_POST['img_url'], 
      // //   'img_timbre_id'=>$_POST['img_timbre_id']]; 
      //   echo "<pre>".  print_r($img) . "<pre>";
      // $oImg = new Img($img);
      // $erreursImg = $oImg->getErreurs();
      if (count($erreurs) === 0 ) {
        
        $utilId = $_SESSION["oUtilConn"]->utilisateur_id;
        $retour=$this->oRequetesSQL->ajouterEnchere([
          
          'enchere_date_debut'      => $oEnchere->enchere_date_debut,
              
          'enchere_date_fin'   => $oEnchere->enchere_date_fin,
          'enchere_utilisateur_id' => $utilId
        ]); 
        
        //  echo "<pre>".  print_r($enchere_id , true) . "<pre>"; exit;
        // $dernierEnchereId = $enchere_id[0]["enchere_id"];
     
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
      // $dernierTimbreId = $enchere_id[0]["timbre_id"];
      // echo "<pre>".  print_r($nom_fichier, true) . "<pre>"; exit;
      $enchere_id=$this->oRequetesSQL->ajouterImg(
        ['img_url'      => $nom_fichier,
        'img_timbre_id'=> $retour] 
      
      );
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
        'titre'       => 'Ajouter un enchere',
        'enchere' => $enchere,
        'pays' => $pays,
        'erreurs'     => $erreurs
      ],
      'gabarit-membre');

  }

  
//   /**
//    * Voir les informations d'une timbre
//    * 
//    */  
 

//   public function voirTimbre(){
//     //echo "voir timbre" ; 
//     $timbre = false;
//     if (!is_null($this->timbre_id)) {
      
//       $timbre         = $this->oRequetesSQL->getTimbre($this->timbre_id);
      
//     }
//     if (!$timbre) throw new Exception("Timbre inexistant.");
//     $nom = $timbre['timbre_nom'];
//     $donnees = ["nom" => $nom, "timbre"=> $timbre];
//     (new Vue)->generer("vTimbre", $donnees, "gabarit-frontend-membre");
// }
  
  
}