<?php
$dbuser = "user"; 	//user for access database for store logs of your's HC2, 
						//need privilage to CREATE TABLE in databases defined in $dbname and created manualy
$dbpass = "password";		//password for user
$dbname = "fibaro";		//databases name, need created manualy for security resons, the table for all HC2 
						//will be created on first HC2 connection
$con=mysqli_connect("localhost",$dbuser,$dbpass,$dbname);
// Check connection
if (mysqli_connect_errno())
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

if(isset($_GET['id']) && !empty($_GET['id'])){
    echo "Selected = ".$_GET['id']."<br>";
    echo "<A HREF='javascript:javascript:history.go(-1)'>Click here to go back to previous page</A>";

    $hcid = $_GET["id"];
// -------------------


echo "<br>";
echo "HC2 source - Vnn or Snn - the VD or Scene sending message";
$sql2 = "SELECT * FROM $hcid ORDER BY `" . $hcid . "`.`SQL_Time` DESC LIMIT 100";
//echo $sql2;

//$kolumn WHERE Zrodlo LIKE $urz
$result = mysqli_query($con,$sql2);

echo "<table border='1'>
<tr>
<th>SQL Server time</th>
<th>HC2 time</th>
<th>HC2 level</th>
<th>HC2 source</th>
<th>HC2 message</th>

</tr>";

while($row = mysqli_fetch_array($result))
{
echo "<tr>";
echo "<td>" . $row['SQL_Time'] . "</td>";
echo "<td>" . $row['HC2_Time'] . "</td>";
echo "<td>" . $row['HC2_Level'] . "</td>";
echo "<td>" . $row['HC2_Source'] . "</td>";
echo "<td>" . $row['HC2_Message'] . "</td>";
echo "</tr>";
}
echo "</table>";



// --------------------
} else {
    echo "Select HC2";


$sql = "SHOW TABLES";
$result = mysqli_query($con, $sql);

echo "<table border='1'>
<tr>
<th>Syslog for:</th>
<th>Display 100 row</th>
<th>Export CSV</th>
</tr>";
while($row = mysqli_fetch_array($result))
{
echo "<tr>";
echo "<td>" . $row[0] . "</td>";
echo "<td> <a href='/fibaro_log.php?id=" . $row[0] . "' title='abc' class='whatEver'>Link </a></td>";
echo "<td> <a href='/fibaro_csv.php?id=" . $row[0] . "' title='abc' class='whatEver'>Export </a </td>";

$hcid = $row[0];
//echo "</tr>";
}
echo "</table>";

}


mysqli_close($con);
?>