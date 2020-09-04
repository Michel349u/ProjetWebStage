<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Menu évaluateur</title>

		    <link rel="stylesheet" type="text/css" href="style_projet.css" />
        <?php
            session_start();
			if (!isset($_SESSION["nom"]))
                header("location:index.html");
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
			<li><a href = "SaisieDesNotes.php">Saisie des notes</a></li>
			<li><a href = "Planning.php">Le Planning</a></li>
			        <?php
					if ($_SESSION["isAdmin"]  == "o") {
					?>

                <li><a href = "CreationSoutenance.php">Créer soutenance</a></li>
                <li><a href = "AccesNote.php">Accès aux notes</a></li>
				        <li><a href = "SelectionHoraire.php">Sessions</a> </li>
				<?php
						}
					?>
          <li><a href="ChoixPromo.php">Retour</a></li>
		        <li><a href = "Deconnexion.php">Déconnexion </a></li>
          </ul>
        </nav>
        <div class="main">


        <section class="margegauche">
        <p><p style="text-decoration: underline;"><b>Saisie des notes : </b></p></br>Dans cette section il est possible d’attribuer les notes de chaque groupe, élève par élève avec des coefficients. </p></br>
        <p><p style="text-decoration: underline;"><b>Le Planning :</b></p></br> Dans cette section il est possible de visualiser les soutenances et les élèves qui y participent. </p></br>
        <?php if ($_SESSION["isAdmin"]  == "o") {
          ?>
        <p><p style="text-decoration: underline;"><b>Création soutenances/sessions poster :</b></p></br> Dans cette section il est possible de créer une soutenance, d’y ajouter des élèves et un tuteur ainsi que de  programmer des sessions d’examens pour les posters.</p></br>
        <p><p style="text-decoration: underline;"><b>Accès aux notes :</b></p></br> Dans cette section il est possible d’accéder aux notes qui ont été préalablement publié par les professeurs.</p></br>
        <p><p style="text-decoration: underline;"><b>Sessions :</b></p></br>Dans cette section vous pouvez gérer les encadrant pour les soutenances et les posters.</p></br>
        <?php
          }
          ?>
        <p><p style="text-decoration: underline;"><b>Déconnexion :</b></p></br> Dans cette section il est possible de se déconnecter.</p>
        </section>
      </div>
    </body>
</html>
