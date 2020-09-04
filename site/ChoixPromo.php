<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Choix de la promotion</title>
          <link rel="stylesheet" href="bootstrap.min.css">
		     <link rel="stylesheet" type="text/css" href="style_projet.css" />
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


            if ($_POST["validerPromo"] == "Valider la sélection") {
                $_SESSION["promo"] = $_POST["promo"];
                header('location:AccueilEncadrant.php');
            }
            $sql = "SELECT * FROM promo";
            $req = $bdd->prepare($sql);
            $req->execute();

            $i = 0;
            $promo = "";
            foreach ($req as $row)
            {
                $promo = $row["nom_promo"];
                $i = $i + 1;
            }
            if ($i == 1) {
                $_SESSION["promo"] = $promo;
                header('location:AccueilEncadrant.php');
            }
        ?>
    </head>

    <body>
	<header>
        <h1>GESTIONNAIRE DE PROJET</h1>
		<h2> Sélection de la promotion</h2>
      </header>
      <div class="centrage">


        <form style="margin-top: 30px;" method="POST">
            <select name = "promo" class="select">
                <?php
                    $sql = "SELECT * FROM promo";
                    $req = $bdd->prepare($sql);
                    $req->execute();

                    foreach ($req as $row)
                        echo "<option>" . $row["nom_promo"];
                ?>
            </select>
            <p style="margin-top: 30px;margin-bottom:30px;">
                <input type = "submit" value = "Valider la sélection" name = "validerPromo" />
            </p>
        </form>
            <p class="font-italic">Choisissez la promotion avec laquelle vous voulez travailler.</p>



  </div>
    </body>
</html>
