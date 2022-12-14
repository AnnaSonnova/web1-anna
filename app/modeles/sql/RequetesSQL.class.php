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

  /* GESTION DES ENCHERES 
     ================== */

      /**
   * Récupération des encheres 
   * @param  string $critere
   * @return array tableau des lignes produites par la select   
   */ 
  public function getEncheres(){

    // $oAujourdhui = ENV === "DEV" ? new DateTime(MOCK_NOW) : new DateTime();
    // $aujourdhui  = $oAujourdhui->format('Y-m-d');
    // $dernierJour = $oAujourdhui->modify('+6 day')->format('Y-m-d');
    $this->sql ="
    SELECT enchere_id, enchere_date_debut, enchere_date_fin, enchere_utilisateur_id, timbre_id, timbre_nom, timbre_date, utilisateur_nom , timbre_tirage,
    timbre_description, timbre_prix_plancher, timbre_dimension, pays_nom, img_url
    FROM enchere
    INNER JOIN  utilisateur ON utilisateur.utilisateur_id = enchere.enchere_utilisateur_id 
    
    INNER JOIN  timbre ON timbre.timbre_enchere_id = enchere.enchere_id 
    INNER JOIN img ON img.img_timbre_id  = timbre.timbre_id 
    INNER JOIN pays ON pays.pays_id = timbre.timbre_pays_id
    GROUP BY enchere_id";
    // echo "<pre>".  print_r($this->sql, true) . "<pre>"; exit;
    return $this->getLignes();
  }

  /**
   * Récupération d'un timbre
   * @param int $timbre_id, clé du timbre 
   * @return array|false tableau associatif de la ligne produite par la select, false si aucune ligne  
   */ 
  public function getEnchere($enchere_id) {
    $this->sql = "
      SELECT enchere_id, enchere_date_debut, enchere_date_fin, enchere_utilisateur_id, timbre_id, timbre_nom, timbre_date, utilisateur_nom, timbre_tirage,
      timbre_description, timbre_prix_plancher, timbre_dimension, pays_nom, img_url
      FROM enchere
      INNER JOIN  utilisateur ON utilisateur.utilisateur_id = enchere.enchere_utilisateur_id 
      INNER JOIN  timbre ON timbre.timbre_enchere_id = enchere.enchere_id 
      INNER JOIN pays ON pays_id = timbre_pays_id
      INNER JOIN img ON img.img_timbre_id  = timbre.timbre_id
      WHERE enchere_id = :enchere_id";

    return $this->getLignes(['enchere_id' => $enchere_id], RequetesPDO::UNE_SEULE_LIGNE);
  }

  /**
   * Récupération d'un timbre par id utilisateur
   * @param int $timbre_id, clé du timbre 
   * @return array|false tableau associatif de la ligne produite par la select, false si aucune ligne  
   */ 
  public function getEnchereParIdUtilisateur($champs) {
    // echo "<pre>".  print_r($champs) . "<pre>"; exit;
    $this->sql = "
      SELECT enchere_id, enchere_date_debut, enchere_date_fin, enchere_utilisateur_id, timbre_id, timbre_nom, timbre_date, timbre_utilisateur_id, timbre_tirage,
      timbre_description, timbre_prix_plancher, timbre_dimension, pays_nom, img_url
      FROM enchere
      INNER JOIN  utilisateur ON utilisateur.utilisateur_id = enchere.enchere_utilisateur_id 
      INNER JOIN timbre ON timbre.timbre_enchere_id  = enchere.enchere_id 
      INNER JOIN pays ON pays.pays_id = timbre.timbre_pays_id
      INNER JOIN img ON img.img_timbre_id  = timbre.timbre_id 
      WHERE enchere_utilisateur_id  = $champs
      ";
      return $this->getLignes();
  }

  /**
   * Ajouter une timbre
   * @param array $champs tableau des champs de l'utilisateur 
   * @return int|string clé primaire de la ligne ajoutée, message d'erreur sinon
   */ 
  public function ajouterEnchere($champs) {
    // echo 'isi';
    // print_r($champs);
    $this->sql = '
      INSERT INTO enchere SET
      enchere_date_debut  = :enchere_date_debut,
      enchere_date_fin   = :enchere_date_fin,
      enchere_utilisateur_id   = :enchere_utilisateur_id
      '
      ;
      return $this->CUDLigne($champs);
      $this->sql ='SELECT enchere_id FROM enchere ORDER BY enchere_id DESC ' ;
      return $this->getLignes();
  }

  /**
   * Modifier enchere
   * @param array $champs tableau des champs de enchere 
   * @return boolean|string true si modifié, message d'erreur sinon
   */ 
  public function modifierEnchere($champs) {
    echo "<pre>".  print_r($champs , true) . "<pre>"; exit;
    $this->sql = '
      UPDATE enchere SET
      enchere_date_debut  = :enchere_date_debut,
      enchere_date_fin   = :enchere_date_fin,
      enchere_utilisateur_id   = :enchere_utilisateur_id
      WHERE enchere_id = :enchere_id';
    return $this->CUDLigne($champs);
  }

  /**
   * Supprimer un utilisateur
   * @param int $utilisateur_id clé primaire
   * @return boolean|string true si suppression effectuée, message d'erreur sinon
   */ 
  public function supprimerEnchere($enchere_id) {
    $this->sql = '
      DELETE FROM enchere WHERE enchere_id = :enchere_id';
    return $this->CUDLigne(['enchere_id' => $enchere_id]);
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
      SELECT timbre_id, timbre_nom, timbre_date, utilisateur_nom , timbre_tirage,
      timbre_description, timbre_prix_plancher, timbre_dimension, pays_nom, enchere_date_debut, enchere_date_fin, img_url
      FROM timbre
      INNER JOIN pays ON pays.pays_id = timbre.timbre_pays_id
      INNER JOIN enchere ON enchere.enchere_id = timbre.timbre_enchere_id
      INNER JOIN utilisateur ON utilisateur.utilisateur_id = timbre.timbre_utilisateur_id 
      INNER JOIN img ON img.img_timbre_id  = timbre.timbre_id 
      GROUP BY timbre_id ";
       //echo "<pre>"; var_dump($this->getLignes()) ;
    return $this->getLignes();
  }

  

  /**
   * Récupération d'un timbre
   * @param int $timbre_id, clé du timbre 
   * @return array|false tableau associatif de la ligne produite par la select, false si aucune ligne  
   */ 
  public function getTimbre($timbre_id) {
    $this->sql = "
      SELECT timbre_id, timbre_nom, timbre_date, utilisateur_nom, timbre_tirage,
      timbre_description, timbre_prix_plancher, timbre_dimension, pays_nom, enchere_date_debut, enchere_date_fin, img_url
      FROM timbre
      INNER JOIN pays ON pays_id = timbre_pays_id
      INNER JOIN enchere ON enchere_id = timbre_enchere_id
      INNER JOIN utilisateur ON utilisateur_id = timbre_utilisateur_id
      INNER JOIN img ON img.img_timbre_id  = timbre.timbre_id 
      WHERE timbre_id = :timbre_id";

    return $this->getLignes(['timbre_id' => $timbre_id], RequetesPDO::UNE_SEULE_LIGNE);
  }


   /**
   * Récupération d'un timbre par id utilisateur
   * @param int $timbre_id, clé du timbre 
   * @return array|false tableau associatif de la ligne produite par la select, false si aucune ligne  
   */ 
  public function getTimbreParIdUtilisateur($champs) {

    // echo "<pre>".  print_r($champs) . "<pre>"; exit;
    $this->sql = "
      SELECT timbre_id, timbre_nom, timbre_date, timbre_utilisateur_id, timbre_tirage,
      timbre_description, timbre_prix_plancher, timbre_dimension, timbre_pays_id, timbre_enchere_id, img_url
      FROM timbre
      INNER JOIN pays ON pays.pays_id = timbre.timbre_pays_id
      INNER JOIN enchere ON enchere.enchere_id = timbre.timbre_enchere_id
      INNER JOIN utilisateur ON utilisateur.utilisateur_id = timbre.timbre_utilisateur_id
      INNER JOIN img ON img.img_timbre_id  = timbre.timbre_id 
      WHERE timbre_utilisateur_id = $champs
      ";


      return $this->getLignes();
  }

  

  /**
   * Ajouter une timbre
   * @param array $champs tableau des champs de l'utilisateur 
   * @return int|string clé primaire de la ligne ajoutée, message d'erreur sinon
   */ 
  public function ajouterTimbre($champs) {
    // echo 'isi';
    //echo "<pre>".  print_r($champs , true) . "<pre>"; exit;
    // print_r($champs);
    $this->sql = '
      INSERT INTO timbre SET
      timbre_nom   = :timbre_nom,
      timbre_date   = :timbre_date,
      timbre_utilisateur_id   = :timbre_utilisateur_id,
      timbre_tirage   = :timbre_tirage,
      timbre_description   = :timbre_description,
      timbre_prix_plancher = :timbre_prix_plancher,
      timbre_dimension      = :timbre_dimension,
      timbre_pays_id   = :timbre_pays_id,
      timbre_enchere_id = :timbre_enchere_id
     
      '
      ;
    return $this->CUDLigne($champs);

    $this->sql ='SELECT timbre_id FROM timbre ORDER BY timbre_id DESC ' ;
      return $this->getLignes();
  }

  // /**
  //  * Récupération des img 
  //  * @param  string $critere
  //  * @return array tableau des lignes produites par la select   
  //  */ 
  // public function getImg() {
    
  //   $this->sql = "
  //     SELECT img_id, img_url , img_timbre_id
  //     FROM img
  //     INNER JOIN timbre ON timbre_id = img_timbre_id
  //      ";
       
  //   return $this->getLignes();
  // }

  /**
   * Ajouter une img
   * @param array $champs tableau des champs de l'utilisateur 
   * @return int|string clé primaire de la ligne ajoutée, message d'erreur sinon
   */ 
  public function ajouterImg($champs) {
    
 //echo "<pre>".  print_r($champs , true) . "<pre>"; exit;
    // print_r($champs);
    $this->sql = '
    INSERT INTO img SET
     img_url = :img_url,
     img_timbre_id = :img_timbre_id
      
      '
      ;
    return $this->CUDLigne($champs);
  }

  

  

  /**
   * Modifier timbre
   * @param array $champs tableau des champs de timbre 
   * @return boolean|string true si modifié, message d'erreur sinon
   */ 
  public function modifierTimbre($champs) {
    //echo "<pre>".  print_r($champs , true) . "<pre>"; exit;
    // print_r($champs);
    $this->sql = '
      UPDATE timbre SET
      timbre_nom   = :timbre_nom,
      timbre_date   = :timbre_date,
      timbre_utilisateur_id   = :timbre_utilisateur_id,
      timbre_tirage   = :timbre_tirage,
      timbre_description   = :timbre_description,
      timbre_prix_plancher = :timbre_prix_plancher,
      timbre_dimension      = :timbre_dimension,
      timbre_pays_id   = :timbre_pays_id,
      timbre_enchere_id = :timbre_enchere_id
      WHERE timbre_id = :timbre_id';
    return $this->CUDLigne($champs);
  }


   /**
   * Ajouter une img
   * @param array $champs tableau des champs de l'utilisateur 
   * @return int|string clé primaire de la ligne ajoutée, message d'erreur sinon
   */ 
  public function modifierImg($champs) {
    
    //echo "<pre>".  print_r($champs , true) . "<pre>"; exit;
       // print_r($champs);
       $this->sql = '
       UPDATE img SET
        img_url = :img_url,
        img_timbre_id = :img_timbre_id
        WHERE img_id = :img_id  
         '
         ;
       return $this->CUDLigne($champs);
     }

  /**
   * Supprimer timbre
   * @param int $timbre_id clé primaire
   * @return boolean|string true si suppression effectuée, message d'erreur sinon
   */ 
  public function supprimerTimbre($timbre_id) {
    $this->sql = '
      DELETE FROM timbre WHERE timbre_id = :timbre_id';
    return $this->CUDLigne(['timbre_id' => $timbre_id]);
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

  /* GESTION DES MISES 
     ================== */

public function mise($mise_id) {
    $this->sql = "
      SELECT  mise_id, mise_prix, mise_date, mise_utilisateur_id, mise_enchere_id
      FROM mise
      
      INNER JOIN enchere ON enchere_id = mise_enchere_id
      INNER JOIN utilisateur ON utilisateur_id = mise_utilisateur_id
     
      WHERE mise_id = :mise_id";

    return $this->getLignes(['mise_id' => $mise_id], RequetesPDO::UNE_SEULE_LIGNE);
  }

}