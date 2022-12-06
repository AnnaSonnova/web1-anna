<?php

/**
 * Classe Contrôleur des requêtes sur l'entité Acteur de l'application admin
 */

class AdminActeur extends Admin {

  protected $methodes = [
    'l' => ['nom'    => 'listerActeurs', 'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR, Utilisateur::PROFIL_EDITEUR]]
  ];

  /**
   * Constructeur qui initialise des propriétés à partir du query string
   * et la propriété oRequetesSQL déclarée dans la classe Routeur
   * 
   */
  public function __construct() {
    $this->Acteur_id = $_GET['acteur_id'] ?? null;
    $this->oRequetesSQL = new RequetesSQL;
  }

  /**
   * Lister les Acteurs
   */
  public function listerActeurs() {
    (new Vue)->generer(
      'vAdminActeurs',
      [
        'oUtilConn' => self::$oUtilConn,
        'titre'     => 'Gestion des Acteurs',
      ],
      'gabarit-admin');
  }
}