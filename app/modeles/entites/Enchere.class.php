<?php

class Enchere extends Entite{
    protected $enchere_id;
    protected $enchere_date_debut;
    protected $enchere_date_fin;
    protected $enchere_utilisateur_id;




    private $erreurs = [];

    public function getEnchere_id()       { return $this->enchere_id; }
    public function getEnchere_date_debut()      { return $this->enchere_date_debut; }
    public function getEnchere_date_fin()      { return $this->enchere_date_fin; }
    public function getEnchere_utilisateur_id()   { return $this->enchere_utilisateur_id; }
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
    unset($this->erreurs['enchere_date_debut']);
    if (!preg_match('/^\d+$/', $enchere_date_debut) ) {
      $this->erreurs['enchere_date_debut'] = "Entre et l'année en cours.";
    }
    $this->enchere_date_debut = $enchere_date_debut;
    return $this;
  }

  /**
   * Mutateur de la propriété timbre_annee_sortie 
   * @param int $timbre_annee_sortie
   * @return $this
   */        
  public function setEnchere_date_fin($enchere_date_fin) {
    unset($this->erreurs['enchere_date_fin']);
    if (!preg_match('/^\d+$/', $enchere_date_fin) ) {
      $this->erreurs['enchere_date_debut'] = "Entre et l'année en cours.";
    }
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
}