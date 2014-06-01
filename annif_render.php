<!--annif_render.php

Interface de mise en page des anniversaires (positionnement et modification du fond)
    Nom : Mise en page des anniversaires
    Feuille de style CSS : style.css
-->

<?php
    require_once("function.php");
    html_head("style.css", "Mise en page des anniversaires");

    html_headOfPage();

    /*Importation des fonctions javascript*/
    print("<script type=\"text/javascript\" src=\"function.js\"></script>\n");

    html_leftMenuWithOptPan_annif();

    /*Création de la surface principale c-a-d celle qui sera manipulée par l'utilisateur*/
    echo "<div id=\"annivForm\">\n";
    genAnnifDisplay();
    echo "</div>\n";

    html_end();
?>
