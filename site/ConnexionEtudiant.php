<html lang = "fr">
    <head>
        <meta charset = "UTF-8" />
        <title>Connexion etudiant</title>
        <link rel="stylesheet" type="text/css" href="style_projet.css" />

        <?php
            error_reporting(E_ALL ^ E_NOTICE);
            session_start();
            function isEmail($str)
            {
                return (strpos($str, '@'));
            }

            if (isset($_POST["newuser"]))
            header('location:NouvelUser.php');
            else if (isset($_POST["connecter"]))
            {
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
                        $champ = "email";
                    else
                        $champ = "login";
                    echo $champ;
                    $sql = "SELECT * 
                            FROM etudiant 
                            WHERE " . $champ . " = :valeur 
                              AND mdp = :mdp";
                    $req = $bdd->prepare($sql);
                    $req->execute(array('valeur'=>$_POST["username"], 'mdp'=>$_POST["password"]));
                    if ($row = $req->fetch())
                    {
                        echo "Bien";
                        $_SESSION["user"] = $row["id_etud"];
                        $_SESSION["nom"] = $row["nom"];
                        $_SESSION["prenom"] = $row["prenom"];
                        $_SESSION["id_groupe"] = $row["id_groupe"];
                        header('location:AccueilEtudiant.php');
                    }
                    else
                        header('location:error2.html');
                }
            }
            else
            {
        ?>
    </head>
    <body>
      <a href = "index.html">
        <header>
        <h1>GESTIONNAIRE DE PROJET</h1>
        <h2>Espace Etudiant - Connexion</h2>
      </header>
    </a>
      <div class="main">
        <form method = "POST">
            <table>
                <tr>
                    <td>Identifiant</td>
                    <td><input type = "text" name = "username" size = "25" placeholder = "pseudo ou email" required /></td>
                </tr>
                <tr>
                    <td>Mot de passe</td>
                    <td><input type = "password" name = "password" size = "25" placeholder = "mot de passe" required /></td>
                </tr>
            </table>
            <p>
                <input type = "submit" value = "Connexion" name = "connecter" />
            </p>
           <!-- <a href="index.html">Retour en arrière</a>-->
        </form>
        <?php
            }
        ?>
    </body>
</html>
