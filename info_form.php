<!--info_form.php

Interface de gestion des informations diverses (affichage/modification et ajout)
    Nom : Infos
    Feuille de style CSS : style.css
    Feuille de style CSS pour l'impression : style_print.css
-->

<?php
    require_once("function.php");
    html_head("style.css", "Infos", "style_print.css");

    html_headOfPage();

    /*Importation des fonctions javascript*/
    print("<script type=\"text/javascript\" src=\"function.js\"></script>\n");

    if(isset($_POST['htmlCode']))
    {
        if(isset($_POST['htmlBg']))
            createRenderPage($_POST['htmlCode'], $_POST['htmlBg'], "informations", "Rendu");
        //si on arrive ici, c'est que l'image de fond n'a pas été définie => bug, on met donc l'image par défaut => prévoir un système plus cohérent pour l'image par défaut
        else
            createRenderPage($_POST['htmlCode'], "images/haricot.gif", "informations", "Rendu");
    }

    html_leftMenu();
?>

    <div id="infoForm">
        <?php
            /*Traitement des requêtes*/
            /*ajout*/
            if(isset($_POST['info_txtArea']) AND isset($_POST['infoAdd']))
            {
                sql_addInfoInDb($_POST['info_txtArea']);
            }
            else if(isset($_POST['suppress_all']))
            {
                sql_deleteInfo();
            }
            else
            {
                $array_infoId = getAllInfosId();
                foreach($array_infoId as $index)
                {
                    /* -- Avec les boutons -- */
                    /*suppression*/
                    if (isset($_POST['infoId_'.$index]) AND isset($_POST['suppression']))
                        sql_deleteInfo($index);

                    /*demande de modification*/
                    else if(isset($_POST['infoId_'.$index]) AND isset($_POST['ask_modification'])){
                        html_infoModifForm($index);
                        print("<hr/>\n");
                        break;
                    }
                    /* -- Avec les checkboxes -- */
                    /*suppression*/
                    if (isset($_POST['suppression_'.$index]))
                        sql_deleteInfo($index);

                    /*demande de modification*/
                    else if(isset($_POST['ask_modification_'.$index])){
                        html_infoModifForm($index);
                        print("<hr/>\n");
                        break;
                    }
                }

                /*modification effective*/
                if(isset($_POST['modification']) AND isset($_POST['info_txtArea']) AND isset($_POST['idToModify']))
                    sql_modifyInfo($_POST['idToModify'], $_POST['info_txtArea']);
            }

            /*Si les paramètres sont reconnus, on affiche la liste des informations et le formulaire d'ajout  <== filtrage en fonction des args*/
            create_InfoTable();
            print("<hr />\n");                
            html_addInfoFrom();
        ?>
    </div>

<?php
    html_end();
?>
