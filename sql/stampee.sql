--
-- Base de données : `stampee`
--

DROP SCHEMA IF EXISTS stampee;
CREATE SCHEMA stampee DEFAULT CHARACTER SET utf8 ;
USE stampee ;

--
-- Structure de la table utilisateur
--



--
-- Structure de la table utilisateur
--

CREATE TABLE utilisateur (
  utilisateur_id       int UNSIGNED NOT NULL AUTO_INCREMENT,
  utilisateur_nom      varchar(100) NOT NULL,
  utilisateur_prenom   varchar(100) NOT NULL,
  utilisateur_courriel varchar(255) NOT NULL UNIQUE,
  utilisateur_mdp      varchar(255) NOT NULL,
  utilisateur_profil   varchar(45) NOT NULL,
  PRIMARY KEY (utilisateur_id)
) ENGINE=InnoDB  DEFAULT CHARSET=UTF8;



INSERT INTO utilisateur VALUES
(null, "Pitt", "Bread", "bpitt@netflix.ca", SHA2("1a2a3a4a", 512), "membre"),
(null, "Sonnova",  "Anna",    "asonnova@gmail.ca",  SHA2("1a2a3a4a", 512), "administrateur"),
(null, "Ivanov",   "Ivan", "ivanov@site2.ca",      SHA2("1a2a3a4a", 512), "membre");


