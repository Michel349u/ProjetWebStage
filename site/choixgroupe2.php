<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Choix groupe pour poster</title>
        <link rel="stylesheet" href="bootstrap.min.css">
		    <link rel="stylesheet" type="text/css" href="style_projet.css" />
        <?php
            error_reporting(E_ALL ^ E_NOTICE);
            session_start();
			if (!isset($_SESSION["nom"]))
                header("location:index.html");
            $e = "";
			$user = $_SESSION['user'];
            try
            {
                $bdd = new PDO('mysql:host=devbdd.iutmetz.univ-lorraine.fr;dbname=duluye1u_PROJET_TUT_5_EME_EDITION;charset=utf8', 'duluye1u_appli', '31820405');
            }
            catch (Exception $e)
            {
                die('Erreur : ' . $e->getMessage());
                $e = "Erreur";
            }


            if ($_POST["validerGroupe"] == "Valider la sélection") {
                $_SESSION["id_groupe"] = $_POST["id_groupe"];
				$_SESSION['groupeid'] = $_POST['groupeid'];
                header('location:grille3.php');
            }
            $sql = "SELECT DISTINCT id_groupe FROM etudiant WHERE id_groupe IS NOT NULL";
            $req = $bdd->prepare($sql);
            $req->execute();

            $i = 0;
            $groupe = "";
            foreach ($req as $row)
            {
                $groupe = $row["id_groupe"];
                $i = $i + 1;
            }
            if ($i == 1) {
                $_SESSION["id_groupe"] = $groupe;
				$_SESSION['groupeid'] = $_POST['groupeid'];
                header('location:grille3.php');
            }
        ?>
    </head>
    <body>
       <header>
        <h1>GESTIONNAIRE DE PROJET</h1>
		<h2> Choix groupe</h2>
      </header>
          <div class="main">
        <form method="POST" style="margin: 30px;">
            <select name = "id_groupe" style="margin-bottom: 10px;">
                <?php
                    $sql = "SELECT DISTINCT etudiant.id_groupe FROM etudiant,eval_poster WHERE eval_poster.id_eval = :user and eval_poster.id_groupe = etudiant.id_groupe and etudiant.id_groupe IS NOT NULL";
                    $req = $bdd->prepare($sql);
                    $req->execute(array(':user' => $user));
                   foreach ($req as $row) {
                                                $str = "";
                                                $sql2 = "SELECT nom
                                                         FROM etudiant
                                                         WHERE id_groupe = :groupe";
                                                $req2 = $bdd->prepare($sql2);
                                                $req2->execute(array('groupe'=>$row["id_groupe"]));
                                                foreach ($req2 as $row2) {
                                                    $str = $str . " - " . $row2["nom"];
                                                }
                                                echo "<option>" . $row["id_groupe"] . " " . $str;
                                            }

                ?>
            </select>
            <p>
                <input type = "submit" value = "Valider la sélection" name = "validerGroupe" />
				    <a href="AccueilEncadrant.php"><button type="button" class="btn btn-dark" href="index.html">Retour</button></a>
            </p>
        </form>
      </div>
    </body>
</html>
