<?php
$dbuser = "user"; 	//user for access database for store logs of your's HC2, 
						//need privilage to CREATE TABLE in databases defined in $dbname and created manualy
$dbpass = "password";		//password for user
$dbname = "fibaro";		//databases name, need created manualy for security resons, the table for all HC2 
						//will be created on first HC2 connection
$debug = 0;				//more information sending to HC2
$hc2 = $_GET["hcname"];
$hct = $_GET["hctime"];
$hcm = $_GET["hcmsg"];
$hci = $_GET["hcid"];
$hcl = $_GET["hclev"];
if($debug === 1) {
echo nl2br("=========Recived==========\n");
echo 'HC2name/id [' . htmlspecialchars($_GET["hcname"]) . '] ';
echo nl2br ("\n");
echo 'HC2time [' . htmlspecialchars($_GET["hctime"]) . '] ';
echo nl2br ("\n");
echo 'HC2message [' . htmlspecialchars($_GET["hcmsg"]) .'] ';
echo nl2br ("\n");
echo 'HC2VD/scene[' . htmlspecialchars($_GET["hcid"]) . '] ';
echo nl2br ("\n");
echo 'HC2Level [' . htmlspecialchars($_GET["hclev"]) .'] ';
echo nl2br ("\n");
}

// on se connecte à  MySQL
$db = mysql_connect('localhost',$dbuser,$dbpass);

// on sélectionne la base
mysql_select_db($dbname,$db);

// on crée la requête SQL
$sql = "INSERT INTO $hc2(HC2_Time, HC2_Level, HC2_Source, HC2_Message) VALUES('$hct','$hcl','$hci','$hcm')";

// on envoie la requête
$req = mysql_query($sql);
if(mysql_errno() === 1046) {
echo mysql_error()." (".mysql_errno().")";
die();
echo nl2br ("\n");
}

if(mysql_errno() === 1146) {
echo mysql_error()." (".mysql_errno().")";
echo nl2br ("\n");
echo "creating table ".$hc2;
echo nl2br ("\n");
//mysql_select_db($dbname);

$create = "CREATE TABLE $hc2 (
      `SQL_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      `HC2_Time` datetime NOT NULL,
      `HC2_Level` varchar(10) NOT NULL,
      `HC2_Source` varchar(5) NOT NULL,
      `HC2_Message` varchar(200) CHARACTER SET utf8 NOT NULL
    ) ENGINE=MyISAM DEFAULT CHARSET=latin2
";

$results = mysql_query($create) or die (mysql_error());
echo "Table for ".$hc2." created";
$req = mysql_query($sql);
} else { 
if($debug === 1) {echo "Table ".$hc2." exist";};
}


// on ferme la connexion à  mysql
mysql_close();
echo nl2br ("\n");
echo 'Commited';
?> 