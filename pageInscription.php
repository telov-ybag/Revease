<?php
// Activer les erreurs pour faciliter le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure le fichier de connexion à la base de données
include 'connexion.php';

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $e_mail = trim($_POST['e_mail'] ?? '');
    $mot_de_passe = trim($_POST['mot_de_passe'] ?? '');
    $nom_utilisateur= trim($_POST['nom_utilisateur']??'');

    if (!empty($e_mail) && !empty($mot_de_passe)) {
        try {
            // Vérifier si un utilisateur avec cet email existe déjà
            $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE e_mail = :e_mail");
            $stmt->bindParam(':e_mail', $e_mail);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                // Alerte si l'utilisateur existe déjà
                echo "<script>alert('Un compte avec cet e-mail existe déjà.');</script>";
            } else {
                // Crypter le mot de passe avant de l'insérer dans la base
                $mot_de_passe_crypte = password_hash($mot_de_passe, PASSWORD_DEFAULT);

                // Insérer le nouvel utilisateur dans la base de données
                $stmt = $pdo->prepare("INSERT INTO utilisateurs (e_mail, mot_de_passe,nom_utilisateur) VALUES (:e_mail, :mot_de_passe,:nom_utilisateur)");
                $stmt->bindParam(':e_mail', $e_mail);
                $stmt->bindParam(':mot_de_passe', $mot_de_passe_crypte);
                $stmt->bindParam(':nom_utilisateur',$nom_utilisateur);

                if ($stmt->execute()) {
                    // Rediriger vers la page de connexion après une inscription réussie
                    header('Location: pageConnexion.php');
                    exit;
                } else {
                    echo "<script>alert('Erreur lors de l\'enregistrement.');</script>";
                }
            }
        } catch (PDOException $e) {
            // Gérer les erreurs de la base de données
            echo "<script>alert('Erreur : " . $e->getMessage() . "');</script>";
        }
    } else {
        // Alerte si des champs sont vides
        echo "<script>alert('Veuillez remplir tous les champs.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <style>
        /* Styles généraux */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body, html {
            font-family: 'Arimo', sans-serif;
            height: 100%;
            background-color: rgba(233,45,21,1);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Conteneur principal */
        .page-container {
            width: 1920px;
            height: 1080px;
            position: relative;
            background: rgba(233,45,21,0.75);
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction : column ;
        }

        /* Titre principal */
        .title { 
            margin: 0;
            margin-bottom: 20px; 
            font-size: 45px;
            color: white;
            text-align: center;
        }

        /* Conteneur de formulaire */
        .form-container {
            width: 305px;
            padding: 20px;
            background: rgb(154,154,154,1);
            border-radius: 30px;
            border: 1px solid rgba(0,0,0,1);
            box-shadow: inset 0px 4px 4px rgba(0, 0, 0, 0.25);
            text-align: center;
        }

        /* Titre du formulaire */
        .form-title { color: rgba(0,0,0,1); font-size: 24px; margin-bottom: 20px; }

        /* Champs de saisie */
        .input-field {
            width: 228px;
            height: 52px;
            margin-bottom: 10px;
            padding: 5px;
            background: rgba(255,255,255,1);
            border: 1px solid rgba(0,0,0,1);
        }

        /* Lien de retour vers la connexion */
        .link {
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="page-container">
    <!-- Titre de l'application -->
    <div class="title">Revease</div>

    <!-- Conteneur du formulaire d'inscription -->
    <div class="form-container">
        <form method="POST" action="">
            <div class="form-title">S’inscrire</div>

            <!-- Adresse mail -->
            <div>
                <label>Adresse mail</label>
                <input type="email" class="input-field" name="e_mail" required />
            </div>

            <!-- Mot de passe -->
            <div>
                <label>Mot de passe</label>
                <input type="password" class="input-field" name="mot_de_passe" required />
            </div>

             <!-- Nom d'utilisateur -->
             <div>
                <label>Nom d'utilisateur</label>
                <input type="text" class="input-field" name="nom_utilisateur" required />
            </div>

            <!-- Bouton d'inscription -->
            <button type="submit" class="button" id="signup-button">S’inscrire</button>
        </form>

        <!-- Lien vers la page de connexion -->
        <div class="link">
            <a href="pageConnexion.php">Retour à la connexion</a>
        </div>
    </div>
</div>

</body>
</html>
