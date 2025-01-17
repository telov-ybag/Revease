

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de Passe Oublié</title>
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
            flex-direction : column;
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
            width: 400px;
            padding: 40px;
            background: rgb(154,154,154,1);
            border-radius: 30px;
            border: 1px solid rgba(0,0,0,1);
            box-shadow: inset 0px 4px 4px rgba(0, 0, 0, 0.25);
            text-align: center;
        }

        /* Titre du formulaire */
        .form-title {
            color: rgba(0,0,0,1);
            font-size: 24px;
            margin-bottom: 20px;
        }

        /* Champs de saisie */
        .input-field {
            width: 80%;
            height: 45px;
            margin-bottom: 15px;
            padding: 5px;
            background: rgba(255,255,255,1);
            border: 1px solid rgba(0,0,0,1);
            border-radius: 5px;
            font-size: 16px;
        }

        /* Boutons */
        .button {
            width: 80%;
            height: 45px;
            margin-top: 20px;
            background: rgba(0, 109, 251, 1);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
        }

        .button:hover {
            background: rgba(0, 89, 221, 1);
        }

        /* Lien de retour */
        .link {
            margin-top: 20px;
        }

        .link a {
            text-decoration: none;
            color: rgba(0,109,251,1);
        }

        .link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="page-container">
    <!-- Titre de l'application -->
    <div class="title">Revease</div>

    <!-- Conteneur du formulaire de récupération -->
    <div class="form-container">
        <div class="form-title">Réinitialiser le mot de passe</div>

        <!-- Adresse mail -->
        <div>
            <label for="email">Adresse mail</label>
            <input type="email" id="email" class="input-field" placeholder="Votre email" />
        </div>

        <!-- Bouton de soumission -->
        <div>
            <button class="button" id="reset-button">Envoyer</button>
        </div>

        <!-- Lien de retour -->
        <div class="link">
            <a href="pageConnexion.php">Retour à la connexion</a>
        </div>
    </div>
</div>

<script>
    // Action pour le bouton de réinitialisation
    document.getElementById('reset-button').addEventListener('click', function () {
        const email = document.getElementById('email').value.trim();

        if (!email) {
            alert("Veuillez entrer une adresse email.");
            return;
        }

        // Simule une demande de réinitialisation
        alert(`Un lien de réinitialisation a été envoyé à : ${email}`);

        // Retourner à la page de connexion
        window.location.href = 'pageConnexion.php';
    });
</script>

</body>
</html>
