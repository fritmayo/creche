<!--menu_form.php

interface de gestion des anniversaires (affichage/modification et ajout)
    Nom : Anniversaires
    Feuille de style CSS : style.css
-->

<?php
    require_once("function.php");
    html_head("style.css", "Anniversaires");

    html_headOfPage();

    /*Importation des fonctions javascript*/
    print("<script type=\"text/javascript\" src=\"function.js\"></script>\n");

    /*Importantion des fichiers nécessaires au calendrier*/
    echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"calendar/jsDatePick_ltr.min.css\" />\n";
    echo "<script type=\"text/javascript\" src=\"calendar/jsDatePick.full.1.3.js\"></script>\n";

    if(isset($_POST['htmlCode']))
    {
        if(isset($_POST['htmlBg']))
            createRenderPage($_POST['htmlCode'], $_POST['htmlBg'], "anniversaire", "Rendu");
        //si on arrive ici, c'est que l'image de fond n'a pas été définie => bug, on met donc l'image par défaut => prévoir un système plus cohérent pour l'image par défaut
        else
            createRenderPage($_POST['htmlCode'], "images/haricot.gif", "anniversaire", "Rendu");
    }
    html_leftMenu();
?>

    <div id="annivForm">
        <?php
            $suffixOrder = "";
            /*Traitement des requêtes*/
            /*ajout*/
            if(isset($_POST['annivDate']) AND isset($_POST['nom']) AND isset($_POST['prenom']) AND isset($_POST['annivAdd']))
            {
                sql_addAnnivInDb($_POST['annivDate'], $_POST['nom'], $_POST['prenom']);
            }
            else if(isset($_POST['suppress_all']))
            {
                sql_deleteAnniv();
            }
            else
            {
                $array_annivId = getAllAnnivId();
                foreach($array_annivId as $index)
                {
                    /* -- Avec les boutons -- */
                    /*suppression*/
                    if (isset($_POST['annivId_'.$index]) AND isset($_POST['suppression']))
                        sql_deleteAnniv($index);

                    /*demande de modification*/
                    else if(isset($_POST['annivId_'.$index]) AND isset($_POST['ask_modification'])){
                        html_annivModifForm($index);
                        print("<hr/>\n");
                        break;
                    }
                    /* -- Avec les checkboxes -- */
                    /*suppression*/
                    if (isset($_POST['suppression_'.$index]))
                        sql_deleteAnniv($index);

                    /*demande de modification*/
                    else if(isset($_POST['ask_modification_'.$index])){
                        html_annivModifForm($index);
                        print("<hr/>\n");
                        break;
                    }
                }

                /*modification effective*/
                if(isset($_POST['modification']) AND isset($_POST['annivDate']) AND isset($_POST['nom']) AND isset($_POST['prenom']) AND isset($_POST['idToModify']))
                        sql_modifyAnniv($_POST['idToModify'], $_POST['annivDate'], $_POST['nom'], $_POST['prenom']);

                /*ordonnancement*/
                if(isset($_GET['order']) AND (!strcmp($_GET['order'], "dateAnniv") OR !strcmp($_GET['order'], "nom") OR !strcmp($_GET['order'], "prenom")))
                    $suffixOrder = " ORDER BY a." . $_GET['order'] . " ASC";
                else    //si on ne veut pas ordonner les menus, il faut que cette variable soit égale à la chaine vide pour ne pas lever une erreur
                    $suffixOrder = "";
            }

            /*Si les paramètres sont reconnus, on affiche la liste des anniversaires et le formulaire d'ajout  <== filtrage en fonction des args*/
            create_AnnivTable($suffixOrder);
            print("<hr />\n");                
            html_addAnnivFrom();

        ?>
    </div>

<?php
    html_end();
?>
