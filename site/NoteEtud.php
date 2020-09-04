<!DOCTYPE html>
<html>
<head>
      <link rel="stylesheet" href="bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="style_projet.css" />
   <link rel="stylesheet" href="noteEtud.css">
  <?php
  session_start();
  ob_start();
  error_reporting(E_ALL ^ E_NOTICE);
  $e = "";

  try
  {
      $bdd = new PDO('mysql:host=devbdd.iutmetz.univ-lorraine.fr;dbname=duluye1u_PROJET_TUT_5_EME_EDITION_DEUXIEME_DU_NOM;charset=utf8', 'duluye1u_appli', '31820405');
  }
  catch (Exception $e)
  {
      die('Erreur : ' . $e->getMessage());
      $e = "Erreur";
  }
    if ($_SESSION["isAdmin"]  == "o") {
      if (isset($_POST["modifier"]))
        {
          $j = 0;
          while ($j < 7)
            {
              if ((isset($_POST["note_rapport" . $j])) || (isset($_POST["note_trav" . $j]))||(isset($_POST["note_compet" . $j])))
              {
                $rap=isset($_POST['note_rapport'])?$_POST['note_rapport']:'';
                $trav=isset($_POST['note_trav'])?$_POST['note_trav']:'';
                $comp=isset($_POST['note_compet'])?$_POST['note_compet']:'';
                $sql6 = "UPDATE etudiant
                          SET note_tut_rapport = :noteRapport, note_tut_trav = :noteTrav, note_tut_compet = :noteCompet
                          WHERE etudiant.id_groupe = :idGroupe
                          AND etudiant.nom = :nom";
                $req6 = $bdd->prepare($sql6);
                $req6->execute(array("noteRapport"=>$_POST["note_rapport"], "noteTrav"=>$_POST["note_trav"], "noteCompet"=>$_POST["note_compet"], "idGroupe"=>$row["id_groupe"], "nom"=>$row["nom"]));
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

  <div class="main">

    <table align="center">
      <form  method = "post">
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
    $req="SELECT *
    FROM groupe,etudiant
    WHERE groupe.id_groupe=etudiant.id_groupe
    AND groupe.id_groupe=SUBSTRING(:groupe, 1, 2) ";
    $sql=$bdd->prepare($req);
    $sql->execute(array("groupe"=>$_SESSION['id_groupe']));
    $i = 0;
    foreach ($sql as $row) {

        $test=$row['note_tut_rapport'];
        $test1=$row['note_tut_trav'];
        $test2=$row['note_tut_compet'];
        $testt=$row['id_groupe'];
        echo"<tr>";
        echo"<td align='center'>" ."<input type='hidden' size=2 name=('idGroupe'.$i) value=$testt>".$row['id_groupe']."</td>";
        echo"<td>". $row['nom']."</td>";
        echo"<td align='center'>".  "<input type='text' size=2 name=('note_rapport'.$i) value=$test>"."</td>";
        echo"<td align='center'>". "<input type='text' size=2 name=('note_trav'.$i) value=$test1>"."</td>";
        echo"<td align='center'>"."<input type='text' size=7 (name='note_compet'.$i) value=$test2>"."</td>";
        echo"<td align='center'>". $row['pourcent_travail']."</td>";
        echo"<td align='center'>". $row['note_tut_5']."</td>";
        echo"<td align='center'>". $row['note_tut_20']."</td>";
        echo"<td align='center'>". $row['note_sout']."</td>";
        echo"<td align='center'>". $row['note_poster']."</td>";
        echo"<td align='center'>". $row['note_finale']."</td>";

      echo"<tr>";
        $i++;



    }
    echo"</table></br></br>";
  ?>
  <table align="center">
      <caption><b>Evaluation de la soutenance coefficient 1 </b></caption>
    <tr>
      <th>Numéro du projet</th>
      <th>Nom de l'évaluateur</th>
      <th>Qualité de la présentation /5</th>
      <th>Travail réalisé. Etat d'avancement du projet /5</th>
      <th>Utilisation des nouvelles compétences /5</th>
      <th>Moyenne</th>
    </tr>
    <?php
    $req3="SELECT *
    FROM groupe,eval_sout,evaluateur
    WHERE groupe.id_groupe=eval_sout.id_groupe
    AND groupe.id_groupe=SUBSTRING(:groupe, 1, 2)
    AND evaluateur.id_eval=eval_sout.id_eval";
    $sql3=$bdd->prepare($req3);
    $sql3->execute(array("groupe"=>$_SESSION['id_groupe']));
    $k=0;
    foreach ($sql3 as $row1) {
      $test3=$row1['sout_qual_pres'];
      $test4=$row1['sout_trav'];
      $test5=$row1['sout_compet'];
        echo"<tr>";
        echo"<td align='center'>" .$row1['id_groupe']."</td>";
        echo"<td>". $row1['nom_eval']."</td>";
        echo"<td align='center'>". "<input type='text' size=7 name=('note_sout_qual.$k') value=$test3>"."</td>";
        echo"<td align='center'>". "<input type='text' size=7 name=('note_sout_trav'.$k) value=$test4>"."</td>";
        echo"<td align='center'>"."<input type='text' size=7 name=('note_sout_compet'.$k) value=$test5>"."</td>";
        echo"<td align='center'>". $row1['sout_moyenne']."</td>";
        echo"<tr>";
        $k++;

    }
      echo"</table></br></br>";

     ?>
  <?php
  if($row1['duree_diff']=='n'){
  ?>
     <table align="center">
         <caption><b>Evaluation du poster coefficient 1 </b></caption>
       <tr>
         <th>Numéro du projet</th>
         <th>Nom de l'évaluateur</th>
         <th>Qualité de la présentation /5</th>
         <th>Travail réalisé. Etat d'avancement du projet /5</th>
         <th>Utilisation des nouvelles compétences /5</th>
         <th>Moyenne</th>
       </tr>
       <?php
       $req4="SELECT *
       FROM groupe,eval_poster,evaluateur
       WHERE groupe.id_groupe=eval_poster.id_groupe
       AND groupe.id_groupe=SUBSTRING(:groupe, 1, 2)
       AND evaluateur.id_eval=eval_poster.id_eval";
       $sql4=$bdd->prepare($req4);
       $sql4->execute(array("groupe"=>$_SESSION['id_groupe']));
       $m = 0 ;
       foreach ($sql4 as $row2) {
         $test6=$row2['poster_qual_pres'];
         $test7=$row2['poster_trav'];
         $test8=$row2['poster_compet'];
           echo"<tr>";
           echo"<td align='center'>" .$row2['id_groupe']."</td>";
           echo"<td>". $row2['nom_eval']."</td>";
           echo"<td align='center'>". "<input type='text' size=7 name=('note_post_qual'.$m) value=$test6>"."</td>";
           echo"<td align='center'>". "<input type='text' size=7 name=('note_post_trav'.$m) value=$test7>"."</td>";
           echo"<td align='center'>". "<input type='text' size=7 name=('note_post_compet'.$m) value=$test8>"."</td>";
           echo"<td align='center'>". $row2['poster_moyenne']."</td>";
           echo"<tr>";
           $m++;

       }
         echo"</table></form></br></br>";

        echo"<input type='submit' name='modifier' value='Modifier'/>";

        ?>
<?php

}} ?>


</div>
<footer>  <a href="AccueilEncadrant.php"><button type="button" class="btn btn-dark" href="index.html">Retour</button></a></footer>
</body>
</html>
