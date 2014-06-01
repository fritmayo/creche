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
    print("<script type=\"text/javascript\" src=\"function.js\"></script>\n");

    html_leftMenu();

    echo "<form method=\"POST\" action=\"parsing_render.php\" id='parsed_resultList'>\n";
    echo "  <div id='parsed_topBtn'>\n";
    echo "   <input type='button' value='Imprimer' id='parsed_printBtn' name='parsed_printBtn' onclick='javascript:window.print()' />\n";
    echo "   <input type='submit' value='Générer' id='parsed_generateBtn' name='parsed_generateBtn' />\n";
    echo "  </div>\n";
    echo "  <div id=\"parsedPage_mainSection\">\n";

    if(isset($_POST['submit_parsing']) && isset($_POST['url_field']))
    {
        //transmission des paramètres
        echo "<input type=\"hidden\" name=\"submit_parsing\" value=\"" . $_POST['submit_parsing'] . "\" />\n";
        echo "<input type=\"hidden\" name=\"url_field\" value=\"" . $_POST['url_field'] . "\" />\n";

        $items = parseExternalHTML($_POST['url_field']);

        $NB_TAG_PER_ITEM = 4;

        //il faut multiplier le nombre de résultats souhaité par le nombre de balises manipulées par résultat (ici, 4 balises par résultat)
        if(isset($_POST['nb_result']))
            $max_result = $NB_TAG_PER_ITEM * $_POST['nb_result'];
        else
            $max_result = $NB_TAG_PER_ITEM * 10;

        $htmlCode_buf = "";

        // On parcourt la liste parsée
        $count = 1;
        foreach($items as $item)
        {
            // Ajout du div de début
            if ($count % $NB_TAG_PER_ITEM == 1)
            {
                echo "<div class='parsed_listItem'>\n";
                $htmlCode_buf .= "<div class='parsed_listItem'>\n";
            }

            // Si on doit afficher un titre
            if(!strcmp($item->tagName, "h3"))
            {
                echo "<h3 class='parsed_title'>".$item->nodeValue."</h3>\n";
                $htmlCode_buf .= "<h3 class='parsed_title'>".$item->nodeValue."</h3>\n";
            }

            // Si on doit afficher une image
            //il faut tester localName plutot que tagName pour récupérer les images, ou un warning de php est généré O_o
            else if(isset($item->firstChild) && isset($item->firstChild->firstChild) && !strcmp($item->firstChild->firstChild->localName, "img"))
            {
                echo "<img class='parsed_img' src='".$item->firstChild->firstChild->getAttribute("src")."' alt='".$item->firstChild->firstChild->getAttribute("alt")."'/>\n";
                $htmlCode_buf .= "<img class='parsed_img' src='".$item->firstChild->firstChild->getAttribute("src")."' alt='".$item->firstChild->firstChild->getAttribute("alt")."'/>\n";
            }

            // Si on doit afficher une catégorie
            else if(!strcmp($item->tagName, "div") && !strcmp($item->getAttribute("class"), "agenda_list_type"))
            {
                echo "<p class='parsed_category'>".$item->nodeValue."<br/></p>\n";
                $htmlCode_buf .= "<p class='parsed_category'>".$item->nodeValue."<br/></p>\n";
            }

            // Sinon on affiche du texte brut
            else
            {
                echo "<div class='parsed_divText'><p class='parsed_text'>".$item->nodeValue."<br/></p></div>\n";
                $htmlCode_buf .= "<div class='parsed_divText'><p class='parsed_text'>".$item->nodeValue."<br/></p></div>\n";
            }

            // Ajout du div de fin
            if ($count % $NB_TAG_PER_ITEM == 0)
            {
                echo "</div>\n";
                $htmlCode_buf .= "</div>\n";
            }

            $count++;
            if($count > $max_result)
                break;
        }

        if (isset($_POST['parsed_generateBtn'])){
            print_r($htmlCode_buf);
            createRenderPage_parsedPage($htmlCode_buf, "Evenements");}

    }

    echo "  </div>\n";

    /*echo "<pre>\n";
    print_r($items);
    echo "</pre>\n";*/

    echo "</form>\n";
    html_end();
?>
