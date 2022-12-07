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
      
      //var_dump("isi"); exit;
       $erreurs = $oTimbre->erreurs;
      if (count($erreurs) === 0){
        $timbre_id=$this->oRequetesSQL->ajouterTimbre([
          'timbre_nom'      => $oTimbre->timbre_nom,
          'timbre_date'   => $oTimbre->timbre_date,
          'timbre_couleur' => $oTimbre->timbre_couleur,
          'timbre_tirage'      => $oTimbre->timbre_tirage,
          'timbre_description'   => $oTimbre->timbre_description,
          'timbre_prix_plancher'   => $oTimbre->timbre_prix_plancher,
          'timbre_dimension'   => $oTimbre->timbre_dimension,
          'timbre_pays_id'   => $oTimbre->timbre_pays_id,
          'timbre_enchere_id' => $oTimbre->timbre_enchere_id
        ]);
        if ( $timbre_id  > 0) { // test de la clé de l'utilisateur ajouté
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
    throw new Exception("Développement en cours.");
  }
  
  /**
   * Supprimer un timbre
   */
  public function supprimerTimbre() {
    throw new Exception("Développement en cours.");
  }
}