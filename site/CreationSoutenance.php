<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Creation soutenance/poster</title>
        <link rel="stylesheet" href="bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="style_projet.css" />
        <?php
            error_reporting(E_ALL ^ E_NOTICE);
            session_start();

            if (!isset($_SESSION["nom"]))
                header("location:Index.html");
            if ($_SESSION["isAdmin"] != "o")
                header("location:AccueilEncadrant.php");

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



            if ($_POST["creation"] == "Créer la soutenance")
            {
                $sql = "UPDATE groupe
                        SET heure_sout = :heure
                        WHERE id_groupe = SUBSTRING(:groupe, 1, 2)";
                $req = $bdd->prepare($sql);
                $req->execute(array('heure'=>$_POST["heure"], 'groupe'=>$_POST["groupe"]));

                $sql = "SELECT DISTINCT id_eval
                        FROM eval_sout, groupe, horaire_soutenance
                        WHERE eval_sout.id_groupe = groupe.id_groupe
                          AND groupe.heure_sout = horaire_soutenance.horaire
                          AND id_tranche = (SELECT id_tranche
                                            FROM horaire_soutenance
                                            WHERE horaire = :heure)";
                $req = $bdd->prepare($sql);
                $req->execute(array('heure'=>$_POST["heure"]));

                foreach ($req as $row)
                {
                    $sql2 = "INSERT INTO `eval_sout`(`id_groupe`, `id_eval`)
                             VALUES (SUBSTRING(:id_groupe, 1, 2), :id_eval)";
                    $req2 = $bdd->prepare($sql2);
                    $req2->execute(array('id_groupe'=>$_POST["groupe"], 'id_eval'=>$row["id_eval"]));
                }

                $_POST["creation"] = "et wouala";





                $sql3 = "SELECT id_groupe
                         FROM groupe, horaire_soutenance
                         WHERE groupe.heure_sout = horaire_soutenance.horaire
                           AND id_tranche = (SELECT id_tranche
                                             FROM horaire_soutenance
                                             WHERE horaire = :heure)";
                $req3 = $bdd->prepare($sql3);
                $req3->execute(array('heure'=>$_POST["heure"]));


                foreach ($req3 as $row3) {

                    $sql4 = "SELECT id_eval
                             FROM encadre, groupe
                             WHERE encadre.id_projet = groupe.id_projet
                               AND id_groupe = SUBSTRING(:id_groupe, 1, 2)";
                    $req4 = $bdd->prepare($sql4);
                    $req4->execute(array('id_groupe'=>$_POST["groupe"]));

                    foreach ($req4 as $row4) {
                        $sql5 = "INSERT INTO `eval_sout`(`id_groupe`, `id_eval`)
                                 VALUES (:id_groupe, :id_eval)";
                        $req5 = $bdd->prepare($sql5);
                        $req5->execute(array('id_groupe'=>$row3["id_groupe"], 'id_eval'=>$row4["id_eval"]));
                    }
                }
            }
            else if ($_POST["creation"] == "Créer le poster")
            {

                $sql = "SELECT id_tranche
                        FROM horaire_poster
                        WHERE heure_debut = SUBSTRING(:heure, 1, 8)";
                $req = $bdd->prepare($sql);
                $req->execute(array("heure"=>$_POST["heure"]));

                foreach ($req as $row) {
                    $sql2 = "SELECT *
                             FROM horaire_poster
                             WHERE id_tranche = :tranche
                                OR id_tranche = :tranche2";
                    $req2 = $bdd->prepare($sql2);
                    $req2->execute(array('tranche' => $row["id_tranche"], 'tranche2' =>(($row["id_tranche"] + 2) % 4)));

                    foreach ($req2 as $row2) {
                        $sql3 = "INSERT INTO `creneaux_poster`(`id_groupe`, `horaire`)
                                 VALUES (SUBSTRING(:id_groupe, 1, 2), :heure)";
                        $req3 = $bdd->prepare($sql3);
                        $req3->execute(array('id_groupe'=>$_POST["groupe"], 'heure'=>$row2["heure_debut"]));
                    }
                }
            }

            if ($e == "") {

                                $sql = "SELECT projet.id_projet
                                        FROM promo, projet, groupe
                                        WHERE promo.nom_promo = :promo
                                          AND promo.id_promo = projet.id_promo
                                          AND projet.id_projet = groupe.id_projet
                                        ORDER BY heure_sout";
                                $req = $bdd->prepare($sql);
                                $req->execute(array('promo' => $_SESSION["promo"]));
                $i = 0;
                foreach ($req as $row)
                    $i = $i + 1;

                $sql = "SELECT horaire
                        FROM horaire_soutenance
                        WHERE horaire NOT IN
                                (SELECT heure_sout
                                 FROM groupe
                                 WHERE heure_sout is not null)";
                $req = $bdd->prepare($sql);
                $req->execute();

                $j = 0;
                foreach ($req as $row)
                    $j = $j + 1;
            }
        ?>
    </head>
    <body>
      <header>
          <h1>Création de soutenance</h1>
      </header>
      <div class="main2">

      <h1><u>Affectation des groupes</u></h1>
        <div class="demi">

            <?php
                if ($j != 0) {
                    ?>
                    <form method = "POST">
                        <table>
                            <tr>
                                <th>Choix du groupe :</th>
                                <td>
                                    <select name="groupe">
                                        <?php
                                        if ($e == "") {
                                            $sql = "SELECT groupe.id_groupe
                                                    FROM groupe, projet, promo
                                                    WHERE nom_promo = :promo
                                                      AND promo.id_promo = projet.id_promo
                                                      AND projet.id_projet = groupe.id_projet
                                                      AND projet.id_projet NOT IN
                                                            (SELECT groupe.id_groupe
                                                            FROM groupe
                                                            WHERE heure_sout is not null
                                                               OR SUBSTRING(numero, 1, 1) = 'E'
                                                               OR SUBSTRING(numero, 1, 1) = 'e')";
                                            $req = $bdd->prepare($sql);
                                            $req->execute(array('promo'=>$_SESSION["promo"]));

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
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Choix de l'heure :</th>
                                <td>
                                    <select name="heure">
                                        <?php
                                        if ($e == "") {
                                            $sql = "SELECT horaire
                                                FROM horaire_soutenance
                                                WHERE horaire NOT IN
                                                        (SELECT heure_sout
                                                        FROM groupe
                                                        WHERE heure_sout is not null)";
                                            $req = $bdd->prepare($sql);
                                            $req->execute();

                                            foreach ($req as $row)
                                                echo "<option>" . $row["horaire"];
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <input type="submit" class="btn btn-dark" value="Créer la soutenance" name="creation"/>
                    </form>
                    <?php
                }
                else
                {
                    echo "<p>Toutes les soutenances ont déjà été programmées pour cette promo</p>";
                }

                if ($i != 0) {
                    echo "<h2><i> Planning des soutenances</i></h2>";
                    if ($e == "") {
                                            $sql = "SELECT projet.id_projet, heure_sout, SUBSTRING(numero, 1, 1) num
                                                  FROM promo, projet, groupe
                                                  WHERE promo.nom_promo = :promo
                                                    AND promo.id_promo = projet.id_promo
                                                    AND projet.id_projet = groupe.id_projet
                                                    AND heure_sout is not null
                                                  ORDER BY heure_sout";
                                            $req = $bdd->prepare($sql);
                                            $req->execute(array('promo' => $_SESSION["promo"]));

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
                else
                    echo "<p>Aucune soutenance n'est programmée pour vous pour l'instant.</p>";
            ?>
        </div>
        <div class="demi">
            <form method = "POST">
                <table>
                    <tr>
                        <th>Choix du groupe :</th>
                        <td>
                            <select name="groupe">
                                <?php
                                if ($e == "") {
                                    $sql = "SELECT id_groupe
                                            FROM groupe, projet, promo
                                            WHERE nom_promo = :promo
                                              AND promo.id_promo = projet.id_promo
                                              AND projet.id_projet = groupe.id_projet
                                              AND id_groupe NOT IN
                                                (SELECT id_groupe
                                                 FROM creneaux_poster)
                                              AND id_groupe NOT IN
                                                (SELECT groupe.id_groupe
                                                 FROM groupe
                                                 WHERE SUBSTRING(numero, 1, 1) = 'E'
                                                   OR SUBSTRING(numero, 1, 1) = 'e')";
                                    $req = $bdd->prepare($sql);
                                    $req->execute(array('promo'=>$_SESSION["promo"]));

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
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Choix de l'heure :</th>
                        <td>
                            <select name="heure">
                                <?php
                                if ($e == "") {
                                    $sql = "SELECT *
                                                FROM horaire_poster";
                                    $req = $bdd->prepare($sql);
                                    $req->execute();

                                    foreach ($req as $row)
                                        echo "<option>" . $row["heure_debut"] . " - " . $row["heure_fin"];
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <input type="submit" class="btn btn-dark" value="Créer le poster" name="creation"/>
            </form>
            <h2><i>Planning des posters</i></h2>
            <?php
                $sql = "SELECT *
                        FROM horaire_poster";
                $req = $bdd->prepare($sql);
                $req->execute();

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
        </div>
      </div>
    </body>
    <footer>
      <a href="AccueilEncadrant.php"><button type="button" class="btn btn-dark" href="index.html">Retour</button></a>
    </footer>
</html>
