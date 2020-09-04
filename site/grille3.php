<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Notation poster</title>
        <link rel="stylesheet" href="bootstrap.min.css">
		    <link rel="stylesheet" type="text/css" href="style_projet.css" />
        <?php
            session_start();
            $e = "";
			if (!isset($_SESSION["nom"]))
                header("location:index.html");
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
        <h1>Note du poster</h1>
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
                        <th>Qualité de la présentation       </th>
                        <th>Travail réalisé / État d'avancement du projet      </th>
                        <th>Utilisation de nouvelles compétences    </th>
                    </tr>
					<?php
                        if ($e == "")
                        {
                            $e = "Connexion etablie";
							$user = $_SESSION["user"];
							$groupe = $_SESSION['id_groupe'];
							$sql1 = "SELECT *
								FROM groupe,eval_poster,evaluateur
								WHERE groupe.id_groupe=eval_poster.id_groupe
								AND groupe.id_groupe=SUBSTRING(:groupe, 1, 2)
								AND evaluateur.id_eval=eval_poster.id_eval";
                            $req = $bdd->prepare($sql1);
                            $req->execute(array(':groupe' => $user));
							
							
							$sql2 = "SELECT *
									FROM groupe,etudiant
									WHERE groupe.id_groupe=etudiant.id_groupe
									AND groupe.id_groupe=SUBSTRING(:groupe, 1, 2)";
									
                            $req1 = $bdd->prepare($sql2);
                            $req1->execute(array(':groupe' => $groupe));
                                echo "<tr>";
                                echo "<td> Groupe " . $_SESSION['id_groupe'] . "</td>"; 
						}
						?>


                                <td><input type = 'text' name = 'stud1col1' size = '25' /></td>
                                <td><input type = 'text' name = 'stud1col2' size = '25' /></td>
                                <td><input type = 'text' name = 'stud1col3' size = '25' /></td>
                                </tr>


                </tbody>
            </table>


				<?php
			if (isset($_POST["stud1col1"])) {
				$stud1col1 = $_POST["stud1col1"];
				$stud1col2 = $_POST["stud1col2"];
				$stud1col3 = $_POST["stud1col3"];
				$sql = "UPDATE eval_poster SET poster_qual_pres = :stud1col1, poster_trav = :stud1col2, poster_compet = :stud1col3 WHERE id_groupe = SUBSTRING(:groupe,1,2) AND id_eval= :user ";
                    $req = $bdd->prepare($sql);
                    $res = $req->execute(array(':user' => $user, ':groupe' => $groupe, ':stud1col1' => $stud1col1, ':stud1col2' => $stud1col2, ':stud1col3' => $stud1col3  ));
				header('location:apercu2.php');
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
