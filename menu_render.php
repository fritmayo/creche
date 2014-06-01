<!--menu_render.php

Interface de mise en page des menus (positionnement et modification du fond)
    Nom : Mise en page des menus
    Feuille de style CSS : style.css
-->

<?php
    require_once("function.php");
    html_head("style.css", "Mise en page des menus");

    html_headOfPage();

    /*Importation des fonctions javascript*/
    print("<script type=\"text/javascript\" src=\"function.js\"></script>\n");

    html_leftMenuWithOptPan_menu();

    /*Création de la surface principale c-a-d celle qui sera manipulée par l'utilisateur*/
    echo "<div id=\"menuForm\">\n";
    genMenuDisplay();
    echo "</div>\n";

    html_end();
?>
