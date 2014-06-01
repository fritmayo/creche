/* - Constantes - */
const CST_MIN_FONTSIZE = 10;
const CST_MAX_FONTSIZE = 40;
const CST_STEP_FONTSIZE = 5;

/*Intervertit l'état des cases à chocher (checked, unchecked)
    ref : balise parent
    beginLabel : début du nom de l'id des cases
    lId : liste des identifiants des élements composants la liste
*/
function swapAll(ref, beginLabel, lId)
{
    //les ids des menus sont extraits
    var idList = lId.split("<>");
    for(var i = 0 ; i < idList.length ; i++)
    {
        var currentId = document.getElementById(beginLabel + "_" + idList[i]);     //variable créé pour clarifier la lecture. La supprimer pour optimisation
        currentId.checked = eval(ref.checked);      //retourne true ou false (en fonction de l'état de la balise parente, la checkbox de titre)
    }
}

/*Gère le drag and drop
Une IEF initialise le d'n'd dès que l'utilisateur veut générer l'image principale
    Clic => drag
    Déplacement de la souris => dragging
    Relachement du clic => drop
    Scroll => drop (évite les bugs)
*/
function manageDnD()
{
    var storage = {};

    //Une fonction pour gérer les événements sous tous les navigateurs
    function addEvent(element, event, func)
    {
        if (element.attachEvent) {
            element.attachEvent('on' + event, func);
        } else {
            element.addEventListener(event, func, true);
        }
    }

    //IEF pour exécuter le code dirrectement à l'appel de la fonction
    (function init()
    {
        var movableElements = document.querySelectorAll("#draggableSurface .draggableText");
        var lSize = movableElements.length;

        for(var i = 0 ; i < lSize ; i++)
        {
            //Initialise le drag & drop (drag)
            addEvent(movableElements[i], 'mousedown', function(e) {
                var s = storage;
                s.target = e.target || event.srcElement;
                s.offsetX = e.clientX - s.target.offsetLeft;
                s.offsetY = e.clientY - s.target.offsetTop;
            });

            //Termine le drag & drop (drop)
            addEvent(movableElements[i], 'mouseup', function() {
                storage = {};
            });
        }
        //Termine le drag & drop (drop) en cas de scroll
        addEvent(document, 'scroll', function() {
            storage = {};
        });

        //Permet le suivi du drag & drop (dragging)
        addEvent(document, 'mousemove', function(e) {
            var target = storage.target;
            
            if (target) {
                target.style.top = e.clientY - storage.offsetY + 'px';
                target.style.left = e.clientX - storage.offsetX + 'px';
            }
        });
    })();
}

/*Définit les positions initiales des text boxes déplacables
/!\ surveiller le cas où il y a trop d'élements à rajouter => reset du top et décalage du left.
*/
function initPositionTextAreas()
{
    /* Cette section est a décommenter si l'image de fond passe en positionnement absolut
    //récupération de la position de l'image de fond
    var bgArea = document.querySelector("#draggableSurface");
    var top = 0, left = 0;
    do {
        left += bgArea.offsetLeft;
        top += bgArea.offsetTop;
    } while (bgArea = bgArea.offsetParent);
    */

    //récupération des text box(es)
    var areas = document.querySelectorAll("#draggableSurface .draggableText");
    var lSize = areas.length;

    //il faut prendre en compte les écarts par rapport a la hauteur de l'élement principal. On fixe donc le cas initial comme en cas de récurrence
    var stack = 5;
    areas[0].style.left = '10px';
    areas[0].style.top = '5px';

    for (var i = 1 ; i < lSize ; i++)
    {
        //le + 22 de la fin sert a prendre en compte le paddind et la bordure
        stack += parseInt(getComputedStyle(areas[i-1], null).height, 10) + 22;
        areas[i].style.left = '10px';
        areas[i].style.top = stack + 'px';
    }
}

/*Définit les positions initiales des boites à outils des text boxes déplacables (+, -, (\))
*/
function initPositionToolboxAreas()
{
    //récupération des text box(es)
    var areas = document.querySelectorAll("#draggableSurface .draggableText");
    var lSize = areas.length;

    // Manipulation des tool boxes
    var toolAreas_incFont = document.querySelectorAll("#draggableSurface p.draggableText input.incFont");
    var toolAreas_decFont = document.querySelectorAll("#draggableSurface p.draggableText input.decFont");
    var toolAreas_hideBox = document.querySelectorAll("#draggableSurface p.draggableText input.hideBox");

    for (var i = 0 ; i < lSize ; i++)
    {
        var leftAlign = (12 + parseFloat(window.getComputedStyle(areas[i], null).getPropertyValue('width'))) + 'px';

        toolAreas_incFont[i].style.left = leftAlign;
        toolAreas_incFont[i].style.top = '0px';

        toolAreas_decFont[i].style.left = leftAlign;
        toolAreas_decFont[i].style.top = '18px';

        toolAreas_hideBox[i].style.left = leftAlign;
        toolAreas_hideBox[i].style.top = '36px';
    }
}

