<?php


$con=mysqli_connect("localhost","user","password","fibaro");
// Check connection
if (mysqli_connect_errno())
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
//$kolumn WHERE Zrodlo LIKE $urz
$result = mysqli_query($con,"SELECT * FROM hc123456 ORDER BY `hc123456`.`Pozycja` DESC LIMIT 20");

echo "<table border='1'>
<tr>
<th>SQL Server (NTP)</th>
<th>HC2 Time</th>
<th>HC2 Level</th>
<th>HC2 source ID</th>
<th>HC2 message</th>

</tr>";

while($row = mysqli_fetch_array($result))
{
echo "<tr>";
echo "<td>" . $row['SQL_Time'] . "</td>";
echo "<td>" . $row['HC2_Time'] . "</td>";
echo "<td>" . $row['HC2_Source'] . "</td>";
echo "<td>" . $row['HC2_Level'] . "</td>";
echo "<td>" . $row['HC2_Message'] . "</td>";
echo "</tr>";
}
echo "</table>";

mysqli_close($con);

?>