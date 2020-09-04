<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Planning soutenance/poster</title>
        <link rel="stylesheet" href="bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="style_projet.css" />
        <?php
        error_reporting(E_ALL ^ E_NOTICE);
        session_start();
		if (!isset($_SESSION["nom"]))
                header("location:index.html");
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

        if ($e == "") {
            $sql = "SELECT projet.id_projet
                    FROM promo, projet, groupe, eval_sout
                    WHERE promo.nom_promo = :promo
                      AND promo.id_promo = projet.id_promo
                      AND projet.id_projet = groupe.id_projet
                      AND groupe.id_groupe = eval_sout.id_groupe
                      AND eval_sout.id_eval = :idUser
                    ORDER BY heure_sout";
            $req = $bdd->prepare($sql);
            $req->execute(array('promo' => $_SESSION["promo"], 'idUser' => $_SESSION["user"]));

            $i = 0;
            foreach ($req as $row)
                $i = $i + 1;

            $sql = "SELECT heure_debut, heure_fin
                    FROM promo, projet, groupe, eval_poster, creaneaux_poster, horaire_poster
                    WHERE promo.nom_promo = :promo
                      AND promo.id_promo = projet.id_promo
                      AND projet.id_projet = groupe.id_projet
                      AND groupe.id_groupe = eval_poster.id_groupe
                      AND groupe.id_groupe = creaneaux_poster.id_groupe
                      AND eval_poster.id_eval = :idUser
                      AND creaneaux_poster.horaire = horaire_poster.heure_debut
                    GROUP BY heure_debut
                    ORDER BY heure_debut";
            $req = $bdd->prepare($sql);
            $req->execute(array('promo' => $_SESSION["promo"], 'idUser' => $_SESSION["user"]));

            $j = 0;
            $strPoster = "<h1><u>Planning des posters</u></h1><table><thead><caption>Horaires d'évaluation des posters</caption></thead><tbody>";
            foreach ($req as $row)
            {
                $strPoster = $strPoster . "<tr><th>" . $row["heure_debut"] . " - " . $row["heure_fin"] . "</th></tr>";
                $j = $j + 1;
            }
            $strPoster = $strPoster . "</tbody></table>";
        }
        ?>
    </head>
    <body>
      <header>
        <h1>Voici votre planning</h1>
      </header>
      <div class="main">
        <?php
            if ($i != 0) {
                echo "<div class='demi'><h1><u>Planning des soutenances</u></h1>";
                if ($e == "") {
                    $sql = "SELECT projet.id_projet, heure_sout, SUBSTRING(numero, 1, 1) num
                          FROM promo, projet, groupe, eval_sout
                          WHERE promo.nom_promo = :promo
                            AND promo.id_promo = projet.id_promo
                            AND projet.id_projet = groupe.id_projet
                            AND groupe.id_groupe = eval_sout.id_groupe
                            AND eval_sout.id_eval = :idUser
                          ORDER BY heure_sout";
                    $req = $bdd->prepare($sql);
                    $req->execute(array('promo' => $_SESSION["promo"], 'idUser' => $_SESSION["user"]));

                    foreach ($req as $row) {
                        $sql2 = "SELECT SUBSTRING(prenom_eval, 1, 1) prenom, nom_eval
                                 FROM encadre, evaluateur
                                 WHERE encadre.id_eval = evaluateur.id_eval
                                   AND id_projet = :id";
                        $req2 = $bdd->prepare($sql2);
                        $req2->execute(array('id' => $row["id_projet"]));

                        $strNom = " (";
                        $c = 0;
                        foreach ($req2 as $row2) {
                            if ($c > 0)
                                $strNom = $strNom . ", ";
                            $strNom = $strNom . $row2["prenom"] . ". " . $row2["nom_eval"];
                            $c = $c + 1;
                        }
                        $strNom = $strNom . ")";

                        echo "<table>";
                        echo "<tbody>";
                        echo "<tr>";
                        if ($row["num"] != "E")
                            echo "<th>" . $row["heure_sout"] . "</th>";
                        else
                            echo "<th>Erasmus</th>";
                        echo "<th colspan = '3'>Sujet n°" . $row["id_projet"] . $strNom . "</th>";
                        echo "</tr>";

                        $sql3 = "SELECT nom, prenom, demi_groupe
                                 FROM etudiant, groupe
                                 WHERE id_projet = :id
                                   AND etudiant.id_groupe = groupe.id_groupe";
                        $req3 = $bdd->prepare($sql3);
                        $req3->execute(array('id' => $row["id_projet"]));

                        foreach ($req3 as $row3) {
                            echo "<tr>";
                            echo "<td></td>";
                            echo "<td>" . $row3["demi_groupe"] . "</td>";
                            echo "<td>" . $row3["nom"] . "</td>";
                            echo "<td>" . $row3["prenom"] . "</td>";
                            echo "</tr>";
                        }

                        echo "</tbody>";
                        echo "</table>";
                    }
                }
            }
            else{
                echo "<p><i>Aucune soutenance n'est programmée pour vous pour l'instant.</i></p>";
              }
              echo"</div>";
            if ($j != 0)
                echo "<div class='demi'>".$strPoster.'</div>';
            else
                echo "<div class='demi'><p><i>Aucune présentation de poster n'est programmée pour vous pour l'instant.</p></div>";
        ?>
      </div>
    </body>
    <footer>
        <a href="AccueilEncadrant.php"><button type="button" class="btn btn-dark" href="index.html">Retour</button></a>
    </footer>
</html>
