<!--inter_render_info.php

Gère la page de rendu intermédiaire. Ceci comprend l'intégration des boutons de navigations et l'affichage de l'image de fond et des boites de texte
    Nom : Infos
    Feuille de style CSS : style.css
    Feuille de style CSS pour l'impression : style_print.css
-->

<?php
    require_once("function.php");
    html_head("style.css", "Infos", "style_print.css");

    /*Importation des fonctions javascript*/
    print("<script type=\"text/javascript\" src=\"function.js\"></script>\n");

    if(isset($_POST['htmlCode']) && isset($_POST['htmlBg']))
        html_interRender_info($_POST['htmlCode'], $_POST['htmlBg']);

    html_end();
?>
