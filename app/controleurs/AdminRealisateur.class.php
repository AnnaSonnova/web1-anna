<?php

/**
 * Classe Contrôleur des requêtes sur l'entité Realisateur de l'application admin
 */

class AdminRealisateur extends Admin {

  protected $methodes = [
    'l' => ['nom'    => 'listerRealisateurs', 'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR, Utilisateur::PROFIL_EDITEUR]]
  ];

  /**
   * Constructeur qui initialise des propriétés à partir du query string
   * et la propriété oRequetesSQL déclarée dans la classe Routeur
   * 
   */
  public function __construct() {
    $this->realisateur_id = $_GET['realisateur_id'] ?? null;
    $this->oRequetesSQL = new RequetesSQL;
  }

  /**
   * Lister les realisateurs
   */
  public function listerRealisateurs() {
    (new Vue)->generer(
      'vAdminRealisateurs',
      [
        'oUtilConn' => self::$oUtilConn,
        'titre'     => 'Gestion des realisateurs',
      ],
      'gabarit-admin');
  }
}