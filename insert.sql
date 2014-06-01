USE creche;

/*Insertion des jours de la semaine*/
INSERT INTO DAY VALUES (1, 'Lundi');
INSERT INTO DAY VALUES (2, 'Mardi');
INSERT INTO DAY VALUES (3, 'Mercredi');
INSERT INTO DAY VALUES (4, 'Jeudi');
INSERT INTO DAY VALUES (5, 'Vendredi');
INSERT INTO DAY VALUES (6, 'Samedi');
INSERT INTO DAY VALUES (7, 'Dimanche');

/*Insertion de menus de test*/
INSERT INTO MENU VALUES(null, 'Rosette', 'Raclette', 'Glace à la vanille', 1);
INSERT INTO MENU VALUES(null, 'salade', 'fondue', 'Glace à la fraise', 2);
INSERT INTO MENU VALUES(null, 'chips', 'mont d\'or', 'Glace à la framboise des iles du sud nappée de coppeaux de chocolat au lait', 3);
INSERT INTO MENU VALUES(null, 'taboulé', 'pierrade', 'tiramisu', 4);
INSERT INTO MENU VALUES(null, 'carpaccio', 'crèpes', 'yaourt', 5);
INSERT INTO MENU VALUES(null, 'salade du berger', 'knacki', 'flanc', 6);
INSERT INTO MENU VALUES(null, 'carottes rapées', 'steak frites', 'crème brûlée', 7);

/*Insertion d'anniversaires factices*/
INSERT INTO ANNIVERSAIRE VALUES(null, '1991-12-12', 'truc', 'machin');
INSERT INTO ANNIVERSAIRE VALUES(null, '1992-06-18', 'chouette', 'machine');
INSERT INTO ANNIVERSAIRE VALUES(null, '1998-03-19', 'bidule', 'georges');
INSERT INTO ANNIVERSAIRE VALUES(null, '1999-01-01', 'hubert', 'chose');
INSERT INTO ANNIVERSAIRE VALUES(null, '2012-12-31', 'test', 'tesst');
INSERT INTO ANNIVERSAIRE VALUES(null, '2015-10-02', 'supertest', 'supersupertestdelamort');

/*Insertion d'infos pour les tests*/
INSERT INTO INFOS VALUES(null, 'Il y a une méga réduction sur les mini BN');
INSERT INTO INFOS VALUES(null, 'Demain, GREVE GENERALE ! Ceci est mis en place pour lutter contre la maltraitance des petits chiots unijambistes enroulées dans des tranches de jambon fumé');
INSERT INTO INFOS VALUES(null, '#{[|^@]}&é\"\'(-è_çà)=ù*,;:!?./§');
