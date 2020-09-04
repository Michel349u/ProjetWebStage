 <!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Notation par une tuteur</title>
        <link rel="stylesheet" href="bootstrap.min.css">
		     <link rel="stylesheet" type="text/css" href="style_projet.css" />
        <?php
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
      <h1>Note du tuteur</h2>
    </header>
    <div class="main">
	<form method="POST">


 <table>

                <tbody>
					<tr>
						<th>
							<?php
								echo $_SESSION["nom"] . " " . $_SESSION["prenom"] ;
							?>
						</th>

						<th>
						</th>

						<th>
						</th>
				
						<th>
						</th>
					</tr>
                    <tr>
                        <th>Groupe</th>
                        <th>Qualité du rapport (/5)</th>
                        <th>Travail réalisé (/5)</th>
                        <th>Utilisation de nouvelles compétences (/5)</th>
						<th>Pourcentage du travail </th>
                    </tr>
						<?php
                        if ($e == "")
                        {
                            $e = "Connexion etablie";
							$user = $_SESSION["user"];
                            $i = 0;
							$sql1 = "SELECT DISTINCT * FROM etudiant,encadre,groupe WHERE encadre.id_eval = :user and encadre.id_projet = groupe.id_projet and groupe.id_groupe=etudiant.id_groupe and etudiant.id_groupe= SUBSTRING(:groupe,1,2)";
                            $req = $bdd->prepare($sql1);
                            $req->execute(array(':user' => $user,':groupe' => $_SESSION['id_groupe']));
                            foreach ($req as $row)
                            {	
								
                                echo "<tr>";
                                echo "<td>" . $row["id_etud"] .  " " . $row["nom"] . " " . $row["prenom"] .  "</td>";
                                echo "<td><input style='width : 100px' type = 'number' min = '0' max = '5' name = 'stud" . $i . "col1'  /></td>";
                                echo "<td ><input style='width : 100px' type = 'number' min = '0' max = '5' name = 'stud" . $i . "col2'  /></td>";
                                echo "<td ><input style='width : 100px' type = 'number' min = '0' max = '5' name = 'stud" . $i . "col3'  /></td>";
								echo "<td ><input style='width : 100px' type = 'number' min = '0' max = '100' name = 'stud" . $i . "col4'/></td>"; 
                                echo "</tr>";
                                $i++;
                            }
							
                        }
                    ?>


                </tbody>
            </table>
		<?php
			$j = 0;	
		$sql1 = "SELECT DISTINCT etudiant.id_etud FROM etudiant,encadre,groupe WHERE encadre.id_eval = :user and encadre.id_projet = groupe.id_projet and groupe.id_groupe=etudiant.id_groupe and etudiant.id_groupe= SUBSTRING(:groupe,1,2)";
                            $req = $bdd->prepare($sql1);
                            $req->execute(array(':user' => $user,':groupe' => $_SESSION['id_groupe']));
                            foreach ($req as $row)
                            {
									
			if (isset($_POST["stud".$j."col1"],$_POST["stud".$j."col2"],$_POST["stud".$j."col3"], $_POST["stud".$j."col3"] )) {
				$sql = "UPDATE etudiant  SET note_tut_rapport = :studcol1, note_tut_trav = :studcol2, note_tut_comp = :studcol3, pourcent_travail = :studcol4 WHERE id_groupe = SUBSTRING(:groupe,1,2) AND id_etud = :etud ";
                    $req = $bdd->prepare($sql);
                    $res = $req->execute(array(':etud' => $row['id_etud'], ':groupe' => $_SESSION['id_groupe'], ':studcol1' => $_POST["stud".$j."col1"], ':studcol2' => $_POST["stud".$j."col2"], ':studcol3' => $_POST["stud".$j."col3"], ':studcol4' => $_POST["stud".$j."col4"]  ));
			}
			$j++;
			}
			if (isset($_POST["stud1col1"])) {
				header('location:apercu.php');
			}
				
			
	?>
            <p>
                <input type = "submit" value = "Valider la séléction" name = "validerNote" />
                  <a href="AccueilEncadrant.php"><button type="button" class="btn btn-dark" href="AccueilEncadrant.php">Retour</button></a>
            </p>
        </form>
      </div>
    </body>
</html>
