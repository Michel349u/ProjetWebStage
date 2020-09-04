<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <title>Validation</title>
    <link rel="stylesheet" href="bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="style_projet.css" />
	<link rel="stylesheet" type="text/css" href="noteEtud.css" />
  <?php
  session_start();
  ob_start();
  error_reporting(E_ALL ^ E_NOTICE);
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
      if (isset($_POST["modifier"]))
        {
          $j = 0;
          while ($j < 7)
            {
              if ((isset($_POST["note_rapport" . $j])) || (isset($_POST["note_trav" . $j]))||(isset($_POST["note_comp" . $j])))
              {
                $rap=isset($_POST['note_rapport'])?$_POST['note_rapport']:'';
                $trav=isset($_POST['note_trav'])?$_POST['note_trav']:'';
                $comp=isset($_POST['note_comp'])?$_POST['note_comp']:'';
                $sql6 = "UPDATE etudiant
                          SET note_tut_rapport = :noteRapport, note_tut_trav = :noteTrav, note_tut_comp = :noteComp
                          WHERE etudiant.id_groupe = :idGroupe
                          AND etudiant.nom = :nom";
                $req6 = $bdd->prepare($sql6);
                $req6->execute(array("noteRapport"=>$_POST["note_rapport"], "noteTrav"=>$_POST["note_trav"], "noteComp"=>$_POST["note_comp"], "idGroupe"=>$row["id_groupe"], "nom"=>$row["nom"]));
              }
            $j++;
  }
}
   ?>
</head>
<body>
 <header>
 <h1> Voici les notes du groupe n°<?php echo $_SESSION['id_groupe']  ?></h1>
 </header>
    <table align="center">
      <form  method = "post">
      <caption><b>Evaluation du tuteur et note finale coefficient 1</b> </caption>
      <tr>
        <th>Numéro de projet</th>
        <th>Nom de l'étudiant</th>
        <th>Qualité du rapport /5</th>
        <th>Travail réalisé /5</th>
        <th>Utilisation des nouvelles compétences /5</th>
      </tr>
    <?php
    $req="SELECT *
    FROM eval_poster
    WHERE id_groupe=SUBSTRING(:groupe, 1, 2)
	AND id_eval=:user ";
    $sql=$bdd->prepare($req);
    $sql->execute(array("groupe"=>$_SESSION['id_groupe'], ':user' =>$_SESSION['user']));
    $i = 0;
    foreach ($sql as $row) {

        $test=$row['poster_qual_pres'];
        $test1=$row['poster_trav'];
        $test2=$row['poster_compet'];
        $testt=$row['id_groupe'];
        echo"<tr>";
        echo"<td align='center'>" ."<input type='hidden' size=2 name=('idGroupe'.$i) value=$testt>".$row['id_groupe']."</td>";
        echo"<td>". $_SESSION['id_groupe']."</td>";
        echo"<td align='center'>".  "<input type='text' size=2 name=('note_rapport'.$i) value=$test>"."</td>";
        echo"<td align='center'>". "<input type='text' size=2 name=('note_trav'.$i) value=$test1>"."</td>";
        echo"<td align='center'>"."<input type='text' size=7 (name='note_compet'.$i) value=$test2>"."</td>";
      echo"<tr>";
        $i++;



    }
    echo"</table></br></br>";
	

  ?>
 <footer> 
	<a href='choixgroupe2.php'><button type="button" class="btn btn-dark" href="index.html"> Changer de groupe </button></a>
	<a href="AccueilEncadrant.php"><button type="button" class="btn btn-dark" href="index.html">Retour</button></a>
</footer>
</body>
</html>
