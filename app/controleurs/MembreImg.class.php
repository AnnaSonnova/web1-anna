<?php

/**
 * Classe Contrôleur des requêtes sur l'entité Pays de l'application admin
 */

class MembreImg extends MembreTimbre {

  protected $methodes = [
    
    'ai' => ['nom'    => 'ajouterImg']
  ];

  /**
   * Constructeur qui initialise des propriétés à partir du query string
   * et la propriété oRequetesSQL déclarée dans la classe Routeur
   * 
   */
  public function __construct() {
    $this->img_id = $_GET['img_id'] ?? null;
    $this->oRequetesSQL = new RequetesSQL;
  }  

  /**
   * Lister les pays
   */
  public function ajouterImg() {
    print_r('ajouterImg dans MembreImg');
    $utilId = $_SESSION["oUtilConn"]->utilisateur_id;
    $timbres= $this->oRequetesSQL->getTimbres();
$img = [];
$erreurs     = [];
if (count($_POST) !== 0){
  $img = $_POST;
  echo "<pre>".  print_r($img) . "<pre>";       
   $oImg = new Img($img);
  // echo "<pre>". print_r($oImg) . "<pre>";
  //       //print_r($_FILES); '<br>';
        $nom_fichier = $_FILES['img_url']['name'];
  $fichier = $_FILES['img_url']['tmp_name'];
  $erreurs = $oImg->getErreurs();
  if (count($erreurs) === 0){
    $img_id=$this->oRequetesSQL->ajouterImg(
         ['img_url'      => $oImg->getImg_url()] 
       
       );
      if(move_uploaded_file($fichier, "images/".$nom_fichier)){
           echo "fichier copie";
      
       }else{
           echo "fichier non copie";
       }
       self::listerTimbreParIdUtilisateur(); // retour sur la page de liste des timbres
       exit;        
  }
    (new Vue)->generer(
      'vMembreTimbreAjouter',
      [
        'oUtilConn' => self::$oUtilConn,
        'titre'     => 'Ajouter une image',
         'img'               => $img,
         'timbres'               => $timbres,
        //  'classRetour'         => $this->classRetour,  
        //  'messageRetourAction' => $this->messageRetourAction 
         'erreurs'     => $erreurs
      ],
      'gabarit-membre');
  }
}
}