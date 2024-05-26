<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="dossier.css" />
        <script type="text/javascript" src="jquery-3.6.1.min.js"></script>
        <!-- 连接MySQL -->
        <?php
            try{
                $pdo = new PDO('mysql:host=localhost;dbname=wang','wang','&wang;');
            }catch(Exception $e){
                die("Erreur : ".$e->getMessage());
            }
        ?>

        <!-- Bootstrap CSS -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

        <!-- jQuery  -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


        <!-- Popper.js  -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>

        <!-- Bootstrap JS -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        <!-- Dagre-D3 JavaScript -->
        <script src="https://d3js.org/d3.v6.min.js"></script>
        <script src="https://dagrejs.github.io/project/dagre-d3/latest/dagre-d3.min.js"></script>

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">


    </head>
    <body>
          <!-- Barre de recherche -->
          <div id="searchBar" class="container-fluid bg-light py-2">
            <div class="row justify-content-center">
                <div class="col-auto">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <select id="searchOption" class="custom-select">
                                <option value="FORM">FORM</option>
                                <option value="LEMMA">LEMMA</option>
                                <option value="DEPREL">DEPREL</option>
                            </select>
                        </div>
                        <input type="text" id="searchInput" class="form-control" placeholder="Rechercher...">
                        <div class="input-group-append">
                            <button type="button" id="searchBtn" class="btn btn-outline-secondary">Rechercher</button>
                            <button type="button" id="clearBtn" class="btn btn-outline-secondary" style="display:none;">X</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
 <!-- Barre de navigation -->
 <div id="toolbox">
            <!-- Bouton qui s'affiche lorsque aucun fichier n'est sélectionné -->
            <input type="file" id="fileInput" style="display:none;">
            <button type="button" class="awesome-button" id="uploadBtn"><i class="fa fa-upload"></i> <span>Upload</span></button>
            <button type="button" class="awesome-button" id="categorieBtn" onclick="toggleDropdownMenuPourCate()"><i class="fa fa-list"></i> Categoire</button>
            <div  class="dropdown-menu"  id="dropdown-menu-cate">
                <span class="dropdown-item" onclick="dropdownActionPourCate(1)">Annee plus ancien </span>
                <span class="dropdown-item" onclick="dropdownActionPourCate(2)">Annee plus récent</span>
                <span class="dropdown-item" onclick="dropdownActionPourCate(3)">Langage</span>
                <span class="dropdown-item" onclick="dropdownActionPourCate(4)">Annotations Disponibles</span>
                <span class="dropdown-item" onclick="dropdownActionPourCate(5)">Default</span>
            </div>
            <!-- Bouton qui s'affiche lorsque des fichiers sont sélectionnés -->
            <button type="button"  class="awesome-button"  id="downloadBtn" style="display:none;"><i class="fa fa-download"></i> Download Conll</button>
            <button type="button"  class="awesome-button"  id="downloadTextBtn" style="display:none;"><i class="fa fa-file-text"></i> Download Text</button>
            <button type="button"  class="awesome-button"  id="deleteBtn" style="display:none;"><i class="fa fa-trash"></i> Supprimer</button>
            <button type="button" class="awesome-button"   id="openBtn" style="display:none;" onclick="toggleDropdownMenuPourOuvrir()"><i class="fa fa-folder-open"></i> Ouvrir</button>
            <div  class="dropdown-menu" id="dropdown-menu-open">
                <span class="dropdown-item" onclick="dropdownAction(1)">tableaux</span>
                <span class="dropdown-item" onclick="dropdownAction(2)">texte</span>
                <span class="dropdown-item" onclick="dropdownAction(3)">texte coloré selon les POS</span>
                <span class="dropdown-item" onclick="dropdownAction(4)">représentation graphique des dépendances</span>
            </div>
            <button type="button"  class="awesome-button" id="infBtn"  style="display:none;"><i class="fa fa-info-circle"></i> information</button>
            <!-- Boutons permanents. -->
            <div class="select-all-container">
                <input type="checkbox" id="checkAll">
                <label for="checkAll">Tout sélectionner</label>
            </div>
        </div>
    <!-- Fenêtre de fichier -->
    <div id = "box">
    </div>


        <!-- Fenêtre d'entrée d'informations -->
        <div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="infoModalLabel">Veuillez fournir des informations supplémentaires.</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="infoForm">
                    <div class="form-group">
                        <label for="Annee">Annee</label>
                        <input type="text" class="form-control" id="Annee" name="Annee" required>
                    </div>
                    <div class="form-group">
                        <label for="Langage">Langage</label>
                        <input type="text" class="form-control" id="Langage" name="Langage" required>
                    </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="submitInfo">Soumettre</button>
                </div>
                </div>
            </div>
            </div>


        <!-- Fenetre de information -->
        <div class="modal fade" id="fileInfoModal" tabindex="-1" role="dialog" aria-labelledby="fileInfoModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="fileInfoModalLabel">Informations</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Annee: <span id="fileAnnee"></span></p>
                        <p>Langage: <span id="fileLangage"></span></p>
                        <p>Annotations Disponibles:</p>
                        <ul id="fileConllInfo">
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>








    <script>
        //Obtenir le nom d'utilisateur de Login
        const urlParams = new URLSearchParams(window.location.search);
        const utilisateur = urlParams.get('username');
        // Embellissement des boutons
        const buttons = document.querySelectorAll('.awesome-button');

        buttons.forEach((button) => {
            button.onmousemove = function (e) {
                const boundingClientRect = button.getBoundingClientRect(); 

                const x = e.clientX - boundingClientRect.left;
                const y = e.clientY - boundingClientRect.top;

                const xc = boundingClientRect.width / 2;
                const yc = boundingClientRect.height / 2;

                const dx = x - xc;
                const dy = y - yc;

                button.style.setProperty('--rx', `${dy / -1}deg`); 
                button.style.setProperty('--ry', `${dx / 10}deg`); 
            };

            button.onmouseleave = function (e) {
                button.style.setProperty('--ty', '0'); 
                button.style.setProperty('--rx', '0'); 
                button.style.setProperty('--ry', '0'); 
            };

            button.onmousedown = function (e) {
                button.style.setProperty('--tz', '-25px'); 
            };

            document.body.onmouseup = function (e) {
                button.style.setProperty('--tz', '-12px'); 
            };
        });
        /*#############################################################
        Ces fonctions sont les principales fonctions de l'interface du cloud, 
        elles ont pour fonctionnalités principales l'effet de clic de souris, 
        la vérification de la sélection de fichiers, la fonction de sélection 
        globale et l'affichage/masquage des boutons.
        ###############################################################*/ 
        var box = document.querySelector("#box") 
        //Ajouter un effet de survol de la souris sur les fichiers.
        box.onmouseover=function (e){
            var file = null;
            if (e.target.classList.contains('file')){
                file=e.target;
            }
            else if(e.target.parentNode.classList.contains('file')){
                file=e.target.parentNode;
            }
            if(file){
                file.classList.add('fileActive');
            }
        }
        //Ajouter un effet de sortie de la souris.
        box.onmouseout=function(e){
            var file = null;
            if (e.target.classList.contains('file')){
                file=e.target;
            }
            else if(e.target.parentNode.classList.contains('file')){
                file=e.target.parentNode;
            }
            if(file){
                var checked = file.querySelector('i');
                if(!checked.classList.contains('checked')){
                    file.classList.remove('fileActive');
                }
            }
        }
        //Ajouter un effet de clic dans le coin supérieur gauche des fichiers.
        box.onclick = function(e){
            if(e.target.tagName == "I"){
                e.target.classList.toggle('checked');
            }
            setCheckAll();
            checkSelectedFiles();
        }
        //Vérifier si tout est sélectionné.
        function setCheckAll(){
            var is = document.querySelectorAll('.file > i');
            for (var i = 0; i<is.length;i++){
                if (!is[i].classList.contains('checked')){
                    checkedAll.checked = false;
                    return;
                }
            }
            checkedAll.checked = true;
        }


        //Affichage/masquage des boutons de la barre d'outils.
        function checkSelectedFiles() {

            
            var selectedFiles = document.querySelectorAll('.file > i.checked');
            //Boutons affichés lorsqu'un fichiers sont sélectionnés.
            var selectedoneBtn =  ['#downloadBtn','#openBtn', '#infBtn','#downloadTextBtn'];
            
            //Boutons affichés lorsque plusieurs fichiers sont sélectionnés.
            var selectedBtn =  [ '#deleteBtn'];
            //Boutons affichés lorsqu'aucun fichier n'est sélectionné.
            var otherButtons = [ '#uploadBtn', "#categorieBtn"];

            if (selectedFiles.length ==1 ) {
                selectedoneBtn.forEach(function(selector){
                    document.querySelector(selector).style.display = 'inline-block';
                });
                selectedBtn.forEach(function(selector){
                    document.querySelector(selector).style.display = 'inline-block';
                });
                otherButtons.forEach(function(selector) {
                    document.querySelector(selector).style.display = 'none'; 
                });
            } else if(selectedFiles.length > 1 )  {
                selectedoneBtn.forEach(function(selector){
                    document.querySelector(selector).style.display = 'none';
                });
                otherButtons.forEach(function(selector) {
                    document.querySelector(selector).style.display = 'none'; 
                });
                selectedBtn.forEach(function(selector){
                    document.querySelector(selector).style.display = 'inline-block';
                });
            }else{
                selectedoneBtn.forEach(function(selector) {
                    document.querySelector(selector).style.display = 'none'; 
                });
                selectedBtn.forEach(function(selector) {
                    document.querySelector(selector).style.display = 'none'; 
                });
                otherButtons.forEach(function(selector){
                    document.querySelector(selector).style.display = 'inline-block';
                });
            }
        }
        //Tout sélectionner
        var checkedAll=document.querySelector('#checkAll');
        checkedAll.onchange = function(){
            
            var files = document.querySelectorAll('.file');
            files.forEach(function(item){
                var checked = item.querySelector('i');
                if(checkedAll.checked){
                    item.classList.add('fileActicve');
                    checked.classList.add('checked');
                }
                else{
                    item.classList.remove('fileActicve');
                    checked.classList.remove('checked');
                }
            })
            checkSelectedFiles() ;
        }

        /*################################################################################
        Ce code est destiné à implémenter une fonctionnalité similaire à celle de Onedrive, 
        où l'on clique sur un bouton pour faire apparaître un menu déroulant.
        ####################################################################*/
        // Implémentation d'un menu déroulant.
        //Bouton cliquable pour le menu déroulant.
        function toggleDropdownMenuPourOuvrir() {
              var dropdownMenu = document.getElementById("dropdown-menu-open");
              var openBtn = document.getElementById("openBtn");
              var rect = openBtn.getBoundingClientRect();
              dropdownMenu.style.left = rect.left + "px";
              dropdownMenu.style.top = rect.bottom + "px";
              dropdownMenu.style.display = dropdownMenu.style.display === "block" ? "none" : "block";
          }

          document.addEventListener("click", function (e) {
              if (!e.target.matches("#openBtn")) {
                  var dropdownMenu = document.getElementById("dropdown-menu-open");
                  if (dropdownMenu.style.display === "block") {
                      dropdownMenu.style.display = "none";
                  }
              }
          });

        // Implémentation d'un menu déroulant.
        //Bouton cliquable pour le menu déroulant.
        function toggleDropdownMenuPourCate() {
              var dropdownMenu = document.getElementById("dropdown-menu-cate");
              var cateBtn = document.getElementById("categorieBtn");
              var rect = cateBtn.getBoundingClientRect();
              dropdownMenu.style.left = rect.left + "px";
              dropdownMenu.style.top = rect.bottom + "px";
              dropdownMenu.style.display = dropdownMenu.style.display === "block" ? "none" : "block";
          }

          document.addEventListener("click", function (e) {
              if (!e.target.matches("#categorieBtn")) {
                  var dropdownMenu = document.getElementById("dropdown-menu-cate");
                  if (dropdownMenu.style.display === "block") {
                      dropdownMenu.style.display = "none";
                  }
              }
          });
          
        /*#######################################
        Cette fonction est principalement destinée à mettre à jour en temps 
        réel les fichiers et à les afficher dans la fenêtre du cloud.
         ########################################*/
        //Obtenir le nom de fichier à partir de la base de données SQL.
        function updateFileList() {
            $.ajax({
                url: "file_list.php",
                method: "GET",
                data: { Utilisateur: utilisateur },
                dataType: "json",
                success: function (files) {
                    updateFileWindow(files);
                },
            });
        }

        //Ajouter le nom de fichier à la fenêtre du cloud.

        function updateFileWindow(files) {
            // Vider la liste des fichiers.
            $("#box").empty();

            // Create a div to contain the files and set its style
            var fileGroup = document.createElement("div");
            fileGroup.style.display = "flex";
            fileGroup.style.flexWrap = "wrap";
            $("#box").append(fileGroup);

            // Ajouter une nouvelle liste de fichiers.
            files.forEach(function (file) {
                var fileElement = document.createElement("div");
                fileElement.className = "file";
                fileElement.innerHTML = '<img src="img/dossier.png"><span>' + file + '</span><i class=""></i>';
                fileGroup.appendChild(fileElement);
            });
        }
        updateFileList();

        /*#######################################
        Cette fonction est principalement destinée à télécharger des fichiers. 
        Elle télécharge les fichiers en tant que chaînes de caractères, donc les fichiers trop volumineux ne peuvent pas être téléchargés. 
        En même temps, elle lit les données par ligne et télécharge les données qui correspondent au format (avec neuf séparateurs/t) 
        en tant que données CoNLL dans le SQL Datas.
         ########################################*/
         //upload
        $(document).ready(function() {
            $("#uploadBtn").on("click", function() {
                $("#fileInput").click();
            });

            $("#fileInput").on("change", function() {
                var fileInput = document.getElementById("fileInput");
                var file = fileInput.files[0];
                var formData = new FormData();
                formData.append("file", file);

                formData.append("Utilisateur", utilisateur);
                // Utilisation de la méthode AJAX pour envoyer le fichier au serveur.
                $.ajax({
                    url: "upload_file.php",
                    method: "POST",
                    data: formData,
                    processData: false, 
                    contentType: false, 
                    success: function(response) {
                        alert("Le fichier a été téléchargé avec succès.");
                        //Envoyer le donnee a SQL
                        updateFileList(); 
                        //Avoir le fenetre de Fenêtre d'entrée d'informations
                        $('#infoModal').modal('show');
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        // Si le fichier existe déjà sur le serveur.
                        if (xhr.status === 400 && xhr.responseText === "Le fichier existe déjà.") {
                            alert("Le fichier existe déjà.");
                        } else {
                            alert("Le téléchargement du fichier a échoué.");
                        }
                    }
                });
            });
        });

        //Code de traitement de la soumission des informations supplémentaires(Annee, Langue, etc).
        $("#submitInfo").on("click", function() {
            var annee = $("#Annee").val();
            var langage = $("#Langage").val();
            var file_name = $("#fileInput")[0].files[0].name;

            $.ajax({
                url: "upload_information.php",
                method: "POST",
                data: {
                    Titre: file_name,
                    Annee: annee,
                    Langage: langage,
                    Utilisateur: utilisateur
                },
                dataType: "json", 
                success: function(response) {
                    if (response.status === "success") {
                        alert("Soumission des informations réussie.");
                        $('#infoModal').modal('hide');
                        updateFileList();
                    } else {
                        alert("Échec de la soumission des informations.");
                        console.log(response.error); 
                    }
                    console.log("调试信息:", response.debugInfo); 
                },
                error: function() {
                    alert("Échec de la soumission des informations.");
                }
            });
        });


        /* #############################################################################
        Cette fonction prend en paramètres le nom du fichier et le nom d'utilisateur, récupère les données correspondantes à partir de
        la base de données "datas" sur le serveur, formate ces données en une chaîne de caractères, puis utilise 
        la fonction de téléchargement intégrée du navigateur pour télécharger le fichier.
        #################################################################################*/
        //Download Par Conll
        function downloadFile(file_name) {
            $.ajax({
                url: "download_file.php",
                method: "POST",
                data: {
                    file_name: file_name,
                    user_name: utilisateur
                },
                dataType: "json",
                success: function(data) {
                    var content = data.map(function(row) {
                        return row.ID + "\t" + row.FORM + "\t" + row.LEMMA + "\t" + row.UPOS + "\t" + row.XPOS + "\t" + row.FEATS + "\t" + row.HEAD + "\t" + row.DEPREL + "\t" + row.DEPS + "\t" + row.MISC;
                    }).join("\n");
                    // Créer un objet Blob à partir du texte formatté
                    var blob = new Blob([content], { type: "text/plain;charset=utf-8" });
                    var url = URL.createObjectURL(blob);
                    var link = document.createElement("a");
                    link.href = url;
                    link.download = file_name;
                      // Ajouter le lien de téléchargement au corps du document
                    document.body.appendChild(link);
                    link.click();
                     // Attendre un court délai avant de supprimer le lien de téléchargement et l'URL de l'objet Blob
                    setTimeout(function() {
                        document.body.removeChild(link);
                        URL.revokeObjectURL(url);
                    }, 100);
                },
                error: function() {
                    alert("Echec");
                }
            });
        }


        document.getElementById("downloadBtn").addEventListener("click", function () {
            var checkedFiles = document.querySelectorAll('.file > i.checked');
            var files = [];

            checkedFiles.forEach(function (checkedFile) {
                var fileName = checkedFile.parentNode.querySelector('span').textContent;
                files.push(fileName);
            });

            if (files.length === 1) {
                var file_name = files[0];
                downloadFile(file_name);
            } 
        });


        //Download par Text
        //Meme logique de downloadFile
        function downloadFile_text(file_name) {
            $.ajax({
                url: "download_file_text.php",
                method: "POST",
                data: {
                    file_name: file_name,
                    user_name: utilisateur
                },
                dataType: "json",
                success: function(data) {
                    var content = data.map(function(row) {
                        return row.FORM;
                    }).join(" ");

                    var blob = new Blob([content], { type: "text/plain;charset=utf-8" });
                    var url = URL.createObjectURL(blob);
                    var link = document.createElement("a");
                    link.href = url;
                    link.download = file_name;
                    document.body.appendChild(link);
                    link.click();
                    setTimeout(function() {
                        document.body.removeChild(link);
                        URL.revokeObjectURL(url);
                    }, 100);
                },
                error: function() {
                    alert("Echec");
                }
            });
        }
        document.getElementById("downloadTextBtn").addEventListener("click", function () {
            var checkedFiles = document.querySelectorAll('.file > i.checked');
            var files = [];

            checkedFiles.forEach(function (checkedFile) {
                var fileName = checkedFile.parentNode.querySelector('span').textContent;
                files.push(fileName);
            });

            if (files.length === 1) {
                var file_name = files[0];
                downloadFile_text(file_name);
            } 
        });
        /*#########################################################################################
        Cette fonction est destinée à supprimer des fichiers. Le principe de base consiste à trouver
         les données correspondantes dans les tables Information et Datas de SQL, puis à les supprimer. 
         Ensuite, la liste des fichiers est actualisée. 
         ##########################################################################################*/
        //Supprimer
        document.getElementById("deleteBtn").addEventListener("click", function () {
            //Avoir les noms de dossier
            var selectedFiles = document.querySelectorAll(".file > i.checked");
            var fileNames = [];
            selectedFiles.forEach(function(file) {
                fileNames.push(file.previousElementSibling.textContent);
            });

            $.ajax({
                url: "delete.php",
                method: "POST",
                data: {
                    files: JSON.stringify(fileNames),
                    Utilisateur: utilisateur 
                },
                success: function(response) {
                    alert("Le fichier a été supprimé avec succès.");
                    updateFileList();
                    checkSelectedFiles(); 

                },
                error: function() {
                    alert("La suppression du fichier a échoué.");
                }
            });
            checkSelectedFiles();
        });
        
        // Attribuer une couleur différente à chaque XPOS.
        function getColorByUPOS(upos) {
            switch (upos) {
                case "ADJ":
                    // return "red";
                    return "#FF0000";
                case "ADP":
                    // return "green";
                    return "#00FF00";
                case "ADV":
                    // return "blue";
                    return "#0000FF";
                case "AUX":
                    // return "yellow";
                    return "#FFFF00";
                case "CCONJ":
                    // return "purple";
                    return "#800080";
                case "DET":
                    // return "orange";
                    return "#FFA500";
                case "INTJ":
                    // return "Navy Blue";
                    return "#000080";
                case "NOUN":
                    // return "Light Gray";
                    return "#D3D3D3";
                case "NUM":
                    // return "Gray";
                    return "#808080";
                case "PART":
                    // return "Brown";
                    return "#A52A2A";
                case "PRON":
                    // return "Pink";
                    return "#FFC0CB";
                case "PROPN":
                    // return "Turquoise";
                    return "#40E0D0";
                case "PUNCT":
                    // return "Gold";
                    return "#FFD700";
                case "SCONJ":
                    // return "Silver";
                    return "#C0C0C0";
                case "SYM":
                    // return "SteelBlue";
                    return "#4682B4";
                case "VERB":
                    // return "Teal";
                    return "#008080";
                case "X":
                    // return "Maroon";
                    return "#800000";
                default:
                    // return "black";
                    return "#000000";
            }
        }

        /*#####################################################
        Afficher l'image de l'analyse syntaxique de dépendance.
        ######################################################*/
        function showDependencyGraph(file_name, data) {
            var newWindow = window.open("", "_blank");
            var newDocument = newWindow.document;

            // Ajouter du style.
            var style = newDocument.createElement('style');
            style.innerHTML = `
                .node rect {
                    stroke: #333;
                    fill: #fff;
                }
                .edgePath path {
                    stroke: #333;
                    fill: none;
                }
            `;
            newDocument.head.appendChild(style);

            // Créer un élément SVG
            var svg = newDocument.createElementNS("http://www.w3.org/2000/svg", "svg");
            newDocument.body.appendChild(svg);

            // Créer une disposition graphique
            var g = new dagreD3.graphlib.Graph().setGraph({});

            // Définir l'espacement entre les nœuds du graphique
            g.setGraph({
                nodesep: 50,
                ranksep: 100,
                rankdir: "LR",
                marginx: 10,
                marginy: 10
            });

            // Ajouter un nœud.
            for (var i = 0; i < data.length; i++) {
                var nodeLabel = data[i].ID + ": " + data[i].FORM;
                g.setNode(data[i].ID, { label: nodeLabel, labelStyle: "fill: #333;" });
            }

            // Ajouter une arête.
            for (var i = 0; i < data.length; i++) {
                if (data[i].HEAD !== "0") {
                    g.setEdge(data[i].HEAD, data[i].ID, { label: data[i].DEPREL, style: "stroke: #333;" });
                }
            }

            // Créer un rendu et afficher le graphique
            var render = new dagreD3.render();

            // Rechercher les éléments SVG dans le contexte de la nouvelle fenêtre.
            var svgGroup = d3.select(newWindow.document.querySelector("svg")).append("g");

            render(svgGroup, g);

            // Ajustement automatique de la taille.
            var width = g.graph().width + 40;
            var height = g.graph().height + 40;
            d3.select(newWindow.document.querySelector("svg")).attr("width", width).attr("height", height);

            // Définir le viewport pour que le contenu s'ajuste à la taille de la fenêtre.
            var xCenterOffset = (parseInt(newWindow.document.querySelector("svg").getAttribute("width")) - g.graph().width) / 2;
            svgGroup.attr("transform", "translate(" + xCenterOffset + ", 20)");
            newWindow.document.querySelector("svg").setAttribute("viewBox", "0 0 " + width + " " + height);
        }



        //Afficher le graphique de dépendance.
        function showDataInNewWindow_Graphe(file_name) {
            $.ajax({
                url: "open_relation.php",
                method: "GET",
                data: {
                    file_name: file_name
                },
                dataType: "json",
                success: function (response) {
                    showDependencyGraph(file_name, response);
                    
                },
                error: function () {
                    alert("Échec de la récupération des données.");
                }
            });
        }

        /*#################################################
        Ce code est destiné à afficher les données CoNLL sous forme de tableau.
        ################################################### */
        //Création d'une table (pour Open in Tableau).
        function createTableFromData(file_name, data) {
            var table = document.createElement("table");
            table.setAttribute("border", "1");
            table.style.borderCollapse = "collapse";
            table.style.width = "100%";

            // Création de l'en-tête de tableau
            var headerRow = document.createElement("tr");

            var headers = [
                "ID",
                "FORM",
                "LEMMA",
                "UPOS",
                "XPOS",
                "FEATS",
                "HEAD",
                "DEPREL",
                "DEPS",
                "MISC"
            ];

            headers.forEach(function (header) {
                var th = document.createElement("th");
                th.innerText = header;
                headerRow.appendChild(th);
            });

            table.appendChild(headerRow);

            // Ajout de lignes de données.
            data.forEach(function (row) {
                var tr = document.createElement("tr");

                headers.forEach(function (header) {
                    var td = document.createElement("td");
                    td.innerText = row[header];
                    tr.appendChild(td);
                });

                table.appendChild(tr);
            });

            return table;
        }
        //Afficher la table dans une nouvelle fenêtre.
        function showDataInNewWindow(file_name) {
            $.ajax({
                url: "open_table.php",
                method: "GET",
                data: { file_name: file_name },
                dataType: "json",
                success: function (response) {
                    if (response.error) {
                        alert(response.error);
                    } else {
                        // Créer une nouvelle fenêtre et afficher les résultats.
                        var newWindow = window.open("", "_blank");
                        var newDocument = newWindow.document;

                        // Définir le titre de la nouvelle page comme file_name.
                        newDocument.title = file_name;

                        // Ajouter du style.
                        var style = newDocument.createElement("style");
                        style.innerHTML = `
                            body {
                                font-family: Arial, sans-serif;
                            }
                            table {
                                width: 100%;
                                border-collapse: collapse;
                            }
                            th, td {
                                padding: 8px;
                                text-align: left;
                                border: 1px solid #ddd;
                            }
                            th {
                                background-color: #f2f2f2;
                                font-weight: bold;
                            }
                        `;
                        newDocument.head.appendChild(style);

                        //Créer un tableau à partir des données et l'ajouter au corps de la nouvelle fenêtre
                        var table = createTableFromData(file_name, response);
                        newDocument.body.appendChild(table);
                    }
                },
                error: function () {
                    alert("Échec de la récupération des données.");
                },
            });
        }

        /*#########################################################################
        Différentes méthodes implémentées pour les différents boutons de "Ouvrir".
        ############################################################################*/
        function dropdownAction(action) {
            //Obtenir le nom de fichier que on checked
            var selectedFiles = document.querySelectorAll(".file > i.checked");
                    var fileNames = [];
                    selectedFiles.forEach(function(file) {
                        fileNames.push(file.previousElementSibling.textContent);
                    });
              switch (action) {
                  case 1:
                    //Tableau
                       if (fileNames.length === 1) {
                        var file_name = fileNames[0];
                        showDataInNewWindow(file_name);
                    }                   
                      break;
                  case 2:
                    /*###############################################
                    Ce code est destiné à afficher les données CoNLL sous forme de texte. 
                    ########################################*/
                    if (fileNames.length === 1) {
                        var file_name = fileNames[0];
                            $.ajax({
                            url: "open_text.php",
                            method: "GET",
                            data: { file_name: file_name },
                            success: function (response) {

                            // Créer une nouvelle fenêtre et afficher le résultat
                                var newWindow = window.open("", "_blank");
                                var newDocument = newWindow.document;
                                newDocument.title = file_name;
                                // Ajouter du style pour permettre le retour automatique à la ligne du contenu
                                var style = newDocument.createElement('style');
                                style.innerHTML = `
                                    body {
                                        font-family: Arial, sans-serif;
                                    }
                                    pre {
                                        white-space: pre-wrap;
                                        word-wrap: break-word;
                                    }
                                `;
                                newDocument.head.appendChild(style);

                                newDocument.body.innerHTML = "<pre>" + response + "</pre>";
                            },
                            error: function () {
                                alert("Échec de récupération des données LEMMA.");
                            }
                        });
                    }

                      break;
                  case 3:
                    /*###############################################
                    Ce code est destiné à afficher les données CoNLL sous forme de texte avec coleur. 
                    ########################################*/
                      if (fileNames.length === 1) {
                        var file_name = fileNames[0];
                        $.ajax({
                            url: "open_text_color.php",
                            method: "GET",
                            dataType: "json",
                            data: { file_name: file_name },
                            success: function (response) {
                                // Créer une nouvelle fenêtre et afficher le résultat
                                var newWindow = window.open("", "_blank");
                                var newDocument = newWindow.document;
                                newDocument.title = file_name;
                                // Ajouter du style pour permettre le retour automatique à la ligne du contenu
                                var style = newDocument.createElement('style');
                                style.innerHTML = `
                                    body {
                                        font-family: Arial, sans-serif;
                                    }
                                    pre {
                                        white-space: pre-wrap;
                                        word-wrap: break-word;
                                    }
                                `;
                                newDocument.head.appendChild(style);

                                var content = "";
                                response.forEach(function (item) {
                                    content += `<span style="color:${getColorByUPOS(item.UPOS)}">${item.FORM}</span> `;
                                });

                                newDocument.body.innerHTML = "<pre>" + content + "</pre>";
                            },
                            error: function () {
                                alert("Échec de récupération des données LEMMA.");
                            }
                        });
                    }
                      break;
                  case 4:
                    //graphique des dépendances
                      if (fileNames.length === 1) {
                        var file_name = fileNames[0];
                        showDataInNewWindow_Graphe(file_name);
                    }
                      break;
              }
          }



       /*##################################################################################################
       Ce code permet d'obtenir les résultats de la recherche. 
       Le principe de base est de trouver les noms de fichiers correspondant aux données appropriées en fonction 
       du type de recherche et du mot-clé de recherche choisis par l'utilisateur, puis de faire en sorte que la fenêtre du cloud affiche ces fichiers. 
       ##################################################################################################*/
        function searchTitles() {
            var searchOption = $("#searchOption").val();
            var searchValue = $("#searchInput").val();
            $.ajax({
                url: "search_titles.php",
                method: "POST",
                data: {
                    searchOption: searchOption,
                    searchValue: searchValue,
                    Utilisateur: utilisateur
                },
                dataType: "json",
                success: function(response) {
                    console.log("查询语句: ", response.query);
                    console.log("搜索值: ", response.searchValue);
                    console.log("Utilisateur: ", response.Utilisateur);
                    console.log("查询结果: ", response.titles);
                    updateFileWindow(response.titles);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert("搜索失败, 错误原因: " + textStatus + ": " + errorThrown);
                }
            });
        }

          //Mise en place d'une barre de recherche.
          $(document).ready(function() {
            // Afficher le bouton effacer lorsque la barre de recherche contient du texte.
            $("#searchInput").on("input", function() {
                if ($(this).val()) {
                    $("#clearBtn").show();
                } else {
                    $("#clearBtn").hide();
                }
            });

            // Effacer le contenu de la barre de recherche lorsqu'on clique sur le bouton effacer.
            $("#clearBtn").on("click", function() {
                $("#searchInput").val("");
                $(this).hide();
                updateFileList();
            });

            //Effectuer la recherche en cliquant sur le bouton de recherche.
            $("#searchBtn").on("click", function() {
                var searchOption = $("#searchOption").val();
                var searchInput = $("#searchInput").val();
                searchTitles();
            });
        });







        /*#######################################
         Classer les fichiers par année
         ######################################## */
        function displayFilesByYear() {
            $.ajax({
                url: "get_files_by_year.php",
                method: "POST",
                data: { Utilisateur: utilisateur },
                dataType: "json",
                success: function(data) {
                    $("#box").empty();

                    data.forEach(function(yearGroup) {
                        var yearElement = document.createElement("div");
                        yearElement.className = "year";
                        yearElement.innerText = yearGroup.year;
                        $("#box").append(yearElement);

                        var fileGroup = document.createElement("div");
                        fileGroup.style.display = "flex";
                        fileGroup.style.flexWrap = "wrap";
                        $("#box").append(fileGroup);

                        yearGroup.files.forEach(function(file) {
                            var fileElement = document.createElement("div");
                            fileElement.className = "file";
                            fileElement.innerHTML = '<img src="img/dossier.png"><span>' + file + '</span><i class=""></i>';
                            fileGroup.appendChild(fileElement);
                        });
                    });
                },
                error: function() {
                    alert("Echec");
                }
            });
        }



        function displayFilesByYearDesc(){
            $.ajax({
                url: "get_files_by_year_Desc.php",
                method: "POST",
                data: { Utilisateur: utilisateur },
                dataType: "json",
                success: function(data) {
                    $("#box").empty();

                    data.forEach(function(yearGroup) {
                        var yearElement = document.createElement("div");
                        yearElement.className = "year";
                        yearElement.innerText = yearGroup.year;
                        $("#box").append(yearElement);

                        var fileGroup = document.createElement("div");
                        fileGroup.style.display = "flex";
                        fileGroup.style.flexWrap = "wrap";
                        $("#box").append(fileGroup);

                        yearGroup.files.forEach(function(file) {
                            var fileElement = document.createElement("div");
                            fileElement.className = "file";
                            fileElement.innerHTML = '<img src="img/dossier.png"><span>' + file + '</span><i class=""></i>';
                            fileGroup.appendChild(fileElement);
                        });
                    });
                },
                error: function() {
                    alert("echec");
                }
            });
        }




        /*#######################################
         Classer les fichiers par Langue
         ######################################## */
        function displayFilesByLangue(){
            $.ajax({
                url: "get_files_by_langue.php",
                method: "POST",
                data: { Utilisateur: utilisateur },
                dataType: "json",
                success: function(data) {
                    $("#box").empty();

                    data.forEach(function(yearGroup) {
                        var yearElement = document.createElement("div");
                        yearElement.className = "year";
                        yearElement.innerText = yearGroup.year;
                        $("#box").append(yearElement);

                        var fileGroup = document.createElement("div");
                        fileGroup.style.display = "flex";
                        fileGroup.style.flexWrap = "wrap";
                        $("#box").append(fileGroup);

                        yearGroup.files.forEach(function(file) {
                            var fileElement = document.createElement("div");
                            fileElement.className = "file";
                            fileElement.innerHTML = '<img src="img/dossier.png"><span>' + file + '</span><i class=""></i>';
                            fileGroup.appendChild(fileElement);
                        });
                    });
                },
                error: function() {
                    alert("echec");
                }
            });
        }

        /*#######################################
         Classer les fichiers par annotation disponible
         ######################################## */
        function displayFilesByConll() {
            $.ajax({
                url: "get_files_by_conll.php",
                method: "POST",
                data: { Utilisateur: utilisateur },
                dataType: "json",
                success: function(data) {
                    $("#box").empty();

                    data.forEach(function(conllGroup) {
                        var conllElement = document.createElement("div");
                        conllElement.className = "conll";
                        conllElement.innerText = conllGroup.conll;
                        $("#box").append(conllElement);

                        var filesContainer = document.createElement("div");
                        filesContainer.className = "files-container";

                        conllGroup.files.forEach(function(file) {
                            var fileElement = document.createElement("div");
                            fileElement.className = "file";
                            fileElement.innerHTML = '<img src="img/dossier.png"><span>' + file + '</span><i class=""></i>';
                            filesContainer.append(fileElement);
                        });

                        $("#box").append(filesContainer);
                    });
                },
                error: function() {
                    alert("获取文件失败");
                }
            });
        }

        // Fonction déclenchée par les différentes sélections de la bouton "Catégorie"
        function dropdownActionPourCate(action) {
            
              switch (action) {
                  case 1:
                    displayFilesByYear();         
                      break;
                  case 2:
                    displayFilesByYearDesc();
                      break;
                  case 3:
                   displayFilesByLangue();
                      break;
                  case 4:
                    displayFilesByConll();
                      break;
                case 5:
                    updateFileList();
                    break;
              }
          }

        /*####################################
        Pour avoir information dans SQL
        #####################################*/
        function showFileInfo(file) {
            $.ajax({
                url: "get_file_info.php",
                method: "POST",
                data: {
                    Titre: file,
                    Utilisateur: utilisateur
                },
                dataType: "json",
                success: function (data) {
                    $("#fileAnnee").text(data.Annee);
                    $("#fileLangage").text(data.Langage);
                    var conllNames = ['ID', 'FORM', 'LEMMA', 'UPOS', 'XPOS', 'FEATS', 'HEAD', 'DEPREL', 'DEPS', 'MISC'];
                    var conllInfoList = $("#fileConllInfo");
                    conllInfoList.empty();
                    for (var i = 0; i < data.ConllInfo.length; i++) {
                        if (data.ConllInfo[i] == 1) {
                            conllInfoList.append('<li>' + conllNames[i] + '</li>');
                        }
                    }

                    $('#fileInfoModal').modal('show');
                },
                error: function () {
                    alert("获取文件信息失败");
                }
            });
        }

        document.getElementById("infBtn").addEventListener("click", function () {
            var selectedFiles = document.querySelectorAll(".file > i.checked");
            var fileNames = [];
            selectedFiles.forEach(function(file) {
                fileNames.push(file.previousElementSibling.textContent);
            });
            if (fileNames.length === 1) {
                var file_name = fileNames[0];
                showFileInfo(file_name);
            }           
        });
    </script>
    </body>
</html>