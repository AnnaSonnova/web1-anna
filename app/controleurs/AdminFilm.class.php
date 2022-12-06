<?php

/**
 * Classe Contrôleur des requêtes sur l'entité Film de l'application admin
 */

class AdminFilm extends Admin {

  protected $methodes = [
    'l' => ['nom'    => 'listerFilms',   'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR, Utilisateur::PROFIL_EDITEUR]],
    'a' => ['nom'    => 'ajouterFilm',   'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR, Utilisateur::PROFIL_EDITEUR]],
    'm' => ['nom'    => 'modifierFilm',  'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR, Utilisateur::PROFIL_EDITEUR]],
    's' => ['nom'    => 'supprimerFilm', 'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR, Utilisateur::PROFIL_EDITEUR]]
  ];

  /**
   * Constructeur qui initialise des propriétés à partir du query string
   * et la propriété oRequetesSQL déclarée dans la classe Routeur
   * 
   */
  public function __construct() {
    $this->film_id = $_GET['film_id'] ?? null;
    $this->oRequetesSQL = new RequetesSQL;
  }

  /**
   * Lister les films
   */
  public function listerFilms() {
    $films = $this->oRequetesSQL->getFilms('admin');
    (new Vue)->generer(
      'vAdminFilms',
      [
        'oUtilConn'           => self::$oUtilConn,
        'titre'               => 'Gestion des films',
        'films'               => $films,
        'classRetour'         => $this->classRetour,  
        'messageRetourAction' => $this->messageRetourAction        
      ],
      'gabarit-admin');
  }

/**
   * Ajouter un film
   */
  public function ajouterFilm() {
    throw new Exception("Développement en cours.");
  }

  /**
   * Modifier un film
   */
  public function modifierFilm() {
    throw new Exception("Développement en cours.");
  }
  
  /**
   * Supprimer un film
   */
  public function supprimerFilm() {
    throw new Exception("Développement en cours.");
  }
}