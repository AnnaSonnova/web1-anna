<?php

class Enchere extends Entite{
    // protected $enchere_id;
    protected $enchere_date_debut;
    protected $enchere_date_fin;
    protected $enchere_utilisateur_id;
    
    protected $timbre_nom;
    protected $timbre_date;
  
    protected $timbre_tirage;
    protected $timbre_description;
    protected $timbre_prix_plancher;
    protected $timbre_dimension;
    protected $timbre_pays_id;
  
  




    protected $erreurs = [];

    // public function getEnchere_id()       { return $this->enchere_id; }
    public function getEnchere_date_debut()      { return $this->enchere_date_debut; }
    public function getEnchere_date_fin()      { return $this->enchere_date_fin; }
    public function getEnchere_utilisateur_id()   { return $this->enchere_utilisateur_id; }
    public function getTimbre_nom()      { return $this->timbre_nom; }
    public function getTimbre_date()   { return $this->timbre_date; }
    // public function getTimbre_utilisateur_id() { return $this->timbre_utilisateur_id; }
    public function getTimbre_tirage()      { return $this->timbre_tirage; }
    public function getTimbre_description()   { return $this->timbre_description; }
    public function getTimbre_prix_plancher()   { return $this->timbre_prix_plancher; }
    public function getTimbre_dimension()   { return $this->timbre_dimension; }
    public function getTimbre_pays_id()   { return $this->timbre_pays_id; }
   
    public function getErreurs()              { return $this->erreurs; } 


  

  /**
   * Mutateur de la propriété enchere_id 
   * @param int $enchere_id
   * @return $this
   */    
  public function setEnchere_id($enchere_id) {
    unset($this->erreurs['enchere_id']);
    $regExp = '/^[1-9]\d*$/';
    if (!preg_match($regExp, $enchere_id)) {
      $this->erreurs['enchere_id'] = 'Numéro de enchere incorrect.';
    }
    $this->enchere_id = $enchere_id;
    return $this;
  }   
  
  /**
   * Mutateur de la propriété timbre_annee_sortie 
   * @param int $timbre_annee_sortie
   * @return $this
   */        
  public function setEnchere_date_debut($enchere_date_debut) {
    // unset($this->erreurs['enchere_date_debut']);
    // if (!preg_match('/^\d+$/', $enchere_date_debut) ) {
    //   $this->erreurs['enchere_date_debut'] = "Entre et l'année en cours.";
    // }
    $this->enchere_date_debut = $enchere_date_debut;
    return $this;
  }

  /**
   * Mutateur de la propriété timbre_annee_sortie 
   * @param int $timbre_annee_sortie
   * @return $this
   */        
  public function setEnchere_date_fin($enchere_date_fin) {
    // unset($this->erreurs['enchere_date_fin']);
    // if (!preg_match('/^\d+$/', $enchere_date_fin) ) {
    //   $this->erreurs['enchere_date_fin'] = "Entre et l'année en cours.";
    // }
    $this->enchere_date_fin = $enchere_date_fin;
    return $this;
  }

  
  
   /**
   * Mutateur de la propriété timbre_enchere_id
   * @param int $timbre_enchere_id
   * @return $this
   */    
  public function setEnchere_utilisateur_id($enchere_utilisateur_id) {
    unset($this->erreurs['enchere_utilisateur_id']);
    $regExp = '/^[1-9]\d*$/';
    if (!preg_match($regExp, $enchere_utilisateur_id)) {
      $this->erreurs['enchere_utilisateur_id'] = 'Numéro de utilisateur incorrect.';
    }
    $this->enchere_utilisateur_id = $enchere_utilisateur_id;
    return $this;
  } 
  
  

  
  /**
   * Mutateur de la propriété timbre_nom 
   * @param string $timbre_nom
   * @return $this
   */    
  public function setTimbre_nom($timbre_nom) {
    unset($this->erreurs['timbre_nom']);
    $timbre_nom = trim($timbre_nom);
    $regExp = '/^.+$/';
    if (!preg_match($regExp, $timbre_nom)) {
      $this->erreurs['timbre_nom'] = 'Au moins un caractère.';
    }
    $this->timbre_nom = $timbre_nom;
    return $this;
  }

  

  /**
   * Mutateur de la propriété timbre_date 
   * @param int $timbre_date
   * @return $this
   */        
  public function setTimbre_date($timbre_date) {
    
    $this->timbre_date = $timbre_date;
    return $this;
  }

