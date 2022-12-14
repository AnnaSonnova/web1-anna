<?php

class Timbre extends Entite
{
  private $timbre_id;
  private $timbre_nom;
  private $timbre_date;
  private $timbre_utilisateur_id;
  private $timbre_tirage;
  private $timbre_description;
  private $timbre_prix_plancher;
  private $timbre_dimension;
  private $timbre_pays_id;
  private $timbre_enchere_id;
  private $timbre_enchere_date_debut;
  private $img_timbre_id ;
  private $img_url;
  private $img_id ;

  
  private $erreurs = [];

  const ANNEE_PREMIER_TIMBRE = 1860; 
//   const STATUT_INVISIBLE = 0;
//   const STATUT_VISIBLE   = 1;
//   const STATUT_ARCHIVE   = 2;

public function getTimbre_id()       { return $this->timbre_id; }
public function getTimbre_nom()      { return $this->timbre_nom; }
public function getTimbre_date()   { return $this->timbre_date; }
public function getTimbre_utilisateur_id() { return $this->timbre_utilisateur_id; }
public function getTimbre_tirage()      { return $this->timbre_tirage; }
public function getTimbre_description()   { return $this->timbre_description; }
public function getTimbre_prix_plancher()   { return $this->timbre_prix_plancher; }
public function getTimbre_dimension()   { return $this->timbre_dimension; }
public function getTimbre_pays_id()   { return $this->timbre_pays_id; }
public function getTimbre_enchere_id()   { return $this->timbre_enchere_id; }
public function getTimbre_enchere_date_debut() { return $this->timbre_enchere_date_debut; }
public function getImg_id()       { return $this->img_id; }
public function getImg_url()      { return $this->img_url; }
public function getImg_timbre_id()   { return $this->img_timbre_id; }
 
public function getErreurs()              { return $this->erreurs; } 

 /**
   * Mutateur de la propriété timbre_id 
   * @param int $timbre_id
   * @return $this
   */    
  public function setTimbre_id($timbre_id) {
    unset($this->erreurs['timbre_id']);
    $regExp = '/^[1-9]\d*$/';
    if (!preg_match($regExp, $timbre_id)) {
      $this->erreurs['timbre_id'] = 'Numéro de timbre incorrect.';
    }
    $this->timbre_id = $timbre_id;
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
    unset($this->erreurs['timbre_date']);
    if (!preg_match('/^\d+$/', $timbre_date) ||
        $timbre_date < self::ANNEE_PREMIER_TIMBRE  || 
        $timbre_date > date("Y")) {
      $this->erreurs['timbre_date'] = "Entre ".self::ANNEE_PREMIER_TIMBRE." et l'année en cours.";
    }
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

  /**
   * Mutateur de la propriété  timbre_utilisateur_id
   * @param string $timbre_utilisateur_id
   * @return $this
   */    
  public function setTimbre_utilisateur_id($timbre_utilisateur_id) {
    unset($this->erreurs['timbre_utilisateur_id']);
    $timbre_utilisateur_id = trim($timbre_utilisateur_id);
    $regExp = '/^[1-9]\d*$/';
    if (!preg_match($regExp, $timbre_utilisateur_id)) {
      $this->erreurs['timbre_utilisateur_id'] = 'Numéro de utilisateur incorrect.';
    }
    $this->timbre_utilisateur_id = $timbre_utilisateur_id;
    return $this;
  } 

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
  
  /**
   * Mutateur de la propriété timbre_enchere_id
   * @param int $timbre_enchere_id
   * @return $this
   */    
  public function setTimbre_enchere_id($timbre_enchere_id) {
    unset($this->erreurs['timbre_enchere_id']);
    $regExp = '/^[1-9]\d*$/';
    if (!preg_match($regExp, $timbre_enchere_id)) {
      $this->erreurs['timbre_enchere_id'] = 'Numéro de enchere incorrect.';
    }
    $this->timbre_enchere_id = $timbre_enchere_id;
    return $this;
  }  

  /**
   * Mutateur de la propriété timbre_enchere_id
   * @param int $timbre_enchere_id
   * @return $this
   */    
  public function setTimbre_enchere_date_debut($timbre_enchere_id) {
    unset($this->erreurs['timbre_enchere_id']);
    $regExp = '/^[1-9]\d*$/';
    if (!preg_match($regExp, $timbre_enchere_id)) {
      $this->erreurs['timbre_enchere_id'] = 'Numéro de enchere incorrect.';
    }
    $this->timbre_enchere_id = $timbre_enchere_id;
    return $this;
  }  

  /**
   * Mutateur de la propriété timbre_id 
   * @param int $timbre_id
   * @return $this
   */    
  public function setImg_id($img_id) {
    unset($this->erreurs['img_id']);
    $regExp = '/^[1-9]\d*$/';
    if (!preg_match($regExp, $img_id)) {
      $this->erreurs['img_id'] = 'Numéro de image incorrect.';
    }
    $this->img_id = $img_id;
    return $this;
  }  
  
  public function setImg_url($img_url) {
    // unset($this->erreurs['img_url']);
    // $img_url = trim($img_url);
    // $regExp = '/^.+\$/';
    // if (!preg_match($regExp, $img_url)) {
    //   $this->erreurs['img_url'] = "Vous devez téléverser un fichier de type jpg.";
    // }
    $this->img_url = $img_url;
    return $this;
  }

   /**
   * Mutateur de la propriété timbre_enchere_id
   * @param int $timbre_enchere_id
   * @return $this
   */    
  public function setImg_timbre_id($img_timbre_id) {
    unset($this->erreurs['img_timbre_id']);
    $regExp = '/^[1-9]\d*$/';
    if (!preg_match($regExp, $img_timbre_id)) {
      $this->erreurs['img_timbre_id'] = 'Numéro de timbre incorrect.';
    }
    $this->img_timbre_id = $img_timbre_id;
    return $this;
  }  

  

  
}