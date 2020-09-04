<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Session ajout encadrant</title>
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

            if (isset($_POST["SelectHoraireS"]))
            {
                $_SESSION["horaire"] = $_POST["tranche"];
                header('location:AjoutEncadrantS.php');
            }
            else if (isset($_POST["SelectHoraireP"]))
            {
                $_SESSION["horaire"] = $_POST["tranche"];
                header('location:AjoutEncadrantP.php');
            }
        ?>
    </head>
    <body>
      <header>
        <h1>Selection des horaires</h1>
      </header>
      <div class="main">
        <h2 style="margin-top: 10px;"><i>Sélectionner la tranche horaire de la soutenance</i></h2>
        <form method="POST">
            <select name = "tranche" style="margin: 20px;">
                <?php
                    $i = 1;
                    while ($i < 5) {
                        $sql = "SELECT horaire, id_tranche
                            FROM horaire_soutenance
                            WHERE horaire = (SELECT MIN(horaire)
                                             FROM horaire_soutenance
                                             WHERE id_tranche = :id_tranche)

                            UNION

                            SELECT ADDTIME(horaire, '00:30:00') horaire, id_tranche
                            FROM horaire_soutenance
                            WHERE horaire = (SELECT MAX(horaire)
                                             FROM horaire_soutenance
                                             WHERE id_tranche = :id_tranche)";
                        $req = $bdd->prepare($sql);
                        $req->execute(array('id_tranche' =>$i));

                        $str = "";
                        $j = 0;
                        foreach ($req as $row)
                        {
                            if ($j != 0)
                                $str = $str . " - ";
                            $str = $str . $row["horaire"];
                            $j = $j + 1;
                        }
                        $i = $i + 1;

                        echo "<option>" . $str;
                    }
                ?>
            </select>
            <p>
                <input type = "submit" value = "Sélectionner cette horaire pour une soutenance" name = "SelectHoraireS" />
                <input type = "submit" value = "Sélectionner cette horaire pour un poster" name = "SelectHoraireP" />
            </p>
        </form>
      </div>
    </body>
    <footer>
        <a href="AccueilEncadrant.php"><button type="button" class="btn btn-dark" href="index.html">Retour</button></a>
    </footer>
</html>
