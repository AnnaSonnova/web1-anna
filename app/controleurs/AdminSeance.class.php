<?php

/**
 * Classe Contrôleur des requêtes sur l'entité Seance de l'application admin
 */

class AdminSeance extends Admin {

  protected $methodes = [
    'l' => ['nom'    => 'listerSeances', 'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR, Utilisateur::PROFIL_EDITEUR]]
  ];

  /**
   * Constructeur qui initialise des propriétés à partir du query string
   * et la propriété oRequetesSQL déclarée dans la classe Routeur
   * 
   */
  public function __construct() {
    $this->seance_id = $_GET['seance_id'] ?? null;
    $this->oRequetesSQL = new RequetesSQL;
  }

  /**
   * Lister les seances
   */
  public function listerSeances() {
    (new Vue)->generer(
      'vAdminSeances',
      [
        'oUtilConn' => self::$oUtilConn,
        'titre'     => 'Gestion des seances',
      ],
      'gabarit-admin');
  }
}