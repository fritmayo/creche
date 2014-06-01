<!--parsing_form.php

Interface de gestion de récupération de données externes provenant d'un site web
    Nom : Parsing
    Feuille de style CSS : style.css
    Version 1 : parsing de http://www.alsace-des-petits.fr/Encart/agenda/liste/
-->

<?php
    require_once("function.php");
    html_head("style.css", "Parsing");

    html_headOfPage();

    /*Importation des fonctions javascript*/
    //print("<script type=\"text/javascript\" src=\"function.js\"></script>\n");

    html_leftMenu();
?>

    <div id="parsingForm">
        <form method='POST' action='parsing_render.php'>
            <fieldset>
                <legend>Paramètres</legend>
                <label for='url_field'>Adresse du site externe</label><br/>
                <input type='url' id ='url_field' name='url_field' value='http://www.alsace-des-petits.fr/Encart/agenda/liste/' /><br/>
                <label for='nb_result'>Nombre de résultats</label><br/>
                <input type='number' id='nb_result' name='nb_result' min='1' max='20' step='1' value='10' /><br/>
                <input type='submit' id='submit_parsing' name='submit_parsing' value='Parser' />
            </fieldset>
        </form>
    </div>

<?php
    html_end();
?>