/*Définit les positions initiales des boites à outils des text boxes déplacables (+, -, (\))
*/
function initPositionSpecificToolboxAreas(ident)
{
    //récupération des text box(es)
    var area = document.querySelector("#draggableSurface #draggableText" + ident);

    // Manipulation des tool boxes
    var toolAreas_btn = document.querySelectorAll("#draggableSurface #draggableText" + ident + " input");
    /*var toolAreas_decFont = document.querySelector("#draggableSurface p.draggableText input.decFont");
    var toolAreas_hideBox = document.querySelector("#draggableSurface p.draggableText input.hideBox");*/

    var leftAlign = (12 + parseFloat(window.getComputedStyle(area, null).getPropertyValue('width'))) + 'px';
    for (var i = 0 ; i < toolAreas_btn.length ; i++)
    {
        toolAreas_btn[i].style.left = leftAlign;
        toolAreas_btn[i].style.top = (i * 18) + 'px';
    }
}

/*Réinitialise les boites de textes à leur emplacements et propriétées initiaux
*/
function resetBeginState()
{
    var boxList = getRenderObj();
    var nodeBuf = document.querySelectorAll("#draggableSurface .draggableText");

    for (var i = 0 ; i < boxList.length ; i++)
    {
        boxList[i].fontSize = 20;
        nodeBuf[i].style.setProperty('font-size', "20px");

        boxList[i].visibilityState = 1;
        nodeBuf[i].style.opacity = 0.85;
    }

    initPositionTextAreas();
    initPositionToolboxAreas();
}

/*Objet javascript : correspond aux boites de texte draggables
    _top_pos : attribut de l'objet correspondant à la position (relative) en y
    _left_pos : attribut de l'objet correspondant à la position (relative) en x
    _txt : attribut de l'objet correspondant au texte à intégrer dans la boite
    _fontSize : police du texte contenu
    _class : objet Element contenant le noeud correspondant à la boite de texte
*/
function TxtBox(_top_pos, _left_pos, _txt, _fontSize, _class)
{
    //attributs
    this.top_pos = _top_pos;
    this.left_pos = _left_pos;
    this.txt = _txt;
    this.globClass = _class;
    this.fontSize = _fontSize;

    //attributs fixes par défaut
    this.visibilityState = 1;

    /*fonction de debug : affiche la liste des boites draggables et leurs caractéristiques*/
    this.printBoxList = function () {
        alert("top : " + this.top_pos + "\nleft : " + this.left_pos + "\ntext : " + this.txt + "\n\nvisibility : " + (this.visibilityState ? "visible" : "hidden") + "\nfont size : " + this.fontSize + "px\n");
    }
}

/*Retourne la liste des boites (liste d'objets js) qui doivent être créées.
    @return : liste de boites
*/
function getRenderObj()
{
    var txtBox = 0;

    //il faut le bon sélécteur lors de la génération de l'image (soit depuis le formulaire de rendu, soit depuis l'aperçu)
    if (document.querySelectorAll("#draggableSurface .draggableText").length != 0)
        txtBox = document.querySelectorAll("#draggableSurface .draggableText");
    else
        txtBox = document.querySelectorAll("#mainDiv_renderPage p");

    var lSize = txtBox.length;
    var boxList = [];

    //récupérer les attributs des objets, instancier des objets et les ajouter à l'array
    for(var i = 0 ; i < lSize ; i++)
    {///!\bricolage => a surveiller
        boxList.push(new TxtBox(txtBox[i].offsetTop - 10, txtBox[i].offsetLeft - 10, txtBox[i].innerHTML, parseFloat(window.getComputedStyle(txtBox[i], null).getPropertyValue('font-size')), txtBox));
    }

    return boxList;
}

/*supprime les espaces inutiles entre les balises <br />*/
/*function removeAllUselessSpaces(myString)
{
    var buf = myString;
    while(buf.contains('<br> ')) {
        buf = buf.replace('<br> ', '<br/>');
    }

    return buf;
}*/

/*Traduit la liste de boites (liste d'objets js) en code html. Ce dernier sera injecté dans l'url pour pouvoir transiter entre les pages.
Les infos relevées sont la position relative et le texte des boites
    @return : chaine de caractères correspondant à la liste des boites
*/
function translateBoxToHtml()
{
    var boxList = getRenderObj();
    var contentStr = "";

    for(var i = 0 ; i < boxList.length ; i++) {
        contentStr += "<p style=\"text-align:center;position:absolute;";
        contentStr += "padding:10px 5px;max-width:19%;min-width:13%;";
        contentStr += "font-size:" + boxList[i].fontSize + "px;";
        contentStr += "visibility:" + (boxList[i].visibilityState ? "visible" : "hidden") + ";";
        contentStr += "top:" + boxList[i].top_pos + "px; left:" + boxList[i].left_pos + "px;\">" + boxList[i].txt + "</p>\n";
    }

    return contentStr;
}

