<?php

/**
 * Classe Contrôleur des requêtes sur l'entité Pays de l'application admin
 */

class AdminPays extends Admin {

  protected $methodes = [
    'l' => ['nom'    => 'listerPays', 'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR, Utilisateur::PROFIL_MEMBRE]]
  ];

  /**
   * Constructeur qui initialise des propriétés à partir du query string
   * et la propriété oRequetesSQL déclarée dans la classe Routeur
   * 
   */
  public function __construct() {
    $this->pays_id = $_GET['pays_id'] ?? null;
    $this->oRequetesSQL = new RequetesSQL;
  }  

  /**
   * Lister les payss
   */
  public function listerPays() {
     $pays = $this->oRequetesSQL->getPays('admin');

    (new Vue)->generer(
      'vAdminPays',
      [
        'oUtilConn' => self::$oUtilConn,
        'titre'     => 'Gestion des pays',
         'pays'               => $pays,
         'classRetour'         => $this->classRetour,  
         'messageRetourAction' => $this->messageRetourAction 
      ],
      'gabarit-admin');
  }
}