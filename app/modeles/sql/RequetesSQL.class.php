<?php

/**
 * Classe des requêtes SQL
 *
 */
class RequetesSQL extends RequetesPDO {

  /**
   * Récupération des films à l'affiche ou prochainement ou pour l'interface admin
   * @param  string $critere
   * @return array tableau des lignes produites par la select   
   */ 
  public function getFilms($critere = 'enSalle') {
    $oAujourdhui = ENV === "DEV" ? new DateTime(MOCK_NOW) : new DateTime();
    $aujourdhui  = $oAujourdhui->format('Y-m-d');
    $dernierJour = $oAujourdhui->modify('+6 day')->format('Y-m-d');
    $this->sql = "
      SELECT film_id, film_titre, film_duree, film_annee_sortie, film_resume,
             film_affiche, film_bande_annonce, film_statut, genre_nom";
       
    // alimenter les colonnes nombres de réalisateurs,... de la page d'administration
    if ($critere === 'admin') {
      $this->sql .= ", nb_realisateurs, nb_acteurs, nb_pays";   
    }
       
    $this->sql .= "
      FROM film
      INNER JOIN genre ON genre_id = film_genre_id";
       
    // alimenter les colonnes nombres de réalisateurs,... de la page d'administration
    if ($critere === 'admin') {
      $this->sql .= "
        LEFT JOIN (SELECT COUNT(*) AS nb_realisateurs, f_r_film_id FROM film_realisateur GROUP BY f_r_film_id) AS FR
          ON f_r_film_id = film_id
        LEFT JOIN (SELECT COUNT(*) AS nb_acteurs, f_a_film_id FROM film_acteur GROUP BY f_a_film_id) AS FA
          ON f_a_film_id = film_id
        LEFT JOIN (SELECT COUNT(*) AS nb_pays, f_p_film_id FROM film_pays GROUP BY f_p_film_id) AS FP
          ON f_p_film_id = film_id
        ORDER BY film_id DESC";
    } else {
      // filtrages pour les pages du frontend
      $this->sql .= " WHERE film_statut = ".Film::STATUT_VISIBLE;

      switch($critere) {
        case 'enSalle':
          $this->sql .= " AND film_id IN (SELECT DISTINCT seance_film_id FROM seance
                                         WHERE seance_date >='$aujourdhui' AND seance_date <= '$dernierJour')";
          break;
        case 'prochainement':
          $this->sql .= " AND film_id NOT IN (SELECT DISTINCT seance_film_id FROM seance
                                             WHERE seance_date <= '$dernierJour')";
          break;
      }
    }      
    return $this->getLignes();
  }

  /**
   * Récupération d'un film
   * @param int $film_id, clé du film 
   * @return array|false tableau associatif de la ligne produite par la select, false si aucune ligne  
   */ 
  public function getFilm($film_id) {
    $this->sql = "
      SELECT film_id, film_titre, film_duree, film_annee_sortie, film_resume,
             film_affiche, film_bande_annonce, film_statut, genre_nom
      FROM film
      INNER JOIN genre ON genre_id = film_genre_id
      WHERE film_id = :film_id AND film_statut = ".Film::STATUT_VISIBLE;

    return $this->getLignes(['film_id' => $film_id], RequetesPDO::UNE_SEULE_LIGNE);
  }

  /**
   * Récupération des réalisateurs d'un film
   * @param int $film_id, clé du film
   * @return array tableau des lignes produites par la select 
   */ 
  public function getRealisateursFilm($film_id) {
    $this->sql = "
      SELECT realisateur_nom, realisateur_prenom
      FROM realisateur
      INNER JOIN film_realisateur ON f_r_realisateur_id = realisateur_id
      WHERE f_r_film_id = :film_id";

    return $this->getLignes(['film_id' => $film_id]);
  }

  /**
   * Récupération des pays d'un film
   * @param int $film_id, clé du film
   * @return array tableau des lignes produites par la select 
   */ 
  public function getPaysFilm($film_id) {
    $this->sql = "
      SELECT pays_nom
      FROM pays
      INNER JOIN film_pays ON f_p_pays_id = pays_id
      WHERE f_p_film_id = :film_id";

    return $this->getLignes(['film_id' => $film_id]);
  }

  /**
   * Récupération des acteurs d'un film
   * @param int $film_id, clé du film
   * @return array tableau des lignes produites par la select 
   */ 
  public function getActeursFilm($film_id) {
    $this->sql = "
      SELECT acteur_nom, acteur_prenom
      FROM acteur
      INNER JOIN film_acteur ON f_a_acteur_id = acteur_id
      WHERE f_a_film_id = :film_id
      ORDER BY f_a_priorite ASC";

    return $this->getLignes(['film_id' => $film_id]);
  }

  /**
   * Récupération des séances d'un film
   * @param int $film_id, clé du film
   * @return array tableau des lignes produites par la select 
   */ 
  public function getSeancesFilm($film_id) {
    $oAujourdhui = ENV === "DEV" ? new DateTime(MOCK_NOW) : new DateTime();
    $aujourdhui  = $oAujourdhui->format('Y-m-d');
    $dernierJour = $oAujourdhui->modify('+6 day')->format('Y-m-d');
    $this->sql = "
      SELECT DATE_FORMAT(seance_date, '%W') AS seance_jour, seance_date, seance_heure
      FROM seance
      INNER JOIN film ON seance_film_id = film_id
      WHERE seance_film_id = :film_id AND seance_date >='$aujourdhui' AND seance_date <= '$dernierJour'
      ORDER BY seance_date, seance_heure";

    return $this->getLignes(['film_id' => $film_id]);
  }

  /* GESTION DES UTILISATEURS 
     ======================== */

  /**
   * Récupération des utilisateurs
   * @return array tableau d'objets Utilisateur
   */ 
  public function getUtilisateurs() {
    $this->sql = "
      SELECT utilisateur_id, utilisateur_nom, utilisateur_prenom, utilisateur_courriel, utilisateur_profil
      FROM utilisateur ORDER BY utilisateur_id DESC";
     return $this->getLignes();
  }

  /**
   * Récupération d'un utilisateur
   * @param int $utilisateur_id, clé du utilisateur  
   * @return object Utilisateur
   */ 
  public function getUtilisateur($utilisateur_id) {
    $this->sql = "
      SELECT utilisateur_id, utilisateur_nom, utilisateur_prenom, utilisateur_courriel, utilisateur_profil
      FROM utilisateur
      WHERE utilisateur_id = :utilisateur_id";
    return $this->getLignes(['utilisateur_id' => $utilisateur_id], RequetesPDO::UNE_SEULE_LIGNE);
  }

  /**
   * Contrôler si adresse courriel non déjà utilisée par un autre utilisateur que utilisateur_id
   * @param array $champs tableau utilisateur_courriel et utilisateur_id (0 si dans toute la table)
   * @return string|false utilisateur avec ce courriel, false si courriel disponible
   */ 
  public function controlerCourriel($champs) {
    $this->sql = 'SELECT utilisateur_id FROM utilisateur
                  WHERE utilisateur_courriel = :utilisateur_courriel AND utilisateur_id != :utilisateur_id';
    return $this->getLignes($champs, RequetesPDO::UNE_SEULE_LIGNE);
  }

  /**
   * Connecter un utilisateur
   * @param array $champs, tableau avec les champs utilisateur_courriel et utilisateur_mdp  
   * @return object Utilisateur
   */ 
  public function connecter($champs) {
    $this->sql = "
      SELECT utilisateur_id, utilisateur_nom, utilisateur_prenom, utilisateur_courriel, utilisateur_profil
      FROM utilisateur
      WHERE utilisateur_courriel = :utilisateur_courriel AND utilisateur_mdp = SHA2(:utilisateur_mdp, 512)";
    return $this->getLignes($champs, RequetesPDO::UNE_SEULE_LIGNE);
  }

  /**
   * Ajouter un utilisateur
   * @param array $champs tableau des champs de l'utilisateur 
   * @return int|string clé primaire de la ligne ajoutée, message d'erreur sinon
   */ 
  public function ajouterUtilisateur($champs) {
    $utilisateur = $this->controlerCourriel(
      ['utilisateur_courriel' => $champs['utilisateur_courriel'], 'utilisateur_id' => 0]);
    if ($utilisateur !== false)
      return Utilisateur::ERR_COURRIEL_EXISTANT;
    $this->sql = '
      INSERT INTO utilisateur SET
      utilisateur_nom      = :utilisateur_nom,
      utilisateur_prenom   = :utilisateur_prenom,
      utilisateur_courriel = :utilisateur_courriel,
      utilisateur_mdp      = SHA2(:utilisateur_mdp, 512),
      utilisateur_profil   = :utilisateur_profil';
    return $this->CUDLigne($champs);
  }

  /**
   * Modifier un utilisateur
   * @param array $champs tableau des champs de l'utilisateur 
   * @return boolean|string true si modifié, message d'erreur sinon
   */ 
  public function modifierUtilisateur($champs) {
    $utilisateur = $this->controlerCourriel(
      ['utilisateur_courriel' => $champs['utilisateur_courriel'], 'utilisateur_id' => $champs['utilisateur_id']]);
    if ($utilisateur !== false)
      return Utilisateur::ERR_COURRIEL_EXISTANT;
    $this->sql = '
      UPDATE utilisateur SET
      utilisateur_nom      = :utilisateur_nom,
      utilisateur_prenom   = :utilisateur_prenom,
      utilisateur_courriel = :utilisateur_courriel,
      utilisateur_profil   = :utilisateur_profil
      WHERE utilisateur_id = :utilisateur_id';
    return $this->CUDLigne($champs);
  }

 /**
   * Modifier le mot de passe d'un utilisateur
   * @param array $champs tableau des champs de l'utilisateur 
   * @return boolean true si modifié, false sinon
   */ 
  public function modifierUtilisateurMdp($champs) {
    $this->sql = '
      UPDATE utilisateur SET utilisateur_mdp  = SHA2(:utilisateur_mdp, 512)
      WHERE utilisateur_id = :utilisateur_id AND utilisateur_id > 3';
    return $this->CUDLigne($champs);
  }

  /**
   * Supprimer un utilisateur
   * @param int $utilisateur_id clé primaire
   * @return boolean|string true si suppression effectuée, message d'erreur sinon
   */ 
  public function supprimerUtilisateur($utilisateur_id) {
    $this->sql = '
      DELETE FROM utilisateur WHERE utilisateur_id = :utilisateur_id';
    return $this->CUDLigne(['utilisateur_id' => $utilisateur_id]);
  }
}