/*Augmente la police de toutes les boites de textes draggables (voir la section constantes)
*/
function increaseFont_all()
{
    var boxList = getRenderObj();
    var nodeBuf = document.querySelectorAll("#draggableSurface .draggableText");

    for(var i = 0 ; i < boxList.length ; i++)
    {
        if (boxList[i].fontSize > (CST_MAX_FONTSIZE - CST_STEP_FONTSIZE)) {
            boxList[i].fontSize = CST_MAX_FONTSIZE;
            nodeBuf[i].style.setProperty('font-size', CST_MAX_FONTSIZE + "px");
        }
        else {
            boxList[i].fontSize += CST_STEP_FONTSIZE;
            nodeBuf[i].style.setProperty('font-size', boxList[i].fontSize + "px");
        }
    }

    initPositionToolboxAreas();
}

/*Augmente la police d'une seule boite de texte (voir la section constantes)
*/
function increaseFont_box(ident)
{
    var boxList = getRenderObj();
    var nodeBuf = document.querySelector("#draggableSurface #draggableText" + ident);

    var currentBox = boxList[ident];

    if (currentBox.fontSize > (CST_MAX_FONTSIZE - CST_STEP_FONTSIZE)) {
        currentBox.fontSize = CST_MAX_FONTSIZE;
        nodeBuf.style.setProperty('font-size', CST_MAX_FONTSIZE + "px");
    }
    else {
        currentBox.fontSize += CST_STEP_FONTSIZE;
        nodeBuf.style.setProperty('font-size', currentBox.fontSize + "px");
    }

    initPositionSpecificToolboxAreas(ident);
}

/*Diminue la police de toutes les boites de textes draggables (voir la section constantes)
*/
function decreaseFont_all()
{
    var boxList = getRenderObj();
    var nodeBuf = document.querySelectorAll("#draggableSurface .draggableText");

    for(var i = 0 ; i < boxList.length ; i++)
    {
        if (boxList[i].fontSize < (CST_MIN_FONTSIZE + CST_STEP_FONTSIZE)) {
            boxList[i].fontSize = CST_MIN_FONTSIZE;
            nodeBuf[i].style.setProperty('font-size', CST_MIN_FONTSIZE + "px");
        }
        else {
            boxList[i].fontSize -= CST_STEP_FONTSIZE;
            nodeBuf[i].style.setProperty('font-size', boxList[i].fontSize + "px");
        }
    }

    initPositionToolboxAreas();
}

/*Diminue la police d'une seule boite de texte (voir la section constantes)
*/
function decreaseFont_box(ident)
{
    var boxList = getRenderObj();
    var nodeBuf = document.querySelector("#draggableSurface #draggableText" + ident);

    var currentBox = boxList[ident];

    if (currentBox.fontSize < (CST_MIN_FONTSIZE + CST_STEP_FONTSIZE)) {
        currentBox.fontSize = CST_MIN_FONTSIZE;
        nodeBuf.style.setProperty('font-size', CST_MIN_FONTSIZE + "px");
    }
    else {
        currentBox.fontSize -= CST_STEP_FONTSIZE;
        nodeBuf.style.setProperty('font-size', currentBox.fontSize + "px");
    }

    initPositionSpecificToolboxAreas(ident);
}

/*Active ou désactive l'affichage d'une boite de texte
*/
function swapVisibility(ident)
{
    var boxList = getRenderObj();
    var nodeBuf = document.querySelector("#draggableSurface #draggableText" + ident);

    var currentBox = boxList[ident];

    /*Test incertain => a revoir*/
    if (nodeBuf.style.opacity == 0.3)
    {
        currentBox.visibilityState = 1;
        nodeBuf.style.opacity = 0.85;
    }
    else
    {
        currentBox.visibilityState = 0;
        nodeBuf.style.opacity = 0.3;
    }
}

/*Pour obtenir le chemin relatif de l'image de fond dans la zone de drag and drop
    @return : src de l'image de fond
*/
function getBackgroundImage()
{
    if(document.getElementById("backgroundDnD"))
        return document.getElementById("backgroundDnD").getAttribute("src");
    return "images/defaultBg.png";
}

/* - redirections pour les menus - */
/*Redirige vers la page principale relative aux menus (sans paramètres)*/
/*function renderGeneration_gotoMenu() {
    window.open('menu_form.php?bgFile=' + getBackgroundImage() + '&embed=' + translateBoxToHtml(), '_self');
}*/

