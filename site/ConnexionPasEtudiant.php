<html lang = "fr">
    <head>
        <meta charset = "UTF-8" />
        <title>Connexion enseignant</title>
        <link rel="stylesheet" href="bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="style_projet.css" />

        <?php
            error_reporting(E_ALL ^ E_NOTICE);
            session_start();

            function isEmail($str)
            {
                return (strpos($str, '@'));
            }

            $e = "";

            try
            {
                $bdd = new PDO('mysql:host=devbdd.iutmetz.univ-lorraine.fr;dbname=duluye1u_PROJET_TUT_5_EME_EDITION;charset=utf8', 'duluye1u_appli', '31820405');
            }
            catch (Exception $e)
            {
                die('Erreur : ' . $e->getMessage());
                $e = "Erreur";
            }

            if ($e == "")
            {
                $e = "Connexion etablie";
                if (isEmail(trim($_POST["username"])))
                    $champ = "email_eval";
                else
                    $champ = "login";
                $sql = "SELECT * FROM evaluateur WHERE " . $champ . " = :valeur AND mdp_eval = :mdp";
                $req = $bdd->prepare($sql);
                $req->execute(array('valeur'=>$_POST["username"], 'mdp'=>$_POST["password"]));
                if ($row = $req->fetch())
                {
                    echo "Bieng";
                    $_SESSION["user"] = $row["id_eval"];
                    $_SESSION["nom"] = $row["nom_eval"];
                    $_SESSION["prenom"] = $row["prenom_eval"];
                    $_SESSION["isAdmin"] = $row["droit_admin"];
                    header('location:ChoixPromo.php');
                }
            }
        ?>
    </head>
    <body>
      <header>
        <h1>GESTIONNAIRE DE PROJET</h1>
        <h2>Espace Encadrant - Connexion</h2>
      </header>
      <div class="main">
        <!-- <h1>Connexion</h1>-->
        <form method = "POST">
            <table>
                <tr>
                    <td>Identifiant</td>
                    <td><input type = "text" name = "username" size = "25" placeholder = "Pseudo ou email" required /></td>
                </tr>
                <tr>
                    <td>Mot de passe</td>
                    <td><input type = "password" name = "password" size = "25" placeholder = "Mot de passe" required /></td>

                </tr>
            </table>
            <p>
                <input type = "submit" value = "Connexion" name = "connecter" />
                <a href="index.html"><button type="button" class="btn btn-dark" href="index.html">Retour  </button></a>
            </p>

        </form>
      </div>
      <footer>
          <img src="logo.png">
          <p>Université de Lorraine | Tous droits réservés</p>
      </footer>
    </body>
</html>
