<?php

/**
 * Classe des requêtes SQL
 *
 */
class RequetesSQL extends RequetesPDO {

  

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



  /* GESTION DES TIMBRES 
     ================== */

  /**
   * Récupération des timbres 
   * @param  string $critere
   * @return array tableau des lignes produites par la select   
   */ 
  public function getTimbres() {
    // $oAujourdhui = ENV === "DEV" ? new DateTime(MOCK_NOW) : new DateTime();
    // $aujourdhui  = $oAujourdhui->format('Y-m-d');
    // $dernierJour = $oAujourdhui->modify('+6 day')->format('Y-m-d');
    $this->sql = "
      SELECT timbre_id, timbre_nom, timbre_date, timbre_couleur, timbre_tirage,
      timbre_description, timbre_prix_plancher, timbre_dimension, pays_nom, enchere_date_debut, enchere_date_fin 
      FROM timbre
      INNER JOIN pays ON pays_id = timbre_pays_id
      INNER JOIN enchere ON enchere_id = timbre_enchere_id 
      GROUP BY timbre_id ";
       
    return $this->getLignes();
  }

  /**
   * Récupération d'un timbre
   * @param int $timbre_id, clé du timbre 
   * @return array|false tableau associatif de la ligne produite par la select, false si aucune ligne  
   */ 
  public function getTimbre($timbre_id) {
    $this->sql = "
      SELECT timbre_id, timbre_nom, timbre_date, timbre_couleur, timbre_tirage,
      timbre_description, timbre_prix_plancher, timbre_dimension, pays_nom, enchere_date_debut, enchere_date_fin 
      FROM timbre
      INNER JOIN pays ON pays_id = timbre_pays_id
      INNER JOIN enchere ON enchere_id = timbre_enchere_id 
      WHERE timbre_id = :timbre_id";

    return $this->getLignes(['timbre_id' => $timbre_id], RequetesPDO::UNE_SEULE_LIGNE);
  }

  /**
   * Ajouter une timbre
   * @param array $champs tableau des champs de l'utilisateur 
   * @return int|string clé primaire de la ligne ajoutée, message d'erreur sinon
   */ 
  public function ajouterTimbre($champs) {
    
    $this->sql = '
      INSERT INTO timbre SET
      timbre_nom   = :timbre_nom,
      timbre_date   = :timbre_date,
      timbre_couleur   = :timbre_couleur,
      timbre_tirage   = :timbre_tirage,
      timbre_description   = :timbre_description,
      timbre_prix_plancher = :timbre_prix_plancher,
      timbre_dimension      = :timbre_dimension,
      timbre_pays_id   = :timbre_pays_id,
      timbre_enchere_id = :timbre_enchere_id';
    return $this->CUDLigne($champs);
  }


  /* GESTION DES PAYS 
     ================== */

  /**
   * Récupération des pays 
   * @param  string $critere
   * @return array tableau des lignes produites par la select   
   */ 
  public function getPays() {
    // $oAujourdhui = ENV === "DEV" ? new DateTime(MOCK_NOW) : new DateTime();
    // $aujourdhui  = $oAujourdhui->format('Y-m-d');
    // $dernierJour = $oAujourdhui->modify('+6 day')->format('Y-m-d');
    $this->sql = "
      SELECT pays_id, pays_nom
      FROM pays
      
      GROUP BY pays_id ";
       
    return $this->getLignes();
  }


}