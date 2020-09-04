<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Accueil etudiant</title>
        <link rel="stylesheet" type="text/css" href="style_projet.css" />
		<link rel="stylesheet" type="text/css" href="noteEtud.css" />
		
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
        ?>
    </head>
    <body>
        <header>
            <h1>Menu principal</h1>
            <?php
                echo "<h1>Bienvenue " . $_SESSION["nom"] . " " . $_SESSION["prenom"] . "</h1>";
            ?>
        </header>

        <nav>
            <ul>
                <li><a href = "Deconnexion.php"> Deconnexion </a></li>
            </ul>
        </nav>

        <table align="center">
            <caption><b>Evaluation du tuteur et note finale coefficient 1</b> </caption>
            <tr>
                <th>Numéro de projet</th>
                <th>Nom de l'étudiant</th>
                <th>Qualité du rapport /5</th>
                <th>Travail réalisé /5</th>
                <th>Utilisation des nouvelles compétences /5</th>
                <th>Pourcentage de travail réalisé</th>
                <th>Note sur 5</th>
                <th>Note tuteur sur 20</th>
                <th>Note soutenance</th>
                <th>Note poster</th>
                <th>Note finale</th>
            </tr>
            <?php
                $sql = "SELECT *
                        FROM groupe, etudiant
                        WHERE groupe.id_groupe = etudiant.id_groupe
                          AND groupe.id_groupe = :groupe";
                $req = $bdd->prepare($sql);
                $req->execute(array("groupe"=>$_SESSION['id_groupe']));

                foreach ($req as $row) {
                    echo"<tr>";
                    echo"<td align = 'center'>" . $row['id_groupe']."</td>";
                    echo"<td>". $row['nom']."</td>";
                    echo"<td align='center'>" . $row['note_tut_rapport'] . "</td>";
                    echo"<td align='center'>" . $row['note_tut_trav'] . "</td>";
                    echo"<td align='center'>" . $row['note_tut_comp'] . "</td>";
                    echo"<td align='center'>" . $row['pourcent_travail'] . "</td>";
                    echo"<td align='center'>" . $row['note_tut_5'] . "</td>";
                    echo"<td align='center'>" . $row['note_tut_20'] . "</td>";
                    echo"<td align='center'>" . $row['note_sout'] . "</td>";
                    echo"<td align='center'>" . $row['note_poster'] . "</td>";
                    echo"<td align='center'>" . $row['note_finale'] . "</td>";
                    echo"<tr>";
                }
            ?>
        </table>
        <?php
            $sql = "SELECT heure_sout
                    FROM groupe
                     WHERE id_groupe = :groupe";
            $req = $bdd->prepare($sql);
            $req->execute(array("groupe"=>$_SESSION['id_groupe']));

            foreach ($req as $row) {
                echo "<p>Soutenance programmée à : " . $row["heure_sout"] . "</p>";
            }

            $sql2 = "SELECT *
                     FROM creneaux_poster, horaire_poster
                     WHERE id_groupe = :groupe
                       AND horaire = heure_debut";
            $req2 = $bdd->prepare($sql2);
            $req2->execute(array("groupe"=>$_SESSION['id_groupe']));

            $str = "<p>Présentation des posters de ";
            $i = 0;

            foreach ($req2 as $row) {
                if ($i != 0)
                    $str = $str . " et de ";
                $str = $str . $row["heure_debut"] . " à " . $row["heure_fin"];
                $i++;
            }

            $str = $str . ".</p>";

            echo $str;
        ?>
    </body>
</html>
