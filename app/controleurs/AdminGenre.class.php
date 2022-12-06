<?php

/**
 * Classe Contrôleur des requêtes sur l'entité Genre de l'application admin
 */

class AdminGenre extends Admin {

  protected $methodes = [
    'l' => ['nom'    => 'listerGenres', 'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR, Utilisateur::PROFIL_EDITEUR]]
  ];

  /**
   * Constructeur qui initialise des propriétés à partir du query string
   * et la propriété oRequetesSQL déclarée dans la classe Routeur
   * 
   */
  public function __construct() {
    $this->genre_id = $_GET['genre_id'] ?? null;
    $this->oRequetesSQL = new RequetesSQL;
  }

  /**
   * Lister les genres
   */
  public function listerGenres() {
    (new Vue)->generer(
      'vAdminGenres',
      [
        'oUtilConn' => self::$oUtilConn,
        'titre'     => 'Gestion des genres',
      ],
      'gabarit-admin');
  }
}