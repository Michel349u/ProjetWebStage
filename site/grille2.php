<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Notation soutenance</title>
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
          <h1>Note de Soutenance</h2>
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
                            $i = 1;
							$sql1 = "SELECT *
								FROM groupe,eval_sout,evaluateur
								WHERE groupe.id_groupe=eval_sout.id_groupe
								AND groupe.id_groupe=SUBSTRING(:groupe, 1, 2)
								AND evaluateur.id_eval=eval_sout.id_eval";
                            $req = $bdd->prepare($sql1);
                            $req->execute(array(':groupe' => $user));
							
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
				$user = $_SESSION["user"];
			if (isset($_POST["stud1col1"])) {
				$stud1col1 = $_POST["stud1col1"];
				$stud1col2 = $_POST["stud1col2"];
				$stud1col3 = $_POST["stud1col3"];
				$sql = "UPDATE eval_sout SET sout_qual_pres = :stud1col1, sout_trav = :stud1col2, sout_compet = :stud1col3 WHERE id_groupe = SUBSTRING(:groupe,1,2) AND id_eval= :user ";
                    $req = $bdd->prepare($sql);
                    $res = $req->execute(array(':user' => $user, ':groupe' => $_SESSION['id_groupe'], ':stud1col1' => $stud1col1, ':stud1col2' => $stud1col2, ':stud1col3' => $stud1col3  ));
				header('location:apercu1.php');
			}			
				?>
            <p>
                <input type = "submit" value = "Valider la sélection" name = "validerNote" />
                <a href="AccueilEncadrant.php"><button type="button" class="btn btn-dark" href="AccueilEncadrant.php">Retour</button></a>
            </p>
        </form>
      </div>
    </body>
</html>
