<?php

class Enchere{
    private $enchere_id;
    private $enchere_date_debut;
    private $enchere_date_fin;




    private $erreurs = [];

  //   /**
  //  * Constructeur de la classe 
  //  * @param array $proprietes, tableau associatif des propriétés 
  //  */ 
  // public function __construct($proprietes = []) {
  //   $t = array_keys($proprietes);
  //   foreach ($t as $nom_propriete) {
  //     $this->__set($nom_propriete, $proprietes[$nom_propriete]);
  //   } 
  // }

  // /**
  //  * Accesseur magique d'une propriété de l'objet
  //  * @param string $prop, nom de la propriété
  //  * @return property value
  //  */     
  // public function __get($prop) {
  //   return $this->$prop;
  // }

  //  /**
  //  * Mutateur magique qui exécute le mutateur de la propriété en paramètre 
  //  * @param string $prop, nom de la propriété
  //  * @param $val, contenu de la propriété à mettre à jour
  //  */   
  // public function __set($prop, $val) {
  //   $setProperty = 'set'.ucfirst($prop);
  //   $this->$setProperty($val);
  // }

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
  
}