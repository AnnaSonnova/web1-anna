<?php

/**
 * Classe Contrôleur des requêtes sur l'entité Utilisateur de l'application admin
 */

class Membre extends Routeur {

  protected $utilisateur_id;
  protected $utilisateur_nom;
  protected $utilisateur_premon;
  // protected $timbre_id;
  //protected $timbre_id;
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
  

  protected $methodes = [
    'l'           => ['nom'    =>'listerTimbres',  'droits' => [Utilisateur::PROFIL_MEMBRE]],
    'a'           => ['nom'    =>'ajouterUtilisateur'
    // ,   'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR]
  ],
    // 'm'           => ['nom'    =>'modifierUtilisateur',  'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR]],
    // 's'           => ['nom'    =>'supprimerUtilisateur', 'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR]],
     'd'           => ['nom'    =>'deconnecter'],
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
    $this->oRequetesSQL = new RequetesSQL;
    self::$action = $_GET['action'] ?? 'l';
  }

  

  /**
   * Connecter un utilisateur
   */
  public function showLoginPage() {
    print_r('showLoginPage');
    (new Vue)->generer(
      'vAdminUtilisateurConnecter',
      [
        'titre'                  => 'Connexion'
      ],
      'gabarit-admin-min');
  }
  
  

  /**
   * Ajouter un utilisateur
   */
  public function ajouterUtilisateur() {
    print_r('ajouter utilisateur membre');
    error_log("POST=" . implode($_POST));
    if (count($_POST) !== 0) {
      
  
      $utilisateur = $_POST;
      $oUtilisateur = new Utilisateur($utilisateur);
      $oUtilisateur->courrielExiste();
      $erreurs = $oUtilisateur->erreurs;
      if (count($erreurs) === 0) {
       // $oUtilisateur->genererMdp();
        $retour = $this->oRequetesSQL->ajouterUtilisateur([
          'utilisateur_nom'      => $oUtilisateur->utilisateur_nom,
          'utilisateur_prenom'   => $oUtilisateur->utilisateur_prenom,
          'utilisateur_courriel' => $oUtilisateur->utilisateur_courriel,
          'utilisateur_mdp'      => $oUtilisateur->utilisateur_mdp,
          'utilisateur_profil'   => $oUtilisateur->utilisateur_profil
        ]);
        if ($retour !== Utilisateur::ERR_COURRIEL_EXISTANT) {
          if (preg_match('/^[1-9]\d*$/', $retour)) {
            $this->messageRetourAction = "Ajout de l'utilisateur numéro $retour effectué.";
            $retour = (new GestionCourriel)->envoyerMdp($oUtilisateur); 
            $this->messageRetourAction .= $retour ?  " Courriel envoyé à l'utilisateur." : " Erreur d'envoi d'un courriel à l'utilisateur.";
            if (ENV === "DEV") {
              $this->messageRetourAction .= "<br>Message dans le fichier <a href='$retour' target='_blank'>$retour</a>";
            }   
          } else {
            $this->classRetour = "erreur";         
            $this->messageRetourAction = "Ajout de l'utilisateur non effectué.";
          }
           $this->showLoginPage();
          exit;
        } else {
          $erreurs['utilisateur_courriel'] = $retour;
        }
      }
    } else {
      $utilisateur = [];
      $erreurs     = [];
    }
    
    (new Vue)->generer(
      'vMembreAjouter',
      [
        'oUtilConn'   => self::$oUtilConn,
        'titre'       => 'Creer un compte',
        'utilisateur' => $utilisateur,
        'erreurs'     => $erreurs
      ],
      'gabarit-membre');
  }

  
  /**
   * Modifier un utilisateur
   */
  public function modifierUtilisateur() {
    print_r('modifier utilisateur');
    if (!preg_match('/^\d+$/', $this->utilisateur_id))
      throw new Exception("Numéro d'utilisateur non renseigné pour une modification");

    if (count($_POST) !== 0) {
    $utilisateur = $_POST;
    $oUtilisateur = new Utilisateur($utilisateur);
    $oUtilisateur->courrielExiste();
    $erreurs = $oUtilisateur->erreurs;
    if (count($erreurs) === 0) {
      $retour = $this->oRequetesSQL->modifierUtilisateur([
        'utilisateur_id'       => $oUtilisateur->utilisateur_id, 
        'utilisateur_courriel' => $oUtilisateur->utilisateur_courriel,
        'utilisateur_nom'      => $oUtilisateur->utilisateur_nom,
        'utilisateur_prenom'   => $oUtilisateur->utilisateur_prenom,
        'utilisateur_profil'   => $oUtilisateur->utilisateur_profil
      ]);
      if ($retour !== Utilisateur::ERR_COURRIEL_EXISTANT) {
        if ($retour === true)  {
          $this->messageRetourAction = "Modification de l'utilisateur numéro $this->utilisateur_id effectuée.";    
        } else {  
          $this->classRetour = "erreur";
          $this->messageRetourAction = "Modification de l'utilisateur numéro $this->utilisateur_id non effectuée.";
        }
        // $this->listerUtilisateurs();
        exit;
      } else {
        $erreurs['utilisateur_courriel'] = $retour;
      }
    }
  } else {
    $utilisateur = $this->oRequetesSQL->getUtilisateur($this->utilisateur_id);
    $erreurs = [];
  }
  
  (new Vue)->generer(
    'vAdminUtilisateurModifier',
    [
      'oUtilConn'   => self::$oUtilConn,
      'titre'       => "Modifier l'utilisateur numéro $this->utilisateur_id",
      'utilisateur' => $utilisateur,
      'erreurs'     => $erreurs
    ],
    'gabarit-admin');
  }
  
  
  
}