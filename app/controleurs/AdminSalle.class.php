<?php

/**
 * Classe Contrôleur des requêtes sur l'entité Salle de l'application admin
 */

class AdminSalle extends Admin {

  protected $methodes = [
    'l' => ['nom'    => 'listerSalles', 'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR, Utilisateur::PROFIL_EDITEUR]]
  ];

  /**
   * Constructeur qui initialise des propriétés à partir du query string
   * et la propriété oRequetesSQL déclarée dans la classe Routeur
   * 
   */
  public function __construct() {
    $this->salle_id = $_GET['salle_id'] ?? null;
    $this->oRequetesSQL = new RequetesSQL;
  }

  /**
   * Lister les salles
   */
  public function listerSalles() {
    (new Vue)->generer(
      'vAdminSalles',
      [
        'oUtilConn' => self::$oUtilConn,
        'titre'     => 'Gestion des salles',
      ],
      'gabarit-admin');
  }
}