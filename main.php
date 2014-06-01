<!--main.php

Page d'accueil
    Nom : Crèche
    Feuille de style CSS : style.css
-->

<?php
    require_once("function.php");
    html_head("style.css", "Crèche");

    html_headOfPage();
    html_leftMenu();

    html_end();
?>
<!--
TODO
- refactor
- IHM
- embellir main.php
- correction du decalage du dnd
- rendu : gérer la taille de l'image de fond/de la page
- borner les box dans le dnd
- modif de plusieurs menus
- signals en fin d'opération (ajout, suppression, erreur,...)
- sécurité : sha, .htacces, droits, xss, sql
- éviter de devoir refaire le placement des box en cas de modif d'une ligne du tableau
- order by x DESC
- remettre l'ordonnancement dans menu_form.php après modif ou suppression d'un menu
- ajouter dates
- auto-clean des menus chaque semaine
- save des elements du menus dans la bdd
- zone de dnd => possibilité d'augmenter la police
- ajouter commentaires html dans le code généré par php => utile en cas de modification par quelqu'un d'autre que moi
- normaliser les noms de fichiers
- tester avec d'autres résolutions
-->
