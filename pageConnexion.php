<?php
// Démarrer une session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['e_mail'])) {
    // Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
    header('Location: pageConnexion.php');
    exit;
}





// Récupérer toutes les informations de l'utilisateur
$email = $_SESSION['e_mail'];
$nom_utilisateur= $_SESSION ['nom_utilisateur'];
$objectif= $_SESSION ['objectif'];


?>




<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revease - Organisation des Révisions</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background: linear-gradient(to bottom, #e92d15, #fa8c64);
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .title {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            color: white;
            margin: 10px 0;
        }

        .container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin: 20px;
        }

        .top-container, .middle-container, .bottom-container {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .box {
            background-color: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            flex: 1;
            min-width: 300px;
        }

        .profile-container h3 {
            margin: 0;
            font-size: 20px;
        }

        #profile-info {
            display: none;
            margin-top: 10px;
        }

        .form-container input, .event-container input, .form-container button, .event-container button {
            margin: 5px 0;
            padding: 8px;
            width: 95%;
        }

        .chart-container {
            height: 350px;
            flex: 2;
            min-width: 600px; /* Augmenter la largeur minimale */
            overflow-x: auto; /* Ajouter un défilement horizontal */
        }

        canvas {
            width: 100% !important;
            height: 80% !important;
        }

        .notes-container, .events-container {
            flex: 1;
            min-height: 100px;
            max-height: 150px;
            overflow-y: auto;
            background-color: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .delete-btn, .edit-btn {
            background-color: red;
            color: white;
            padding: 5px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            margin-left: 5px;
        }

        .edit-btn {
            background-color: blue;
        }

        .form-container h4, .event-container h4 {
            margin-bottom: 10px;
        }

        .select-container {
            display: flex;
            flex-direction: column;
            margin-bottom: 10px;
        }

        /* Modifier les boutons pour qu'ils soient verts */
        button {
            background-color: green; /* couleur de fond verte */
            color: white; /* texte en blanc */
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: darkgreen; /* couleur de fond au survol */
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }

        .flex-container {
            display: flex;
            gap: 20px;
            justify-content: space-between;
            width: 100%;
        }

        .flex-container .box {
            flex: 1;
            min-width: 300px;
        }

        @media (max-width: 768px) {
            .top-container, .middle-container, .bottom-container {
                flex-direction: column;
                align-items: center;
            }

            .profile-container, .form-container, .event-container {
                max-width: 100%;
            }

            .chart-container {
                height: 250px;
            }

            .flex-container {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <div class="title">Revease</div>
    <div class="container">
        <div class="top-container">
            <div class="box profile-container">
                <h3 onclick="toggleProfile()">Mon Profil <span id="profile-toggle">+</span></h3>
                <div id="profile-info">
                    <p><strong>Nom d'utilisateur : </strong> <?php echo $nom_utilisateur ?></p>
                    <p><strong>Email :</strong> <?php echo $email ?></p>
                    <p><strong>Objectif :</strong> <?php echo $objectif ?></p>
                    <p><strong>Filière :</strong>
                        <select id="filiere-select" onchange="updateMatieres()">
                            <option value="">-- Sélectionner une filière --</option>
                            <option value="miashs">MIASHS</option>
                            <option value="psycho">Psycho</option>
                            <option value="histoireArt">Histoire de l'art et archéologie</option>
                            <option value="philosophie">Philosophie</option>
                            <option value="sciencesEducation">Sciences de l'éducation</option>
                            <option value="sciencesHumainesAppliquees">Sciences humaines appliquées</option>
                            <option value="sociologie">Sociologie</option>
                        </select>
                    </p>
                    <p><strong>Année :</strong>
                        <select id="annee-select" onchange="updateMatieres()">
                            <option value="">-- Sélectionner une année --</option>
                            <option value="L1">L1</option>
                            <option value="L2">L2</option>
                            <option value="L3">L3</option>
                        </select>
                    </p>
                    <p><strong><a href="http://51.83.79.220/pageConnexion.php">Se déconnecter</a></strong></p>
                    <button onclick="editProfile()">Modifier le Profil</button>
                </div>
                <div id="profile-edit-form" style="display: none;">
                    <label for="edit-name">Nom d'utilisateur :</label>
                    <input id="edit-name" type="text" value="Étudiant"><br>
                    
                   <!--  <label for="edit-email">Email :</label>
                    <input id="edit-email" type="email" value="etudiant@example.com"><br> -->
                    <label for="edit-objectif">Objectif :</label>
                    <input id="edit-objectif" type="text" value=<?php echo $objectif?>><br>
                    <button onclick="saveProfile()">Enregistrer</button>
                </div>
            </div>

            <div class="box form-container">
                <h3>Ajouter une session de révision</h3>
                <div>
                    <h4>Matière :</h4>
                    <div id="matiere-session-container" class="select-container"></div>
                </div>
                <div>
                    <h4>Méthode :</h4>
                    <div id="methode-session-container" class="select-container"></div>
                </div>
                <label for="duree">Durée (en heures) :</label>
                <input id="duree" type="number" placeholder="Ex : 2"><br>
                <label for="date">Date :</label>
                <input id="date" type="date"><br>
                <label for="comment">Commentaire (facultatif) :</label>
                <input id="comment" type="text" placeholder="Ajouter un commentaire"><br>
                <button id="ajouter-session">Ajouter la session</button>
                <div id="session-error" class="error-message"></div>
            </div>

            <div class="box event-container">
                <h3>Ajouter une note</h3>
                <div>
                    <h4>Matière :</h4>
                    <div id="note-matiere-container" class="select-container"></div>
                </div>
                <div>
                    <h4>Méthode :</h4>
                    <div id="note-methode-container" class="select-container"></div>
                </div>
                <label for="note-value">Note :</label>
                <input id="note-value" type="number" placeholder="Ex : 15" min="0" max="20"><br>
                <label for="comment">Commentaire (facultatif) :</label>
                <input id="comment" type="text" placeholder="Ajouter un commentaire"><br>
                <button id="add-note">Ajouter la note</button>
                <div id="note-error" class="error-message"></div>
            </div>
        </div>

        <div class="box todo-container">
            <h3>ToDo List des matières à réviser</h3>
            <div id="todo-list"></div>
            <h3>Ajouter une matière à réviser</h3>
            <input id="todo-input" type="text" placeholder="Nom du cours"><br>
            <button id="add-todo">Ajouter à la ToDo List</button>
        </div>

        <div class="middle-container">
            <div class="box notes-container">
                <h3>Notes et Résultats</h3>
                <div id="results-list"></div>
            </div>

            <div class="box events-container">
                <h3>Événements à venir</h3>
                <div id="events-list"></div>

                <h3>Ajouter un événement</h3>
                <label for="event-name">Nom de l'événement :</label>
                <input id="event-name" type="text" placeholder="Ex : Réunion révisions"><br>
                <label for="event-date">Date de l'événement :</label>
                <input id="event-date" type="date"><br>
                <button id="add-event">Ajouter l'événement</button>
            </div>
        </div>

        <div class="bottom-container">
            <div class="flex-container">
                <div class="box sessions">
                    <h3>Dernières Sessions de révisions</h3>
                    <div id="sessions-container"></div>
                </div>

                <div class="box recap-container">
                    <h3>Récapitulatif des Heures de Révision par Matière et Méthode</h3>
                    <div id="recap-container"></div>
                </div>
            </div>

            <div class="box chart-container">
                <h3>Graphique des Moyennes des Notes par Matière et Méthode</h3>
                <canvas id="myChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        let notes = [];
        let sessions = [];
        let myChart;

        // Fonction pour basculer l'affichage des informations du profil
        function toggleProfile() {
            const profileInfo = document.getElementById('profile-info');
            const toggleButton = document.getElementById('profile-toggle');
            if (profileInfo.style.display === 'none' || profileInfo.style.display === '') {
                profileInfo.style.display = 'block';
                toggleButton.textContent = '−';
            } else {
                profileInfo.style.display = 'none';
                toggleButton.textContent = '+';
            }
        }

        // Fonction pour afficher le formulaire d'édition du profil
        function editProfile() {
            document.getElementById('profile-info').style.display = 'none';
            document.getElementById('profile-edit-form').style.display = 'block';
        }
        // Fonction pour enregistrer les modifications du profil
        function saveProfile() {
            const name = document.getElementById('edit-name').value;
            
            // const email = document.getElementById('edit-email').value;
            const objectif = document.getElementById('edit-objectif').value;

            <?php // mettre a jour info dans table utilisateur
            // Inclure votre fichier de connexion à la base de données
            include('connexion.php');

            // Vérifier si le formulaire est soumis
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Récupérer les données depuis le formulaire
                $nouveau_nom_utilisateur = htmlspecialchars($_POST['name']);
                $nouvel_objectif = htmlspecialchars($_POST['objectif']);
                

                // Préparer et exécuter la requête pour mettre à jour les données
                $stmt = $conn->prepare("UPDATE utilisateurs SET nom_utilisateur = ?, objectif = ? WHERE e_mail = ?");
                $stmt->bind_param("sss", $nouveau_nom_utilisateur, $nouvel_objectif, $email);

                if ($stmt->execute()) {
                    // Mettre à jour la session pour refléter les nouvelles données
                    $_SESSION['nom_utilisateur'] = $nouveau_nom_utilisateur;
                    $_SESSION['objectif'] = $nouvel_objectif;
                    echo "Mise à jour réussie !";
                } else {
                    echo "Erreur lors de la mise à jour : " . $stmt->error;
                }

                $stmt->close();
                $conn->close();
            }
            ?>

            document.getElementById('profile-info').innerHTML =
                `<p><strong>Nom d'utilisateur :</strong> ${name}</p>
                
                 
                <p><strong>Objectif :</strong> ${objectif}</p>
                <p><strong>Filière :</strong>
                    <select id="filiere-select" onchange="updateMatieres()">
                        <option value="">-- Sélectionner une filière --</option>
                        <option value="miashs">MIASHS</option>
                        <option value="psycho">Psycho</option>
                        <option value="histoireArt">Histoire de l'art et archéologie</option>
                        <option value="philosophie">Philosophie</option>
                        <option value="sciencesEducation">Sciences de l'éducation</option>
                        <option value="sciencesHumainesAppliquees">Sciences humaines appliquées</option>
                        <option value="sociologie">Sociologie</option>
                    </select>
                </p>
                <p><strong>Année :</strong>
                    <select id="annee-select" onchange="updateMatieres()">
                        <option value="">-- Sélectionner une année --</option>
                        <option value="L1">L1</option>
                        <option value="L2">L2</option>
                        <option value="L3">L3</option>
                    </select>
                </p>
                <p><strong><a href="http://51.83.79.220/pageConnexion.php">Se déconnecter</a></strong></p>
                <button onclick="editProfile()">Modifier le Profil</button>`;
            document.getElementById('profile-edit-form').style.display = 'none';
            document.getElementById('profile-info').style.display = 'block';
        }
//matiere miashs
        const matieresMIASHS = {
            L1: [
                "Algèbre linéaire 1", "Analyse réelle 1", "Introduction à la statistique", "Algèbre linéaire 2", "Analyse réelle 2", "Probabilités 1",
                "Initiation à l'informatique et à l'algorithmique", "Programmation fonctionnelle",
                "Introduction aux sciences cognitives", "Cognition : du neurone à la pensée", "Langage et cognition",
                "Introduction aux sciences économiques", "Microéconomie 1", "Macroéconomie 1",
                "Anglais 1", "Anglais 2", "Méthodologie du travail universitaire"
            ],
            L2: [
                "Algèbre linéaire 3", "Analyse réelle 3", "Probabilités 2", "Statistique mathématique 1", "Mathématiques pour l'informatique", "Algorithmique et programmation par objets", "Introduction aux bases de données", "Langages formels et calculabilité", "Programmation logique","Cognition : invariants et différences", "Cognition : perception et motricité", "Cognition et développement", "Cognition et ergonomie", "Cognition : mémoire(s) et représentations", "Langage et cerveau",  "Microéconomie 2", "Macroéconomie 2", "Microéconomie 3", "Macroéconomie 3", "Anglais 3", "Anglais 4", "Projet 3"
            ],
            L3: [
                "Statistique mathématique 2", "Programmation objet avancée et structure de données", "Statistique mathématique 3","Compléments de mathématiques 1",  "Compléments de mathématiques 2", "économie A5 : choix matieres 1", "Initiation à l'intelligence artificielle","Réseaux", "Systèmes", "Introduction aux technologies du web", "économie A5 : choix matieres 2", "Cognition et apprentissage(s)", "Cognition distribuée", "Cognition ou intelligence(s) : l'intégration", "Modélisation des fonctions langagières","Econométrie 1", "Econométrie 2", "Economie des contrats et des relations verticales","Anglais 5", "Anglais 6", "Projet 5", "Stage 5"
            ]
        };
// matiere psycho
        const matieresPsycho = {
            L1: [
                "Histoire de la psychologie et ses grands thèmes", "Introduction générales + champs disciplinaires",
                "Introduction aux sciences de l'éducation", "Méthodes en SHS", "Méthodes de travail universitaire (MTU)", "Traitement de données 1",
                "Anglais 1", "Initiation à l'informatique 1 (accès régulé)", "UEO", "ETC", "Psychologie clinique 1", "Psychologie cognitive 1",
                "Psychologie sociale 1", "Bases biologiques en psychologie", "Psychologie de l'adolescent",
                "Psychologie de spécialisation clinique", "Questions actuelles en psychologie cognitive", "Sde : Communication, travail et formation professionnelle",
                "Sde : théorie et modèle de l'apprentissage", "Traitement de données 2", "Techniques de compréhension écrite de l'anglais",
                "Initiation à l'informatique 1", "Enseignement d'ouverture", "ETC"
            ],
            L2: [
                "Psychopathologie de l'enfant et de l'adolescent", "Introduction à la psychopathologie de l'adulte", "Psychologie sociale 2",
                "Psychologie du travail 1", "Psychologie cognitive 2", "Psychologie du développement 1", "Évolution et comportement", "Éthologie et chronobiologie 1", "Traitement de données 2",
                "Techniques de compréhension écrite de l'anglais 2", "Initiation à l'informatique 2 (accès régulé)", "Projet individuel de formation",
                "UEO", "ETC", "Introduction à la clinique de la santé", "Méthodes cliniques", "Psychologie sociale 3", "Psychologie différentielle et psychométrie",
                "Neurosciences fondamentales", "Neuroanatomie fonctionnelle", "Analyse et interprétation des données expérimentales",
                "Anglais pour psychologues 2", "Psychologie clinique de spécialisation 1", "Psychologie clinique de spécialisation 2",
                "Psychologie évolutionniste", "Psychologie cognitive de spécialisation", "Psychologie du travail de spécialisation"
            ],
            L3: [
                "Psychopathologie", "Psychologie sociale 4", "Psychologie des organisations",
                "Psychologie cognitive 3 : perception, action et catégorisation", "Pratique des tests cognitifs et neuropsychologiques", "Neurophysiologie sensorielle et motrice",
                "Traitement de données 3", "Anglais pour psychologues 2", "Projet individuel de formation",
                "UEO", "Stage", "ETC", "Evaluation clinique et entretien", "Introduction aux prises en charge", "Psychologie sociale 5",
                "Psychologie cognitive 4 : mémoire et langage", "Pratique des tests cognitifs et neuropsychologiques", "Neurosciences cognitives",
                "Neuropsychologie clinique", "Introduction à la recherche & analyse des données", "Psychologie du développement et des apprentissages",
                "Psychologie clinique du vieillissement", "Ethologie et chronobiologie 2", "Psychologie clinique de la santé",  "Psychologie sociale"
            ]
        };
//matiere histoire de l’art
        const matieresHistoireArt = {
            L1: [
                "Antiquité", "Moyen Âge", "Temps modernes", "Époque contemporaine","Introduction à l'archéologie", "Méthodologie de l'analyse d'œuvres", "Langues vivantes"
            ],
            L2: [
                "Approfondissement des périodes artistiques", "Iconographie", "Techniques artistiques",
                "Archéologie des périodes antique et médiévale", "Langues vivantes", "Méthodologie de la recherche"
            ],
            L3: [
                "Patrimoine", "Muséologie", "Critique d'art", "Séminaires de recherche",
                "Stage obligatoire (70h minimum)", "Préparation au mémoire", "Langues vivantes"
            ]
        };
//matieres philo
        const matieresPhilosophie = {
            L1: [
                "Histoire de la philosophie", "Logique", "Éthique","Méthodologie du travail universitaire", "Langues vivantes"
            ],
            L2: [
                "Approfondissement des courants philosophiques", "Philosophie contemporaine",
                "Épistémologie", "Langues vivantes", "Méthodologie de la dissertation et du commentaire de texte"
            ],
            L3: [
                "Philosophie politique", "Esthétique", "Métaphysique", "Séminaires de recherche", "Préparation au mémoire", "Langues vivantes"
            ]
        };
//matiere sciences de l’education
        const matieresSciencesEducation = {
            L1: [
                "Enseignements de culture générale et d'approfondissement disciplinaire (dispensés en lycée)",
                "Introduction aux sciences de l'éducation", "Stages d'observation en école primaire"
            ],
            L2: [
                "Poursuite des enseignements de spécialisation (dispensés à l'université)",
                "Approfondissement des sciences de l'éducation", "Stages de pratique accompagnée en école primaire"
            ],
            L3: [
                "Enseignements adossés à la recherche", "Stage de mobilité internationale",
                "Préparation aux concours de l'enseignement"
            ]
        };
//matieres sciences humaines appliquées
        const matieresSciencesHumainesAppliquees = {
            L1: [
                "Géographie", "Histoire", "Philosophie","Méthodologie du travail universitaire", "Langues vivantes"
            ],
            L2: [
                "Introduction au droit et à l'économie", "Choix d'enseignements optionnels en fonction du projet professionnel","Langues vivantes"
            ],
            L3: [ "Thème central 'Société et travail'", "Stage obligatoire","Séminaires de spécialisation", "Préparation au mémoire", "Langues vivantes"
            ]
        };
//matieres sociologie
        const matieresSociologie = {
            L1: [
                "Introduction à la sociologie", "Méthodologie des sciences sociales", "Initiation aux enquêtes de terrain", "Langues vivantes"
            ],
            L2: [
                "Théories sociologiques contemporaines", "Statistiques appliquées aux sciences sociales",
                "Sociologie thématique : Famille, Travail, Éducation", "Langues vivantes"
            ],
            L3: [
                "Approfondissement des méthodes de recherche", "Séminaires spécialisés : Sociologie urbaine, Sociologie des migrations, Sociologie de la culture","Stage obligatoire"
            ]
        };


        // Fonction pour mettre à jour les matières en fonction de la filière et de l'année sélectionnées
        function updateMatieres() {
            const filiere = document.getElementById('filiere-select').value;
            const annee = document.getElementById('annee-select').value;
            let matieres;

            switch (filiere) {
                case 'miashs':
                    matieres = matieresMIASHS[annee];
                    break;
                case 'psycho':
                    matieres = matieresPsycho[annee];
                    break;
                case 'histoireArt':
                    matieres = matieresHistoireArt[annee];
                    break;
                case 'philosophie':
                    matieres = matieresPhilosophie[annee];
                    break;
                case 'sciencesEducation':
                    matieres = matieresSciencesEducation[annee];
                    break;
                case 'sciencesHumainesAppliquees':
                    matieres = matieresSciencesHumainesAppliquees[annee];
                    break;
                case 'sociologie':
                    matieres = matieresSociologie[annee];
                    break;
                default:
                    matieres = [];
            }

            updateSelect('matiere-session-select', matieres);
            updateSelect('matiere-note-select', matieres);
        }

        // Fonction pour mettre à jour les options de sélection des matières
        function updateSelect(selectId, matieres) {
            const select = document.getElementById(selectId);
            select.innerHTML = '<option value="">-- Sélectionner une matière --</option>';
            matieres.forEach(matiere => {
                const option = document.createElement('option');
                option.value = matiere;
                option.textContent = matiere;
                select.appendChild(option);
            });
        }

        // Classe pour gérer les méthodes de révision
        class MethodeManager {
            constructor() {
                this.methodes = [
                    "Fiche de révision",
                    "Lecture du cours",
                    "Exercices",
                    "Doodling",
                    "Pratique",
                    "Projet",
                    "Par cœur"
                ];
            }

            // Générer le HTML pour le sélecteur de méthodes
            generateSelectHTML(selectId = "methode-select") {
                let html = `<select id="${selectId}"><option value="">-- Sélectionner une méthode --</option>`;
                this.methodes.forEach(methode => {
                    html += `<option value="${methode}">${methode}</option>`;
                });
                html += `</select>`;
                return html;
            }

            // Obtenir la liste des méthodes
            getMethodes() {
                return this.methodes;
            }
        }

        const methodeManager = new MethodeManager();

        // Initialiser les sélecteurs de matières et de méthodes
        document.getElementById("matiere-session-container").innerHTML = `<select id="matiere-session-select"><option value="">-- Sélectionner une matière --</option></select>`;
        document.getElementById("methode-session-container").innerHTML = methodeManager.generateSelectHTML("methode-session-select");
        document.getElementById("note-matiere-container").innerHTML = `<select id="matiere-note-select"><option value="">-- Sélectionner une matière --</option></select>`;
        document.getElementById("note-methode-container").innerHTML = methodeManager.generateSelectHTML("methode-note-select");

        // Ajouter une session de révision
        document.getElementById("ajouter-session").addEventListener("click", () => {
            const matiere = document.getElementById("matiere-session-select").value;
            const methode = document.getElementById("methode-session-select").value;
            const duree = document.getElementById("duree").value;
            const date = document.getElementById("date").value;
            const comment = document.getElementById("comment").value;
            const errorMessage = document.getElementById("session-error");

            if (!matiere || !methode || !duree || !date) {
                errorMessage.textContent = "Veuillez remplir tous les champs.";
                return;
            }

            errorMessage.textContent = "";

            const sessionItem = document.createElement("div");
            sessionItem.setAttribute("data-matiere", matiere);
            sessionItem.setAttribute("data-methode", methode);
            sessionItem.setAttribute("data-duree", duree);
            sessionItem.setAttribute("data-date", date);
            sessionItem.setAttribute("data-comment", comment);
            sessionItem.innerHTML = `<strong>Matière:</strong> ${matiere}, <strong>Méthode:</strong> ${methode}, <strong>Durée:</strong> ${duree}h, <strong>Date:</strong> ${date}, <strong>Commentaire:</strong> ${comment} <button class="delete-btn" onclick="deleteItem(this)">Supprimer</button> <button class="edit-btn" onclick="editSession(this)">Éditer</button>`;
            document.getElementById("sessions-container").appendChild(sessionItem);

            sessions.push({ matiere, methode, duree, date, comment });

            updateRecap(); // Mettre à jour le récapitulatif
        });

        // Ajouter une note
        document.getElementById("add-note").addEventListener("click", () => {
            const matiere = document.getElementById("matiere-note-select").value;
            const methode = document.getElementById("methode-note-select").value;
            const note = document.getElementById("note-value").value;
            const comment = document.getElementById("comment").value;
            const errorMessage = document.getElementById("note-error");

            if (!matiere || !methode || !note) {
                errorMessage.textContent = "Veuillez remplir tous les champs.";
                return;
            }

            if (note < 0 || note > 20) {
                errorMessage.textContent = "La note doit être comprise entre 0 et 20.";
                return;
            }

            errorMessage.textContent = "";

            const noteItem = document.createElement("div");
            noteItem.setAttribute("data-matiere", matiere);
            noteItem.setAttribute("data-methode", methode);
            noteItem.setAttribute("data-note", note);
            noteItem.setAttribute("data-comment", comment);
            noteItem.innerHTML = `<strong>Matière:</strong> ${matiere}, <strong>Méthode:</strong> ${methode}, <strong>Note:</strong> ${note}, <strong>Commentaire:</strong> ${comment} <button class="delete-btn" onclick="deleteItem(this)">Supprimer</button> <button class="edit-btn" onclick="editNote(this)">Éditer</button>`;
            document.getElementById("results-list").appendChild(noteItem);

            notes.push({ matiere, methode, note: parseFloat(note), comment });

            updateChart();
        });

        // Ajouter un événement
        document.getElementById("add-event").addEventListener("click", () => {
            const eventName = document.getElementById("event-name").value;
            const eventDate = document.getElementById("event-date").value;

            if (eventName && eventDate) {
                const eventItem = document.createElement("div");
                eventItem.innerHTML = `<strong>Événement:</strong> ${eventName}, <strong>Date:</strong> ${eventDate} <button class="delete-btn" onclick="deleteItem(this)">Supprimer</button> <button class="edit-btn" onclick="editEvent(this)">Éditer</button>`;
                document.getElementById("events-list").appendChild(eventItem);

                // Planifier une notification
                const eventTime = new Date(eventDate).getTime();
                const now = new Date().getTime();
                const timeUntilEvent = eventTime - now;

                if (timeUntilEvent > 0) {
                    setTimeout(() => {
                        showNotification("Rappel d'événement", `L'événement "${eventName}" est prévu pour aujourd'hui.`);
                    }, timeUntilEvent);
                }
            }
        });

        // Ajouter une matière à la ToDo List
        document.getElementById("add-todo").addEventListener("click", () => {
            const todoInput = document.getElementById("todo-input").value;
            if (todoInput.trim() !== "") {
                const todoList = document.getElementById("todo-list");
                const todoItem = document.createElement("div");
                todoItem.innerHTML = `${todoInput} <button class="delete-btn" onclick="deleteItem(this)">Supprimer</button> <button class="edit-btn" onclick="editTodo(this)">Éditer</button>`;
                todoList.appendChild(todoItem);
                document.getElementById("todo-input").value = "";
            }
        });

        // Supprimer un élément
        function deleteItem(button) {
            const item = button.parentElement;

            const matiere = item.getAttribute("data-matiere");
            const methode = item.getAttribute("data-methode");
            const note = parseFloat(item.getAttribute("data-note"));
            const duree = item.getAttribute("data-duree");
            const date = item.getAttribute("data-date");

            item.remove();

            if (note !== null) {
                notes = notes.filter(n => !(n.matiere === matiere && n.methode === methode && n.note === note));
            } else {
                sessions = sessions.filter(s => !(s.matiere === matiere && s.methode === methode && s.duree === duree && s.date === date));
            }

            updateChart();
            updateRecap(); // Mettre à jour le récapitulatif
        }

        // Éditer une session de révision
        function editSession(button) {
            const item = button.parentElement;

            const matiere = item.getAttribute("data-matiere");
            const methode = item.getAttribute("data-methode");
            const duree = item.getAttribute("data-duree");
            const date = item.getAttribute("data-date");
            const comment = item.getAttribute("data-comment");

            document.getElementById("matiere-session-select").value = matiere;
            document.getElementById("methode-session-select").value = methode;
            document.getElementById("duree").value = duree;
            document.getElementById("date").value = date;
            document.getElementById("comment").value = comment;

            item.remove();
            sessions = sessions.filter(s => !(s.matiere === matiere && s.methode === methode && s.duree === duree && s.date === date && s.comment === comment));

            updateRecap(); // Mettre à jour le récapitulatif
        }

        // Éditer une note
        function editNote(button) {
            const item = button.parentElement;

            const matiere = item.getAttribute("data-matiere");
            const methode = item.getAttribute("data-methode");
            const note = item.getAttribute("data-note");
            const comment = item.getAttribute("data-comment");

            document.getElementById("matiere-note-select").value = matiere;
            document.getElementById("methode-note-select").value = methode;
            document.getElementById("note-value").value = note;
            document.getElementById("comment").value = comment;

            item.remove();
            notes = notes.filter(n => !(n.matiere === matiere && n.methode === methode && n.note === parseFloat(note) && n.comment === comment));
        }

        // Éditer un événement
        function editEvent(button) {
            const item = button.parentElement;

            const eventName = item.querySelector("strong:nth-child(1)").textContent.trim();
            const eventDate = item.querySelector("strong:nth-child(2)").textContent.trim();

            document.getElementById("event-name").value = eventName;
            document.getElementById("event-date").value = eventDate;

            item.remove();
        }

        // Éditer une tâche de la ToDo List
        function editTodo(button) {
            const item = button.parentElement;

            const todoText = item.textContent.replace("Supprimer", "").replace("Éditer", "").trim();

            document.getElementById("todo-input").value = todoText;

            item.remove();
        }

        // Mettre à jour le graphique des notes
        function updateChart() {
            const ctx = document.getElementById('myChart').getContext('2d');
            const noteMap = {};

            notes.forEach(note => {
                const key = `${note.matiere} - ${note.methode}`;
                if (!noteMap[key]) {
                    noteMap[key] = { total: 0, count: 0 };
                }
                noteMap[key].total += note.note;
                noteMap[key].count += 1;
            });

            const labels = Object.keys(noteMap);
            const data = labels.map(label => noteMap[label].total / noteMap[label].count);

            if (myChart) {
                myChart.destroy();
            }

            myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Moyenne',
                        data: data,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            ticks: {
                                autoSkip: true,
                                maxRotation: 45,
                                minRotation: 0,
                            },
                        },
                        y: {
                            beginAtZero: true,
                        },
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                    },
                },
            });
        }

        // Fonction pour mettre à jour le récapitulatif des heures de révision
        function updateRecap() {
            const recapContainer = document.getElementById('recap-container');
            recapContainer.innerHTML = '';

            const recapMap = {};

            sessions.forEach(session => {
                const key = `${session.matiere} - ${session.methode}`;
                if (!recapMap[key]) {
                    recapMap[key] = 0;
                }
                recapMap[key] += parseFloat(session.duree);
            });

            for (const [key, totalHours] of Object.entries(recapMap)) {
                const recapItem = document.createElement('div');
                recapItem.innerHTML = `<strong>${key}:</strong> ${totalHours} heures`;
                recapContainer.appendChild(recapItem);
            }
        }
        // Demander la permission pour les notifications
        function requestNotificationPermission() {
            if (Notification.permission !== "granted") {
                Notification.requestPermission().then(permission => {
                    if (permission === "granted") {
                        console.log("Notification permission granted.");
                    } else {
                        console.log("Notification permission denied.");
                    }
                });
            }
        }

        // Afficher une notification
        function showNotification(title, body) {
            if (Notification.permission === "granted") {
                new Notification(title, {
                    body: body,
                    icon: "icon.png"
                });
            }
        }

        // Demander la permission pour les notifications lorsque la page se charge
        window.addEventListener("load", requestNotificationPermission);

        updateChart();
        updateRecap(); // Appeler la fonction updateRecap initialement pour afficher les données existantes
    </script>
</body>
</html>