--
-- Structure de la table `enchere`
--
CREATE TABLE enchere (
  enchere_id int UNSIGNED NOT NULL  AUTO_INCREMENT,
  enchere_date_debut date NOT NULL,
  enchere_date_fin date NOT NULL,
  PRIMARY KEY (enchere_id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO enchere VALUES
(1, "2022-11-10", "2022-11-30" ),
(2, "2022-11-10", "2022-11-30" ),
(3, "2022-11-10", "2022-11-30" ),
(4, "2022-10-10", "2022-10-30" );  


--
-- Structure de la table `timbre`
--

CREATE TABLE pays (
  pays_id int UNSIGNED NOT NULL  AUTO_INCREMENT,
  pays_nom varchar(100) NOT NULL,
  PRIMARY KEY (pays_id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE timbre (
  timbre_id int UNSIGNED NOT NULL  AUTO_INCREMENT,
  timbre_nom varchar(255) NOT NULL,
  timbre_date smallint(5) NOT NULL,
  timbre_couleur varchar(45) ,
  timbre_tirage int,
  timbre_description text(400),
  timbre_prix_plancher double NOT NULL,
  timbre_dimension varchar(255) NOT NULL,
  timbre_pays_id int UNSIGNED NOT NULL,
  timbre_enchere_id int UNSIGNED NOT NULL,
--   timbre_condition_id int UNSIGNED NOT NULL,
--   timbre_certificat_id int UNSIGNED NOT NULL, 
  PRIMARY KEY (timbre_id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;




ALTER TABLE timbre
  ADD KEY fk_timbre_pays_idx (timbre_pays_id);

--
-- Contraintes pour la table timbre
--
ALTER TABLE timbre
  ADD CONSTRAINT fk_timbre_pays_id FOREIGN KEY (timbre_pays_id) REFERENCES pays (pays_id) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE timbre
  ADD KEY fk_timbre_enchere_idx (timbre_enchere_id);

--
-- Contraintes pour la table timbre
--
ALTER TABLE timbre
  ADD CONSTRAINT fk_timbre_enchere_id FOREIGN KEY (timbre_enchere_id) REFERENCES enchere (enchere_id) ON DELETE NO ACTION ON UPDATE NO ACTION;  



CREATE TABLE img (
  img_id int UNSIGNED NOT NULL  AUTO_INCREMENT,
  img_url varchar(255) NOT NULL,
  img_timbre_id int UNSIGNED NOT NULL,
  PRIMARY KEY (img_id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE img
  ADD KEY fk_img_timbre_idx (img_timbre_id);

ALTER TABLE img
  ADD CONSTRAINT fk_img_timbre_id FOREIGN KEY (img_timbre_id) REFERENCES timbre (timbre_id) ON DELETE NO ACTION ON UPDATE NO ACTION;    


CREATE TABLE mise (
  mise_id  int UNSIGNED NOT NULL AUTO_INCREMENT,
  mise_prix  double NOT NULL,
  mise_date  date NOT NULL,
  mise_utilisateur_id int UNSIGNED NOT NULL,
  mise_enchere_id int UNSIGNED NOT NULL,
  PRIMARY KEY (mise_id)
) ENGINE=InnoDB  DEFAULT CHARSET=UTF8;

ALTER TABLE mise
  ADD KEY fk_mise_utilisateur_idx (mise_utilisateur_id),
  ADD KEY fk_mise_enchere_idx (mise_enchere_id);

--
-- Contraintes pour la table utilisateur
--
ALTER TABLE mise
  ADD CONSTRAINT fk_mise_utilisateur_id FOREIGN KEY (mise_utilisateur_id) REFERENCES utilisateur (utilisateur_id) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT fk_mise_enchere_id FOREIGN KEY (mise_enchere_id) REFERENCES enchere (enchere_id) ON DELETE NO ACTION ON UPDATE NO ACTION;




--
-- Insertion de donnee
--
-- INSERT INTO profil VALUES
-- (1, "administrateur"),
-- (2, "membre");  




INSERT INTO pays (pays_id, pays_nom) VALUES
(1, 'Canada'),
(2, 'États-Unis'),
(3, 'Japon'),
(4, 'France'),
(5, 'Royaume-Uni'),
(6, 'Italie'),
(7, 'Suisse'),
(8, 'Ukraine'),
(9, 'Allemagne'),
(10, 'Belgique');

INSERT INTO timbre (timbre_id, timbre_nom, timbre_date, timbre_couleur, timbre_tirage, timbre_description, timbre_prix_plancher, timbre_dimension, timbre_pays_id, timbre_enchere_id) VALUES
(1, 'Penny rouge', '1871', 'rouge', 100, '«Penny rouge» ayant perforation, lettres aux quatre coins et numéro de plaque 148', '1000.00', '12mm*12mm*1mm', 5, 1),
(2,'Lord-Maire de Londres', '1989', 'blanche', 1000, 'GRANDE-BRETAGNE - CIRCA 1989 : Un timbre-poste utilisé du Royaume-Uni, représentant une illustration célébrant le 800e anniversaire de l’exposition du Lord-Maire de Londres, vers 1989.', '800.00', '12mm*12mm*1mm', 5, 2),
(3,'Navire de guerre russe Go F**k Yourself', '2022', 'blanche', 2000, 'Le timbre de la guerre dans Ukraine', '100.00', '12mm*12mm*1mm', 8, 3),
(4, 'Victorieux', '1966', 'grise', 10000, 'timbre[en] avec la silhouette d’Elizabeth II par Mary Gillick[en] et la surimpression en l’honneur de la victoire de l’équipe nationale d’Angleterre à la Coupe du Monde de la FIFA', '300.00', '12mm*12mm*1mm', 8, 4),
-- (5,'Timbre militaire', 1936, 'grise', 5000, 'L’armée britannique en Egypte pour la correspondance écrite en 1932-1936', '800.00', '12mm*12mm*1mm', 5),
-- (6,'Wilding', 1952, 'blanche', 2000, 'Timbre standard avec portrait d’Elizabeth II', '500.00', '12mm*12mm*1mm', 5),
-- (7,'France', 1871, 'grise', 500000, '-', '20.00', '12mm*12mm*1mm', 4),
-- (8, '25ème anniversaire du mariage impérial', 1894, 'rouge', 10000, '-', '500.00', '12mm*12mm*1mm', 3),
-- (9,'Penny noir', 1880, 'noir', 10000, '-', '500.00', '12mm*12mm*1mm', 5)
;



-- CREATE TABLE modePayement (
--   modePayement_id       int UNSIGNED NOT NULL AUTO_INCREMENT,
--   modePayement_carte      varchar(100) NOT NULL,
--   modePayement_dateExp  date NOT NULL,
--   modePayement_utilisateur_id int UNSIGNED NOT NULL,
--   PRIMARY KEY (modePayement_id)
-- ) ENGINE=InnoDB  DEFAULT CHARSET=UTF8;

-- ALTER TABLE modePayement
--   ADD KEY fk_modePayement_utilisateur_idx (modePayement_utilisateur_id);


-- CREATE TABLE condition (
--   condition_id int UNSIGNED NOT NULL  AUTO_INCREMENT,
--   condition_nom varchar(45) NOT NULL,
--   PRIMARY KEY (condition_id)

-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ALTER TABLE timbre
--   ADD KEY fk_timbre_condition_idx (timbre_condition_id);

-- CREATE TABLE certificat (
--   certificat_id int UNSIGNED NOT NULL  AUTO_INCREMENT,
--   certificat_boleen tinyint NOT NULL,
--   PRIMARY KEY (certificat_id)

-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ALTER TABLE timbre
--   ADD KEY fk_timbre_certificat_idx (timbre_certificat_id);




-- CREATE TABLE commentaire (
--   commentaire_id  int UNSIGNED NOT NULL AUTO_INCREMENT,
--   commentaire_text  text(500) NOT NULL,
--   commentaire_utilisateur_id int UNSIGNED NOT NULL
--   PRIMARY KEY (commentaire_id)
-- ) ENGINE=InnoDB  DEFAULT CHARSET=UTF8;

-- ALTER TABLE commentaire
--   ADD KEY fk_commentaire_utilisateur_idx (commentaire_utilisateur_id);

-- CREATE TABLE timbre_commentaire (
--   t_c_timbre_id int(10) UNSIGNED NOT NULL,
--   t_c_commentaire_id int(10) UNSIGNED NOT NULL,
   
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --
-- -- Index pour la table timbre_commentaire
-- --
-- ALTER TABLE timbre_commentaire
--   ADD PRIMARY KEY (t_c_timbre_id,t_c_commentaire_id),
--   ADD KEY fk_t_c_timbre_idx (t_c_timbre_id),
--   ADD KEY fk_t_c_commentaire_idx (t_c_commentaire_id);

-- CREATE TABLE coupCoeur (
--   coupCoeur_id int UNSIGNED NOT NULL  AUTO_INCREMENT,
--   coupCoeur_timbre_id int UNSIGNED NOT NULL
--   PRIMARY KEY (coupCoeur_id)

-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ALTER TABLE coupCoeur
--   ADD KEY fk_coupCoeur_timbre_idx (coupCoeur_timbre_id);  







