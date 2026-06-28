<!-- Ferrah Nihal Yasmine G1 242431423103
     Ziane Damia Feriel G1 232431847516
	 Ouldedine Chaima 232331595914
	 Djahel Yousra 232331406306 -->
<html>
<head>
  <title>USTHB - Page d’accueil</title>
  <link rel="stylesheet" type="text/css" href="acceuil1.css">
</head>
<body>
  <header>
  <div style="display: flex; justify-content: center; align-items: center; gap: 40px; width: 100%; padding: 20px 0;">
    
    <a href="../index.php/acceuil1.php" class="active" style="text-decoration: none; color: purple;">Accueil</a>
    
    <a href="../index.php/acceuil2.php" style="text-decoration: none; color: purple;">Connexion</a>
    
    <a href="../fichier.html/apropos.html" style="text-decoration: none; color: purple;">À propos</a>
  </div>
</header>
<hr style="height:2px; background-color:#00008B; border:none;">
  
  &nbsp; &nbsp; &nbsp;
  <div align="center" >
    <img src="../image/usthb2.jpg" style="width:300px" height="150">
	</div>
    <div align="center">
	<h2>Universit&eacute; des sciences et de la Technologie Houari Boumediene</h2>
	</div>
	<div align="center">
    <p><I><b>University of Science and Technology Houari Boumediene</b></I></p>
	</div>
	<div align="center">
    <button onclick="window.location.href='../index.php/acceuil2.php'">Se connecter</button>
  </div>
  <div align="center">
  <footer style="padding-top: 80px; text-align: center;">
  <p> 
  <img src="../image/info2.jfif" align="abs middle" width="30" height="30"/><A href="https://lsi.usthb.dz/" style="font-size:0.6em">Facult&eacute; d'informatique</A> |
&nbsp;&nbsp;&nbsp;
<img src="../image/chimie.png" align="abs middle" width="30" height="30"/><A href="https://fchimie.usthb.dz/" style="font-size:0.6em">Facult&eacute; de chimie</A> |
&nbsp;&nbsp;&nbsp;
<img src="../image/physique.png" align="abs middle" width="30" height="30"/><A href="https://fphy.usthb.dz/" style="font-size:0.6em">Facult&eacute; de physique</A> |
&nbsp;&nbsp;&nbsp;
<img src="../image/math3.png" align="abs middle" width="30" height="30"/><A href="https://fmath.usthb.dz/" style="font-size:0.6em">Facult&eacute; de Math&eacute;matique</A>|
&nbsp;&nbsp;&nbsp;
<img src="../image/gc.jfif" align="abs middle" width="30" height="30"/><A href="https://fgc.usthb.dz/" style="font-size:0.6em">Facult&eacute; de genie civil</A>
&nbsp;&nbsp;&nbsp; </p>
</footer>
  </div>
<div style="display: flex; justify-content: center; align-items: center; gap: 40px; width: 100%; padding: 20px 0;">
<div> Ferrah Nihal Yasmine <br> G1 <br> 242431423103</div>
<div>Ziane Damia Feriel <br>G1 <br>232431847516</div>
<div>Ouldedine Chaima<br>G1<br>232331595914</div>
<div>Djahel Yousra<br>G1<br>232331406306</div>
</div>
<div align="right" style="margin:-5px">
<?php
echo date("d/m/Y").'</br>';
echo date("H:i:s").'<br/>';
?>
</body>
</html>