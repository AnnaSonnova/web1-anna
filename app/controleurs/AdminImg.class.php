<?php

/**
 * Classe Contrôleur des requêtes sur l'entité Img de l'application admin
 */

// class AdminImg extends Admin {

//   protected $methodes = [
//     'a' => ['nom'    => 'ajouterImg',   'droits' => [Utilisateur::PROFIL_ADMINISTRATEUR, Utilisateur::PROFIL_MEMBRE]],
//   ];

//   /**
//    * Constructeur qui initialise des propriétés à partir du query string
//    * et la propriété oRequetesSQL déclarée dans la classe Routeur
//    * 
//    */
//   public function __construct() {
//     $this->img_id = $_GET['img_id'] ?? null;
//     $this->oRequetesSQL = new RequetesSQL;
//   } 
  
//   /**
//    * Lister les timbres
//    */
//   public function listerImg() {
//     $img = $this->oRequetesSQL->getImg('admin');
//     (new Vue)->generer(
//       'vAdminImg',
//       [
//         'oUtilConn'           => self::$oUtilConn,
//         'titre'               => 'Gestion des images',
//         'img'               => $img,
//         'classRetour'         => $this->classRetour,  
//         'messageRetourAction' => $this->messageRetourAction        
//       ],
//       'gabarit-admin');
//   }

//   /**
//    * Ajouter un timbre
//    */
//   public function ajouterImg() {
//     $img = [];
//     $erreurs     = [];
//     if (count($_POST) !== 0){
//       $img = $_POST;
//       $oImg = new Img($img);
//       print_r($_FILES); '<br>';
//       print_r($_POST);
//       $nom_fichier = $_FILES['img_url']['name'];
//       $fichier = $_FILES['img_url']['tmp_name'];

//       //var_dump("isi"); exit;
//        $erreurs = $oImg->getErreurs();
//       if (count($erreurs) === 0){
        
//         $img_id=$this->oRequetesSQL->ajouterImg([ 
//           'img_url'      => $oImg->getImg_url(),
//           'img_timbre_id' => 
//           $oImg->getImg_timbre_id()  
//         ]);

//         if(move_uploaded_file($fichier, "images/".$nom_fichier)){
           
//           $img_id=$this->oRequetesSQL->ajouterImg([ 
//             'img_url'      => $oImg->getImg_url(),
//             'img_timbre_id' => 
//             $oImg->getImg_timbre_id()  
//           ]);
//         }else{
//           echo "fichier non copie";
//         }
        
//         if ( $img_id  > 0) { // test de la clé de timbre ajouté
//           $this->messageRetourAction = "Ajout de image numéro $img_id  effectué.";
//         } else {
//           $this->classRetour = "erreur";
//           $this->messageRetourAction = "Ajout de img non effectué.";
//         }
//         exit;
//        } 
//     }

//     (new Vue)->generer(
//       'vAdminTimbreAjouter',
//       [
//         'oUtilConn'   => self::$oUtilConn,
//         'titre'       => 'Ajouter un timbre',
//         'img' => $img,
//         'erreurs'     => $erreurs
//       ],
//       'gabarit-admin');

//   }
// }