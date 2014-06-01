<!--inter_render_menu.php

Gère la page de rendu intermédiaire. Ceci comprend l'intégration des boutons de navigations et l'affichage de l'image de fond et des boites de texte
    Nom : Menus
    Feuille de style CSS : style.css
-->

<?php
    require_once("function.php");
    html_head("style.css", "Menus");

    /*Importation des fonctions javascript*/
    print("<script type=\"text/javascript\" src=\"function.js\"></script>\n");

    if(isset($_POST['htmlCode']) && isset($_POST['htmlBg']))
        html_interRender_menu($_POST['htmlCode'], $_POST['htmlBg']);

    html_end();
?>
