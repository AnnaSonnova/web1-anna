<?php

class Mise extends Entite
{
  protected $mise_id;
  protected $mise_prix;
  protected $mise_date;
  protected $mise_utilisateur_id;
  protected $mise_enchere_id;
  protected $erreurs = [];


  public function getMise_id()       { return $this->mise_id; }
  public function getMise_prix()      { return $this->mise_prix; }
  public function getMise_date()   { return $this->mise_date; }
  public function getMise_utilisateur_id()   { return $this->mise_utilisateur_id; }
  public function getMise_enchere_id()   { return $this->mise_enchere_id; }
  public function getErreurs()              { return $this->erreurs; } 

 
  

 /**
   * Mutateur de la propriété timbre_id 
   * @param int $timbre_id
   * @return $this
   */    
  public function setMise_id($mise_id) {
    unset($this->erreurs['mise_id']);
    $regExp = '/^[1-9]\d*$/';
    if (!preg_match($regExp, $mise_id)) {
      $this->erreurs['mise_id'] = 'Numéro de mise incorrect.';
    }
    $this->mise_id = $mise_id;
    return $this;
  }  
  
  public function setMise_prix($mise_prix) {
    
    $this->mise_prix = $mise_prix;
    return $this;
  }

  public function setMise_date($mise_date) {
    
    $this->mise_date = $mise_date;
    return $this;
  }

   /**
   * Mutateur de la propriété mise_utilisateur_id
   * @param int $mise_utilisateur_id
   * @return $this
   */    
  public function setMise_utilisateur_id($mise_utilisateur_id) {
    unset($this->erreurs['mise_utilisateur_id']);
    $regExp = '/^[1-9]\d*$/';
    if (!preg_match($regExp, $mise_utilisateur_id)) {
      $this->erreurs['mise_utilisateur_id'] = 'Numéro de utilisateur incorrect.';
    }
    $this->mise_utilisateur_id = $mise_utilisateur_id;
    return $this;
  }  

  /**
   * Mutateur de la propriété mise_utilisateur_id
   * @param int $mise_utilisateur_id
   * @return $this
   */    
  public function setMise_enchere_id($mise_enchere_id) {
    unset($this->erreurs['mise_enchere_id']);
    $regExp = '/^[1-9]\d*$/';
    if (!preg_match($regExp, $mise_enchere_id)) {
      $this->erreurs['mise_enchere_id'] = 'Numéro de enchere incorrect.';
    }
    $this->mise_enchere_id = $mise_enchere_id;
    return $this;
  }  

  

   
}