/*Redirige vers la page principale relative aux menus
    code : code html qui doit transiter (boite draggable). L'image de fond n'a pas besoin d'être passée en paramètre.
*/
/*function renderGeneration_gotoMenuWithCode(code) {
    window.open('menu_form.php?bgFile=' + getBackgroundImage() + '&embed=' + code, '_self');
}*/

/*Redirige vers la page de drag and drop des menus*/
/*function renderGeneration_gotoMenuRenderPage() {
    window.open('inter_render_menu.php?bgFile=' + getBackgroundImage() + '&embed=' + translateBoxToHtml(), '_self');
}*/

/* - redirections pour les anniversaires - */
/*Redirige vers la page principale relative aux anniversaires (sans paramètres)*/
/*function renderGeneration_gotoAnnif() {
    window.open('anniv_form.php?bgFile=' + getBackgroundImage() + '&embed=' + translateBoxToHtml(), '_self');
}*/

/*Redirige vers la page principale relative aux anniversaires
    code : code html qui doit transiter (boite draggable). L'image de fond n'a pas besoin d'être passée en paramètre.
*/
/*function renderGeneration_gotoAnnifWithCode(code) {
    window.open('anniv_form.php?bgFile=' + getBackgroundImage() + '&embed=' + code, '_self');
}*/

/*Redirige vers la page de drag and drop des anniversaires*/
/*function renderGeneration_gotoAnnifRenderPage() {
    window.open('inter_render_annif.php?bgFile=' + getBackgroundImage() + '&embed=' + translateBoxToHtml(), '_self');
}*/

/* - redirections pour les informations - */
/*    _container : noeud auquel ajouter les input hidden faisant office de variables (code html et background)
      _form_action : page à atteindre
*/
function renderGeneration_goto(_container, _form_action)
{
    var myForm = document.getElementById(_container);
    var tmpFormAction = myForm.action;
    myForm.action = _form_action;

    var newNode_code = document.createElement('input');
    newNode_code.id = "htmlCode";
    newNode_code.name = "htmlCode";
    newNode_code.type = "hidden";
    newNode_code.value = translateBoxToHtml();
    myForm.appendChild(newNode_code);

    var newNode_bg = document.createElement('input');
    newNode_bg.id = "htmlBg";
    newNode_bg.name = "htmlBg";
    newNode_bg.type = "hidden";
    newNode_bg.value = getBackgroundImage();
    myForm.appendChild(newNode_bg);

    myForm.submit();
    myForm.action = tmpFormAction;
}
/* - fin des fonctions de redirection - */

/*Trigger vers le input file dans le menu de gauche avec option panel (utilisé pour embellir le <input file> et ainsi éviter le champ par défaut)*/
function browseFile() {
    document.getElementById("bgSelector_file").click();
}

/*Renvoie une chaine de caractères en fonction du numéro de mois passé en paramètre
A utiliser dans le cas ou la fonction php ne marche pas
    $nb_month : numéro du mois
    @return : str correspondante au numéro du mois
*/
/*function getMonthLabelWithNumber(nb_month)
{
    var lst_month = new Array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
    return lst_month[nb_month -1];
}*/

function createJsCalendar()
{
    // Fixation des constantes pour le français
    g_l.MONTHS = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];
    g_l.DAYS_3 = ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"];
    g_l.MONTH_FWD = "Avancer d'un mois";
    g_l.MONTH_BCK = "Reculer d'un mois";
    g_l.YEAR_FWD = "Avancer d'une année";
    g_l.YEAR_BCK = "Reculer d'une année";
    g_l.CLOSE = "Fermer le calendrier";
    g_l.ERROR_2 = g_l.ERROR_1 = "Objet date invalide";

    // Si la fonction est appelée avec des paramètres, on initialise la date
    if (arguments.length == 3)
    {
        var myCalendar_modif = new JsDatePick({
			useMode : 2,
            isStripped : false,
			target : "calendarWidget_modif",
			dateFormat : "%d %F %Y",
			selectedDate : {
                day : arguments[0],
                month : arguments[1],
                year : arguments[2]
			}
        });
        document.getElementById("calendarWidget_modif").value = arguments[0].toString() + " " + g_l.MONTHS[arguments[1]-1] + " " + arguments[2].toString();
        document.getElementById("calendarWidget_modif").fireEvent('change');
    }
    // Sinon, on crèe simplement le widget
    else
    {
		var myCalendar_add = new JsDatePick({
			useMode : 2,
            isStripped : false,
			target : "calendarWidget_add",
			dateFormat : "%d %F %Y"

			/*yearsRange:[1978,2020],
			limitToToday:false,
			cellColorScheme:"beige",
			dateFormat:"%m-%d-%Y",
			imgPath:"img/",
			weekStartDay:1*/
		});
    }
}

//Imprime la page courante
function printPage()
{
    window.print();
}


