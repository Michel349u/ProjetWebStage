<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Ajout d'un encadrant pour une soutenance</title>
        <link rel="stylesheet" href="bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="style_projet.css" />
        <?php
            error_reporting(E_ALL ^ E_NOTICE);
            session_start();

            if (!isset($_SESSION["nom"]))
                header("location:Index.html");

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

            if ($_POST["ajouterEncadrant"] == "Ajouter l'encadrant à la soutenance")
            {
                $sql = "SELECT id_groupe
                        FROM groupe, horaire_soutenance
                        WHERE groupe.heure_sout = horaire_soutenance.horaire
                          AND id_tranche = (SELECT id_tranche
                                            FROM horaire_soutenance
                                            WHERE horaire = SUBSTRING(:horaire, 1, 8))";
                $req = $bdd->prepare($sql);
                $req->execute(array('horaire' => $_SESSION["horaire"]));

                foreach ($req as $row)
                {
                    $sql2 = "INSERT INTO `eval_sout`(`id_groupe`, `id_eval`)
                             VALUES (:id_groupe, SUBSTRING(:id_eval, 1, 2))";
                    $req2 = $bdd->prepare($sql2);
                    $req2->execute(array('id_groupe'=>$row["id_groupe"], 'id_eval'=>$_POST["evaluateur"]));
                }


                $_POST["ajouterEncadrant"] = "Et wouala";
            }
            else if ($_POST["retirerEncadrant"] == "Retirer l'encadrant de la soutenance")
            {
                $sql = "SELECT id_groupe
                                FROM groupe, horaire_soutenance
                                WHERE groupe.heure_sout = horaire_soutenance.horaire
                                  AND id_tranche = (SELECT id_tranche
                                                    FROM horaire_soutenance
                                                    WHERE horaire = SUBSTRING(:horaire, 1, 8))";
                $req = $bdd->prepare($sql);
                $req->execute(array('horaire' => $_SESSION["horaire"]));

                foreach ($req as $row)
                {
                    $sql2 = "DELETE FROM eval_sout
                             WHERE id_groupe = :id_groupe
                               AND id_eval = SUBSTRING(:id_eval, 1, 2)";
                    $req2 = $bdd->prepare($sql2);
                    $req2->execute(array('id_groupe'=>$row["id_groupe"], 'id_eval'=>$_POST["evaluateur"]));
                    var_dump($req2);
                }

                    $_POST["retirerEncadrant"] = "Et wouala";
            }
        ?>
    </head>
    <body>
        <?php
            echo "<header><h1>Soutenances de " . $_SESSION["horaire"] . "</h1></header><div class='main'";

            $sql = "SELECT nom_eval, SUBSTRING(prenom_eval, 1, 1) as prenom
                    FROM evaluateur
                    WHERE id_eval IN
                            (SELECT evaluateur.id_eval
                             FROM evaluateur, eval_sout, groupe, horaire_soutenance
                             WHERE evaluateur.id_eval = eval_sout.id_eval
                               AND eval_sout.id_groupe = groupe.id_groupe
                               AND groupe.heure_sout = horaire_soutenance.horaire
                               AND id_tranche = (SELECT id_tranche
                                                 FROM horaire_soutenance
                                                 WHERE SUBSTRING(:horaire, 1, 8) = horaire))";
            $req = $bdd->prepare($sql);
            $req->execute(array('horaire' => $_SESSION["horaire"]));

            $str = "<div class='demi'><p style='margin-top: 10px;'>Évalués par : ";
            $i = 0;

            foreach ($req as $row) {
                if ($i != 0)
                    $str = $str . " - ";
                $str = $str . $row["prenom"] . "." . $row["nom_eval"];
                $i++;
            }
            echo "</p>";
            if ($str != "Évalués par : ")
                echo "<p>" . $str . "</p>";

        ?>
        <div>
            <form method="POST">
                <select name = "evaluateur" style="margin: 20px;">
                    <?php
                        if ($e == "") {
                            $sql = "SELECT *
                                    FROM evaluateur
                                    WHERE id_eval NOT IN
                                        (SELECT evaluateur.id_eval
                                        FROM evaluateur, eval_sout, groupe, horaire_soutenance
                                        WHERE evaluateur.id_eval = eval_sout.id_eval
                                          AND eval_sout.id_groupe = groupe.id_groupe
                                          AND groupe.heure_sout = horaire_soutenance.horaire
                                          AND id_tranche = (SELECT id_tranche
                                                            FROM horaire_soutenance
                                                            WHERE SUBSTRING(:horaire, 1, 8) = horaire))
                                    AND id_eval NOT IN
                                        (SELECT evaluateur.id_eval
                                        FROM evaluateur, eval_poster, creneaux_poster, horaire_poster
                                        WHERE evaluateur.id_eval = eval_poster.id_eval
                                          AND eval_poster.id_groupe = creneaux_poster.id_groupe
                                          AND creneaux_poster.horaire = horaire_poster.heure_debut
                                          AND id_tranche = (SELECT id_tranche
                                                            FROM horaire_soutenance
                                                            WHERE SUBSTRING(:horaire, 1, 8) = horaire))";
                            $req = $bdd->prepare($sql);
                            $req->execute(array('horaire' => $_SESSION["horaire"]));

                            foreach ($req as $row)
                                echo "<option>" . $row["id_eval"] . " " . $row["nom_eval"] . " " . $row["prenom_eval"];
                        }
                    ?>
                </select>
                <p>
                    <input type = "submit" value = "Ajouter l'encadrant à la soutenance" name = "ajouterEncadrant" />
                </p>
            </form>
        </div>
        <div>
            <form method="POST">
                <select name = "evaluateur" style="margin: 20px;">
                    <?php
                    if ($e == "") {
                        $sql = "SELECT *
                                FROM evaluateur
                                WHERE id_eval IN
                                    (SELECT evaluateur.id_eval
                                     FROM evaluateur, eval_sout, groupe, horaire_soutenance
                                     WHERE evaluateur.id_eval = eval_sout.id_eval
                                       AND eval_sout.id_groupe = groupe.id_groupe
                                       AND groupe.heure_sout = horaire_soutenance.horaire
                                       AND id_tranche = (SELECT id_tranche
                                                         FROM horaire_soutenance
                                                         WHERE SUBSTRING(:horaire, 1, 8) = horaire))";
                        $req = $bdd->prepare($sql);
                        $req->execute(array('horaire' => $_SESSION["horaire"]));

                        foreach ($req as $row)
                            echo "<option>" . $row["id_eval"] . " " . $row["nom_eval"] . " " . $row["prenom_eval"];
                    }
                    ?>
                </select>
                <p>
                    <input type = "submit" value = "Retirer l'encadrant de la soutenance" name = "retirerEncadrant" />
                </p>
            </form>
        </div>
      </div>
        <?php
        if ($e == "") {
            $sql = "SELECT projet.id_projet, heure_sout, SUBSTRING(numero, 1, 1) num
                    FROM promo, projet, groupe, horaire_soutenance
                    WHERE promo.nom_promo = :promo
                      AND promo.id_promo = projet.id_promo
                      AND projet.id_projet = groupe.id_projet
                      AND groupe.heure_sout = horaire_soutenance.horaire
                      AND id_tranche = (SELECT id_tranche
                                        FROM horaire_soutenance
                                        WHERE horaire = SUBSTRING(:heure, 1, 8))
                    ORDER BY heure_sout";
            $req = $bdd->prepare($sql);
            $req->execute(array('promo' => $_SESSION["promo"], 'heure'=>$_SESSION["horaire"]));

            foreach ($req as $row) {
                $sql2 = "SELECT SUBSTRING(prenom_eval, 1, 1) prenom, nom_eval
                         FROM encadre, evaluateur
                         WHERE encadre.id_eval = evaluateur.id_eval
                           AND id_projet = :id";
                $req2 = $bdd->prepare($sql2);
                $req2->execute(array('id' => $row["id_projet"]));

                $strNom = " (";
                $c = 0;
                foreach ($req2 as $row2)
                {
                    if ($c > 0)
                        $strNom = $strNom . ", ";
                    $strNom = $strNom . $row2["prenom"] . ". " . $row2["nom_eval"];
                    $c = $c + 1;
                }
                $strNom = $strNom . ")";

                echo "<div class='demi'><table>";
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
                    echo "<tr style='line-height:1em;'>";
                    echo "<td></td>";
                    echo "<td>" . $row3["demi_groupe"] . "</td>";
                    echo "<td>" . $row3["nom"] . "</td>";
                    echo "<td>" . $row3["prenom"] . "</td>";
                    echo "</tr>";
                }

                echo "</tbody>";
                echo "</table></div>";
            }
        }
        ?>
    </body>
    <footer>
        <a href="SelectionHoraire.php"><button type="button" class="btn btn-dark" href="index.html">Retour</button></a>
    </footer>
</html>
