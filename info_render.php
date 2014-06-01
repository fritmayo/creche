<!--info_render.php

Interface de mise en page de la section information (positionnement et modification du fond)
    Nom : Mise en page des menus
    Feuille de style CSS : style.css
    Feuille de style CSS pour l'impression : style_print.css
-->

<?php
    require_once("function.php");
    html_head("style.css", "Mise en page des infos");

    html_headOfPage();

    /*Importation des fonctions javascript*/
    print("<script type=\"text/javascript\" src=\"function.js\"></script>\n");

    html_leftMenuWithOptPan_info();

    /*Création de la surface principale c-a-d celle qui sera manipulée par l'utilisateur*/
    echo "<div id=\"infoForm\">\n";
    genInfoDisplay();
    echo "</div>\n";

    html_end();
?>
