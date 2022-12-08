<?php

/**
 * Classe Contrôleur des requêtes sur l'entité timbre de l'application admin
 */

class AdminTimbre extends Admin {

  protected $methodes = [
    'l' => ['nom'    => 'listerTimbres',   'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR, Utilisateur::PROFIL_MEMBRE]],
    'a' => ['nom'    => 'ajouterTimbre',   'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR, Utilisateur::PROFIL_MEMBRE]],
    'm' => ['nom'    => 'modifierTimbre',  'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR, Utilisateur::PROFIL_MEMBRE]],
    's' => ['nom'    => 'supprimerTimbre', 'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR, Utilisateur::PROFIL_MEMBRE]]
  ];

  /**
   * Constructeur qui initialise des propriétés à partir du query string
   * et la propriété oRequetesSQL déclarée dans la classe Routeur
   * 
   */
  public function __construct() {
    $this->timbre_id = $_GET['timbre_id'] ?? null;
    $this->oRequetesSQL = new RequetesSQL;
  }

  /**
   * Lister les timbres
   */
  public function listerTimbres() {
    $timbres = $this->oRequetesSQL->getTimbres('admin');
    (new Vue)->generer(
      'vAdminTimbres',
      [
        'oUtilConn'           => self::$oUtilConn,
        'titre'               => 'Gestion des timbres',
        'timbres'               => $timbres,
        'classRetour'         => $this->classRetour,  
        'messageRetourAction' => $this->messageRetourAction        
      ],
      'gabarit-admin');
  }

/**
   * Ajouter un timbre
   */
  public function ajouterTimbre() {
    $timbre = [];
    $erreurs     = [];
    if (count($_POST) !== 0){
      $timbre = $_POST;
      $oTimbre = new Timbre($timbre);
//       $img = $_POST;
//       print_r($_FILES); '<br>';
// print_r($_POST);
//       $oImg = new Img($img);
//       $nom_fichier = $_FILES['userfile']['name'];
// $fichier = $_FILES['userfile']['tmp_name'];

      //print_r($oTimbre);
      //var_dump("isi"); exit;
       $erreurs = $oTimbre->getErreurs();
      if (count($erreurs) === 0){
        
        $timbre_id=$this->oRequetesSQL->ajouterTimbre([
         
          'timbre_nom'      => $oTimbre->getTimbre_nom() ,
          'timbre_date'   => $oTimbre->getTimbre_date(),
          'timbre_couleur' => $oTimbre->getTimbre_couleur(),
          'timbre_tirage'      => $oTimbre->getTimbre_tirage(),
          'timbre_description'   => $oTimbre->getTimbre_description(),
          'timbre_prix_plancher'   => $oTimbre->getTimbre_prix_plancher(),
          'timbre_dimension'   => $oTimbre->getTimbre_dimension(),
          'timbre_pays_id'   => $oTimbre->getTimbre_pays_id(),
          'timbre_enchere_id' => 
          $oTimbre->getTimbre_enchere_id(),
          // 'img_url' => $oImg->getImg_url()
          
        ]);

        
      //   if(move_uploaded_file($fichier, "images/".$nom_fichier)){
      //     echo "fichier copie";
      
      // }else{
      //     echo "fichier non copie";
      // }
        
        if ( $timbre_id  > 0) { // test de la clé de timbre ajouté
          $this->messageRetourAction = "Ajout de l'utilisateur numéro $timbre_id  effectué.";
        } else {
          $this->classRetour = "erreur";
          $this->messageRetourAction = "Ajout de timbre non effectué.";
        }
        $this->listerTimbres(); // retour sur la page de liste des timbres
        exit;
       }
    
     
    }

    (new Vue)->generer(
      'vAdminTimbreAjouter',
      [
        'oUtilConn'   => self::$oUtilConn,
        'titre'       => 'Ajouter un timbre',
        'timbre' => $timbre,
        'erreurs'     => $erreurs
      ],
      'gabarit-admin');

  }

  /**
   * Modifier un timbre
   */
  public function modifierTimbre() {
    
    //  if (!preg_match('/^\d+$/', 
    //  $this->timbre_id))
    //    throw new Exception("Numéro de la timbre non renseigné pour une modification");
    if (count($_POST) !== 0){
      $timbre = $_POST;
      $oTimbre = new Timbre($timbre);
      $erreurs = $oTimbre->getErreurs();
      if (count($erreurs) === 0) {
        $retour = $this->oRequetesSQL->modifierTimbre([
          'timbre_nom'      => $oTimbre->getTimbre_nom() ,
          'timbre_date'   => $oTimbre->getTimbre_date(),
          'timbre_couleur' => $oTimbre->getTimbre_couleur(),
          'timbre_tirage'      => $oTimbre->getTimbre_tirage(),
          'timbre_description'   => $oTimbre->getTimbre_description(),
          'timbre_prix_plancher'   => $oTimbre->getTimbre_prix_plancher(),
          'timbre_dimension'   => $oTimbre->getTimbre_dimension(),
          'timbre_pays_id'   => $oTimbre->getTimbre_pays_id(),
          'timbre_enchere_id' => $oTimbre->getTimbre_enchere_id()
        ]);
        
          if ($retour === true)  {
            $this->messageRetourAction = "Modification de timbre numéro $this->timbre_id effectuée.";    
          } else {  
            $this->classRetour = "erreur";
            $this->messageRetourAction = "Modification de timbre numéro $this->timbre_id non effectuée.";
          }
          $this->listerTimbres();
          exit;
       
      }
    } else {
      $timbre = $this->oRequetesSQL->getTimbre($this->timbre_id);
      $erreurs = [];
    } 

    (new Vue)->generer(
      'vAdminTimbreModifier',
      [
        'oUtilConn'   => self::$oUtilConn,
        'titre'       => "Modifier timbre numéro $this->timbre_id",
        'timbre' => $timbre,
        'erreurs'     => $erreurs
      ],
      'gabarit-admin');

    
  }
  
  /**
   * Supprimer un timbre
   */
  public function supprimerTimbre() {
    if (!preg_match('/^\d+$/', $this->timbre_id))
      throw new Exception("Numéro du timbre incorrect pour une suppression.");

    $retour =$this->oRequetesSQL->supprimerTimbre($this->timbre_id);
    if ($retour === false) $this->classRetour = "erreur";
    $this->messageRetourAction = "Suppression de timbre numéro $this->timbre_id ".($retour ? "" : "non ")."effectuée.";
    $this->listerTimbres();
    
  }
}