<!--inter_render_annif.php

Gère la page de rendu intermédiaire. Ceci comprend l'intégration des boutons de navigations et l'affichage de l'image de fond et des boites de texte
    Nom : Anniversaires
    Feuille de style CSS : style.css
-->

<?php
    require_once("function.php");
    html_head("style.css", "Anniversaires");

    /*Importation des fonctions javascript*/
    print("<script type=\"text/javascript\" src=\"function.js\"></script>\n");

    if(isset($_POST['htmlCode']) && isset($_POST['htmlBg']))
        html_interRender_annif($_POST['htmlCode'], $_POST['htmlBg']);

    html_end();
?>
