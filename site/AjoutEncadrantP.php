<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Ajout d'un encadrant pour une poster</title>
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

            if ($_POST["ajouterEncadrant"] == "Ajouter l'encadrant au poster")
            {
                $sql = "SELECT id_groupe
                        FROM creneaux_poster
                        WHERE SUBSTRING(horaire, 1, 2) = SUBSTRING(:horaire, 1, 2)";
                $req = $bdd->prepare($sql);
                $req->execute(array('horaire' => $_SESSION["horaire"]));

                foreach ($req as $row)
                {
                    $sql2 = "INSERT INTO `eval_poster`(`id_groupe`, `id_eval`)
                             VALUES (:id_groupe, SUBSTRING(:id_eval, 1, 2))";
                    $req2 = $bdd->prepare($sql2);
                    $req2->execute(array('id_groupe'=>$row["id_groupe"], 'id_eval'=>$_POST["evaluateur"]));
                }

                $_POST["ajouterEncadrant"] = "Et wouala";
            }
            else if ($_POST["retirerEncadrant"] == "Retirer l'encadrant du poster")
            {
                $sql = "SELECT id_groupe
                        FROM creneaux_poster
                        WHERE SUBSTRING(horaire, 1, 2) = SUBSTRING(:horaire, 1, 2)";
                $req = $bdd->prepare($sql);
                $req->execute(array('horaire' => $_SESSION["horaire"]));

                foreach ($req as $row)
                {
                    $sql2 = "DELETE FROM eval_poster
                             WHERE id_groupe = :id_groupe
                               AND id_eval = SUBSTRING(:id_eval, 1, 2)";
                    $req2 = $bdd->prepare($sql2);
                    $req2->execute(array('id_groupe'=>$row["id_groupe"], 'id_eval'=>$_POST["evaluateur"]));
                }

                $_POST["retirerEncadrant"] = "Et wouala";
            }

        ?>
    </head>
    <body>
        <?php
            echo "<header><h1>Posters de " . $_SESSION["horaire"] . "</h1></header><div class='main'";

            $sql = "SELECT nom_eval, SUBSTRING(prenom_eval, 1, 1) as prenom
                    FROM evaluateur
                    WHERE id_eval IN
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

            $str = "<div class='demi'><p style='margin-top: 10px;'>Évalués par : ";
            $i = 0;

            foreach ($req as $row) {
                if ($i != 0)
                    $str = $str . " - ";
                $str = $str . $row["prenom"] . "." . $row["nom_eval"];
                $i++;
            }

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
                    <input type = "submit" value = "Ajouter l'encadrant au poster" name = "ajouterEncadrant" />
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
                    <input type = "submit" value = "Retirer l'encadrant du poster" name = "retirerEncadrant" />
                </p>
            </form>
        </div>
      </div>
        <?php
            $sql = "SELECT *
                    FROM horaire_poster
                    WHERE SUBSTRING(heure_debut, 1, 2) = SUBSTRING(:horaire, 1, 2)";
            $req = $bdd->prepare($sql);
            $req->execute(array('horaire'=>$_SESSION["horaire"]));

            echo "<table>";
            echo "<tbdoy>";

            foreach ($req as $row) {

                echo "<tr>";
                echo "<th colspan = '2'>" . $row["heure_debut"] . " - " . $row["heure_fin"] . "</th>";
                echo "</tr>";

                $sql2 = "SELECT *
                                 FROM creneaux_poster
                                 WHERE horaire = :horaire";
                $req2 = $bdd->prepare($sql2);
                $req2->execute(array("horaire"=>$row["heure_debut"]));

                foreach ($req2 as $row2) {

                    echo "<tr>";
                    echo "<th>Groupe n°" . $row2["id_groupe"] . "</th>";

                    $sql3 = "SELECT *
                                     FROM etudiant
                                     WHERE etudiant.id_groupe = :id_groupe";
                    $req3 = $bdd->prepare($sql3);
                    $req3->execute(array(":id_groupe"=>$row2["id_groupe"]));

                    $i = 0;
                    $str = "";
                    foreach ($req3 as $row3) {

                        if ($i != 0)
                            $str = $str . " - ";
                        $str = $str . $row3["nom"];
                        $i++;
                    }
                    echo "<td>" . $str . "</td>";
                    echo "</tr>";
                }
            }

            echo "</tbody>";
            echo "</table>";
        ?>
    </body>
    <footer>
        <a href="SelectionHoraire.php"><button type="button" class="btn btn-dark" href="index.html">Retour</button></a>
    </footer>
</html>
