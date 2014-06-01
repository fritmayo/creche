<?php

/*Crèe l'en-tête des fichiers html
    $css_style : nom du fichier css associé
    $window_name : nom de la fenêtre
*/
function html_head($css_style = "style.css", $window_name = "Crèche", $css_print_style = "style_print.css")
{
    print("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n
           <html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"fr\" lang=\"fr\">\n
               <head>\n
                   <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n
                   <link rel=\"stylesheet\" href=\"" . $css_style . "\" type=\"text/css\" />\n
                   <link rel=\"stylesheet\" href=\"" .$css_print_style . "\" type=\"text/css\" media=\"print\" />\n
                   <!--[if lt IE 9]>\n
                       <script src=\"http://html5shiv.googlecode.com/svn/trunk/html5.js\"></script>\n
                   <![endif]-->\n
               <title>" . $window_name . "</title>\n
               </head>\n
           <body>\n");
}

/*Ajoute les dernières balises des fichiers html. Utiliser pour clore les fichiers.
*/
function html_end()
{
    echo "</body>\n</html>\n";
}

/*Crèe l'en-tête de la page
    $title : titre à afficher en haut de la page
*/
function html_headOfPage($title = "Interface d'administration")
{
    print(" <header>\n
                <h1>" . $title . "</h1>\n
            </header>\n");
}

/*Crèe le menu le plus à gauche
*/
function html_leftMenu()
{
    print(" <div id=\"leftMenu\">\n
                <ul>\n
                    <li class=\"item\"><a href=\"menu_form.php\" title=\"Menus\">Menus</a></li>\n
                    <li><hr /></li>\n
                    <li class=\"item\"><a href=\"anniv_form.php\" title=\"Anniversaires\">Anniversaires</a></li>\n
                    <li><hr /></li>\n
                    <li class=\"item\"><a href=\"info_form.php\" title=\"Informations\">Infos</a></li>\n
                    <li><hr /></li>\n
                    <li class=\"item\"><a href=\"parsing_form.php\" title=\"Parsing\">Parsing</a></li>\n
                </ul>\n
            </div>\n");
}

/*Crèe le menu le plus à gauche avec le panneau d'options pour le drag and drop (pour les menus)
*/
function html_leftMenuWithOptPan_menu()
{
    print(" <div id=\"leftMenu\">\n
                <ul>\n
                    <li class=\"item\"><a href=\"menu_form.php\" title=\"Menus\">Menus</a></li>\n
                    <li><hr /></li>\n
                    <li class=\"item\"><a href=\"anniv_form.php\" title=\"Anniversaires\">Anniversaires</a></li>\n
                    <li><hr /></li>\n
                    <li class=\"item\"><a href=\"info_form.php\" title=\"Informations\">Infos</a></li>\n
                    <li><hr /></li>\n
                    <li class=\"item\"><a href=\"parsing_form.php\" title=\"Parsing\">Parsing</a></li>\n
                </ul>\n

                <form method=\"post\" action=\"menu_render.php\" enctype=\"multipart/form-data\" id=\"leftMenuForm\">\n
                    <div id=\"leftMenuWithOpt\">\n
                        <input type=\"button\" value=\"Remise à zéro\" onclick=\"initPositionTextAreas()\" />\n

                        <input type=\"button\" id=\"bgSelector_btn\" value=\"Changer l'image\" name=\"bgSelector_btn\" onclick=\"browseFile();\" />\n
                        <input type=\"file\" id=\"bgSelector_file\" name=\"bgSelector_file\" accept=\"image/*\" style=\"display:none\" onchange=\"this.form.submit();\" />\n

                        <input type=\"button\" value=\"Augmenter la police\" onclick=\"increaseFont_all()\" />\n
                        <input type=\"button\" value=\"Diminuer la police\" onclick=\"decreaseFont_all()\" />\n
                        <hr />\n
                        <input type=\"button\" value=\"Afficher l'aperçu\" onclick=\"renderGeneration_goto('leftMenuForm', 'inter_render_menu.php')\" />\n
                        <input type=\"button\" value=\"Terminer\" onclick=\"renderGeneration_goto('leftMenuForm', 'menu_form.php')\" />\n
                    </div>\n
                </form>\n
            </div>\n");
}

/*Création du tableau affichant les menus
    $suffixOrder : fin de la requête SQL. Utilisé pour ordonner l'affichage
*/
function create_MenuTable($suffixOrder = "")
{
    /*connection à la base de données*/
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=creche', 'creche', 'azerty', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    }
    catch (Exception $e) {
        die("Erreur : " . $e->getMessage());
    }

    /*$suffixOrder sert a ajouter le texte final de la requête sql si besoin est (ORDER BY ...)*/
    $menuList = $bdd->query("SELECT m.idMenu, m.entree, m.plat, m.dessert, d.labelDay FROM MENU m, DAY d WHERE m.idDay = d.idDay".$suffixOrder);
    $menuArray = implode("<>", getAllMenusId());      //formatage du tableau d'id pour qu'il puisse être lu par le javascript

    /*Création du formulaire principal*/
    print("<form method=\"POST\" action=\"menu_form.php\">\n");
    print("<table>\n<caption>Menu de la semaine</caption>\n");
    print(" <thead>\n
                <tr>\n
                    <th id='thead_checkbox'><input type='checkbox' title='Cochez cette case pour sélectionner tous les menus' id='mainCheckbox' onclick=\"swapAll(this, 'menuId', '".$menuArray."')\"/></th>\n
                    <th id='thead_action'>Action</th>\n
                    <th><a href=\"menu_form.php?order=idDay\">Jour</a></th>\n
                    <th><a href=\"menu_form.php?order=entree\">Entrée</a></th>\n
                    <th><a href=\"menu_form.php?order=plat\">Plat</a></th>\n
                    <th><a href=\"menu_form.php?order=dessert\">Déssert</a></th>\n
                </tr>\n
            </thead>\n");

    print("<tbody>\n");
    while($data = $menuList->fetch())
    {
        print("<tr>\n<td class=\"checkbox\">\n
                <input type=\"checkbox\" title=\"Cochez cette case pour sélectionner ce menu\" name=\"menuId_" . $data[0] . "\" id=\"menuId_" . $data[0] . "\" />
                </td>\n<td class='tbody_shortcutsBtnCell'>
                           <input type=\"submit\" value=\"\" title=\"Modifier le menu sélectionné\" alt=\"Modifier\" name=\"ask_modification_" .$data[0]. "\" class=\"action_modif\" />\n
                           <input type=\"submit\" value=\"\" title=\"Supprimer le menu sélectionné\" alt=\"Supprimer\" name=\"suppression_" .$data[0]. "\" class=\"action_suppr\" />\n
                </td>\n
                <td>" . $data[4] . "</td>\n<td>" . $data[1] . "</td><td>" . $data[2] . "</td>\n<td>" . $data[3] . "</td>\n</tr>\n");
    }

    print("</tbody>\n</table>\n");

    /*Boutons de gestion*/
    print(" <input type=\"submit\" value=\"Modifier\" title=\"Modifier le menu sélectionné\" name=\"ask_modification\"/>\n
            <input type=\"submit\" value=\"Supprimer\" title=\"Supprimer le(s) menu(s) sélectionné(s)\" name=\"suppression\"/>\n
            <input type=\"submit\" value=\"Supprimer tout\" title=\"Supprimer tous les menus\" name=\"suppress_all\" onclick=\"return confirm('Tous les menus vont être supprimés. Continuer ?')\">\n
            <input type=\"button\" value=\"Imprimer\" title=\"Imprimer tous les anniversaires\" name=\"print_button\" onclick='javascript:window.print()' />\n
            <input type=\"button\" value=\"Générer\" title=\"Générer l'affichage\" name=\"display\" onclick=\"document.location.href='menu_render.php'\"/></form>\n");

    /*Fermeture du pointeur de la base de données*/
    $menuList->closeCursor();
}

/*Crèe le formulaire permettant d'ajouter un menu
*/
function html_addMenuFrom()
{
    print("<form method=\"post\" action=\"menu_form.php\">\n
            <table id='tbl_addMenu'>\n<caption>Ajouter un menu</caption>\n
            <thead>\n
                <tr>\n
                    <th><label for=\"jour\">Jour</label></th>\n
                    <th><label for=\"entree\">Entrée</label></th>\n
                    <th><label for=\"plat\">Plat</label></th>\n
                    <th><label for=\"dessert\">Déssert</label></th>\n
                </tr>\n
            </thead>\n
            <tfoot>\n
                <tr>\n<th></th>\n<th></th>\n<th></th>\n<th>\n
                    <input type=\"reset\" title=\"Réinitialiser le contenu des champs de texte\" value=\"Effacer le contenu\" />\n
                    <input type=\"submit\" title=\"Enregister ce menu\" value=\"Enregister\" name=\"menuAdd\"/>\n
                </th>\n</tr>\n
            </tfoot>\n
            <tbody>\n
                <tr>\n
                    <td>\n");

    print("             <select name=\"jour\" id=\"jour\" size=\"1\" title=\"Sélectionnez un jour\">\n
                            <option value=\"Lundi\">Lundi</option>\n
                            <option value=\"Mardi\">Mardi</option>\n
                            <option value=\"Mercredi\">Mercredi</option>\n
                            <option value=\"Jeudi\">Jeudi</option>\n
                            <option value=\"Vendredi\">Vendredi</option>\n
                            <option value=\"Samedi\">Samedi</option>\n
                            <option value=\"Dimanche\">Dimanche</option>\n
                        </select>\n");

    print("         </td>\n
                    <td><input type=\"text\" name=\"entree\" id=\"entree\" size=\"25\" maxlength=\"498\" placeholder=\"Saisissez une entrée\" title=\"Exemple : Salade\" /></td>\n
                    <td><input type=\"text\" name=\"plat\" id=\"plat\" size=\"25\" maxlength=\"498\" placeholder=\"Saisissez un plat\" title=\"Exemple : Steak pâtes\" /></td>\n
                    <td><input type=\"text\" name=\"dessert\" id=\"dessert\" size=\"25\" maxlength=\"498\" placeholder=\"Saisissez un dessert\" title=\"Exemple : Glace\" /></td>\n
                </tr>\n
            </tbody>\n
            </table>\n
           </form>\n");
}

/*Ajoute un menu dans la base de données    <= ajouter un meilleur controle des données
    $m_entree : la chaine de caracrères contenant l'entrée
    $m_plat : la chaine de caracrères contenant le plat
    $m_dessert : la chaine de caracrères contenant le dessert
    $m_day : la chaine de caracrères contenant le jour
*/
function sql_addMenuInDb($m_entree, $m_plat, $m_dessert, $m_day)
{
    //connection à la bdd
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=creche', 'creche', 'azerty', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    }
    catch (Exception $e) {
        die("Erreur : " . $e->getMessage());
    }

    //exécution de la requête
    $bdd->exec("INSERT INTO MENU(entree, plat, dessert, idDay) VALUES('" . htmlspecialchars(trim($m_entree), ENT_QUOTES | ENT_HTML401) . 
               "', '" . htmlspecialchars(trim($m_plat), ENT_QUOTES | ENT_HTML401) . "', '" . 
               htmlspecialchars(trim($m_dessert), ENT_QUOTES | ENT_HTML401) . "', '" . getNumberFromDay($m_day) . "')");
}

/*Retourne le numéro du jour en fonction du libellé passé en paramètre
    $day : libellé du jour
*/
function getNumberFromDay($day)
{
    if (!strcmp($day, "Lundi"))  return 1;
    else if(!strcmp($day, "Mardi"))  return 2;
    else if(!strcmp($day, "Mercredi"))  return 3;
    else if(!strcmp($day, "Jeudi"))  return 4;
    else if(!strcmp($day, "Vendredi"))  return 5;
    else if(!strcmp($day, "Samedi"))  return 6;
    else if(!strcmp($day, "Dimanche"))  return 7;
    else
        return -1;
}

/*Retourne le libellé du jour en fonction du chiffre passé en paramètre
    $nb : numéro du jour
*/
function getDayFromNumber($nb)
{
    if     ($nb == 1)  return "Lundi";
    else if($nb == 2)  return "Mardi";
    else if($nb == 3)  return "Mercredi";
    else if($nb == 4)  return "Jeudi";
    else if($nb == 5)  return "Vendredi";
    else if($nb == 6)  return "Samedi";
    else if($nb == 7)  return "Dimanche";
    else               return -1;
}

/*Retourne la liste des tous les identifiants des menus
    $day : filtre selon un jour en particulier (str)
*/
function getAllMenusId($day = "")
{
    $stack = array();

    //connection à la bdd
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=creche', 'creche', 'azerty');
    }
    catch (Exception $e) {
        die("Erreur : " . $e->getMessage());
    }

    //exécution de la requête => récupération des menus
    if(empty($day))
        $query_id = $bdd->query("SELECT m.idMenu FROM MENU m");
    else
        $query_id = $bdd->query("SELECT m.idMenu FROM MENU m, DAY d WHERE m.idDay = d.idDay AND d.labelDay LIKE '".$day."'");

    //étoffage du tableau avec les informations de la requête (jours de la semaine)
    while($data = $query_id->fetch())
    {
        array_push($stack, $data[0]);
    }

    $query_id->closeCursor();
    return $stack;
}

/*Supprime un ou tous les menu(s) de la table
    $menuId : identifiant du menu à supprimer. Si ce dernier n'est pas indiqué, tous les menus sont supprimés
*/
function sql_deleteMenu($menuId = -1)
{
    //connection à la bdd
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=creche', 'creche', 'azerty');
    }
    catch (Exception $e) {
        die("Erreur : " . $e->getMessage());
    }

    //exécution de la requête => delete
    if($menuId >= 0)
        $bdd->exec("DELETE FROM MENU WHERE idMenu = " . $menuId);
    else
        $bdd->exec("DELETE FROM MENU");
}

/*Formulaire de modification d'un menu
    $idMenu : menu qui sera hypothétiquement modifié
*/
function html_menuModifForm($idMenu)
{
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=creche', 'creche', 'azerty', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    }
    catch (Exception $e) {
        die("Erreur : " . $e->getMessage());
    }
    $target = $bdd->query("SELECT * FROM MENU m WHERE m.idMenu = " . $idMenu);

    //pour avoir le seul élement retenu     <= a améliorer
    $target_tmp = $target->fetch();

    print("<form method=\"post\" action=\"menu_form.php\">\n
            <input type=\"hidden\" name=\"idToModify\" value=\"" . $target_tmp[0] . "\" />
            <table>\n<caption>Modifier un menu</caption>\n
            <thead>\n
                <tr>\n
                    <th><label for=\"jour\">Jour</label></th>\n
                    <th><label for=\"entree\">Entrée</label></th>\n
                    <th><label for=\"plat\">Plat</label></th>\n
                    <th><label for=\"dessert\">Déssert</label></th>\n
                </tr>\n
            </thead>\n
            <tfoot>\n
                <tr>\n<th></th>\n<th></th>\n<th></th>\n<th>\n
                    <input type=\"reset\" title=\"Réinitialiser le contenu des champs de texte\" value=\"Réinitialiser\" />\n
                    <input type=\"submit\" title=\"Enregister les modifications apportées à ce menu\" value=\"Modifier\" name=\"modification\" />\n
                </th>\n</tr>\n
            </tfoot>\n
            <tbody>\n
                <tr>\n
                    <td>\n");

    print("             <select name=\"jour\" id=\"jour\" size=\"1\" title=\"Sélectionnez un jour\" >\n
                            <option value=\"Lundi\" ".(($target_tmp[4] == 1) ? 'selected' : '').">Lundi</option>\n
                            <option value=\"Mardi\" ".(($target_tmp[4] == 2) ? 'selected' : '').">Mardi</option>\n
                            <option value=\"Mercredi\" ".(($target_tmp[4] == 3) ? 'selected' : '').">Mercredi</option>\n
                            <option value=\"Jeudi\" ".(($target_tmp[4] == 4) ? 'selected' : '').">Jeudi</option>\n
                            <option value=\"Vendredi\" ".(($target_tmp[4] == 5) ? 'selected' : '').">Vendredi</option>\n
                            <option value=\"Samedi\" ".(($target_tmp[4] == 6) ? 'selected' : '').">Samedi</option>\n
                            <option value=\"Dimanche\" ".(($target_tmp[4] == 7) ? 'selected' : '').">Dimanche</option>\n
                        </select>\n");

    print("         </td>\n
                    <td><input type=\"text\" name=\"entree\" id=\"entree\" size=\"30\" maxlength=\"498\" value=\"".$target_tmp[1]."\" title=\"Exemple : Salade\" /></td>\n
                    <td><input type=\"text\" name=\"plat\" id=\"plat\" size=\"30\" maxlength=\"498\" value=\"".$target_tmp[2]."\" title=\"Exemple : Steak pâtes\" /></td>\n
                    <td><input type=\"text\" name=\"dessert\" id=\"dessert\" size=\"30\" maxlength=\"498\" value=\"".$target_tmp[3]."\" title=\"Exemple : Glace\" /></td>\n
                <tr>\n
            </tbody>\n
            </table>\n
           </form>\n");

    $target->closeCursor();
}

/*Concerne la modification d'un menu existant
    $m_id : id du menu à modifier
    $m_entree : nouvelle valeur de l'entrée
    $m_plat : nouvelle valeur du plat
    $m_dessert : nouvelle valeur du déssert
    $m_day : nouvelle valeur du jour
    /!\ ===> de meilleures verifications doivent être faites ici /!\
*/
function sql_modifyMenu($m_id, $m_entree, $m_plat, $m_dessert, $m_day)
{
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=creche', 'creche', 'azerty', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    }
    catch (Exception $e) {
        die("Erreur : " . $e->getMessage());
    }

    $bdd->exec("UPDATE MENU m SET m.entree = '" .htmlspecialchars(trim($m_entree), ENT_QUOTES | ENT_HTML401). 
               "', m.plat = '" .htmlspecialchars(trim($m_plat), ENT_QUOTES | ENT_HTML401). "', m.dessert = '"
               .htmlspecialchars(trim($m_dessert), ENT_QUOTES | ENT_HTML401). "', m.idDay = " .getNumberFromDay($m_day). " WHERE m.idMenu = " .$m_id);
}

/*Gère la page ayant pour rôle de manipuler la surface de rendu finale*/
function genMenuDisplay()
{
    //connection à la bdd, puis sélection des menus ordonnées par jours (croissant)
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=creche', 'creche', 'azerty', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    }
    catch (Exception $e) {
        die("Erreur : " . $e->getMessage());
    }
    $menuList = $bdd->query("SELECT m.idMenu, d.idDay, m.entree, m.plat, m.dessert, d.labelDay FROM MENU m, DAY d WHERE m.idDay = d.idDay ORDER BY m.idDay ASC");

    //création de l'image de fond et des boites en fonction des élements dans la bdd
    // -> si l'image a été changée, on upload la nouvelle et on l'intègre
    if(isset($_FILES['bgSelector_file']))
    {
        //Pour le debug
        /*echo "<pre>\n";
        print_r($_FILES['bgSelector_file']);
        echo "</pre>\n";*/

        $fileDest = "images/".$_FILES['bgSelector_file']['name'];
        if (!move_uploaded_file($_FILES['bgSelector_file']['tmp_name'], $fileDest))
            die ("Erreur : impossible d'upload le fichier sélectionné");

        print("<div id=\"draggableSurface\"><img src=\"" . $fileDest . "\" alt=\"background\" id=\"backgroundDnD\" />\n");
    }
    // -> utilisation de l'image par défaut
    else
    {
        print("<div id=\"draggableSurface\"><div id='default_dragSurface'></div>\n");
    }

    //création des boites de texte draggables
    while($data = $menuList->fetch())
    {
        print("<p class=\"draggableText\">\n<strong>" . $data[5] . "</strong><br />\n" . $data[2] . "<br />\n" . $data[3] . "<br />\n" . $data[4] . "\n</p>\n");
    }
    print("</div>\n");

    /*javascript destiné à gérer le drag and drop*/
    print("<script type=\"text/javascript\">
                initPositionTextAreas();
                manageDnD();
           </script>\n");

    //fermeture du curseur virtuel de la base de données
    $menuList->closeCursor();
}

/*Génère et retourne le code html nécessaire à l'affichage des boites et de l'image de fond de la page de rendu finale
Cela concerne de corps de la page html c-a-d sans les balises doctype, head et body (contient l'intérieur de body)
    $strToBoxList : code javascript déja formaté contenant les caractéristiques des boxes
    $backgroundImg : image de fond à utiliser
    @return : chaine de caractère contenant le code html final
*/
function getMainHtmlForRenderPage($strToBoxList, $backgroundImg)
{
    $strFileContent = "<img src=\"../" . $backgroundImg . "\" alt=\"Image de fond\" id=\"backgroundDnD\" />\n" . $strToBoxList;
    return $strFileContent;
}

/*Génère et retourne tout le code html nécessaire à la page de rendu finale
    $strToBoxList : code javascript déja formaté contenant les caractéristiques des boxes
    $backgroundImg : image de fond à utiliser
    $window_name : nom de la page qui sera générée
    @return : chaine de caractère contenant le code html final
*/
function getAllHtmlForRenderPage($strToBoxList, $backgroundImg, $window_name = "Rendu")
{
    if(!file_exists($backgroundImg))
    {
        return "<p>Erreur, le fichier sélectionné n'a pas été généré correctement du à une erreur d'image de fond</p>\n";
    }
    list($bg_width, $bg_height) = getimagesize($backgroundImg);

    $strFileContent = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n
                        <html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"fr\" lang=\"fr\">\n
                        <head>\n
                            <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n
                            <link rel=\"stylesheet\" href=\"../style.css\" />\n
                            <style>
                                body
                                {
                                    width : " . $bg_width . "px;
                                    height : " . $bg_height . "px;
                                }
                            </style>
                            <title>" . $window_name . "</title>\n
                        </head>\n
                        <body>\n<div id='mainDiv_renderPage'>\n";
    $strFileContent = $strFileContent . "<img src=\"../". $backgroundImg . "\" alt=\"Image de fond\" />\n" . $strToBoxList . "\n</div>\n</body>\n</html>\n";
    //$strFileContent = $strFileContent . getMainHtmlForRenderPage($strToBoxList, $backgroundImg) . "</body>\n</html>\n";
    return $strFileContent;
}

/*Crèe le fichier html du rendu
    $strToBoxList : code javascript déja formaté contenant les caractéristiques des boxes
    $backgroundImg : image de fond à utiliser
    $page_name : nom physique de la page. Doit être renseigné sans extension
    $window_name : nom de la page qui sera générée (nom affiché dans le navigateur)
*/
function createRenderPage($strToBoxList, $backgroundImg, $page_name, $window_name = "Rendu")
{
    //ouverture du fichier => TODO (gérer les cas où il y aurait plusieurs rendus)
    $renderFile = fopen("rendus/" . $page_name . ".html", "w");

    //insertion du code html
    $embedStr = getAllHtmlForRenderPage($strToBoxList, $backgroundImg, $window_name);
    fputs($renderFile, $embedStr);

    //fermeture du fichier
    fclose($renderFile);
}

/*Gère la page manipulant l'apercu intermédiaire du rendu (boutons de navigation + rendu)
    $mainCode : code javascript formaté contenant les informations sur les boites de texte draggables
    $bgImage : image passée en paramètre , utilisée comme background
*/
function html_interRender_menu($mainCode, $bgImage)
{
    //il faut bien formater la chaine de caractères qui sera envoyée au javascript => remplacer les " et ' par \'
    $rules = array("\"" => "\\'", "'" => "\\'");
    $code = strtr($mainCode, $rules);

    print("<form method=\"POST\" action=\"info_form.php\" id=\"topForm_renderPage\">
            <input type=\"button\" value=\"Valider l'aperçu\" onclick=\"renderGeneration_goto('topForm_renderPage', 'menu_form.php')\" />\n
            <input type=\"button\" value=\"Imprimer l'aperçu\" onclick=\"javascript:window.print()\" />\n
            <input type=\"button\" value=\"Retour à l'édition\" onclick=\"javascript:history.go(-1)\" />\n
           </form>\n");

    //rendu en lui-même /!\ attention, ca ne fonctionnera plus en cas de modif du nom du projet => a améliorer
    print("<div id=\"mainDiv_renderPage\">\n" . getMainHtmlForRenderPage($mainCode, "creche/".$bgImage) . "</div>\n");
}

/*---------------
/ section annif /
-------------- */

/*Crèe le menu le plus à gauche avec le panneau d'options pour le drag and drop (pour les anniversaires)
*/
function html_leftMenuWithOptPan_annif()
{
    print(" <div id=\"leftMenu\">\n
                <ul>\n
                    <li class=\"item\"><a href=\"menu_form.php\" title=\"Menus\">Menus</a></li>\n
                    <li><hr /></li>\n
                    <li class=\"item\"><a href=\"anniv_form.php\" title=\"Anniversaires\">Anniversaires</a></li>\n
                    <li><hr /></li>\n
                    <li class=\"item\"><a href=\"info_form.php\" title=\"Informations\">Infos</a></li>\n
                    <li><hr /></li>\n
                    <li class=\"item\"><a href=\"parsing_form.php\" title=\"Parsing\">Parsing</a></li>\n
                </ul>\n

                <form method=\"post\" action=\"annif_render.php\" enctype=\"multipart/form-data\" id=\"leftMenuForm\">\n
                    <div id=\"leftMenuWithOpt\">\n
                        <input type=\"button\" value=\"Remise à zéro\" onclick=\"initPositionTextAreas()\" />\n

                        <input type=\"button\" id=\"bgSelector_btn\" value=\"Changer l'image\" name=\"bgSelector_btn\" onclick=\"browseFile();\" />
                        <input type=\"file\" id=\"bgSelector_file\" name=\"bgSelector_file\" accept=\"image/*\" style=\"display:none\" onchange=\"this.form.submit();\" />

                        <input type=\"button\" value=\"Augmenter la police\" onclick=\"increaseFont_all()\" />\n
                        <input type=\"button\" value=\"Diminuer la police\" onclick=\"decreaseFont_all()\" />\n
                        <hr />\n
                        <input type=\"button\" value=\"Afficher l'aperçu\" onclick=\"renderGeneration_goto('leftMenuForm', 'inter_render_annif.php')\" />\n
                        <input type=\"button\" value=\"Terminer\" onclick=\"renderGeneration_goto('leftMenuForm', 'anniv_form.php')\" />\n
                    </div>\n
                </form>\n
            </div>\n");
}

function getAllAnnivId()
{
    $stack = array();

    //connection à la bdd
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=creche', 'creche', 'azerty');
    }
    catch (Exception $e) {
        die("Erreur : " . $e->getMessage());
    }

    //exécution de la requête => récupération des anniversaires
    $query_id = $bdd->query("SELECT a.idAnniversaire FROM ANNIVERSAIRE a");

    //étoffage du tableau avec les informations de la requête (jours de la semaine)
    while($data = $query_id->fetch())
    {
        array_push($stack, $data[0]);
    }

    $query_id->closeCursor();
    return $stack;
}

/*Retourne le libellé du mois en fonction du numéro passé en paramètre
    $mois_nb : numéro correspondant au mois ([1 ; 12])
*/
function getMonthFromNumber($mois_nb)
{
    $month_list = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
    return $month_list[$mois_nb -1];
}

/*Retourne le numéro du mois en fonction du libellé passé en paramètre
    $mois_str : libellé correspondant au mois => /!\ éventuellement prévoir un test plus générique (en utilisant tolower(), en enlevant les caractères spéciaux avant le test,...)
*/
function getNumberFromMonth($mois_str)
{
    switch($mois_str)
    {
        case "Janvier" : return 1;
        case "Février" : return 2;
        case "Mars" : return 3;
        case "Avril" : return 4;
        case "Mai" : return 5;
        case "Juin" : return 6;
        case "Juillet" : return 7;
        case "Août" : return 8;
        case "Septembre" : return 9;
        case "Octobre" : return 10;
        case "Novembre" : return 11;
        case "Décembre" : return 12;
        default : return 0;
    }
}

function create_AnnivTable($suffixOrder)
{
    /*connection à la base de données*/
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=creche', 'creche', 'azerty', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    }
    catch (Exception $e) {
        die("Erreur : " . $e->getMessage());
    }

    /*$suffixOrder sert a ajouter le texte final de la requête sql si besoin est (ORDER BY ...)*/
    $annivList = $bdd->query("SELECT a.idAnniversaire, a.dateAnniv, a.prenom, a.nom FROM ANNIVERSAIRE a".$suffixOrder);
    $annivArray = implode("<>", getAllAnnivId());      //formatage du tableau d'id pour qu'il puisse être lu par le javascript

    /*Création du formulaire principal*/
    print("<form method=\"POST\" action=\"anniv_form.php\">\n");
    print("<table>\n<caption>Anniversaires à venir</caption>\n");
    print(" <thead>\n
                <tr>\n
                    <th id='thead_checkbox'><input type=\"checkbox\" title=\"Cochez cette case pour sélectionner tous les anniversaires\" id=\"mainCheckbox\" onclick=\"swapAll(this, 'annivId', '".$annivArray."')\"/></th>\n
                    <th id='thead_action'>Action</th>\n
                    <th><a href=\"anniv_form.php?order=dateAnniv\">Date de naissance</a></th>\n
                    <th><a href=\"anniv_form.php?order=prenom\">Prénom</a></th>\n
                    <th><a href=\"anniv_form.php?order=nom\">Nom</a></th>\n
                </tr>\n
            </thead>\n");

    print("<tbody>\n");
    while($data = $annivList->fetch())
    {
        list($l_year, $l_month, $l_day) = explode("-", $data[1]);
        print("<tr>\n<td class=\"checkbox\">\n
                <input type=\"checkbox\" title=\"Cochez cette case pour sélectionner cet anniversaire\" name=\"annivId_" . $data[0] . "\" id=\"annivId_" . $data[0] . "\" />
                </td>\n<td class='tbody_shortcutsBtnCell'>
                           <input type=\"submit\" value=\"\" title=\"Modifier l'anniversaire sélectionné\" alt=\"Modifier\" name=\"ask_modification_" .$data[0]. "\" class=\"action_modif\" />\n
                           <input type=\"submit\" value=\"\" title=\"Supprimer l'anniversaire sélectionné\" alt=\"Supprimer\" name=\"suppression_" .$data[0]. "\" class=\"action_suppr\" />\n
                </td>\n
                <td>" . $l_day." ".getMonthFromNumber($l_month)." ".$l_year . "</td><td>" . $data[2] . "</td>\n<td>" . $data[3] . "</td>\n</tr>\n");
    }

    print("</tbody>\n</table>\n");

    /*Boutons de gestion*/
    print(" <input type=\"submit\" value=\"Modifier\" title=\"Modifier l'anniversaire sélectionné\" name=\"ask_modification\"/>\n
            <input type=\"submit\" value=\"Supprimer\" title=\"Supprimer la sélection\" name=\"suppression\"/>\n
            <input type=\"submit\" value=\"Supprimer tout\" title=\"Supprimer tous les anniversaires\" name=\"suppress_all\" onclick=\"return confirm('Tous les anniversaires vont être supprimés. Continuer ?')\"/>\n
            <input type=\"button\" value=\"Imprimer\" title=\"Imprimer tous les anniversaires\" name=\"print_button\" onclick='javascript:window.print()' />\n
            <input type=\"button\" value=\"Générer\" title=\"Générer l'affichage\" name=\"display\" onclick=\"document.location.href='annif_render.php'\"/></form>\n");

    /*Fermeture du pointeur de la base de données*/
    $annivList->closeCursor();
}

function html_addAnnivFrom()
{
    print("<form method=\"post\" action=\"anniv_form.php\">\n
            <table id='tbl_addAnniv'>\n<caption>Ajouter un anniversaire</caption>\n
            <thead>\n
                <tr>\n
                    <th><label for=\"birthDate\">Date de naissance</label></th>\n
                    <th><label for=\"nom\">Nom</label></th>\n
                    <th><label for=\"prenom\">Prénom</label></th>\n
                </tr>\n
            </thead>\n
            <tfoot>\n
                <tr>\n<th></th>\n<th></th>\n<th>\n
                    <input type=\"reset\" title=\"Réinitialiser le contenu des champs de texte\" value=\"Effacer le contenu\" />\n
                    <input type=\"submit\" title=\"Enregister cet anniversaire\" value=\"Enregister\" name=\"annivAdd\"/>\n
                </th>\n</tr>\n
            </tfoot>\n
            <tbody>\n
                <tr>\n
                    <td>\n");

    echo "              <input type=\"text\" size=\"12\" id=\"calendarWidget_add\" name=\"annivDate\" />";
    echo "              <script type=\"text/javascript\">createJsCalendar();</script>\n";       //importation du js pour le calendrier

    print("         </td>\n
                    <td><input type=\"text\" name=\"nom\" id=\"nom\" size=\"25\" maxlength=\"99\" placeholder=\"Saisissez un nom de famille\" title=\"Exemple : Dupond\" /></td>\n
                    <td><input type=\"text\" name=\"prenom\" id=\"prenom\" size=\"25\" maxlength=\"99\" placeholder=\"Saisissez un prénom\" title=\"Exemple : Jean\" /></td>\n
                </tr>\n
            </tbody>\n
            </table>\n
           </form>\n");
}

function sql_addAnnivInDb($a_date, $a_nom, $a_prenom)
{
    //connection à la bdd
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=creche', 'creche', 'azerty', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    }
    catch (Exception $e) {
        die("Erreur : " . $e->getMessage());
    }

    //formatage de la date pour pouvoir l'insérer dans la base de données
    list($spl_day, $spl_month, $spl_year) = explode(" ", $a_date);
    $formated_date = $spl_year."-".getNumberFromMonth($spl_month)."-".$spl_day;

    //exécution de la requête
    $bdd->exec("INSERT INTO ANNIVERSAIRE(dateAnniv, nom, prenom) VALUES('" . $formated_date . "', '" . htmlspecialchars(trim($a_nom), ENT_QUOTES | ENT_HTML401) . "', '" . 
               htmlspecialchars(trim($a_prenom), ENT_QUOTES | ENT_HTML401) . "')");
}

function sql_deleteAnniv($annivId = -1)
{
    //connection à la bdd
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=creche', 'creche', 'azerty');
    }
    catch (Exception $e) {
        die("Erreur : " . $e->getMessage());
    }

    //exécution de la requête => delete
    if($annivId >= 0)
        $bdd->exec("DELETE FROM ANNIVERSAIRE WHERE idAnniversaire = " . $annivId);
    else
        $bdd->exec("DELETE FROM ANNIVERSAIRE");
}

function html_annivModifForm($idAnniv)
{
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=creche', 'creche', 'azerty', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    }
    catch (Exception $e) {
        die("Erreur : " . $e->getMessage());
    }
    $target_tmp = $bdd->query("SELECT * FROM ANNIVERSAIRE a WHERE a.idAnniversaire = " . $idAnniv);

    //pour avoir le seul élement retenu     <= a améliorer
    $target = $target_tmp->fetch();

    //formatage de la date
    list($t_year, $t_month, $t_day) = explode("-", $target[1]);

    print("<form method=\"post\" action=\"anniv_form.php\">\n
            <table>\n<caption>Modifier un anniversaire</caption>\n
            <input type=\"hidden\" name=\"idToModify\" value=\"" . $target[0] . "\" />
            <thead>\n
                <tr>\n
                    <th><label for=\"date\">Date de naissance</label></th>\n
                    <th><label for=\"nom\">Nom</label></th>\n
                    <th><label for=\"prenom\">Prénom</label></th>\n
                </tr>\n
            </thead>\n
            <tfoot>\n
                <tr>\n<th></th>\n<th></th>\n<th>\n
                    <input type=\"reset\" title=\"Réinitialiser le contenu des champs de texte\" value=\"Réinitialiser\" />\n
                    <input type=\"submit\" title=\"Enregister les modifications apportées à cet anniversaire\" value=\"Modifier\" name=\"modification\" />\n
                </th>\n</tr>\n
            </tfoot>\n
            <tbody>\n
                <tr>\n
                    <td>\n");

    echo "              <input type=\"text\" size=\"12\" id=\"calendarWidget_modif\" name=\"annivDate\" />\n";
    echo "              <script type=\"text/javascript\">createJsCalendar(".$t_day.", ".$t_month.", ".$t_year.");</script>\n";

    print("         </td>\n
                    <td><input type=\"text\" name=\"nom\" id=\"nom\" size=\"25\" maxlength=\"99\" value=\"".$target[2]."\" title=\"Exemple : Dupond\" /></td>\n
                    <td><input type=\"text\" name=\"prenom\" id=\"prenom\" size=\"25\" maxlength=\"99\" value=\"".$target[3]."\" title=\"Exemple : Jean\" /></td>\n
                <tr>\n
            </tbody>\n
            </table>\n
           </form>\n");

    $target_tmp->closeCursor();
}

/*Provoque la modification effective d'un anniversaire
    $a_id : identificant de l'anniv à modifier
    $a_date : nouvelle date de l'anniv
    $a_nom : nouveau nom de la personne relatif à l'anniversaire
    $a_prenom : nouveau prénom de la personne relatif à l'anniversaire
*/
function sql_modifyAnniv($a_id, $a_date, $a_nom, $a_prenom)
{
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=creche', 'creche', 'azerty', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    }
    catch (Exception $e) {
        die("Erreur : " . $e->getMessage());
    }

    //formatage de la date avant insertion
    list($exp_day, $exp_month, $exp_year) = explode(" ", $a_date);
    $af_date = $exp_year."-".getNumberFromMonth($exp_month)."-".$exp_day;

    $bdd->exec("UPDATE ANNIVERSAIRE a SET a.dateAnniv = '" .$af_date. 
               "', a.nom = '" .htmlspecialchars(trim($a_nom), ENT_QUOTES | ENT_HTML401). "', a.prenom = '"
               .htmlspecialchars(trim($a_prenom), ENT_QUOTES | ENT_HTML401). "' WHERE a.idAnniversaire = " .$a_id);
}

/*Gère la page ayant pour rôle de manipuler la surface de rendu finale des anniversaires*/
function genAnnifDisplay()
{
    //connection à la bdd, puis sélection des menus ordonnées par jours (croissant)
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=creche', 'creche', 'azerty', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    }
    catch (Exception $e) {
        die("Erreur : " . $e->getMessage());
    }
    $annifList = $bdd->query("SELECT a.idAnniversaire, a.dateAnniv, a.nom, a.prenom FROM ANNIVERSAIRE a ORDER BY a.dateAnniv ASC");

    //création de l'image de fond et des boites en fonction des élements dans la bdd
    // -> si l'image a été changée, on upload la nouvelle et on l'intègre
    if(isset($_FILES['bgSelector_file']))
    {
        //Pour le debug
        /*echo "<pre>\n";
        print_r($_FILES['bgSelector_file']);
        echo "</pre>\n";*/

        $fileDest = "images/".$_FILES['bgSelector_file']['name'];
        if (!move_uploaded_file($_FILES['bgSelector_file']['tmp_name'], $fileDest))
            die ("Erreur : impossible d'upload le fichier sélectionné");

        print("<div id=\"draggableSurface\"><img src=\"" . $fileDest . "\" alt=\"background\" id=\"backgroundDnD\" />\n");
    }
    // -> utilisation de l'image par défaut
    else
    {
        print("<div id=\"draggableSurface\"><div id='default_dragSurface'></div>\n");
    }

    //création des boites de texte draggables
    while($data = $annifList->fetch())
    {
        //on formate la date avant toute manipulation
        list($exp_year, $exp_month, $exp_day) = explode("-", $data[1]);
        $af_date = $exp_day." ".getMonthFromNumber($exp_month)." ".$exp_year;

        print("<p class=\"draggableText\">\n<strong>" . $af_date . "</strong><br />\n" . $data[3] . "<br />\n" . $data[2] . "\n</p>\n");
    }
    print("</div>\n");

    /*javascript destiné à gérer le drag and drop*/
    print("<script type=\"text/javascript\">
                initPositionTextAreas();
                manageDnD();
           </script>\n");

    //fermeture du curseur virtuel de la base de données
    $annifList->closeCursor();
}

/*Gère la page manipulant l'apercu intermédiaire du rendu (boutons de navigation + rendu)
    $mainCode : code javascript formaté contenant les informations sur les boites de texte draggables
    $bgImage : chemin de l'image uploadée utilisée comme image de fond
*/
function html_interRender_annif($mainCode, $bgImage)
{
    //il faut bien formater la chaine de caractères qui sera envoyée au javascript => remplacer les " et ' par \'
    $rules = array("\"" => "\\'", "'" => "\\'");
    $code = strtr($mainCode, $rules);

    print("<form method=\"POST\" action=\"info_form.php\" id=\"topForm_renderPage\">
            <input type=\"button\" value=\"Valider l'aperçu\" onclick=\"renderGeneration_goto('topForm_renderPage', 'anniv_form.php')\" />\n
            <input type=\"button\" value=\"Imprimer l'aperçu\" onclick=\"javascript:window.print()\" />\n
            <input type=\"button\" value=\"Retour à l'édition\" onclick=\"javascript:history.go(-1)\" />\n
           </form>\n");

    //rendu en lui-même /!\ attention, ca ne fonctionnera plus en cas de modif du nom du projet => a améliorer
    print("<div id=\"mainDiv_renderPage\">\n" . getMainHtmlForRenderPage($mainCode, "creche/".$bgImage) . "</div>\n");
}

/*-----------------
/ section parsing /
---------------- */

/*Parse (parseur DOM) l'url passée en paramètre en utilisant une ressources cUrl
    $url : url du site à parser
    @return : résultat de la requête xpath
*/
function parseExternalHTML($url)
{
    //$url = "http://www.alsace-des-petits.fr/Encart/agenda/liste/";

    // Création d'une ressource cURL
    $cUrlSessionId = curl_init($url);
    curl_setopt($cUrlSessionId, CURLOPT_USERAGENT, 'Récupère liste événements');

    // Définition de l'URL et autres options appropriées
    //curl_setopt($cUrlSessionId, CURLOPT_URL, $url);             //<-- fixe l'url à atteindre
    curl_setopt($cUrlSessionId, CURLOPT_RETURNTRANSFER, true);  //<-- permet de récupérer le résultat sous forme d'une chaine au lieu de l'afficher directement
    curl_setopt($cUrlSessionId, CURLOPT_HEADER, false);         //<-- permet de ne pas inclure le header dans le retour de l'execution

    // Récupération du contenu
    $content = curl_exec($cUrlSessionId);

    // Parsing du résultat
    // - définition du comportement à adopter en cas d'erreur de validation du code HTML récupéré   => surveiller retour
    libxml_use_internal_errors(true);

    $doc = new DOMDocument();
    $doc->validateOnParse = true;
    $doc->preserveWhiteSpace = false;
    $doc->loadHTML($content);

    $xpath = new DOMXpath($doc);
    $items = $xpath->query("/html/body/div[@class='container']/div[@class='maincontent']/div[@class='contenu']/div[@class='resultats']/div[@class='agenda_liste']/div[@class!='agenda_lstpage']/*[@class!='agenda_list_ESP'] | /html/body/div[@class='container']/div[@class='maincontent']/div[@class='contenu']/div[@class='resultats']/div[@class='agenda_liste']/div[@class!='agenda_lstpage']/h3");

    curl_close($cUrlSessionId);
    return $items;
}

/*Crèe le code html qui sera injecté dans le fichier .html de rendu
    $bodyCode : corps du code html à injecter dans la page de rendu
    $window_name : nom de la page qui sera générée (nom affiché dans le navigateur)
    @return : code html complet correspondant à la page qui doit être générée
*/
function generateParsingRender($bodyCode, $window_name)
{
    $strFileContent = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n
                        <html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"fr\" lang=\"fr\">\n
                        <head>\n
                            <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n
                            <link rel=\"stylesheet\" href=\"../style.css\" />\n
                            <title>" . $window_name . "</title>\n
                        </head>\n
                        <body>\n<div id='parsedPage_mainSection'>\n";

    $strFileContent .= $bodyCode . "</div>\n</body>\n</html>\n";
    return $strFileContent;
}

/*Crèe le fichier html du rendu concernant la page parsée
    $bodyCode : corps du code html à injecter dans la page de rendu
    $page_name : nom physique de la page. Doit être renseigné sans extension
    $window_name : nom de la page qui sera générée (nom affiché dans le navigateur)
*/
function createRenderPage_parsedPage($bodyCode, $page_name, $window_name = "Rendu")
{
    //ouverture du fichier => TODO (gérer les cas où il y aurait plusieurs rendus)
    $renderFile = fopen("rendus/" . $page_name . ".html", "w");

    //insertion du code html
    fputs($renderFile, generateParsingRender($bodyCode, $window_name));

    //fermeture du fichier
    fclose($renderFile);
}

/*---------------
/ section infos /
---------------*/

/*Crèe le menu le plus à gauche avec le panneau d'options pour le drag and drop (pour les infos)
*/
function html_leftMenuWithOptPan_info()
{
    print(" <div id=\"leftMenu\">\n
                <ul>\n
                    <li class=\"item\"><a href=\"menu_form.php\" title=\"Menus\">Menus</a></li>\n
                    <li><hr /></li>\n
                    <li class=\"item\"><a href=\"anniv_form.php\" title=\"Anniversaires\">Anniversaires</a></li>\n
                    <li><hr /></li>\n
                    <li class=\"item\"><a href=\"info_form.php\" title=\"Informations\">Infos</a></li>\n
                    <li><hr /></li>\n
                    <li class=\"item\"><a href=\"parsing_form.php\" title=\"Parsing\">Parsing</a></li>\n
                </ul>\n

                <form method=\"post\" action=\"info_render.php\" enctype=\"multipart/form-data\" id=\"leftMenuForm\">\n
                    <div id=\"leftMenuWithOpt\">\n
                        <input type=\"button\" value=\"Remise à zéro\" onclick=\"resetBeginState()\" />\n

                        <input type=\"button\" id=\"bgSelector_btn\" value=\"Changer l'image\" name=\"bgSelector_btn\" onclick=\"browseFile();\" />\n
                        <input type=\"file\" id=\"bgSelector_file\" name=\"bgSelector_file\" accept=\"image/*\" style=\"display:none\" onchange=\"this.form.submit();\" />\n

                        <input type=\"button\" value=\"Augmenter la police\" onclick=\"increaseFont_all()\" />\n
                        <input type=\"button\" value=\"Diminuer la police\" onclick=\"decreaseFont_all()\" />\n
                        <hr />\n
                        <input type=\"button\" value=\"Afficher l'aperçu\" onclick=\"renderGeneration_goto('leftMenuForm', 'inter_render_info.php')\" />\n
                        <input type=\"button\" value=\"Terminer\" onclick=\"renderGeneration_goto('leftMenuForm', 'info_form.php')\" />\n
                    </div>\n
                </form>\n
            </div>\n");
}

/*Crèe la page permettant l'ajout d'informations générales
*/
function html_addInfoFrom()
{
    print("<form method=\"post\" action=\"info_form.php\">\n
            <table id='tbl_addInfo'>\n<caption>Ajouter une information</caption>\n
            <thead>\n
                <tr>\n
                    <th><label for=\"info\">Information</label></th>\n
                </tr>\n
            </thead>\n
            <tfoot>\n
                <tr>\n<th>\n
                    <input type=\"reset\" title=\"Réinitialiser le contenu des champs de texte\" value=\"Effacer le contenu\" />\n
                    <input type=\"submit\" title=\"Enregister cette information\" value=\"Enregister\" name=\"infoAdd\"/>\n
                </th>\n</tr>\n
            </tfoot>\n
            <tbody>\n
                <tr>\n
                    <td>\n");

    echo "              <textarea id=\"info_txtArea\" name=\"info_txtArea\" maxlength=\"1000\"></textarea>\n";

    echo "         </td>\n
                </tr>\n
            </tbody>\n
            </table>\n
           </form>\n";
}

/*Crèe la page affichant les informations générales selon la base de données
*/
function create_InfoTable()
{
    /*connection à la base de données*/
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=creche', 'creche', 'azerty', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    }
    catch (Exception $e) {
        die("Erreur : " . $e->getMessage());
    }

    $infoList = $bdd->query("SELECT i.idInfo, i.txtInfo FROM INFOS i");
    $infoArray = implode("<>", getAllInfosId());      //formatage du tableau d'id pour qu'il puisse être lu par le javascript

    /*Création du formulaire principal*/
    print("<form method=\"POST\" action=\"info_form.php\">\n");
    print("<table>\n<caption>Informations générales</caption>\n");
    print(" <thead>\n
                <tr>\n
                    <th id='thead_checkbox'><input type='checkbox' title='Cochez cette case pour sélectionner toutes les informations' id='mainCheckbox' onclick=\"swapAll(this, 'infoId', '".$infoArray."')\"/></th>\n
                    <th id='thead_action'>Action</th>\n
                    <th>Informations</th>\n
                </tr>\n
            </thead>\n");

    print("<tbody>\n");
    while($data = $infoList->fetch())
    {
        print("<tr>\n<td class=\"checkbox\">\n
                <input type=\"checkbox\" title=\"Cochez cette case pour sélectionner cette information\" name=\"infoId_" . $data[0] . "\" id=\"infoId_" . $data[0] . "\" />
                </td>\n<td class='tbody_shortcutsBtnCell'>
                           <input type=\"submit\" value=\"\" title=\"Modifier le menu sélectionné\" alt=\"Modifier\" name=\"ask_modification_" .$data[0]. "\" class=\"action_modif\" />\n
                           <input type=\"submit\" value=\"\" title=\"Supprimer le menu sélectionné\" alt=\"Supprimer\" name=\"suppression_" .$data[0]. "\" class=\"action_suppr\" />\n
                </td>\n
                <td>" . $data[1] . "</td>\n</tr>\n");
    }

    print("</tbody>\n</table>\n");

    /*Boutons de gestion*/
    print(" <input type=\"submit\" value=\"Modifier\" title=\"Modifier l'information sélectionnée\" name=\"ask_modification\"/>\n
            <input type=\"submit\" value=\"Supprimer\" title=\"Supprimer l'(es) information(s) sélectionnée(s)\" name=\"suppression\"/>\n
            <input type=\"submit\" value=\"Supprimer tout\" title=\"Supprimer toutes les informations\" name=\"suppress_all\" onclick=\"return confirm('Toutes les informations vont être supprimées. Continuer ?')\">\n
            <input type=\"button\" value=\"Imprimer\" title=\"Imprimer toutes les informations\" name=\"print_button\" onclick='javascript:window.print()' />\n
            <input type=\"button\" value=\"Générer\" title=\"Générer l'affichage\" name=\"display\" onclick=\"document.location.href='info_render.php'\"/></form>\n");

    /*Fermeture du pointeur de la base de données*/
    $infoList->closeCursor();
}

/*Retourne la liste de tous les ids de la table INFOS
*/
function getAllInfosId()
{
    $stack = array();

    //connection à la bdd
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=creche', 'creche', 'azerty');
    }
    catch (Exception $e) {
        die("Erreur : " . $e->getMessage());
    }

    //exécution de la requête => récupération des anniversaires
    $query_id = $bdd->query("SELECT i.idInfo FROM INFOS i");

    //étoffage du tableau avec les informations de la requête (jours de la semaine)
    while($data = $query_id->fetch())
    {
        array_push($stack, $data[0]);
    }

    $query_id->closeCursor();
    return $stack;
}

/*Gère l'ajout d'une entitié de la table INFO dans la BDD
    $txt_info : Texte de l'information à ajouter
*/
function sql_addInfoInDb($txt_info)
{
    //connection à la bdd
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=creche', 'creche', 'azerty', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    }
    catch (Exception $e) {
        die("Erreur : " . $e->getMessage());
    }

    //exécution de la requête
    $bdd->exec("INSERT INTO INFOS(txtInfo) VALUES('" . htmlspecialchars(trim($txt_info), ENT_QUOTES | ENT_HTML401) . "')");
}

/*Gère la suppression d'entrée de la table INFOS dans la BDD
    $id_info : identifiant de l'info à supprimer. Un nombre inférieur à 0 reviens à tout supprimer
*/
function sql_deleteInfo($infoId = -1)
{
    //connection à la bdd
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=creche', 'creche', 'azerty');
    }
    catch (Exception $e) {
        die("Erreur : " . $e->getMessage());
    }

    //exécution de la requête => delete
    if($infoId >= 0)
        $bdd->exec("DELETE FROM INFOS WHERE idInfo = " . $infoId);
    else
        $bdd->exec("DELETE FROM INFOS");
}

/*Formulaire gérant la modification d'une information
    $id_info : identificant de l'info à modifier
*/
function html_infoModifForm($idInfo)
{
    //connexion à la base de données
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=creche', 'creche', 'azerty', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    }
    catch (Exception $e) {
        die("Erreur : " . $e->getMessage());
    }
    $target_tmp = $bdd->query("SELECT i.idInfo, i.txtInfo FROM INFOS i WHERE i.idInfo = " . $idInfo);

    //pour avoir le seul élement retenu     <= a améliorer
    $target = $target_tmp->fetch();

    print("<form method=\"post\" action=\"info_form.php\">\n
            <input type=\"hidden\" name=\"idToModify\" value=\"" . $target[0] . "\" />\n
            <table>\n<caption>Modifier une information</caption>\n
            <thead>\n
                <tr>\n
                    <th><label for=\"txt\">Information</label></th>\n
                </tr>\n
            </thead>\n
            <tfoot>\n
                <tr>\n<th>\n
                    <input type=\"reset\" title=\"Réinitialiser le contenu des champs de texte\" value=\"Réinitialiser\" />\n
                    <input type=\"submit\" title=\"Enregister les modifications apportées à cette information\" value=\"Modifier\" name=\"modification\" />\n
                </th>\n</tr>\n
            </tfoot>\n
            <tbody>\n
                <tr>\n
                    <td><textarea id=\"info_txtArea\" name=\"info_txtArea\" maxlength=\"1000\">" . $target[1] . "</textarea>\n
                <tr>\n
            </tbody>\n
            </table>\n
           </form>\n");

    $target_tmp->closeCursor();
}

/*Provoque la modification effective (dans la BDD) d'une information
    $i_id : identificant de l'info à modifier
    $i_txt : nouveau texte de l'info
*/
function sql_modifyInfo($i_id, $i_txt)
{
    //Connexion à la BDD
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=creche', 'creche', 'azerty', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    }
    catch (Exception $e) {
        die("Erreur : " . $e->getMessage());
    }

    //Modification de l'info
    $bdd->exec("UPDATE INFOS i SET i.txtInfo = '".htmlspecialchars(trim($i_txt), ENT_QUOTES | ENT_HTML401)."' WHERE i.idInfo = ".$i_id);
}

/*Gère la page ayant pour rôle de manipuler la surface de rendu finale des informations*/
function genInfoDisplay()
{
    $count = 0;

    //connection à la bdd, puis sélection des menus ordonnées par jours (croissant)
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=creche', 'creche', 'azerty', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    }
    catch (Exception $e) {
        die("Erreur : " . $e->getMessage());
    }
    $infoList = $bdd->query("SELECT i.idInfo, i.txtInfo FROM INFOS i");

    //création de l'image de fond et des boites en fonction des élements dans la bdd
    // -> si l'image a été changée, on upload la nouvelle et on l'intègre
    if(isset($_FILES['bgSelector_file']))
    {
        //Pour le debug
        /*echo "<pre>\n";
        print_r($_FILES['bgSelector_file']);
        echo "</pre>\n";*/

        $fileDest = "images/".$_FILES['bgSelector_file']['name'];
        if (!move_uploaded_file($_FILES['bgSelector_file']['tmp_name'], $fileDest))
            die ("Erreur : impossible d'upload le fichier sélectionné");

        print("<div id=\"draggableSurface\"><img src=\"" . $fileDest . "\" alt=\"background\" id=\"backgroundDnD\" />\n");
    }
    // -> utilisation de l'image par défaut
    else
    {
        print("<div id=\"draggableSurface\"><div id='default_dragSurface'></div>\n");
    }

    //création des boites de texte draggables
    while($data = $infoList->fetch())
    {
        print("<p class=\"draggableText\" id=\"draggableText" . $count . "\">" . $data[1] . "\n
                    <input type=\"button\" value=\"\" title=\"Augmenter la police\" class=\"incFont\" onclick=\"increaseFont_box(" . $count . ")\" />\n
                    <input type=\"button\" value=\"\" title=\"Diminuer la police\" class=\"decFont\" onclick=\"decreaseFont_box(" . $count . ")\" />\n
                    <input type=\"button\" value=\"\" title=\"Cacher la boite\" class=\"hideBox\" onclick=\"swapVisibility(" . $count . ")\" />\n
               </p>\n");

        $count += 1;
    }
    print("</div>\n");

    /*javascript destiné à gérer le drag and drop*/
    print("<script type=\"text/javascript\">
                initPositionTextAreas();
                initPositionToolboxAreas();
                manageDnD();
           </script>\n");

    //fermeture du curseur virtuel de la base de données
    $infoList->closeCursor();
}

/*Gère la page manipulant l'apercu intermédiaire du rendu (boutons de navigation + rendu)
    $mainCode : code javascript formaté contenant les informations sur les boites de texte draggables
    $bgImage : image passée en paramètre, utilisée comme background
*/
function html_interRender_info($mainCode, $bgImage)
{
    //il faut bien formater la chaine de caractères qui sera envoyée au javascript => remplacer les " et ' par \'
    $rules = array("\"" => "\\'", "'" => "\\'");
    $code = strtr($mainCode, $rules);

    /*Importation des fonctions javascript*/
    //print("<script type=\"text/javascript\" src=\"function.js\"></script>\n");

    print("<form method=\"POST\" action=\"info_form.php\" id=\"topForm_renderPage\">\n
            <input type=\"button\" value=\"Valider l'aperçu\" onclick=\"renderGeneration_goto('topForm_renderPage', 'info_form.php')\" />\n
            <input type=\"button\" value=\"Imprimer l'aperçu\" onclick=\"javascript:window.print()\" />\n
            <input type=\"button\" value=\"Retour à l'édition\" onclick=\"javascript:history.go(-1)\" />\n
           </form>\n");

    //rendu en lui-même /!\ attention, ca ne fonctionnera plus en cas de modif du nom du projet => a améliorer
    print("<div id=\"mainDiv_renderPage\">\n" . getMainHtmlForRenderPage($mainCode, "creche/".$bgImage) . "</div>\n");
}




