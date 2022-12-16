<?php

/**
 * Classe Contrôleur des requêtes sur l'entité Pays de l'application admin
 */

class MembreMise extends Membre {

  protected $methodes = [
    
    'am' => ['nom'    => 'ajouterMise']
  ];
  

  /**
   * Constructeur qui initialise des propriétés à partir du query string
   * et la propriété oRequetesSQL déclarée dans la classe Routeur
   * 
   */
  public function __construct() {
    $this->mise_id = $_GET['mise_id'] ?? null;
    $this->oRequetesSQL = new RequetesSQL;
    date_default_timezone_set('America/Toronto');
  }  

  /**
   * Lister les pays
   */
  public function ajouterMise() {
    //print_r('ajouterMise dans MembreMise');
    $utilId = $_SESSION["oUtilConn"]->utilisateur_id;
    //echo "<pre>".  print_r($utilId) . "<pre>";  
    $mise = [];
    $erreurs     = [];
    if (count($_POST) !== 0){
        $mise = $_POST;
        //echo "<pre>".  print_r($mise) . "<pre>";       

        $oMise = new Mise($mise);
        //Verification  mise dans base de donner
        $maxMisPrix = $this->oRequetesSQL->getMaxMise($oMise->mise_enchere_id);

        if ($maxMisPrix["max_mise_prix"] !== null && $maxMisPrix["max_mise_prix"] > $oMise->mise_prix) {
        //   error_log("Prix nest pas valide!!!!!!");
            $erreurs["mise_invalid"] = "Minimale mise maintenaint est" .  $maxMisPrix['max_mise_prix']+1;
        }

        $oMise->mise_utilisateur_id = $utilId;
        $aujourdui = date('Y-m-d h:i:sa');
        //echo($aujourdui);
  
        //$erreurs = $oMise->getErreurs();
        if (count($erreurs) === 0){
            $this->oRequetesSQL->addMise(
                [   'mise_prix'      => $oMise->mise_prix,
                    'mise_date'      => $aujourdui, 
                    'mise_utilisateur_id'      =>   $oMise->mise_utilisateur_id,
                    'mise_enchere_id'      => $oMise->mise_enchere_id 
       
                 ] );
         $message = "Votre mise est fait";
         
    }
    (new Vue)->generer(
      'vMembreConfirmMise',
      [
        'oUtilConn' => $_SESSION["oUtilConn"],
        'message'     => $message,
         'mise'               => $mise,
         
        //  'classRetour'         => $this->classRetour,  
        //  'messageRetourAction' => $this->messageRetourAction 
         'erreurs'     => $erreurs
      ],
      'gabarit-membre');
  }
}
}