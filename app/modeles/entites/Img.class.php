<?php

class Img extends Entite
{
  private $img_id;
  private $img_url;
  private $img_timbre_id;
  

  
  private $erreurs = [];


  public function getImg_id()       { return $this->img_id; }
  public function getImg_url()      { return $this->img_url; }
  public function getImg_timbre_id()   { return $this->img_timbre_id; }

 
  

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
    unset($this->erreurs['img_url']);
    $img_url = trim($img_url);
    $regExp = '/^.+\.jpg$/';
    if (!preg_match($regExp, $img_url)) {
      $this->erreurs['img_url'] = "Vous devez téléverser un fichier de type jpg.";
    }
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