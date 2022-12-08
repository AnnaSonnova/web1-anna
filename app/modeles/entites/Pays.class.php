<?php

class Pays extends Entite
{
  private $pays_id;
  private $pays_nom;
  

  
  private $erreurs = [];

 
  

 /**
   * Mutateur de la propriété timbre_id 
   * @param int $timbre_id
   * @return $this
   */    
  public function setPays_id($pays_id) {
    unset($this->erreurs['pays_id']);
    $regExp = '/^[1-9]\d*$/';
    if (!preg_match($regExp, $pays_id)) {
      $this->erreurs['pays_id'] = 'Numéro de pays incorrect.';
    }
    $this->pays_id = $pays_id;
    return $this;
  }    

  /**
   * Mutateur de la propriété timbre_nom 
   * @param string $timbre_nom
   * @return $this
   */    
  public function setPays_nom($pays_nom) {
    unset($this->erreurs['pays_nom']);
    $pays_nom = trim($pays_nom);
    $regExp = '/^.+$/';
    if (!preg_match($regExp, $pays_nom)) {
      $this->erreurs['pays_nom'] = 'Au moins un caractère.';
    }
    $this->pays_nom = mb_strtoupper($pays_nom);
    return $this;
  }

  

   
}