  /**
   * Mutateur de la propriété timbre_description
   * @param string $timbre_description
   * @return $this
   */    
  public function setTimbre_description($timbre_description) {
    unset($this->erreurs['timbre_description']);
    $timbre_description = trim($timbre_description);
    $regExp = '/^\S+(\s+\S+){4,}$/';
    if (!preg_match($regExp, $timbre_description)) {
      $this->erreurs['timbre_description'] = 'Au moins 5 mots.';
    }
    $this->timbre_description = $timbre_description;
    return $this;
  }

  


  /**
   * Mutateur de la propriété timbre_tirage 
   * @param int $timbre_tirage
   * @return $this
   */    
  public function setTimbre_tirage($timbre_tirage) {
    unset($this->erreurs['timbre_tirage']);
    $regExp = '/^[1-9]\d*$/';
    if (!preg_match($regExp, $timbre_tirage)) {
      $this->erreurs['timbre_tirage'] = 'Tirage incorrect.';
    }
    $this->timbre_tirage = $timbre_tirage;
    return $this;
  } 
  
  /**
   * Mutateur de la propriété timbre_prix_plancher 
   * @param int $timbre_prix_plancher
   * @return $this
   */    
  public function setTimbre_prix_plancher($timbre_prix_plancher) {
    unset($this->erreurs['timbre_prix_plancher']);
    $regExp = '/^[1-9]\d*$/';
    if (!preg_match($regExp, $timbre_prix_plancher)) {
      $this->erreurs['timbre_prix_plancher'] = 'Numéro de timbre incorrect.';
    }
    $this->timbre_prix_plancher = $timbre_prix_plancher;
    return $this;
  }    

  /**
   * Mutateur de la propriété timbre_dimension 
   * @param int $timbre_dimension
   * @return $this
   */    
  public function setTimbre_dimension($timbre_dimension) {
    unset($this->erreurs['timbre_dimension']);
    // $regExp = '/^[1-9]\d*$/';
    // if (!preg_match($regExp, $timbre_tirage)) {
    //   $this->erreurs['timbre_tirage'] = 'Tirage incorrect.';
    // }
    $this->timbre_dimension = $timbre_dimension;
    return $this;
  }  

  // /**
  //  * Mutateur de la propriété  timbre_utilisateur_id
  //  * @param string $timbre_utilisateur_id
  //  * @return $this
  //  */    
  // public function setTimbre_utilisateur_id($timbre_utilisateur_id) {
  //   unset($this->erreurs['timbre_utilisateur_id']);
  //   $timbre_utilisateur_id = trim($timbre_utilisateur_id);
  //   $regExp = '/^[1-9]\d*$/';
  //   if (!preg_match($regExp, $timbre_utilisateur_id)) {
  //     $this->erreurs['timbre_utilisateur_id'] = 'Numéro de utilisateur incorrect.';
  //   }
  //   $this->timbre_utilisateur_id = $timbre_utilisateur_id;
  //   return $this;
  // } 

  /**
   * Mutateur de la propriété timbre_pays_id 
   * @param int $timbre_pays_id
   * @return $this
   */    
  public function setTimbre_pays_id($timbre_pays_id) {
    unset($this->erreurs['timbre_pays_id']);
    $regExp = '/^[1-9]\d*$/';
    if (!preg_match($regExp, $timbre_pays_id)) {
      $this->erreurs['timbre_pays_id'] = 'Numéro de pays incorrect.';
    }
    $this->timbre_pays_id = $timbre_pays_id;
    return $this;
  }  
  
  // /**
  //  * Mutateur de la propriété timbre_enchere_id
  //  * @param int $timbre_enchere_id
  //  * @return $this
  //  */    
  // public function setTimbre_enchere_id($timbre_enchere_id) {
  //   unset($this->erreurs['timbre_enchere_id']);
  //   $regExp = '/^[1-9]\d*$/';
  //   if (!preg_match($regExp, $timbre_enchere_id)) {
  //     $this->erreurs['timbre_enchere_id'] = 'Numéro de enchere incorrect.';
  //   }
  //   $this->timbre_enchere_id = $timbre_enchere_id;
  //   return $this;
  // }  

  // /**
  //  * Mutateur de la propriété timbre_enchere_id
  //  * @param int $timbre_enchere_id
  //  * @return $this
  //  */    
  // public function setTimbre_enchere_date_debut($timbre_enchere_id) {
  //   unset($this->erreurs['timbre_enchere_id']);
  //   $regExp = '/^[1-9]\d*$/';
  //   if (!preg_match($regExp, $timbre_enchere_id)) {
  //     $this->erreurs['timbre_enchere_id'] = 'Numéro de enchere incorrect.';
  //   }
  //   $this->timbre_enchere_id = $timbre_enchere_id;
  //   return $this;
  // }  
}