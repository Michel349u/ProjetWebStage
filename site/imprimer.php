<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="style_projet.css" />
        <title>Accès aux notes</title>
        <?php
            error_reporting(E_ALL ^ E_NOTICE);
            session_start();

            $e = "";

            try
            {
                $bdd = new 		PDO('mysql:host=devbdd.iutmetz.univ-lorraine.fr;dbname=duluye1u_PROJET_TUT_5_EME_EDITION_DEUXIEME_DU_NOM;charset=utf8', 'duluye1u_appli', '31820405');
            }
            catch (Exception $e)
            {
                die('Erreur : ' . $e->getMessage());
                $e = "Erreur";
            }
        ?>
    </head>
    <body>
    <a href = "AccueilEncadrant.php">
        <header>
            <h1>Gestion des notes ... </h1>
        </header>
    </a>
    <h2>Export dans un fichier .csv (séparateur ';')</h2>
    <form name="creation" method="post" action="imprimer.php">
        Entrez le nom du fichier : <input type="text" name="nom_fichier"/> <br />
        <input type="submit" name="valider" value="Enregistrer"/>
    </form>

    <?php
        if (isset($_POST["nom_fichier"])) {
            if ($_POST["nom_fichier"] == "")
                echo "<p>Veuillez saisir un nom de fichier valide</p>";
            else {
                $f = $_POST['nom_fichier'];

                if (file_exists($f)) {
                    /* le fichier existe déjà */
                    if (is_file($f)) {
                        echo 'Le fichier ' . $f . ' existe déjà. Voulez-vous l\'écraser ?';
                        echo '<form name="ecraser" method="post" action="imprimer.php">
                                <input type="submit" name="ecraser" value="oui"/>
                                <a href="imprimer.php" ><input type="submit"  value ="non"/></a>
                                </form>';


                        if ($_POST['ecraser'] = "oui") {
                            unlink($f); /* pour effacer */
                            $fichier = fopen($f, 'c+b'); /* pour recréer */
                        }
                    } else {
                        echo $f . ' existe mais n\'est pas un fichier régulier';
                    }
                } else {
                    /* le fichier n'existait pas avant, on le crée */
                    $fichier = fopen($f, 'c+b');
                }

                fwrite($fichier, "IUT de METZ - Département Informatique;;;;
                Deuxième Année - Semestre 3 ;;;;
                ;;;;
                ;;Enseignant :;;
                ;;Matière :;Projet tut S3;
                ;;Date :;;
                ;;ORDRE PAR SOUS-GROUPE;;
                ;;;;
                osg;sg;Nom;Prénom;Note\n");


                $req = "SELECT *
                    FROM etudiant
                    WHERE id_promo=id_promo
                    ORDER BY  demi_groupe, nom, prenom";
                $sql = $bdd->prepare($req);
                $sql->execute();
                $i = 1;

                foreach ($sql as $row) {

                    fwrite($fichier, $i . ";" . $row['demi_groupe'] . ";" . $row['nom'] . ";" . $row['prenom'] . ";" . $row['note_finale'] . "\n");
                    $i++;
                }

                fclose($fichier);
				echo "<p>Le fichier a bien été créé !</p>";
            }
        }
    ?>

    <!--<a href="#" download="A2_ProjetTut_S3_anneedebanneefin.csv">Télécharger les notes en csv</a> -->
    </br>
    <a href = "AccesNote.php">Retour aux notes</a>
    </body>
</html>
