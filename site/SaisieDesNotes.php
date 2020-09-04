<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Menu saisie des notes</title>

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
            $_SESSION["idGroupe"] = 1;
        ?>
    </head>
    <body>
		<header>
			<h1>Sélection des notes</h1>
      </header>
	<nav>

		<ul>

			<li><a href="choixtuteur.php">Évaluation des étudiants par le tuteur</a></li>

			<li><a href="choixgroupe.php"> Note de soutenance </a></li>

			<li><a href="choixgroupe2.php"> Note du poster </a></li>

      <li><a href="AccueilEncadrant.php"> Retour  </a></li>
  	</ul>
  </nav>

      <div class="main">
      <section class="margegauche">
        <p><p style="text-decoration: underline;"><b>Note du tuteur :</b></p></br>Ici vous pouvez mettre les notes des étudiants pour les tuteurs</p></br>
        <p><p style="text-decoration: underline;"><b>Note de soutenance :</b></p></br>Vous pouvez ici mettre la note de soutenance pour un groupe.</p></br>
        <p><p style="text-decoration: underline;"><b>Note du poster :</b></p></br>Vous pouvez ici mettre la note du poster pour un groupe.</p></br>
      </section>
      </div>
    </body>
</html>
