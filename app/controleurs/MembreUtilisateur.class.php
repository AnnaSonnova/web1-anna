<?php

/**
 * Classe Contrôleur des requêtes sur l'entité Utilisateur de l'application admin
 */

class MembreUtilisateur extends Membre {

  protected $methodes = [
    'l'           => ['nom'    =>'listerTimbres',  'droits' => [Utilisateur::PROFIL_MEMBRE]],
    'a'           => ['nom'    =>'ajouterUtilisateur'
    // ,   'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR]
  ],
    // 'm'           => ['nom'    =>'modifierUtilisateur',  'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR]],
    // 's'           => ['nom'    =>'supprimerUtilisateur', 'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR]],
     'd'           => ['nom'    =>'deconnecter'],
     'aa'           => ['nom'    =>'ajouterTimbreParId', 'droits' => [Utilisateur::PROFIL_MEMBRE]]
   
  ];

  /**
   * Constructeur qui initialise des propriétés à partir du query string
   * et la propriété oRequetesSQL déclarée dans la classe Routeur
   * 
   */
  public function __construct() {
    $this->utilisateur_id = $_GET['utilisateur_id'] ?? null; 
    $this->oRequetesSQL = new RequetesSQL;
  }

  /**
   * Lister les timbres
   */
  public function listerTimbreParIdUtilisateur() {

    // $timbre = [];
    // $oTimbre = new Timbre($timbre);
    $utilId = $_SESSION["oUtilConn"]->utilisateur_id;
    // echo "<pre>".  print_r($_SESSION["oUtilConn"]->utilisateur_id, true) . "<pre>"; exit;
    $timbres = $this->oRequetesSQL->getTimbreParIdUtilisateur($utilId
    //   [
    //   'timbre_id'      => $oTimbre->timbre_id,
    //   'timbre_nom'   =>$oTimbre->getTimbre_nom(),
    //   'timbre_date' =>$oTimbre->getTimbre_date(),
    //   'timbre_utilisateur_nom'      => $oTimbre->getTimbre_utilisateur_nom(),
    //   'timbre_tirage'   => $oTimbre->getTimbre_tirage(),
    //   'timbre_description'      =>$oTimbre->getTimbre_description(),
    //   'timbre_prix_plancher'      => $oTimbre->getTimbre_prix_plancher(),
    //   'timbre_dimension'      => $oTimbre->getTimbre_dimension(),
    //   'timbre_pays_nom'      => $oTimbre->getTimbre_pays_nom(),
    //   'timbre_enchere_debut'      => $oTimbre->getTimbre_enchere_debut()
    // ]
  );

    // print_r('listerTimbreParIdUtilisateur-function');
    // var_dump($timbres);
    // exit;
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

  // /**
  //  * Connecter un utilisateur
  //  */
  // public function connecter() {
  //   error_log("connecter");
  //   $this->oRequetesSQL = new RequetesSQL;
    
  //   $messageErreurConnexion = ""; 
  //   if (count($_POST) !== 0) {
  //     error_log("POST=". implode($_POST));
  //     $u = $this->oRequetesSQL->connecter($_POST);
  //     print_r($u);
  //     echo('dans la fonction connecter');
  //     if ($u !== false) {
  //       $_SESSION['oUtilConn'] = new Utilisateur($u);
  //       $this -> listerTimbreParIdUtilisateur();
  //       print_r('listerTimbreParIdUtilisateur()');
  //       exit;         
  //     } else {
  //       $messageErreurConnexion = "Courriel ou mot de passe incorrect.";
  //     }
  //   }
  //   error_log("connecter1");

  //   (new Vue)->generer(
  //     'vMembreConnecter',
  //     [
  //       'titre'                  => 'Connexion',
  //       'messageErreurConnexion' => $messageErreurConnexion, 
  //          'seccion' => $_SESSION

  //     ],
  //     'gabarit-membre-min');
  // }

  // /**
  //  * Connecter un utilisateur
  //  */
  // public function showLoginPage() {
  //   (new Vue)->generer(
  //     'vAdminUtilisateurConnecter',
  //     [
  //       'titre'                  => 'Connexion'
  //     ],
  //     'gabarit-admin-min');
  // }
  // /**
  //  * Déconnecter un utilisateur
  //  */
  // public function deconnecter() {
  //   unset ($_SESSION['oUtilConn']);
  //   parent::gererEntiteMembre();
  // }

  

  // /**
  //  * Ajouter un utilisateur
  //  */
  // public function ajouterUtilisateur() {
  //   error_log("POST=" . implode($_POST));
  //   if (count($_POST) !== 0) {
      
  
  //     $utilisateur = $_POST;
  //     $oUtilisateur = new Utilisateur($utilisateur);
  //     $oUtilisateur->courrielExiste();
  //     $erreurs = $oUtilisateur->erreurs;
  //     if (count($erreurs) === 0) {
  //      // $oUtilisateur->genererMdp();
  //       $retour = $this->oRequetesSQL->ajouterUtilisateur([
  //         'utilisateur_nom'      => $oUtilisateur->utilisateur_nom,
  //         'utilisateur_prenom'   => $oUtilisateur->utilisateur_prenom,
  //         'utilisateur_courriel' => $oUtilisateur->utilisateur_courriel,
  //         'utilisateur_mdp'      => $oUtilisateur->utilisateur_mdp,
  //         'utilisateur_profil'   => $oUtilisateur->utilisateur_profil
  //       ]);
  //       if ($retour !== Utilisateur::ERR_COURRIEL_EXISTANT) {
  //         if (preg_match('/^[1-9]\d*$/', $retour)) {
  //           $this->messageRetourAction = "Ajout de l'utilisateur numéro $retour effectué.";
  //           $retour = (new GestionCourriel)->envoyerMdp($oUtilisateur); 
  //           $this->messageRetourAction .= $retour ?  " Courriel envoyé à l'utilisateur." : " Erreur d'envoi d'un courriel à l'utilisateur.";
  //           if (ENV === "DEV") {
  //             $this->messageRetourAction .= "<br>Message dans le fichier <a href='$retour' target='_blank'>$retour</a>";
  //           }   
  //         } else {
  //           $this->classRetour = "erreur";         
  //           $this->messageRetourAction = "Ajout de l'utilisateur non effectué.";
  //         }
  //          $this->showLoginPage();
  //         exit;
  //       } else {
  //         $erreurs['utilisateur_courriel'] = $retour;
  //       }
  //     }
  //   } else {
  //     $utilisateur = [];
  //     $erreurs     = [];
  //   }
    
  //   (new Vue)->generer(
  //     'vMembreAjouter',
  //     [
  //       'oUtilConn'   => self::$oUtilConn,
  //       'titre'       => 'Creer un compte',
  //       'utilisateur' => $utilisateur,
  //       'erreurs'     => $erreurs
  //     ],
  //     'gabarit-membre');
  // }

  /**
   * Modifier un utilisateur
   */
  public function modifierUtilisateur() {
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
  
  // /**
  //  * Supprimer un utilisateur
  //  */
  // public function supprimerUtilisateur() {
  //   if (!preg_match('/^\d+$/', $this->utilisateur_id))
  //     throw new Exception("Numéro d'utilisateur incorrect pour une suppression.");

  //   $retour = $this->oRequetesSQL->supprimerUtilisateur($this->utilisateur_id);
  //   if ($retour === false) $this->classRetour = "erreur";
  //   $this->messageRetourAction = "Suppression de l'utilisateur numéro $this->utilisateur_id ".($retour ? "" : "non ")."effectuée.";
  //   // $this->listerUtilisateurs();
  // }

  /**
   * Générer un nouveau mot de passe
   */
  public function genererMdp() {
    if (!preg_match('/^\d+$/', $this->utilisateur_id))
      throw new Exception("Numéro d'utilisateur incorrect pour une modification du mot de passe.");

    $utilisateur = $this->oRequetesSQL->getUtilisateur($this->utilisateur_id);
    $oUtilisateur = new Utilisateur($utilisateur);
    $mdp = $oUtilisateur->genererMdp();
    $retour = $this->oRequetesSQL->modifierUtilisateurMdp([
        'utilisateur_id'  => $this->utilisateur_id, 
        'utilisateur_mdp' => $mdp
    ]);
    if ($retour === true)  {
      $this->messageRetourAction = "Modification du mot de passe de l'utilisateur numéro $this->utilisateur_id effectuée.";
      $retour = (new GestionCourriel)->envoyerMdp($oUtilisateur); 
      $this->messageRetourAction .= $retour ?  " Courriel envoyé à l'utilisateur." : " Erreur d'envoi d'un courriel à l'utilisateur.";
      if (ENV === "DEV") {
        $this->messageRetourAction .= "<br>Message dans le fichier <a href='$retour' target='_blank'>$retour</a>";
      } 
    } else {  
      $this->classRetour = "erreur";
      $this->messageRetourAction = "Modification du mot de passe de l'utilisateur numéro $this->utilisateur_id non effectuée.";
    }
    // $this->listerUtilisateurs();
  }
}