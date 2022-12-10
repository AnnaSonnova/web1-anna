<?php

/**
 * Classe Contrôleur des requêtes de l'application admin
 */

class Admin extends Routeur {

  protected $utilisateur_id;
  // protected $timbre_id;
  protected $timbre_id;
   //protected $pays_id;

  protected static $entite;
  protected static $action;
  protected static $oUtilConn; // objet Utilisateur connecté
  
  protected $classRetour = "fait";
  protected $messageRetourAction = "";

  /**
   * Constructeur qui initialise des propriétés à partir du query string
   * 
   */
  public function __construct() {
    self::$entite = $_GET['entite'] ?? 'timbre';
    // self::$entite = $_GET['entite'] ?? 'pays';
     
    self::$action = $_GET['action'] ?? 'l';
  }

  /**
   * Gérer l'interface d'administration 
   */  
  public function gererEntite() {
    // error_log("Admin.gererEntite");
    if (isset($_SESSION['oUtilConn'])) {
      self::$oUtilConn = $_SESSION['oUtilConn'];
      // error_log(self::$oUtilConn -> utilisateur_courriel);
      // error_log(self::$oUtilConn -> utilisateur_profil);
      if (self::$oUtilConn -> utilisateur_profil == Utilisateur::PROFIL_ADMINISTRATEUR) {
        $entite = ucwords(self::$entite);
        $classe = "Admin$entite";
        if (class_exists($classe)) {
          (new $classe())->gererAction();
        } else {
          throw new Exception("L'entité ".self::$entite." n'existe pas.");
          // print_r(self::$oUtilConn);
          // exit;
        }
      } else {
        $entite = ucwords(self::$entite);
        //print_r( $entite);
        //self::$oUtilConn -> utilisateur_profil == Utilisateur::PROFIL_MEMBRE;
         $_SESSION["oUtilConn"]->utilisateur_id;
         
        (new Membre) -> listerTimbreParIdUtilisateur();
      }
     } else {
      (new AdminUtilisateur)->connecter();
     }    
  }

  /**
   * Gérer l'interface d'administration d'une entité
   */  
  public function gererAction() {
    
    if (isset($this->methodes[self::$action])) {
      $methode = $this->methodes[self::$action]['nom'];
      if (isset($this->methodes[self::$action]['droits'])) {
        $droits = $this->methodes[self::$action]['droits'];
        foreach ( $droits as $droit) {
          if ($droit === self::$oUtilConn->utilisateur_profil) {
            $this->$methode();
            exit;
          }
        }
        throw new Exception(self::ERROR_FORBIDDEN);
      } else {
        $this->$methode();
      }
    } else {
      throw new Exception("L'action ".self::$action." de l'entité ".self::$entite." n'existe pas.");
    }

  }
}