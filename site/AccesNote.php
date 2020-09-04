<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Accès aux notes</title>
        <link rel="stylesheet" href="bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="style_projet.css" />
        <?php
            error_reporting(E_ALL ^ E_NOTICE);
            session_start();

            $e = "";

            try
            {
                $bdd = new PDO('mysql:host=devbdd.iutmetz.univ-lorraine.fr;dbname=duluye1u_PROJET_TUT_5_EME_EDITION_DEUXIEME_DU_NOM;charset=utf8', 'duluye1u_appli', '31820405');
            }
            catch (Exception $e)
            {
                die('Erreur : ' . $e->getMessage());
                $e = "Erreur";
            }


            if ($_POST["validerNote"] == "Valider la sélection") {

                $_SESSION["id_groupe"] = $_POST["id_groupe"];

                header('location:NoteEtud.php');
            }
        ?>
<?php
  if ($_SESSION["isAdmin"]  == "o") {
?>
</head>
  <body>
    <header>
      <h1>Accès aux notes des élèves</h1>
    </header>
    <div class="main">

    <h2 style="margin: 30px;"><i>Choisissez un groupe pour voir leurs notes</i></h2>
    <form method="POST">
        <select name = "id_groupe" style="margin-bottom: 30px;">
            <?php

                $sql = "SELECT *
                        FROM groupe ";
                $req = $bdd->prepare($sql);
                $req->execute();

                foreach ($req as $row) {
                                                $str = "";

                                                $sql2 = "SELECT nom
                                                         FROM etudiant
                                                         WHERE id_groupe = SUBSTRING(:groupe, 1, 2)";
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
            <input type = "submit" value = "Valider la sélection" name = "validerNote" />
            <a href="AccueilEncadrant.php"><button type="button" class="btn btn-dark" href="index.html">Retour</button></a>
        </p>
    </form>
    <a href="imprimer.php" ><input type="button"  value ="Voir toutes les notes pour les imprimer"/></a>
  </div>
</body>
</html>
<?php

  }

 ?>
