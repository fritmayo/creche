USE creche;

/*Nettoyage de la base avant modifications*/
DROP TABLE IF EXISTS ANNIVERSAIRE;
DROP TABLE IF EXISTS MENU;
DROP TABLE IF EXISTS DAY;
DROP TABLE IF EXISTS INFOS;

/*Création de la table gérant les jours de la semaine*/
CREATE TABLE DAY(
    idDay int NOT NULL,
    labelDay varchar(10) NOT NULL,
    PRIMARY KEY(idDay)
) ENGINE = INNODB DEFAULT CHARSET='utf8' COLLATE='utf8_general_ci';

/*Création de la table gérant les menus*/
CREATE TABLE MENU(
    idMenu int NOT NULL AUTO_INCREMENT,
    entree varchar(500),
    plat varchar(500),
    dessert varchar(500),
    idDay int NOT NULL,
    PRIMARY KEY(idMenu),
    FOREIGN KEY(idDay) REFERENCES DAY(idDay)
) ENGINE = INNODB DEFAULT CHARSET='utf8' COLLATE='utf8_general_ci';

/*Création de la table gérant les anniversaires*/
CREATE TABLE ANNIVERSAIRE(
    idAnniversaire int NOT NULL AUTO_INCREMENT,
    dateAnniv date,
    nom varchar(100),
    prenom varchar(100),
    PRIMARY KEY(idAnniversaire)
) ENGINE = INNODB DEFAULT CHARSET='utf8' COLLATE='utf8_general_ci';

/*Création de la table gérant les informations générales*/
CREATE TABLE INFOS(
    idInfo int not null AUTO_INCREMENT,
    txtInfo varchar(1010),
    PRIMARY KEY(idInfo)
) ENGINE = INNODB DEFAULT CHARSET='utf8' COLLATE='utf8_general_ci';
