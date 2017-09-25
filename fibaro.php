<?php
echo 'Recived: ';
$czas = $_GET["hctime"];
$type = $_GET["hcmsg"];
$urz = $_GET["hcid"];
$poz = $_GET["hclev"];
echo 'hc2_time[' . htmlspecialchars($_GET["hctime"]) . '] ';
echo 'hc2_mess[' . htmlspecialchars($_GET["hcmsg"]) .'] ';
echo 'hc2_source[' . htmlspecialchars($_GET["hcid"]) . '] ';
echo 'hc2_level[' . htmlspecialchars($_GET["hclev"]) .'] ';

// on se connecte à  MySQL
$db = mysql_connect('localhost', 'user', 'password');

// on sélectionne la base
mysql_select_db('fibaro',$db);

// on crée la requête SQL
$sql = "INSERT INTO hc123456(HC2_Time, HC2_Level, HC2_Source, HC2_Message) VALUES('$czas','$poz','$urz','$type')";

// on envoie la requête
$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());

// on ferme la connexion à  mysql
mysql_close();
echo 'Commited';
?> 