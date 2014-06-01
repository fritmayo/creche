<!--menu_form.php

Interface de gestion des menus (affichage/modification et ajout)
    Nom : Menu
    Feuille de style CSS : style.css
-->

<?php
    require_once("function.php");
    html_head("style.css", "Menu");

    html_headOfPage();

    /*Importation des fonctions javascript*/
    print("<script type=\"text/javascript\" src=\"function.js\"></script>\n");

    /*Si la page html de rendu doit être générée, alors on entrera dans le if*/
    if(isset($_POST['htmlCode']))
    {
        if(isset($_POST['htmlBg']))
            createRenderPage($_POST['htmlCode'], $_POST['htmlBg'], "menu", "Rendu");
        //si on arrive ici, c'est que l'image de fond n'a pas été définie => bug, on met donc l'image par défaut => prévoir un système plus cohérent
        else
            createRenderPage($_POST['htmlCode'], "images/defaultBg.png", "menu", "Rendu");
    }

    html_leftMenu();
?>

    <div id="menuForm">
        <?php
            $suffixOrder = "";
            /*Traitement des requêtes*/
            /*ajout*/
            if(isset($_POST['entree']) AND isset($_POST['plat']) AND isset($_POST['dessert'])
                AND isset($_POST['menuAdd']) AND isset($_POST['jour']))
            {
                sql_addMenuInDb($_POST['entree'], $_POST['plat'], $_POST['dessert'], $_POST['jour']);
            }
            /*Suppression de tous les menus*/
            else if(isset($_POST['suppress_all']))
            {
                sql_deleteMenu();
            }
            else
            {
                $array_menuId = getAllMenusId();
                foreach($array_menuId as $index)
                {
                    /* -- Avec les boutons -- */
                    /*suppression*/
                    if (isset($_POST['menuId_'.$index]) AND isset($_POST['suppression']))
                        sql_deleteMenu($index);

                    /*demande de modification*/
                    else if(isset($_POST['menuId_'.$index]) AND isset($_POST['ask_modification'])){
                        html_menuModifForm($index);
                        print("<hr/>\n");
                        break;
                    }
                    /* -- Avec les checkboxes -- */
                    /*suppression*/
                    if (isset($_POST['suppression_'.$index]))
                        sql_deleteMenu($index);

                    /*demande de modification*/
                    else if(isset($_POST['ask_modification_'.$index])){
                        html_menuModifForm($index);
                        print("<hr/>\n");
                        break;
                    }
                }
                /*modification effective*/
                if(isset($_POST['modification']) AND isset($_POST['entree']) AND isset($_POST['plat']) AND isset($_POST['dessert']) AND isset($_POST['idToModify']))
                        sql_modifyMenu($_POST['idToModify'], $_POST['entree'], $_POST['plat'], $_POST['dessert'], $_POST['jour']);

                /*ordonnancement*/
                if(isset($_GET['order']) AND (!strcmp($_GET['order'], "idDay") OR !strcmp($_GET['order'], "entree") OR !strcmp($_GET['order'], "plat") OR !strcmp($_GET['order'], "dessert")))
                    $suffixOrder = " ORDER BY m." . $_GET['order'] . " ASC";
                else    //si on ne veut pas ordonner les menus, il faut que cette variable soit égale à la chaine vide pour ne pas lever une erreur
                    $suffixOrder = "";
            }

            /*On affiche la liste des menus et le formulaire d'ajout  <== filtrage en fonction des args*/
            create_MenuTable($suffixOrder);
            print("<hr />\n");                
            html_addMenuFrom();

        ?>
    </div>

<?php
    html_end();
?>
